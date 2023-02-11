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
        Permission::create(['name' => 'ban other users']);
        Permission::create(['name' => 'see own profile']);
        Permission::create(['name' => 'create users']);

        
        $role = Role::create(['name' => 'Клиент']);
        $role->givePermissionTo('see own profile');


        $role = Role::create(['name' => 'Админ']);
        $role->givePermissionTo('see other profiles');
        $role->givePermissionTo('assign roles');
        $role->givePermissionTo('ban other users');
        $role->givePermissionTo('create users');


        $role = Role::create(['name' => 'АСБК']);


        $role = Role::create(['name' => 'Юрист']);
    }
}
