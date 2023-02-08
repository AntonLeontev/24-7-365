<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class TestUsersWithRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            ['email' => 'client@test.ru', 'first_name' => 'Клиент', 'role' => 'Клиент'],
            ['email' => 'admin@test.ru', 'first_name' => 'Админ', 'role' => 'Админ'],
            ['email' => 'asbk@test.ru', 'first_name' => 'АСБК', 'role' => 'АСБК'],
            ['email' => 'lawyer@test.ru', 'first_name' => 'Юрист', 'role' => 'Юрист'],
        ];
        
        foreach ($users as $user) {
            $this->create($user);
        }
    }

    private function create(array $data)
    {
        $user = User::create([
            'email' => $data['email'],
            'password' => bcrypt('12345678'),
            'first_name' => $data['first_name']
        ]);
        
        $user->assignRole($data['role']);
    }
}
