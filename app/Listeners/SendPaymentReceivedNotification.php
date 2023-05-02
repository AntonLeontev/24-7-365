<?php

namespace App\Listeners;

use App\Events\PaymentReceived;
use App\Notifications\PaymentReceivedNotification;

class SendPaymentReceivedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(PaymentReceived $event): void
    {
        $user = $event->payment->contract->user;

        $user->notify(new PaymentReceivedNotification($event->payment));
    }
}
