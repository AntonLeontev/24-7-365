<?php

namespace App\Listeners;

use App\Enums\ContractChangeStatus;

class UpdateContractChange
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
        $changes = $event->payment->contract->contractChanges;

        if ($changes->count() === 1) {
            $changes->last()->updateOrFail([
                'status' => ContractChangeStatus::actual->value,
                'starts_at' => now(),
            ]);
            return;
        }

        $changes->last()->updateOrFail([
            'status' => ContractChangeStatus::waitingPeriodEnd->value,
        ]);
    }
}
