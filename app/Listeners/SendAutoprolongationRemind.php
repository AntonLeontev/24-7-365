<?php

namespace App\Listeners;

use App\Enums\ContractChangeType;
use App\Enums\ContractStatus;
use App\Events\BillingPeriodEnded;
use App\Notifications\AutoProlongationRemind;

class SendAutoprolongationRemind
{
    public function handle(BillingPeriodEnded $event): void
    {
        $contract = $event->contract->refresh();

		if ($contract->prolongate === false) {
			return;
		}
        
        if ($contract->end()->subMonths(2)->gt(now())) {
            return;
        }
        
        if ($contract->status === ContractStatus::finished) {
            return;
        }

        $lastChange = $contract->contractChanges->last();

        if (
            $lastChange->type === ContractChangeType::prolongation &&
			$lastChange->duration === 0
        ) {
            return;
        }

        $contract->user->notify(new AutoProlongationRemind($contract));
    }
}
