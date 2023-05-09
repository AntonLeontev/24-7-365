<?php

namespace App\Notifications;

use App\Models\Contract;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AutoProlongationRemind extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private Contract $contract)
    {
    }

    public function via(User $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(User $notifiable): MailMessage
    {
        return (new MailMessage())
                    ->subject('Договор скоро будет автоматически продлен')
                    ->line("Договор №{$this->contract->id} от {$this->contract->paid_at->format('j.m.Y г.')} будет автоматически продлен на тех же условиях {$this->contract->end()->format('j.m.Y г.')}")
					->line('Если Вы хотите отменить автопродление перейдите в договор и отключите его.')
                    ->action('Посмотреть договор', route('users.contract_show', $this->contract->id));
    }

    public function toArray(User $notifiable): array
    {
        return [
            'title' => 'Автопродление договора',
            'text' => "Договор №{$this->contract->id} от {$this->contract->paid_at->format('j.m.Y г.')} будет автоматически продлен на тех же условиях {$this->contract->end()->format('j.m.Y г.')}",
            'button' => [
                'href' => route('users.contract_show', $this->contract->id),
                'text' => 'К договору'
            ],
        ];
    }
}
