<?php

namespace App\Notifications;

use App\Models\Contract;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContractCreatedNotification extends Notification implements ShouldQueue
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
    public function via(User $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(User $notifiable): MailMessage
    {
        return (new MailMessage())
                    ->subject('Создан новый договор')
                    ->line("Создан новый договор №{$this->contract->id}")
                    ->action('Посмотреть договор', route('users.contract_show', $this->contract->id));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(User $notifiable): array
    {
        return [
            'title' => 'Создан новый договор',
            'text' => "Создан новый договор №{$this->contract->id}",
            'button' => [
                'href' => route('users.contract_show', $this->contract->id),
                'text' => 'К договору'
            ],
        ];
    }
}
