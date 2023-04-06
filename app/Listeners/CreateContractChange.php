<?php

namespace App\Listeners;

use App\Enums\ContractChangeStatus;
use App\Enums\ContractChangeType;
use App\Events\ContractAmountIncreased;
use App\Events\ContractCreated;
use App\Models\ContractChange;

class CreateContractChange
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $contract = $event->contract;
        
        $type = match (true) {
            $event instanceof ContractCreated => ContractChangeType::init->value,
            $event instanceof ContractAmountIncreased => ContractChangeType::increaseAmount->value,
            default => ContractChangeType::init->value,
        };

        $amount = match (true) {
            $event instanceof ContractCreated => $contract->amount,
            $event instanceof ContractAmountIncreased => $event->amount + $contract->amount->raw(),
            default => $contract->amount,
        };

        ContractChange::create([
            'contract_id' => $contract->id,
            'type' => $type,
            'tariff_id' => $contract->tariff->id,
            'status' => ContractChangeStatus::pending->value,
            'amount' => $amount,
            'starts_at' => null,
        ]);
    }
}
