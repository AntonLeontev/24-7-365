<?php

namespace App\Listeners;

use App\Support\CreditPaymentsManager;

class DeletePendingCreditPayments
{
    public function __construct(public CreditPaymentsManager $manager)
    {
    }

    public function handle($event)
    {
        $contract = $event->contract ?? $event->payment->contract;

        $this->manager->deletePendingPayments($contract);
    }
}
