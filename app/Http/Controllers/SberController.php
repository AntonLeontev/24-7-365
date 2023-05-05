<?php

namespace App\Http\Controllers;

use App\Exceptions\SberApiException;
use App\Models\SberToken;
use App\Support\Services\SberBusinessApiService;

class SberController extends Controller
{
    public function auth(SberBusinessApiService $service)
    {
        return redirect($service->getAuthRedirectUrl());
    }

    public function authCodeRedirect(SberBusinessApiService $service)
    {
        try {
            $tokens = $service->tokensViaAuthCode(request('code'));
        } catch (SberApiException $e) {
            return 'ERROR:<br>' . $e->getMessage();
        }

        SberToken::updateOrCreate(
            ['id' => 1],
            [
                'refresh_token' => $tokens->refreshToken,
                'access_token' => $tokens->accessToken,
            ]
        );

        return sprintf(
			'refresh token saved:<br>%s<br>access token saved:<br>%s', 
			$tokens->refreshToken, 
			$tokens->accessToken
		);
    }
}
