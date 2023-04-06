<?php

namespace App\Listeners;

use App\Enums\ContractStatus;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Events\ContractFinished;
use App\Events\ContractTerminated;
use App\Models\Payment;

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

        //Если договор Canceled то сделать Terminated
        if ($contract->status->value === ContractStatus::canceled->value) {
            $contract->updateOrFail(['status' => ContractStatus::terminated->value]);
            event(new ContractTerminated($contract));
            return;
        }

        //Если последняя выплата, то завершить
        $pendingCreditPaymentsCount = $contract->payments->where('type', PaymentType::credit)
            ->where('status', PaymentStatus::pending)
            ->count();

        if ($pendingCreditPaymentsCount === 0) {
            $contract->updateOrFail(['status' => ContractStatus::finished->value]);
            event(new ContractFinished($contract));
            return;
        }
    }
}
