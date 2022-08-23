<?php

namespace Deployer;

desc('Run "php artisan cat3:tac-sitemap" on the host.');
task('artisan:cat3:tac-sitemap', function () {
    cd('{{release_path}}');
    run('php artisan cat3:tac-sitemap');
});
