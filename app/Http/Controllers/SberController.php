<?php

namespace App\Http\Controllers;

use App\Events\PaymentSentToBank;
use App\Exceptions\Sber\SberApiException;
use App\Models\Organization;
use App\Models\Payment;
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

	//TODO delete
    public function test(SberBusinessApiService $service)
    {
        // dispatch(new GetTransactions());
        // return 'ok';

        // $response = $service->transactions();
        // dd($response);

        /**---------------------------------- */
        $org = Organization::latest()->with('accounts', 'contracts')->get()->first();

        $account = $org->accounts->first();

        $payment = Payment::factory()->create([
            'account_id' => $account->id,
            'contract_id' => $org->contracts->first()->id,
        ]);

		$service->createPayment($payment);

		event(new PaymentSentToBank($payment));
    }
}
