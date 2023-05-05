<?php

namespace App\Support\Services\Sber;

use App\DTOs\SberTokensDTO;
use App\Exceptions\Sber\SberApiException;
use App\Exceptions\Sber\TransactionsNotReadyException;
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
}
