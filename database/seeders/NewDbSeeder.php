<?php

namespace Database\Seeders;

use App\Events\ContractCreated;
use App\Models\Account;
use App\Models\Contract;
use App\Models\ContractChange;
use App\Models\Organization;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

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
        for ($i = 0; $i < 10; $i++) {
            $user = User::factory()
            ->create();
            
            $organization = Organization::factory()
            ->for($user)
            ->create();
            
            $account = Account::factory()
            ->for($organization)
            ->create();
            
            $contracts = Contract::factory()
            ->count(rand(2, 8))
            ->for($user)
            ->for($organization)
            ->create();

            foreach ($contracts as $contract) {
                event(new ContractCreated($contract));
            }
            
            // $payment = Payment::factory()
            // ->count(rand(3, 11))
            // ->for($account)
            // ->for($contracts[0])
            // ->create();
        }
        
        $users = User::all();
        
        foreach ($users as $user) {
            $user->assignRole('Клиент');
        }
    }
}
