<?php

namespace App\Jobs;

use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Events\PaymentReceived;
use App\Events\PaymentSent;
use App\Models\Payment;
use App\Models\Transaction;
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


    public $tries = 2;
    public $timeout = 120;


    public function __construct(private object $transaction)
    {
    }

    public function handle(): void
    {
        $transaction = Transaction::query()->where('operation_id', $this->transaction->operationId)->first();

        if ($transaction) {
            return;
        }

        $this->saveTransactionToDb();

        if (in_array($this->transaction->rurTransfer->payerInn, config('allowed-settings.ignored_inn'))) {
            return;
        }

        foreach (config('allowed-settings.ignored_accounts') as $bic => $account) {
            if (
                $this->transaction->rurTransfer->payerBankBic === $bic &&
                $this->transaction->rurTransfer->payerAccount === $account
            ) {
                return;
            }
        }

        if ($this->type($this->transaction->direction) === PaymentType::debet) {
            $this->handleDebetPayment();
        } else {
            $this->handleCreditPayment();
        }
    }

    private function handleDebetPayment()
    {
        $payments = Payment::query()
            ->select($this->columns())
            ->leftJoin('accounts', 'payments.account_id', 'accounts.id')
            ->where('type', PaymentType::debet)
            ->where('accounts.bik', $this->transaction->rurTransfer->payerBankBic)
            ->where('accounts.payment_account', $this->transaction->rurTransfer->payerAccount)
            ->get();

        if ($payments->isEmpty()) {
            $this->log("Неопознанный входящий платеж от [{$this->transaction->rurTransfer->payerName}]");
            return;
        }

        $exactPayments = $payments->where('amount.raw', $this->transaction->amount->amount * 100)
            ->where('description', $this->transaction->paymentPurpose);

        if ($exactPayments->isEmpty()) {
            $this->log('Не удалось привязать входящий платеж от нашего клиента');
            return;
        }

        if ($exactPayments->count() > 1) {
            $this->log('Более одного платежа подходит под транзакцию банка. Не понятно к какому привязывать');
            return;
        }

        if ($exactPayments->first()->status === PaymentStatus::processed) {
            return;
        }

        $exactPayments->first()->updateOrFail(['paid_at' => now(), 'status' => PaymentStatus::processed]);
        event(new PaymentReceived($exactPayments->first()));

        Log::channel('bank')->info("Обработан платеж сумма +{$this->transaction->amount->amount}. {$this->transaction->paymentPurpose}");
    }

    private function handleCreditPayment()
    {
        $payments = Payment::query()
            ->select($this->columns())
            ->leftJoin('accounts', 'payments.account_id', 'accounts.id')
            ->where('type', PaymentType::credit)
            ->where('accounts.bik', $this->transaction->rurTransfer->payeeBankBic)
            ->where('accounts.payment_account', $this->transaction->rurTransfer->payeeAccount)
            ->get();
        
        if ($payments->isEmpty()) {
            $this->log("Неопознанный исходящий платеж на [{$this->transaction->rurTransfer->payeeName}]");
            return;
        }

        $exactPayments = $payments->where('amount.raw', $this->transaction->amount->amount * 100)
            ->where('description', $this->transaction->paymentPurpose);

        if ($exactPayments->isEmpty()) {
            $this->log('Не удалось привязать входящий платеж от нашего клиента');
            return;
        }

        if ($exactPayments->count() > 1) {
            $this->log('Более одного платежа подходит под транзакцию банка. Не понятно к какому привязывать');
            return;
        }

        if ($exactPayments->first()->status === PaymentStatus::processed) {
            return;
        }

        $exactPayments->first()->updateOrFail(['paid_at' => now(), 'status' => PaymentStatus::processed]);
        event(new PaymentSent($exactPayments->first()));

        Log::channel('bank')->info("Обработан платеж сумма -{$this->transaction->amount->amount}. {$this->transaction->paymentPurpose}");
    }

    private function saveTransactionToDb()
    {
        Transaction::create([
            'operation_id' => $this->transaction->operationId,
            'direction' => $this->transaction->direction,
            'purpose' => $this->transaction->paymentPurpose,
            'amount' => $this->transaction->amount->amount * 100,
            'currency' => $this->transaction->amount->currencyName,
            'payer_account' => $this->transaction->rurTransfer->payerAccount,
            'payer_name' => $this->transaction->rurTransfer->payerName,
            'payer_inn' => $this->transaction->rurTransfer->payerInn,
            'payer_kpp' => $this->transaction->rurTransfer->payerKpp,
            'payer_bank_name' => $this->transaction->rurTransfer->payerBankName,
            'payer_bank_bic' => $this->transaction->rurTransfer->payerBankBic,
            'payer_bank_corr_account' => $this->transaction->rurTransfer->payerBankCorrAccount,
            'payee_account' => $this->transaction->rurTransfer->payeeAccount,
            'payee_name' => $this->transaction->rurTransfer->payeeName,
            'payee_inn' => $this->transaction->rurTransfer->payeeInn,
            'payee_kpp' => $this->transaction->rurTransfer->payeeKpp,
            'payee_bank_name' => $this->transaction->rurTransfer->payeeBankName,
            'payee_bank_bic' => $this->transaction->rurTransfer->payeeBankBic,
            'payee_bank_corr_account' => $this->transaction->rurTransfer->payeeBankCorrAccount,
        ]);
    }

    private function columns(): array
    {
        return [
            'payments.amount',
            'payments.type',
            'payments.status',
            'payments.contract_id',
            'payments.planned_at',
            'payments.description',
            DB::raw('accounts.bik AS bic'),
            DB::raw('accounts.payment_account AS account'),
        ];
    }

    private function type(string $direction): PaymentType
    {
        return $direction === 'CREDIT'
            ? PaymentType::debet
            : PaymentType::credit;
    }

    private function log(string $message): void
    {
        Log::channel('bankerr')->alert($message, [
            'description' => $this->transaction->paymentPurpose,
            'name' => $this->transaction->rurTransfer->payerName,
            'bic' => $this->transaction->rurTransfer->payerBankBic,
            'account' => $this->transaction->rurTransfer->payerAccount,
            'amount' => $this->transaction->amount->amount,
        ]);
        Log::channel('telegram')->alert($message, [$this->transaction->paymentPurpose]);
    }
}
