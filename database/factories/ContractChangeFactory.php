<?php

namespace Database\Factories;

use App\Enums\ContractChangeStatus;
use App\Enums\ContractChangeType;
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
            'type' => $this->faker->randomElement(ContractChangeType::cases()),
            'tariff_id' => $contract->tariff_id,
            'status' => $this->faker->randomElement(ContractChangeStatus::cases()),
            'amount' => $contract->amount,
            'starts_at' => null,
        ];
    }
}
