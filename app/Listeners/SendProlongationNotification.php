<?php

namespace App\Listeners;

use App\Events\ContractProlongated;
use App\Notifications\ContractProlongated as ProlongatedNotification;

class SendProlongationNotification
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
    public function handle(ContractProlongated $event)
    {
        auth()->user()->notify(new ProlongatedNotification($event->contract));
    }
}
