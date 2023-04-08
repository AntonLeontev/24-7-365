<?php

namespace App\Listeners;

use App\Enums\ContractChangeStatus;
use App\Events\BillingPeriodEnded;

class IncreaseContractChangeDuration
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
        $event->contract->contractChanges
            ->where('status', ContractChangeStatus::actual)
            ->first()
            ->increment('duration');
    }
}
