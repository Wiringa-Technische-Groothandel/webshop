<?php

namespace Deployer;

require 'recipe/laravel.php';

// Shared files/dirs between deploys
set('shared_files', [
    '.env'
]);

set('shared_dirs', [
    'storage/logs',
    'storage/app/public/uploads',
    'storage/app/public/downloads',
    'storage/app/import'
]);

set('writable_dirs', [
    'bootstrap/cache',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
]);

set('allow_anonymous_stats', false);

set('http_user', 'www-data');
set('http_group', 'www-data');
set('writable_mode', 'chmod');
set('writable_chmod_mode', '0777');

host('staging')
    ->hostname(getenv('SSH_HOSTNAME'))
    ->set('deploy_path', '~/sites/staging.wiringa.nl');

host('production')
    ->hostname(getenv('SSH_HOSTNAME'))
    ->set('deploy_path', '~/sites/www.wiringa.nl');

// Tasks

task('deploy:update_code', function () {
    $remote = getenv('SSH_HOSTNAME');

    // Copying artifacts to remote
    runLocally('scp deployment.tar.gz ' . $remote . ':{{deploy_path}}');

    run('cd {{deploy_path}}; tar -C {{release_path}} -zxvf deployment.tar.gz');
});

desc('Deploy your project');
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:writable',
    'artisan:storage:link',
    'artisan:view:clear',
    'artisan:config:cache',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
]);

task('cleanup:code_archive', function () {
    run('rm {{deploy_path}}/deployment.tar.gz');
});
before('cleanup', 'cleanup:code_archive');

// [Optional] If deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
