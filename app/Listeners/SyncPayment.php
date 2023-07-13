<?php

namespace App\Listeners;

use App\Contracts\AccountingSystemContract;
use App\Events\PaymentReceived;

class SyncPayment
{
    /**
     * Create the event listener.
     */
    public function __construct(private AccountingSystemContract $service)
    {
    }

    /**
     * Handle the event.
     */
    public function handle(PaymentReceived $event): void
    {
		$this->service->syncPayment($event->payment);
    }
}
