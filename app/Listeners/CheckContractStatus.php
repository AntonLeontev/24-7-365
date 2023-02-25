<?php

namespace App\Listeners;

use App\Events\ContractFinished;
use App\Events\ContractTerminated;
use App\Models\Contract;
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
        if ($contract->status === Contract::CANCELED) {
            $contract->updateOrFail(['status' => Contract::TERMINATED]);
			event(new ContractTerminated($contract));
            return;
        }

        //Если последняя выплата, то завершить
        $pendingCreditPaymentsCount = $contract->payments->where('type', Payment::TYPE_CREDIT)
            ->where('status', Payment::STATUS_PENDING)
            ->count();

        if ($pendingCreditPaymentsCount === 0) {
            $contract->updateOrFail(['status' => Contract::FINISHED]);
			event(new ContractFinished($contract));
            return;
        }
    }
}
