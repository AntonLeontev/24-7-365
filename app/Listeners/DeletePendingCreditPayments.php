<?php

namespace App\Listeners;

use App\Support\Managers\UpdateCreditPaymentsManager;

class DeletePendingCreditPayments
{
    public function __construct(public UpdateCreditPaymentsManager $manager)
    {
    }

    public function handle($event)
    {
        $contract = $event->contract ?? $event->payment->contract;

        $this->manager->deletePendingPayments($contract);
    }
}
