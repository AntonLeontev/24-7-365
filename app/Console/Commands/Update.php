<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class Update extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $role = Role::where('name', 'АСБК')->first();
        $role->givePermissionTo('see other profiles');
        $role->givePermissionTo('see invoices');
    }
}
