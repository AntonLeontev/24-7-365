<?php

namespace App\Support\Services\StreamTelecom;

use App\Contracts\SmsService;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StreamTelecomService implements SmsService
{
    public const URL = '';

    public function sendSms(string $phone, string $message): Response
    {
        $response =  Http::streamTelecom()
            ->get('', [
                'user' => config('services.stream-telecom.login'),
                'pwd' => config('services.stream-telecom.password'),
                'sadr' => config('services.stream-telecom.sadr'),
                'dadr' => $phone,
                'text' => $message,
            ]);

        if (!is_numeric($response->body())) {
		 	Log::channel('telegram')->alert($response->body(), [$phone, $message]);
        }

        return $response;
    }

    public function balance(): string
    {
        $response = Http::streamTelecom()
            ->get('', [
                'user' => config('services.stream-telecom.login'),
                'pwd' => config('services.stream-telecom.password'),
                'balance' => 1,
            ]);

        return $response->body();
    }
}
