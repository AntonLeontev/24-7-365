<?php

namespace App\Listeners;

use App\Events\BillingPeriodEnded;
use App\Models\ContractChange;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class IncreaseContractChangeDuration
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
			->where('status', ContractChange::STATUS_ACTUAL)
			->first()
			->increment('duration');
    }
}
