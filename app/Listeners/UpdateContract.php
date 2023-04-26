<?php

namespace App\Listeners;

use App\Enums\ContractStatus;
use App\Events\PaymentReceived;

class UpdateContract
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
    public function handle(PaymentReceived $event)
    {
        $contract = $event->payment->contract;

        if ($contract->status !== ContractStatus::init) {
			return;
        }
		
		$contract->updateOrFail(['status' => ContractStatus::active, 'paid_at' => now()]);
    }
}
