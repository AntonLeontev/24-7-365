<?php

namespace App\Listeners;

use App\Events\ContractFinished;
use App\Notifications\ContractFinishedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendContractFinishedNotification
{
    public function handle(ContractFinished $event): void
    {
        $user = $event->contract->user;

		$user->notify(new ContractFinishedNotification($event->contract));
    }
}
