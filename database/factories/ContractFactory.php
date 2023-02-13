<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

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
      
        $user_id = rand(1,20);
        
       
        return [
            'user_id' => $user_id,
            'organization_id' => User::with('organization')->find($user_id)->organization->id,
            'tariff_id' => rand(1,14),
            'amount' => rand(50000000,80000000),
            'status' => 1,
        ];
    }
}
