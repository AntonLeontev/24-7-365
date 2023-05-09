<?php

namespace App\Support\Services\Sber;

use App\DTOs\SberTokensDTO;
use App\Models\Payment;
use App\Models\SberToken;
use Carbon\Carbon;
use DomainException;

class SberBusinessApiService
{
    public function __construct(private SberBusinessApi $api)
    {
    }

    public function transactions(?string $query = null, ?Carbon $date = null)
    {
        $token = $this->getAccessToken();

        return $this->api->getTransactions($token, $date, $query);
    }

    public function createPayment(Payment $payment): bool
    {
        $token = $this->getAccessToken();

        return $this->api->createPayment($token, $payment);
    }

    private function getAccessToken(): string
    {
        $tokens = SberToken::find(1);

        throw_if(is_null($tokens), DomainException::class, 'Refresh токен не найден. Нужно первый раз получить токен в Сбере');

        if ($tokens->updated_at->addSeconds($tokens->expires_in - 60) > now()) {
            return "{$tokens->token_type} {$tokens->access_token}";
        }

        $newTokens = $this->refreshTokens($tokens->refresh_token);

        $this->updateTokens($newTokens);

        return "{$newTokens->tokenType} {$newTokens->accessToken}";
    }

    private function refreshTokens(string $refreshToken): SberTokensDTO
    {
        return $this->api->tokensViaRefreshToken($refreshToken);
    }

    private function updateTokens(SberTokensDTO $tokens): void
    {
        SberToken::find(1)->update([
            'access_token' => $tokens->accessToken,
            'token_type' => $tokens->tokenType,
            'refresh_token' => $tokens->refreshToken,
        ]);
    }
}
