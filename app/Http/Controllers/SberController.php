<?php

namespace App\Http\Controllers;

use App\Exceptions\Sber\SberApiException;
use App\Jobs\GetTransactions;
use App\Models\SberToken;
use App\Support\Services\Sber\SberBusinessApi;
use App\Support\Services\Sber\SberBusinessApiService;

class SberController extends Controller
{
    public function auth(SberBusinessApi $api)
    {
        return redirect($api->getAuthRedirectUrl());
    }

    public function authCodeRedirect(SberBusinessApi $api)
    {
        try {
            $tokens = $api->tokensViaAuthCode(request('code'));
        } catch (SberApiException $e) {
            return 'ERROR:<br>' . $e->getMessage();
        }

        SberToken::updateOrCreate(
            ['id' => 1],
            [
                'refresh_token' => $tokens->refreshToken,
                'access_token' => $tokens->accessToken,
                'token_type' => $tokens->tokenType,
                'expires_in' => $tokens->expiresIn,
            ]
        );

        return sprintf(
            'refresh token saved:<br>%s<br>access token saved:<br>%s',
            $tokens->refreshToken,
            $tokens->accessToken
        );
    }

    public function test(SberBusinessApiService $service)
    {
        dispatch(new GetTransactions());
        return 'ok';

        // $response = $service->transactions();
		// dd($response);
    }
}
