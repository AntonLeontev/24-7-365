<?php

namespace App\Listeners;

use App\Events\BillingPeriodEnded;
use App\Models\ContractChange;

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
        $changes = $event->contract->changes;

        $count = $changes
            ->where('status', ContractChange::STATUS_WAITING_FOR_PERIOD_END)
            ->count();

        if ($count === 0) {
            return;
        }

        $prevContractChange = $changes
            ->where('status', ContractChange::STATUS_ACTUAL)
            ->first();
        $newContractChange = $changes
            ->where('status', ContractChange::STATUS_WAITING_FOR_PERIOD_END)
            ->first();

        $prevContractChange->update(['status' => ContractChange::STATUS_PAST]);
        $newContractChange->update([
            'status' => ContractChange::STATUS_ACTUAL,
            'starts_at' => now(),
        ]);

        $event->contract->update([
            'amount' => $newContractChange->amount,
            'tariff_id' => $newContractChange->tariff_id,
        ]);
    }
}
