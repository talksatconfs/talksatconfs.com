<?php
/**
 * ─────────────────────────────────────────────────────────────────
 *  Deployer config for Laravel on DigitalOcean (single droplet)
 *  Copy this file into the ROOT of each Laravel project.
 *
 *  Docs : https://deployer.org/docs/7.x/getting-started
 *  Usage: dep deploy          → deploy to production
 *         dep rollback        → roll back to previous release
 *         dep deploy:unlock   → clear stuck lock (if deploy crashed)
 *         dep ssh             → SSH into server in deploy context
 * ─────────────────────────────────────────────────────────────────
 */

namespace Deployer;

require 'recipe/laravel.php';


// ═══════════════════════════════════════════════════════════════
//  1. PROJECT  — change these per app
// ═══════════════════════════════════════════════════════════════

set('application', 'talksatconfscom');                          // ← app name used in add-laravel-app.sh
set('repository',  'git@github.com:talksatconfs/talksatconfs.com.git');   // ← your git repo (SSH URL)
set('branch',      'main');                           // ← branch to deploy


// ═══════════════════════════════════════════════════════════════
//  2. SERVER  — your DigitalOcean droplet
// ═══════════════════════════════════════════════════════════════

host('production')
    ->setHostname('178.128.120.164')          // ← droplet IP or domain
    ->setRemoteUser('root')                   // ← SSH user (root, or 'deployer' if you set one up)
    ->setIdentityFile('~/.ssh/id_ed25519')    // ← your local SSH private key
    ->set('deploy_path', '/var/www/{{application}}');


// ═══════════════════════════════════════════════════════════════
//  3. DEPLOYER SETTINGS
// ═══════════════════════════════════════════════════════════════

set('keep_releases',         5);     // keep last 5 releases for rollback
set('git_tty',               false); // required for non-interactive SSH deploys
set('allow_anonymous_stats', false); // opt out of Deployer telemetry
set('default_timeout',       600);   // composer install can be slow on first run (empty cache)

// PHP-FPM runs as the app system user created by add-laravel-app.sh
set('http_user',  '{{application}}');
set('http_group', '{{application}}');

// 'chown' mode works when deploying as root (no sudo needed)
set('writable_mode',     'chown');
set('writable_use_sudo', false);


// ═══════════════════════════════════════════════════════════════
//  4. SHARED — files/dirs that persist across all releases
//             Lives in: /var/www/appname/shared/
// ═══════════════════════════════════════════════════════════════

set('shared_files', [
    '.env',
]);

set('shared_dirs', [
    'storage',        // logs, sessions, cache, file uploads
    // 'public/uploads', // uncomment if you store uploads in public/
]);


// ═══════════════════════════════════════════════════════════════
//  5. WRITABLE — dirs PHP-FPM must be able to write to
// ═══════════════════════════════════════════════════════════════

set('writable_dirs', [
    'bootstrap/cache',
    'storage',
    'storage/app',
    'storage/app/public',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
]);


// ═══════════════════════════════════════════════════════════════
//  6. CUSTOM TASKS
// ═══════════════════════════════════════════════════════════════

/**
 * Fix ownership of the entire deploy tree so PHP-FPM
 * (which runs as the app system user) can read all files.
 * Runs as root → no sudo needed.
 */
task('deploy:fix-ownership', function () {
    run('chown -R {{http_user}}:{{http_group}} {{deploy_path}}');
})->desc('Fix file ownership for PHP-FPM user');

/**
 * Gracefully reload the per-app PHP-FPM pool.
 * - Workers finish current requests before restarting (zero downtime)
 * - Also clears OPcache (each new worker boots fresh)
 */
task('php-fpm:reload', function () {
    $phpVer = run('php -r "echo PHP_MAJOR_VERSION.\'.\'.PHP_MINOR_VERSION;"');
    run("systemctl reload php{$phpVer}-fpm");
})->desc('Reload PHP-FPM pool (clears OPcache)');

/**
 * Optional: run artisan queue:restart after deploy
 * so queue workers pick up the new code.
 * Uncomment the line in the pipeline below if you use queues.
 */
task('artisan:tac:generate-sitemap', artisan('tac:generate-sitemap'))
    ->desc('Generate sitemap.xml and the entity sitemaps');

task('artisan:pulse:restart', artisan('pulse:restart'))
    ->desc('Signal the pulse:check daemon (systemd: pulse-talksatconfscom) to restart');

task('artisan:queue:restart', artisan('queue:restart'))
    ->desc('Signal queue workers to restart after next job');


// ═══════════════════════════════════════════════════════════════
//  7. DEPLOYMENT PIPELINE
//     Runs top-to-bottom on every `dep deploy`
// ═══════════════════════════════════════════════════════════════

task('deploy', [
    // ── Prepare ────────────────────────────────────────────────
    'deploy:prepare',        // creates /releases/<timestamp>/, clones repo, locks

    // ── Build ──────────────────────────────────────────────────
    'deploy:vendors',        // composer install --no-dev --optimize-autoloader

    // ── Wire up shared assets ──────────────────────────────────
    'deploy:shared',         // symlink shared/.env  → release/.env
                             // symlink shared/storage → release/storage

    // ── Permissions ────────────────────────────────────────────
    'deploy:writable',       // chown writable_dirs to http_user:http_group

    // ── Laravel bootstrap ──────────────────────────────────────
    'artisan:storage:link',  // php artisan storage:link
    'artisan:config:cache',  // php artisan config:cache
    'artisan:route:cache',   // php artisan route:cache
    'artisan:view:cache',    // php artisan view:cache
    'artisan:migrate',       // php artisan migrate --force
    'artisan:tac:generate-sitemap', // sitemap.xml + public/sitemaps (needs APP_URL)

    // ── Go live (atomic symlink swap — zero downtime) ──────────
    'deploy:symlink',        // current → /releases/<new>

    // ── Post-live ──────────────────────────────────────────────
    'deploy:fix-ownership',  // chown entire deploy tree to app user
    'php-fpm:reload',        // graceful pool reload (picks up new code)
    'artisan:pulse:restart', // systemd restarts the pulse:check daemon on new code
    // 'artisan:queue:restart', // ← uncomment if you use Laravel queues

    // ── Housekeeping ───────────────────────────────────────────
    'deploy:unlock',         // release deploy lock
    'deploy:cleanup',        // delete old releases (keep 5)
    'deploy:success',        // print success message + timing
]);

/**
 * deploy:fix-ownership chowns the whole tree (including .dep/repo) to the app
 * user, but Deployer connects as root, so git rejects the mirror with
 * "dubious ownership". Mark it safe before the code is updated.
 */
task('deploy:safe-directory', function () {
    run('git config --global --add safe.directory {{deploy_path}}/.dep/repo');
})->desc('Mark the git mirror as safe for the deploying user');

before('deploy:update_code', 'deploy:safe-directory');

// Roll back the deploy lock automatically if something fails
// (prevents your next deploy from erroring with "deploy is locked")
after('deploy:failed', 'deploy:unlock');
