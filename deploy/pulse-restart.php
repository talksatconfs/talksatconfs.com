<?php

namespace Deployer;

desc('Run "php artisan pulse:restart" on the host so the pulse:check daemon picks up the new release.');
task('artisan:pulse:restart', function () {
    cd('{{release_path}}');
    run('php artisan pulse:restart');
});
