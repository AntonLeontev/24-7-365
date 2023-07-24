<?php

namespace App\Support\Services\TochkaBank;

use App\Enums\PaymentType;
use App\Exceptions\TochkaBank\StatementCreatingError;
use App\Exceptions\TochkaBank\TochkaBankException;
use App\Jobs\FindPaymentByTransaction;
use App\Jobs\ProcessTransactionsFromStatement;
use App\Models\Payment;
use App\Models\Statement;
use App\Support\Services\TransactionFactory;

class TochkaBankService
{
    public function __construct(public TochkaBankApi $api, public TransactionFactory $factory)
    {
    }

    public function createPayment(Payment $payment)
    {
        if ($payment->type === PaymentType::debet) {
            throw new TochkaBankException("Создание платежного поручения по входящему платежу", 1);
        }
        
        return $this->api->createPaymentForSign(
            settings()->payment_account,
            settings()->bik,
            $payment->account->bik,
            $payment->account->payment_account,
            $payment->account->organization->inn,
            $payment->account->organization->title,
            $payment->amount->amount(),
            now(),
            $payment->number,
            $payment->description,
        );
    }

    public function createIncomePaymentWebhook(string $url)
    {
        return $this->api->createWebhook(['incomingPayment'], $url);
    }

    public function getTransactions(string | int | null $statementId = null)
    {
        if (is_null($statementId)) {
            $response = $this->api->initStatement();
            $status = $response->json('Data.Statement.status');
            $statementId = $response->json('Data.Statement.statementId');
            $transactions = $response->json('Data.Statement.Transaction');

            Statement::updateOrCreate(
                ['date' => now()->format('Y-m-d')],
                ['external_id' => $statementId]
            );
        } else {
            $response =  $this->api->getStatement(config('services.tochka.account_id'), $statementId);
            $status = $response->json('Data.Statement.0.status');
            $transactions = $response->json('Data.Statement.0.Transaction');
        }


        if ($status === 'Error') {
            throw new StatementCreatingError($statementId);
        }

        if ($status === 'Ready') {
            foreach ($transactions as $transaction) {
                $dto = $this->factory->fromTochkaTransaction($transaction);

                dispatch(new FindPaymentByTransaction($dto));
            }
            return;
        }

        dispatch(new ProcessTransactionsFromStatement($statementId))->delay(now()->addSeconds(10));
    }
}
