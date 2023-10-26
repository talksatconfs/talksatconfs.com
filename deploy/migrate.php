<?php

namespace Deployer;


// Talks at Confs
desc('Run "php artisan migrate --database=talksatconfs --path=domain/TalksAtConfs/Database/Migrations --force" on the host.');
task('artisan:migrate:talksatconfs', function () {
    cd('{{release_path}}');
    run('php artisan migrate --database=talksatconfs --path=domain/TalksAtConfs/Database/Migrations --force');
});
