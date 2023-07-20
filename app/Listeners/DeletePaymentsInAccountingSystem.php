<?php

namespace App\Listeners;

use App\Contracts\AccountingSystemContract;
use App\Events\PaymentsDeleted;

class DeletePaymentsInAccountingSystem
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
    public function handle(PaymentsDeleted $event): void
    {
        foreach ($event->payments as $payment) {
            $this->service->deletePayment($payment);
        }
    }
}
