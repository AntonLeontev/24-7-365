<?php

namespace App\Listeners;

use App\Models\ContractChange;
use Illuminate\Database\Eloquent\Collection;

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
        $changes = $event->payment->contract->changes;

        if ($changes->count() === 1) {
            $changes->last()->updateOrFail([
                'status' => ContractChange::STATUS_ACTUAL,
                'starts_at' => now(),
            ]);
            return;
        }

        $changes->last()->updateOrFail([
            'status' => ContractChange::STATUS_WAITING_FOR_PERIOD_END,
        ]);
    }
}
