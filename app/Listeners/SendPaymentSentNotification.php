<?php

namespace App\Listeners;

use App\Events\PaymentSent;
use App\Notifications\PaymentSentNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPaymentSentNotification
{
    public function handle(PaymentSent $event): void
    {
        $user = $event->payment->contract->user;

		$user->notify(new PaymentSentNotification($event->payment));
    }
}
