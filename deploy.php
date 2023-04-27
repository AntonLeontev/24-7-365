<?php

namespace Deployer;

require 'recipe/laravel.php';

// Config

set('repository', 'https://github.com/AntonLeontev/24-7-365.git');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('45.146.165.254')
    ->set('remote_user', 'deployer')
    ->set('deploy_path', '~/24-7-365');

// Hooks

task('build', function () {
    cd('{{release_path}}');
    run('npm install');
    run('npm run build');
});

after('deploy:update_code', 'build');

after('deploy:failed', 'deploy:unlock');
