<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::factory(30)->create();

        foreach ($users as $user) {
            $user->assignRole('Клиент');
        }

        Organization::factory(30)->create();
    }
}
