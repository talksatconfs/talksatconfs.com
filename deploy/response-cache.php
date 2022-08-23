<?php

namespace Deployer;

desc('Run "php artisan responsecache:clear" on the host.');
task('artisan:responsecache:clear', function () {
    cd('{{release_path}}');
    run('php artisan responsecache:clear');
});
