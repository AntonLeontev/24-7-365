<?php

namespace Database\Seeders;

use App\Models\Contract;
use App\Models\ContractChange;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class ContractsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contracts = Contract::factory(50)->create();

        foreach ($contracts as $contract) {
            ContractChange::factory()
                ->state(new Sequence(
                    [
                        'contract_id' => $contract->id,
                        'tariff_id' => $contract->tariff_id,
                        'amount' => $contract->amount,
                        'type' => ContractChange::TYPE_INITIAL,
                        'status' => ContractChange::STATUS_PENDING,
                    ]
                ))
                ->create();
        }
    }
}
