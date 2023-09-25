<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class HelperSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            $user->assignRole('Клиент');
        }
    }
}
