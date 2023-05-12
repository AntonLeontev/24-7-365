<?php

namespace App\Support\Services\StreamTelecom;

use App\Contracts\SmsService;
use Illuminate\Support\Facades\Http;

class StreamTelecomService implements SmsService
{
    public const URL = 'https://gateway.api.sc/get/';

    public function sendSms(string $phone, string $message): void
    {
        Http::get(self::URL, [
            'user' => config('services.stream-telecom.login'),
            'pwd' => config('services.stream-telecom.password'),
            'sadr' => config('services.stream-telecom.sadr'),
            'dadr' => $phone,
            'text' => $message,
        ]);
    }

    public function balance(): string
    {
        $response = Http::get(self::URL, [
            'user' => config('services.stream-telecom.login'),
            'pwd' => config('services.stream-telecom.password'),
            'balance' => 1,
        ]);

        return $response->body();
    }
}
