<?php

namespace App\Listeners;

use App\Enums\ContractStatus;
use App\Events\BillingPeriodEnded;

class FinishContract
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
    public function handle(BillingPeriodEnded $event): void
    {
        $contract = $event->contract;
        $prolongate = $contract->prolongate;

        if ($prolongate || is_null($prolongate)) {
            return;
        }

        if ($contract->end() >= $contract->paid_at->addMonths($contract->duration())) {
            return;
        }

		$contract->update(['status' => ContractStatus::finished]);
    }
}
