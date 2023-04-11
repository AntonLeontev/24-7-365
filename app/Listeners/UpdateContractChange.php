<?php

namespace App\Listeners;

use App\Enums\ContractChangeStatus;
use App\Events\PaymentReceived;

class UpdateContractChange
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(PaymentReceived $event)
    {
        $changes = $event->payment->contract->contractChanges;

        if ($changes->count() === 1) {
            $changes->last()->updateOrFail([
                'status' => ContractChangeStatus::actual,
                'starts_at' => now(),
            ]);
            return;
        }

        $changes->last()->updateOrFail([
            'status' => ContractChangeStatus::waitingPeriodEnd,
        ]);
    }
}
