<?php

namespace Database\Factories;

use App\Models\Contract;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContractChange>
 */
class ContractChangeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $contract = Contract::inRandomOrder()->first();

        return [
            'contract_id' => $contract->id,
            'type' => random_int(0, 3),
            'tariff_id' => $contract->tariff_id,
            'status' => random_int(1, 4),
            'amount' => $contract->amount,
            'starts_at' => now(),
        ];
    }
}
