<?php

namespace App\Listeners;

use App\Models\Payment;

class CreateIncomingPayment
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
        Payment::create([
            'account_id' => $event->contract->organization->accounts->first()->id,
			'contract_id' => $event->contract->id,
			'amount' => $event->contract->amount,
			'type' => Payment::TYPE_DEBET,
			'status' => Payment::STATUS_PENDING,
        ]);
    }
}
