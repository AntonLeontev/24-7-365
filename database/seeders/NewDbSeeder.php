<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Organization;
use App\Models\User;
use App\Models\Account;
use App\Models\Contract;
use App\Models\Payment;

class NewDbSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      
        //$organization = Organization::factory()->count(3)->create();
        for($i=0;$i<10;$i++){
        
			$user = User::factory()
			->create();
			
			$organization = Organization::factory()
			->for($user)
			->create();
			
			$account = Account::factory()
			->for($organization)
			->create();   
			
			$contract = Contract::factory()
			->count(rand(2,8))
			->for($user)
			->for($organization)
			->create();
			
			$payment = Payment::factory()
			->count(rand(3,11))
			->for($account)
			->for($contract[0])
			->create();
        
        }
        
        $users = User::all();
        
        foreach ($users as $user) {
            $user->assignRole('Клиент');
        }
        
        
      
    }
}
