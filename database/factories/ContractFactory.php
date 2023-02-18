<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Organization;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contract>
 */
class ContractFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
      
       
       
        return [
            //'user_id' => User::factory(),
            //'organization_id'=>,
            'tariff_id' => rand(1,14),
            'amount' => rand(50000000,80000000),
            'status' => 1,
        ];
    }
}
