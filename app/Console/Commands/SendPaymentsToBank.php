<?php

namespace App\Console\Commands;

use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Events\PaymentSentToBank;
use App\Jobs\CreateBankPayment;
use App\Models\Payment;
use App\Support\Services\Sber\SberBusinessApiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendPaymentsToBank extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '24:send-payments-to-bank';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $payments = Payment::query()
            ->where('type', PaymentType::credit)
			->where('status', PaymentStatus::pending)
            ->where('planned_at', '<=', now())
            ->get();

		foreach ($payments as $payment) {
			dispatch(new CreateBankPayment($payment));
		}

		Log::channel('schedule')->info("В банк отправлено {$payments->count()} платежей");
    }
}
