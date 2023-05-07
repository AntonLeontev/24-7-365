<?php

namespace Database\Factories;

use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use Illuminate\Database\Eloquent\Factories\Factory;


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
			'id' => $this->faker->uuid(),
            // 'account_id' => ,
            // 'organization_id' => ,
            // 'contract_id'=>,
            'type' => PaymentType::credit,
            'amount' => rand(2000000, 80000000),
            'status' => PaymentStatus::pending,
			'planned_at' => now(),
			'description' => 'Test payment'
        ];
    }
}
