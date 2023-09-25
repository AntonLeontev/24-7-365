<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentSentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private Payment $payment)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Выплата по договору')
            ->line("Отправлена выплата по договору №{$this->payment->contract->id} на сумму {$this->payment->amount}")
            ->action('Посмотреть договор', route('users.contract_show', $this->payment->contract->id));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Выплата по договору',
            'text' => "Отправлена выплата по договору №{$this->payment->contract->id}. Сумма {$this->payment->amount}",
            'button' => [
                'href' => route('users.contract_show', $this->payment->contract->id),
                'text' => 'К договору',
            ],
        ];
    }
}
