<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RefreshCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '24:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refreshes application';


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (app()->isProduction()) {
            echo 'На проде нельзя!' . PHP_EOL;
            return Command::FAILURE;
        }

        $this->call('migrate:fresh');
        $this->call('db:seed', ['--class' => 'SuperUserSeeder']);
        $this->call('db:seed', ['--class' => 'RolesPermissionsSeeder']);
        $this->call('db:seed', ['--class' => 'TestUsersWithRolesSeeder']);
        $this->call('db:seed', ['--class' => 'UsersSeeder']);
        $this->call('db:seed', ['--class' => 'TariffsSeeder']);
        $this->call('db:seed', ['--class' => 'ApplicationSettingsSeeder']);

        $this->call('cache:clear');

        return Command::SUCCESS;
    }
}
