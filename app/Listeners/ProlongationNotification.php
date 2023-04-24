<?php

namespace App\Listeners;

use App\Events\ContractProlongated;

class ProlongationNotification
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
        auth()->user()->notify(new ContractProlongated($event->contract));
    }
}
