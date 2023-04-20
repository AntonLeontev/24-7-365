<?php

namespace App\Listeners;

use App\Enums\ContractStatus;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Events\ContractFinished;
use App\Events\ContractTerminated;

class CheckContractStatus
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $contract = $event->payment->contract;

        $pendingCreditPayments = $contract->payments
            ->where('type', PaymentType::credit)
            ->where('status', PaymentStatus::pending);

        //Если договор Canceled то сделать Terminated
        if (
            $contract->status === ContractStatus::canceled &&
            $pendingCreditPayments->isEmpty()
        ) {
            $contract->updateOrFail(['status' => ContractStatus::terminated]);
            event(new ContractTerminated($contract));
            return;
        }

        //Если последняя выплата, то завершить
        if ($pendingCreditPayments->isEmpty()) {
            $contract->updateOrFail(['status' => ContractStatus::finished]);
            event(new ContractFinished($contract));
            return;
        }
    }
}
