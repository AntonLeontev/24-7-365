<?php

namespace App\Notifications;

use App\Notifications\Channels\SmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class SmsCodeNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public string $code, public string $phone = '')
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return string
     */
    public function via(object $notifiable): string
    {
        return SmsChannel::class;
    }

    /**
     * Get the sms representation of the notification.
     */
    public function toSms(object $notifiable): string
    {
        return "{$this->code} - код подтверждения";
    }
}
