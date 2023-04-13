<?php

namespace App\Listeners;

use App\Enums\ContractChangeStatus;
use App\Events\BillingPeriodEnded;

class IncreaseContractChangeDuration
{
    public function handle(BillingPeriodEnded $event)
    {
        $event->contract->contractChanges
            ->where('status', ContractChangeStatus::actual)
            ->first()
            ->increment('duration');
    }
}
