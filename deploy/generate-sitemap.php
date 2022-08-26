<?php

namespace Deployer;

desc('Run "php artisan tac:generate-sitemap" on the host.');
task('artisan:tac:generate-sitemap', function () {
    cd('{{release_path}}');
    run('php artisan tac:generate-sitemap');
});
