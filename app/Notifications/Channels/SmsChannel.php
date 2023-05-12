<?php

namespace App\Notifications\Channels;

use App\Contracts\SmsService;
use App\Models\User;
use Illuminate\Notifications\Notification;

class SmsChannel
{
	public function __construct(private SmsService $service)
	{}

    public function send(User $notifiable, Notification $notification): void
    {
        $message = $notification->toSms($notifiable);
 
        $this->service->sendSms($notifiable->phone, $message);
    }
}
