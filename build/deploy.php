<?php
namespace Deployer;

require 'recipe/common.php';

// Shared files/dirs between deploys 
set('shared_files', [
    '.env'
]);

set('shared_dirs', [
    'storage/logs',
    'storage/app/public/uploads'
]);

// Writable dirs by web server 
set('writable_dirs', []);
set('allow_anonymous_stats', false);

host('staging')
    ->hostname('artemis.wiringa.nl')
    ->set('deploy_path', '~/sites/staging.wiringa.nl');

host('production')
    ->stage('artemis.wiringa.nl')
    ->set('deploy_path', '~/sites/www.wiringa.nl');

// Tasks

task('deploy:update_code', function () {
    // Copying artifacts to remote
    runLocally('scp deployment.tar.gz artemis.wiringa.nl:sites/staging.wiringa.nl');

    run('cd {{deploy_path}}; tar zxvfC {{release_path}} deployment.tar.gz');
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
    'deploy:vendors',
    'deploy:clear_paths',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
    'success'
]);

//task('deploy:symlink:storage', function () {
//    run('php artisan storage:link');
//});
//after('deploy:symlink', 'deploy:symlink:storage');

// [Optional] If deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
