<?php

namespace App\Jobs;

use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FindPaymentByTransaction implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;


    public function __construct(private object $transaction)
    {
    }

    public function handle(Payment $payment): void
    {
        $payments = $payment->query()
            ->select([
                'payments.amount',
                'payments.type',
                'payments.status',
                'payments.contract_id',
                'payments.planned_at',
                'payments.description',
                DB::raw('accounts.bik AS bic'),
                DB::raw('accounts.payment_account AS account'),
            ])
            ->leftJoin('accounts', 'payments.account_id', 'accounts.id')
            ->where('type', $this->type($this->transaction->direction))
            ->where('bic', $this->transaction->RURTransfer->payerBankBic)
            ->where('account', $this->transaction->RURTransfer->payerAccount)
			->where('status', PaymentStatus::pending)
            ->get();

        Log::channel('bank')->info("Обработан {$this->type($this->transaction->direction)} платеж на сумму {$this->transaction->amount->amount}. {$this->transaction->paymentPurpose}");
    }

    private function type(string $direction): PaymentType
    {
        return $direction === 'CREDIT'
            ? PaymentType::credit
            : PaymentType::debet;
    }
}
