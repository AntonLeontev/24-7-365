<?php

namespace App\Support\Services\TochkaBank;

use Carbon\Carbon;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class TochkaBankApi
{
    public static function accounts(): Response
    {
        return Http::tochka()
            ->get('/open-banking/v2.0/accounts');
    }

    public static function accountInfo(string $id): Response
    {
        return Http::tochka()
            ->get("/open-banking/v2.0/accounts/$id");
    }

    public static function balanceInfo(string $id): Response
    {
        return Http::tochka()
            ->get("/open-banking/v2.0/accounts/$id/balances");
    }

    public static function getWebhooks(): Response
    {
        return Http::tochka()
            ->get("/webhook/v2.0/" . config('services.tochka.client_id'));
    }

    public static function createWebhook(array $webhooksList, string $url): Response
    {
        return Http::tochka()
            ->put("/webhook/v2.0/" . config('services.tochka.client_id'), [
                'webhooksList' => $webhooksList,
                'url' => $url,
            ]);
    }

    public static function initStatement(): Response
    {
        return Http::tochka()
            ->post('/open-banking/v2.0/statements', [
                'Data' => [
                    'Statement' => [
                        'accountId' => config('services.tochka.account_id'),
                        'startDateTime' => now()->format('Y-m-d'),
                        'endDateTime' => now()->format('Y-m-d'),
                    ]
                ]
            ]);
    }

    public static function getStatementsList(): Response
    {
        return Http::tochka()
            ->get('/open-banking/v2.0/statements');
    }
    
    public static function getStatement(string $accountId, string $statementId): Response
    {
        return Http::tochka()
            ->get("/open-banking/v2.0/accounts/$accountId/statements/$statementId");
    }

    public static function createPaymentForSign(
        string $accountCode,
        string $bankCode,
        string $counterpartyBankBic,
        string $counterpartyAccountNumber,
        string $counterpartyINN,
        string $counterpartyName,
        int | float $paymentAmount,
        Carbon $paymentDate,
        string | int $paymentNumber,
        string $paymentPurpose,
    ): Response {
        return Http::tochka()
            ->post('/payment/v2.0/for-sign', [
                'Data' => [
                    'accountCode' => $accountCode,
                    'bankCode' => $bankCode,
                    'counterpartyBankBic' => $counterpartyBankBic,
                    'counterpartyAccountNumber' => $counterpartyAccountNumber,
                    'counterpartyINN' => $counterpartyINN,
                    'counterpartyName' => $counterpartyName,
                    'paymentAmount' => $paymentAmount,
                    'paymentDate' => $paymentDate->format('Y-m-d'),
                    'paymentNumber' => $paymentNumber,
                    'paymentPurpose' => $paymentPurpose,
                ]
            ]);
    }
}
