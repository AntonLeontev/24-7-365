<?php

namespace App\Listeners;

use App\Models\Contract;
use App\Models\Payment;

class ActivateContract
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
        Contract::find($event->payment->contract->id)
            ->updateOrFail(['status' => Contract::ACTIVE, 'paid_at' => now()]);
    }
}
