<?php

namespace App\Listeners;

use App\Events\ContractCreated;
use App\Notifications\ContractCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendContractCreatedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ContractCreated $event): void
    {
        $user = $event->contract->user;
		
		$user->notify(new ContractCreatedNotification($event->contract));
    }
}
