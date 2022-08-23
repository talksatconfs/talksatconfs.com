<?php

namespace Deployer;

desc('Run "php artisan horizon:terminate" on the host.');
task('artisan:horizon:terminate', function () {
    cd('{{release_path}}');
    run('php artisan horizon:terminate');
});
