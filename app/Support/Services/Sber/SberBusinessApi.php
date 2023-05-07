<?php

namespace App\Support\Services\Sber;

use App\DTOs\SberTokensDTO;
use App\Enums\PaymentType;
use App\Exceptions\Sber\SberApiException;
use App\Exceptions\Sber\TransactionsNotReadyException;
use App\Models\Payment;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;

class SberBusinessApi
{
    public function __construct(private Client $client)
    {
    }

    public function getAuthRedirectUrl(): string
    {
        $response = $this->client->get($this->authUrl(), [
            'query' => $this->getAuthQuery(),
            'allow_redirects' => false
        ]);

        return $response->getHeader('location')[0];
    }

    public function tokensViaAuthCode(string $code): SberTokensDTO
    {
        return $this->requestTokens($this->queryViaAuthCode($code));
    }

    public function tokensViaRefreshToken(string $refreshToken): SberTokensDTO
    {
        return $this->requestTokens($this->queryViaRefreshToken($refreshToken));
    }

    public function getTransactions(string $accessToken, ?Carbon $date = null, ?string $query = null): object
    {
        try {
            $response = $this->client->get($this->transactionsUrl(), [
                'headers' => [
                    'Authorization' => $accessToken,
                    'Accept' => 'application/json',
                ],
                'cert' => [config('services.sber.cert_path'), config('services.sber.cert_pass')],
                'query' => $query ?? $this->transactionsQuery($date ?? now()),
                'timeout' => 10.0,
            ]);
        } catch (TransferException $e) {
            throw new SberApiException($e->getMessage(), 1);
        }

        if ($response->getStatusCode() === 202 && $response->getReasonPhrase() === 'STATEMENT_RESPONSE_PROCESSING') {
            throw new TransactionsNotReadyException();
        }
        
        $response = json_decode($response->getBody()->getContents());

        return $response;
    }

    public function createPayment(string $accessToken, Payment $payment): bool
    {
        if ($payment->type === PaymentType::debet) {
            throw new SberApiException("Создание платежного поручения по входящему платежу", 1);
        }

        try {
            $response = $this->client->post($this->paymentsUrl(), [
                'headers' => [
                    'Authorization' => $accessToken,
                    'Accept' => 'application/json',
                ],
                'cert' => [config('services.sber.cert_path'), config('services.sber.cert_pass')],
                'json' => $this->paymentsQuery($payment),
                'timeout' => 10.0,
				'http_errors' => false,
            ]);
        } catch (TransferException $e) {
            throw new SberApiException($e->getMessage(), 1);
        }

		if ($response->getStatusCode() >= 400) {
			throw new SberApiException($response->getBody()->getContents());
		}

		return true;
    }

    private function requestTokens(array $query): SberTokensDTO
    {
        try {
            $response = $this->client->post($this->tokenUrl(), [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Accept' => 'application/json',
                ],
                'cert' => [config('services.sber.cert_path'), config('services.sber.cert_pass')],
                'query' => $query,
                'timeout' => 10.0,
                'allow_redirects' => false
            ]);
        } catch (TransferException $e) {
            throw new SberApiException($e->getMessage(), 1);
        }

        $response = json_decode($response->getBody()->getContents(), true);

        return new SberTokensDTO(
            $response['access_token'],
            $response['token_type'],
            $response['refresh_token'],
            $response['expires_in']
        );
    }

    private function getAuthQuery(): array
    {
        return [
            'client_id' => config('services.sber.client_id'),
            'scope' => config('services.sber.scope'),
            'response_type' => 'code',
            'redirect_uri' => route('sber.auth-code'),
            'state' => session('_token'),
            'nonce' => str()->random(10),
        ];
    }

    private function queryViaAuthCode(string $code): array
    {
        return [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'client_id' => config('services.sber.client_id'),
            'redirect_uri' => route('sber.auth-code'),
            'client_secret' => config('services.sber.client_secret'),
        ];
    }

    private function queryViaRefreshToken(string $refreshToken): array
    {
        return [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
            'client_id' => config('services.sber.client_id'),
            'client_secret' => config('services.sber.client_secret'),
        ];
    }

    private function transactionsQuery(Carbon $date): array
    {
        return [
            'accountNumber' => config('services.sber.account_number'),
            'statementDate' => $date->format('Y-m-d'),
            'page' => 1,
        ];
    }

    private function paymentsQuery(Payment $payment): array
    {
        return [
            'amount' => $payment->amount->amount(),
            'date' => $payment->planned_at->format('Y-m-d'),
            'externalId' => $payment->id,
            'operationCode' => '01',
            'payeeAccount' => $payment->account->payment_account,
            'payeeBankBic' => $payment->account->bik,
            'payeeBankCorrAccount' => $payment->account->correspondent_account,
            'payeeInn' => $payment->account->organization->inn,
            'payeeKpp' => $payment->account->organization->kpp,
            'payeeName' => $payment->account->organization->title,
            'payerAccount' => settings()->payment_account,
            'payerBankBic' => settings()->bik,
            'payerBankCorrAccount' => settings()->correspondent_account,
            'payerInn' => settings()->inn,
            'payerKpp' => settings()->kpp,
            'payerName' => settings()->organization_title,
            'priority' => 3,
            'purpose' => $payment->description,
            'vat' => [
                'type' => 'NO_VAT',
                'rate' => '0',
                'amount' => 0,
			],
        ];
    }

    private function authUrl(): string
    {
        return config('services.sber.auth_host') . '/ic/sso/api/v2/oauth/authorize';
    }

    private function tokenUrl(): string
    {
        return config('services.sber.api_host') . '/ic/sso/api/v2/oauth/token';
    }

    private function transactionsUrl(): string
    {
        return config('services.sber.api_host') . '/fintech/api/v2/statement/transactions';
    }

    private function paymentsUrl(): string
    {
        return config('services.sber.api_host') . '/fintech/api/v1/payments';
    }
}
