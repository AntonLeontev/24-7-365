<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        Permission::create(['name' => 'see other profiles']);
        Permission::create(['name' => 'edit other profiles']);
        Permission::create(['name' => 'ban other profiles']);

        
        $role = Role::create(['name' => 'Клиент']);


        $role = Role::create(['name' => 'Админ']);
        $role->givePermissionTo('see other profiles');
        $role->givePermissionTo('edit other profiles');
        $role->givePermissionTo('ban other profiles');

        $role = Role::create(['name' => 'АСБК']);


        $role = Role::create(['name' => 'Юрист']);
    }
}
