<?php

namespace App\Listeners;

use App\Contracts\AccountingSystemContract;
use App\Events\OzonIncomePayment;
use Illuminate\Support\Facades\Log;

class CreateAccountingSystemIncome
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
    public function handle(OzonIncomePayment $event): void
    {
        $this->service->createIncomeFromOzon($event->transaction);

        Log::channel('telegram')->debug('Платеж от Озона', [$event->transaction->amount->format(2) ,$event->transaction->description]);
    }
}
