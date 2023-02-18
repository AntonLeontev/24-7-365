<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Account;
use App\Models\Contract;
use App\Models\Organization;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
       
        return [
            //'account_id' => ,
            //'organization_id' => ,
            //'contract_id'=>,
            'type'=>2,
            'amount' => rand(2000000,80000000),
            'status' => 1,
        ];
    }
}
