<?php
namespace Deployer;

require 'recipe/common.php';

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true); 

// Shared files/dirs between deploys 
set('shared_files', []);
set('shared_dirs', []);

// Writable dirs by web server 
set('writable_dirs', []);
set('allow_anonymous_stats', false);

// Hosts

host(getenv('SSH_HOSTNAME'))
    ->stage('staging')
    ->set('deploy_path', '~/sites/staging.wiringa.nl');

host(getenv('SSH_HOSTNAME'))
    ->stage('production')
    ->set('deploy_path', '~/sites/www.wiringa.nl');

// Tasks

task('deploy:update_code', function () {
    $remote = getenv('SSH_HOSTNAME');

    // Copying artifacts to remote
    runLocally('scp build/deployment.tar.gz ' . $remote . ':sites/staging.wiringa.nl');

    run('cd {{deploy_path}}; tar zxvf -C {{release_path}} deployment.tar.gz');
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

// [Optional] If deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
