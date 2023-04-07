<?php

namespace App\Listeners;

use App\Enums\ContractChangeStatus;
use App\Enums\ContractChangeType;
use App\Events\ContractAmountIncreasing;
use App\Events\ContractCreated;
use App\Models\Contract;
use App\Models\ContractChange;
use App\ValueObjects\Amount;

class ContractChangeCreator
{
    public function createInitContractChange(ContractCreated $event): void
    {
        $type = ContractChangeType::init;
        $amount = $event->contract->amount;

        $this->createContractChange($event->contract, $type, $amount);
    }

    public function createIncreaseAmountContractChange(ContractAmountIncreasing $event): void
    {
        $type = ContractChangeType::increaseAmount;
        $amount = $event->amount->raw() + $event->contract->amount->raw();

        $this->createContractChange($event->contract, $type, $amount);
    }

    private function createContractChange(Contract $contract, ContractChangeType $type, Amount | int $amount): void
    {
        ContractChange::create([
            'contract_id' => $contract->id,
            'type' => $type,
            'tariff_id' => $contract->tariff->id,
            'status' => ContractChangeStatus::pending,
            'amount' => $amount,
            'starts_at' => null,
        ]);
    }
}
