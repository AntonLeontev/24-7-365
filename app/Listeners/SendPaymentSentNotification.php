<?php

namespace App\Listeners;

use App\Events\PaymentSent;
use App\Notifications\PaymentSentNotification;

class SendPaymentSentNotification
{
    public function handle(PaymentSent $event): void
    {
        $user = $event->payment->contract->user;

        $user->notify(new PaymentSentNotification($event->payment));
    }
}
