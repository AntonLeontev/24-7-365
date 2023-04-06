<?php

namespace App\Listeners;

use App\Enums\ContractChangeStatus;
use App\Events\BillingPeriodEnded;

class ApplyContractChanges
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
    public function handle(BillingPeriodEnded $event)
    {
        $changes = $event->contract->contractChanges;

        $count = $changes
            ->where('status', ContractChangeStatus::waitingPeriodEnd->value)
            ->count();

        if ($count === 0) {
            return;
        }

        $prevContractChange = $changes
            ->where('status', ContractChangeStatus::actual->value)
            ->first();
        $newContractChange = $changes
            ->where('status', ContractChangeStatus::waitingPeriodEnd->value)
            ->first();

        $prevContractChange->update(['status' => ContractChangeStatus::past->value]);
        $newContractChange->update([
            'status' => ContractChangeStatus::actual->value,
            'starts_at' => now(),
        ]);

        $event->contract->update([
            'amount' => $newContractChange->amount,
            'tariff_id' => $newContractChange->tariff_id,
        ]);
    }
}
