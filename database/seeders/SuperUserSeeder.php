<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class SuperUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		//TODO сменить пароль на перед продакшеном
        $user = User::create([
			'first_name' => 'Superuser', 
			'email' => 'superuser@test.ru',
			'password' => bcrypt('12345678'),
		]);

		$role = Role::create(['name' => 'Superuser']);

		$user->assignRole($role->name);
    }
}
