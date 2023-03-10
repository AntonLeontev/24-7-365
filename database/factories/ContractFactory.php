<?php

namespace Database\Factories;

use App\Models\Contract;
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
            // 'user_id' => User::factory(),
            // 'organization_id' => 1,
            'tariff_id' => rand(1,14),
            'amount' => rand(500, 8000)* 1000 * 100,
            'status' => Contract::PENDING,
        ];
    }
}
