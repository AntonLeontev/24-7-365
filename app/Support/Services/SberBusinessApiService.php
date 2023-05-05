<?php

namespace App\Support\Services;

use App\DTOs\SberTokensDTO;
use App\Exceptions\SberApiException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;

class SberBusinessApiService
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
        return $this->requestTokens($this->QueryViaAuthCode($code));
    }

    public function tokensViaRefreshToken(string $refreshToken): SberTokensDTO
    {
        return $this->requestTokens($this->QueryViaRefreshToken($refreshToken));
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

        return new SberTokensDTO($response['access_token'], $response['refresh_token']);
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

    private function QueryViaAuthCode(string $code): array
    {
        return [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'client_id' => config('services.sber.client_id'),
            'redirect_uri' => route('sber.auth-code'),
            'client_secret' => config('services.sber.client_secret'),
        ];
    }

    private function QueryViaRefreshToken(string $refreshToken): array
    {
        return [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
            'client_id' => config('services.sber.client_id'),
            'client_secret' => config('services.sber.client_secret'),
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
}
