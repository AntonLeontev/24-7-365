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
        Permission::create(['name' => 'assign roles']);
        Permission::create(['name' => 'see own profile']);
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'block users']);
        Permission::create(['name' => 'change settings']);
        Permission::create(['name' => 'update sber token']);
        Permission::create(['name' => 'see invoices']);
        Permission::create(['name' => 'create news']);

        
        $role = Role::create(['name' => 'Клиент']);
        $role->givePermissionTo('see own profile');


        $role = Role::create(['name' => 'Админ']);
        $role->givePermissionTo('see other profiles');
        $role->givePermissionTo('assign roles');
        $role->givePermissionTo('create users');
        $role->givePermissionTo('block users');
        $role->givePermissionTo('change settings');
        $role->givePermissionTo('update sber token');
        $role->givePermissionTo('see invoices');
        $role->givePermissionTo('create news');


        $role = Role::create(['name' => 'АСБК']);


        $role = Role::create(['name' => 'Юрист']);
    }
}
