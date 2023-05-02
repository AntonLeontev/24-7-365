<?php

namespace Deployer;

require 'recipe/laravel.php';
require 'recipe/deploy/push.php';

// Config

set('repository', 'https://github.com/AntonLeontev/24-7-365.git');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('45.146.165.254')
    ->set('remote_user', 'deployer')
    ->set('deploy_path', '~/24-7-365');

// Tasks

task('build', function () {
    cd('{{release_path}}');
    run('npm install');
    run('npm run build');
});

task('making writable', function () {
    cd('~/24-7-365/current/storage');
    run('chmod -R 777 ./logs');
    run('chmod -R 777 ./framework');
});
    
    // Hooks

// after('deploy:publish', 'build');

after('deploy:failed', 'deploy:unlock');

// after('push', 'making writable');
