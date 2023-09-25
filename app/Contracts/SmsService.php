<?php

namespace App\Contracts;

use Illuminate\Http\Client\Response;

interface SmsService
{
    public function sendSms(string $phone, string $message): Response;
}
