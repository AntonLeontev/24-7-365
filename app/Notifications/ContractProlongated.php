<?php

namespace App\Notifications;

use App\Models\Contract;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContractProlongated extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(private Contract $contract)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->subject('Договор продлен')
            ->line("Договор №{$this->contract->id} от {$this->contract->paid_at->format('d.m.y')} г. атоматически продлен на тех же условиях")
            ->action('Перейти к договору', route('users.contract_show', $this->contract->id));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title' => 'Договор продлен',
            'text' => "Договор №{$this->contract->id} от {$this->contract->paid_at->format('d.m.y')} г. атоматически продлен на тех же условиях",
            'button' => [
                'href' => route('users.contract_show', $this->contract->id),
                'text' => 'К договору',
            ],
        ];
    }
}
