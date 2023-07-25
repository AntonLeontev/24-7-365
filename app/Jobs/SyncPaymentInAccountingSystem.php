<?php

namespace App\Jobs;

use App\Contracts\AccountingSystemContract;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncPaymentInAccountingSystem implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private Payment $payment)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(AccountingSystemContract $service): void
    {
        $service->syncPayment($this->payment);
    }
}
