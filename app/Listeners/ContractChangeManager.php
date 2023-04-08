<?php

namespace App\Listeners;

use App\Enums\ContractChangeStatus;
use App\Enums\ContractChangeType;
use App\Events\ContractChangeCanceled;
use App\Events\ContractChangingWithIncreasingAmount;
use App\Events\ContractCreated;
use App\Events\ContractTariffChanging;
use App\Models\Contract;
use App\Models\ContractChange;
use App\ValueObjects\Amount;

class ContractChangeManager
{
    public function createInitContractChange(ContractCreated $event): void
    {
        $type = ContractChangeType::init;
        $amount = $event->contract->amount;

        $this->createContractChange($event->contract, $type, $amount, $event->contract->tariff_id);
    }

    public function createIncreaseAmountContractChange(ContractChangingWithIncreasingAmount $event): void
    {
        $type = ContractChangeType::change;
        $amount = $event->amount->raw() + $event->contract->amount->raw();
        $newTariffId = $event->newTariffId;

        $this->createContractChange($event->contract, $type, $amount, $newTariffId);
    }

    public function createNewTariffContractChange(ContractTariffChanging $event): void
    {
        ContractChange::create([
            'contract_id' => $event->contract->id,
            'type' => ContractChangeType::change,
            'tariff_id' => $event->newTariffId,
            'status' => ContractChangeStatus::waitingPeriodEnd,
            'amount' => $event->contract->amount->raw(),
            'starts_at' => null,
        ]);
    }

    public function deletePendingContractChanges(ContractChangeCanceled $event)
    {
        $changesIds = $event->contract->contractChanges
            ->where('status', ContractChangeStatus::pending)
            ->pluck('id');

        ContractChange::whereIn('id', $changesIds)->delete();
    }

    private function createContractChange(
        Contract $contract,
        ContractChangeType $type,
        Amount | int $amount,
        int $tariffId,
    ): void {
        ContractChange::create([
            'contract_id' => $contract->id,
            'type' => $type,
            'tariff_id' => $tariffId,
            'status' => ContractChangeStatus::pending,
            'amount' => $amount,
            'starts_at' => null,
        ]);
    }
}
