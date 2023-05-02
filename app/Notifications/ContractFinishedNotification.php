<?php

namespace App\Notifications;

use App\Models\Contract;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContractFinishedNotification extends Notification implements ShouldQueue
{
    use Queueable;


    /**
     * Create a new notification instance.
     */
    public function __construct(private Contract $contract)
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
                    ->subject('Договор успешно завершен')
                    ->line("Завершен договор №{$this->contract->id} от {$this->contract->paid_at->format('d.m.Y')}")
                    ->action('Посмотреть договор', route('users.contract_show', $this->contract->id));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Договор успешно завершен',
            'text' => "Завершен договор №{$this->contract->id} от {$this->contract->paid_at->format('d.m.Y')}",
            'button' => [
                'href' => route('users.contract_show', $this->contract->id),
                'text' => 'К договору'
            ],
        ];
    }
}
