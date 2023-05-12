<?php

namespace App\Contracts;

interface SmsService
{
	public function sendSms(string $phone, string $message): void;
}
