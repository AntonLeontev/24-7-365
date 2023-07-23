<?php

namespace App\Support\Services\StreamTelecom;

use App\Contracts\SmsService;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class StreamTelecomService implements SmsService
{
    public const URL = '';

    public function sendSms(string $phone, string $message): Response
    {
        return Http::streamTelecom()
            ->get('', [
                'user' => config('services.stream-telecom.login'),
                'pwd' => config('services.stream-telecom.password'),
                'sadr' => config('services.stream-telecom.sadr'),
                'dadr' => $phone,
                'text' => $message,
            ]);
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
