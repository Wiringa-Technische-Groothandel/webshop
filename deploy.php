<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'WTG Webshop');

// Project repository
set('repository', 'git+ssh://git@git.tjwiringa.nl:22222/luna/wtg.git');

set('http_user', 'wiringa');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', false);

// Laravel shared dirs
set('shared_dirs', [
    'storage',
]);

// Laravel shared file
set('shared_files', []);

// Writable dirs by web server 
set('writable_dirs', []);
set('allow_anonymous_stats', false);

set('bin/php', 'cd ~/docker/wiringa.nl && docker-compose exec -T php php');
set('bin/composer', 'composer');

host('artemis')
    ->stage('staging')
    ->set('deploy_path', '/httpdocs')
    ->set('branch', 'release');

// Tasks
//
//task('build', function () {
//    run('cd {{release_path}} && build');
//});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Copy env file before migration

before('artisan:config:cache', 'deploy:environment');
task('deploy:environment', function () {
    run('cp /config/environment/laravel {{release_path}}/.env');
});

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');

