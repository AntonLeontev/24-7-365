<?php

namespace App\Listeners;

use App\Models\ContractChange;
use App\Models\Payment;

class CreateAdditionalPayment
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
        $contract = $event->contract;

        Payment::create([
            'account_id' => $contract->organization->accounts->first()->id,
            'contract_id' => $contract->id,
            'amount' => $event->amount,
            'type' => Payment::TYPE_DEBET,
            'status' => Payment::STATUS_PENDING,
        ]);
    }
}
