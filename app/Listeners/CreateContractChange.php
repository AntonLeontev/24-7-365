<?php

namespace App\Listeners;

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
            $event instanceof ContractCreated => ContractChange::TYPE_INITIAL,
            $event instanceof ContractAmountIncreased => ContractChange::TYPE_INCREASE_AMOUNT,
            default => ContractChange::TYPE_INITIAL,
        };

        $amount = match (true) {
            $event instanceof ContractCreated => $contract->amount,
            $event instanceof ContractAmountIncreased => $event->amount,
            default => $contract->amount,
        };

        ContractChange::create([
            'contract_id' => $contract->id,
            'type' => $type,
            'tariff_id' => $contract->tariff->id,
            'status' => ContractChange::STATUS_PENDING,
            'amount' => $amount,
            'starts_at' => null,
        ]);
    }
}
