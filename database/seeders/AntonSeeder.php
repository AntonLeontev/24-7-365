<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AntonSeeder extends Seeder
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
            'first_name' => 'Anton',
            'email' => 'aner-anton@yandex.ru',
            'password' => bcrypt('12345678'),
        ]);

        $user->assignRole('Клиент');
    }
}
