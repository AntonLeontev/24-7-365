<?php

namespace App\Listeners;

use App\Contracts\AccountingSystemContract;
use App\Events\PaymentSent;

class MarkOutcomePaymentPaidInAccountingSystem
{
    /**
     * Create the event listener.
     */
    public function __construct(public AccountingSystemContract $service)
    {
    }

    /**
     * Handle the event.
     */
    public function handle(PaymentSent $event): void
    {
        $payment = $event->payment;

        $this->service->syncPayment($payment);
    }
}
