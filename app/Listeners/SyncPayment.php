<?php

namespace App\Listeners;

use App\Contracts\AccountingSystemContract;
use App\Events\PaymentReceived;
use App\Jobs\SyncPaymentInAccountingSystem;

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
		if (app()->isProduction()) {
			dispatch(new SyncPaymentInAccountingSystem($event->payment));
		}
    }
}
