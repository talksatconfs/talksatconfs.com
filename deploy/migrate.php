<?php

namespace Deployer;

// Comics
desc('Run "php artisan migrate --database=comics --path=domain/Comics/Database/Migrations --force" on the host.');
task('artisan:migrate:comics', function () {
    cd('{{release_path}}');
    run('php artisan migrate --database=comics --path=domain/Comics/Database/Migrations --force');
});

// Rajini Jokes
desc('Run "php artisan migrate --database=rajini_jokes --path=domain/RajiniJokes/Database/Migrations --force" on the host.');
task('artisan:migrate:rajini_jokes', function () {
    cd('{{release_path}}');
    run('php artisan migrate --database=rajini_jokes --path=domain/RajiniJokes/Database/Migrations --force');
});

// Talks at Confs
desc('Run "php artisan migrate --database=talksatconfs --path=domain/TalksAtConfs/Database/Migrations --force" on the host.');
task('artisan:migrate:talksatconfs', function () {
    cd('{{release_path}}');
    run('php artisan migrate --database=talksatconfs --path=domain/TalksAtConfs/Database/Migrations --force');
});

// Misc
desc('Run "php artisan migrate --database=misc --path=domain/Misc/Database/Migrations --force" on the host.');
task('artisan:migrate:misc', function () {
    cd('{{release_path}}');
    run('php artisan migrate --database=misc --path=domain/Misc/Database/Migrations --force');
});
