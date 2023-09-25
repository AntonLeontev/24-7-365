<?php

namespace Database\Factories;

use App\Enums\PaymentType;
use App\Models\Contract;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profitability>
 */
class ProfitabilityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'contract_id' => Contract::inRandomOrder()->first()->id,
            'payment_id' => Payment::where('type', PaymentType::credit)->inRandomOrder()->first()->id,
            'amount' => random_int(1000, 150000),
            'accrued_at' => now(),
        ];
    }
}
