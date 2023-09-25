<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterCompanyRequest;
use Illuminate\Support\Facades\Http;

class RegisterCompanyController extends Controller
{
    public function __invoke(RegisterCompanyRequest $request)
    {
        $response = Http::asJson()->post(
            'https://open.tochka.com:3000/rest/v1/request/registration',
            [
                'token' => config('services.tochka.token'),
                'request' => $request->validated(),
                'workMode' => config('services.tochka.mode'),
            ]
        );

        return response()->json($response->json());
    }
}
