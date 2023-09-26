<?php

namespace App\Jobs;

use App\Events\PaymentSentToBank;
use App\Models\Payment;
use App\Support\Services\TochkaBank\TochkaBankService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateBankPayment implements ShouldQueue
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
    public function handle(TochkaBankService $service): void
    {
        $service->createPayment($this->payment);

        event(new PaymentSentToBank($this->payment));
    }
}
