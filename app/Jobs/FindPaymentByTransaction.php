<?php

namespace App\Jobs;

use App\DTOs\TransactionDTO;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Enums\TransactionType;
use App\Events\OzonIncomePayment;
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

    public $timeout = 30;

    public function __construct(private TransactionDTO $transaction)
    {
    }

    public function handle(): void
    {
        $transaction = Transaction::query()->where('operation_id', $this->transaction->id)->first();

        if ($transaction) {
            return;
        }

        $this->saveTransactionToDb();

        if (in_array($this->transaction->contrAgentInn, config('allowed-settings.ignored_inn'))) {
            return;
        }

        foreach (config('allowed-settings.ignored_accounts') as $bic => $account) {
            if (
                $this->transaction->contrAgentBic === $bic &&
                $this->transaction->contrAgentAccount === $account
            ) {
                return;
            }
        }

        if ($this->checkOzon()) {
            event(new OzonIncomePayment($this->transaction));

            return;
        }

        if ($this->transaction->type === TransactionType::credit) {
            $this->handleIncomePayment();
        } else {
            $this->handleOutcomePayment();
        }
    }

    private function handleIncomePayment()
    {
        $payments = DB::table('payments')
            ->select($this->columns())
            ->leftJoin('accounts', 'payments.account_id', 'accounts.id')
            ->where('payments.type', PaymentType::debet)
            ->where('accounts.bik', $this->transaction->contrAgentBic)
            ->where('accounts.payment_account', $this->transaction->contrAgentAccount)
            ->whereNull('payments.deleted_at')
            ->get();

        if ($payments->isEmpty()) {
            $this->log("Неопознанный входящий платеж от [{$this->transaction->contrAgentTitle} | {$this->transaction->amount}]");

            return;
        }

        preg_match('~договор[^\.,]*?(\d+)~mui', $this->transaction->description, $matches);

        if (empty($matches)) {
            $this->log("Не удалось распознать договор в платеже от [{$this->transaction->contrAgentTitle} | {$this->transaction->amount}]");

            return;
        }

        $exactPayments = $payments
            ->where('amount', $this->transaction->amount->raw())
            ->where('contract_id', $matches[1]);

        if ($exactPayments->isEmpty()) {
            $this->log("Не удалось привязать входящий платеж от нашего клиента [{$this->transaction->contrAgentTitle} | {$this->transaction->amount}]");

            return;
        }

        if ($exactPayments->count() > 1) {
            $this->log("Более одного платежа подходит под транзакцию банка. Не понятно к какому привязывать. [{$this->transaction->contrAgentTitle} | {$this->transaction->amount}]");

            return;
        }

        if ($exactPayments->first()->status === PaymentStatus::processed->value) {
            $this->log("Входящий платеж уже в статусе processed. [{$this->transaction->contrAgentTitle} | {$this->transaction->amount}]");

            return;
        }

        $payment = Payment::find($exactPayments->first()->id);

        $payment->updateOrFail(['paid_at' => now(), 'status' => PaymentStatus::processed]);
        event(new PaymentReceived($payment));

        Log::channel('bank')->info("Обработан платеж сумма +{$this->transaction->amount}. {$this->transaction->description}");
    }

    private function handleOutcomePayment()
    {
        $payments = DB::table('payments')
            ->select($this->columns())
            ->leftJoin('accounts', 'payments.account_id', 'accounts.id')
            ->where('payments.type', PaymentType::credit)
            ->where('accounts.bik', $this->transaction->contrAgentBic)
            ->where('accounts.payment_account', $this->transaction->contrAgentAccount)
            ->whereNull('payments.deleted_at')
            ->get();

        if ($payments->isEmpty()) {
            $this->log("Неопознанный исходящий платеж на [{$this->transaction->contrAgentTitle}]");

            return;
        }

        $exactPayments = $payments
            ->where('amount', $this->transaction->amount->raw())
            ->where('description', $this->transaction->description);

        if ($exactPayments->isEmpty()) {
            $this->log("Не удалось привязать исходящий платеж нашему клиенту [{$this->transaction->contrAgentTitle} | {$this->transaction->amount}]");

            return;
        }

        if ($exactPayments->count() > 1) {
            $this->log("Более одного платежа подходит под транзакцию банка. Не понятно к какому привязывать [{$this->transaction->contrAgentTitle} | {$this->transaction->amount}]");

            return;
        }

        if ($exactPayments->first()->status === PaymentStatus::processed->value) {
            $this->log("Исходящий платеж уже в статусе processed. [{$this->transaction->contrAgentTitle} | {$this->transaction->amount}]");

            return;
        }

        $payment = Payment::find($exactPayments->first()->id);
        $payment->updateOrFail(['paid_at' => now(), 'status' => PaymentStatus::processed]);
        event(new PaymentSent($payment));

        Log::channel('bank')->info("Обработан платеж сумма -{$this->transaction->amount}. {$this->transaction->description}");
    }

    private function saveTransactionToDb()
    {

        Transaction::create([
            'operation_id' => $this->transaction->id,
            'direction' => $this->transaction->type->value,
            'purpose' => $this->transaction->description,
            'amount' => $this->transaction->amount->raw(),
            'currency' => $this->transaction->amount->currency(),

            'payer_account' => $this->transaction->isCredit() ? $this->transaction->contrAgentAccount : settings()->payment_account,
            'payer_name' => $this->transaction->isCredit() ? $this->transaction->contrAgentTitle : settings()->organization_title,
            'payer_inn' => $this->transaction->isCredit() ? $this->transaction->contrAgentInn : settings()->inn,
            'payer_kpp' => $this->transaction->isCredit() ? $this->transaction->contrAgentKpp : settings()->kpp,
            'payer_bank_name' => $this->transaction->isCredit() ? $this->transaction->contrAgentBank : settings()->bank,
            'payer_bank_bic' => $this->transaction->isCredit() ? $this->transaction->contrAgentBic : settings()->bik,
            'payer_bank_corr_account' => $this->transaction->isCredit() ? $this->transaction->contrAgentCorrespondent : settings()->correspondent_account,

            'payee_account' => $this->transaction->isDebet() ? $this->transaction->contrAgentAccount : settings()->payment_account,
            'payee_name' => $this->transaction->isDebet() ? $this->transaction->contrAgentTitle : settings()->organization_title,
            'payee_inn' => $this->transaction->isDebet() ? $this->transaction->contrAgentInn : settings()->inn,
            'payee_kpp' => $this->transaction->isDebet() ? $this->transaction->contrAgentKpp : settings()->kpp,
            'payee_bank_name' => $this->transaction->isDebet() ? $this->transaction->contrAgentBank : settings()->bank,
            'payee_bank_bic' => $this->transaction->isDebet() ? $this->transaction->contrAgentBic : settings()->bik,
            'payee_bank_corr_account' => $this->transaction->isDebet() ? $this->transaction->contrAgentCorrespondent : settings()->correspondent_account,
        ]);
    }

    private function columns(): array
    {
        return [
            'payments.id',
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

    private function checkOzon()
    {
        if ($this->transaction->contrAgentInn === '7704217370') {
            return true;
        }

        if (preg_match('/ИНН 7704217370 по реестру/i', $this->transaction->description)) {
            return true;
        }

        if (preg_match('/ИР-200853\/23 от 14\.06\.2023/i', $this->transaction->description)) {
            return true;
        }

        return false;
    }

    private function log(string $message): void
    {
        Log::channel('bankerr')->alert($message, [
            'description' => $this->transaction->description,
            'name' => $this->transaction->contrAgentTitle,
            'bic' => $this->transaction->contrAgentBic,
            'account' => $this->transaction->contrAgentAccount,
            'amount' => $this->transaction->amount->amount(),
        ]);
        Log::channel('telegram')->alert($message, [$this->transaction->description]);
    }
}
