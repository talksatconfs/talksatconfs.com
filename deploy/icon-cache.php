<?php

namespace Deployer;

desc('Run "php artisan icon:cache" on the host.');
task('artisan:icon:cache', function () {
    cd('{{release_path}}');
    run('php artisan icon:cache');
});
