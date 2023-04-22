<?php

namespace App\Notifications;

use App\Models\Contract;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewInvoice extends Notification implements ShouldQueue
{
    use Queueable;


    private Contract $contract;


    public function __construct(private Payment $payment)
    {
        $this->contract = $this->payment->contract;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $pdf = Pdf::loadView('pdf.invoice', ['payment' => $this->payment])->output();
        $name = "Счет №{$this->payment->id} от {$this->payment->created_at->format('d.m.Y')}";

        return (new MailMessage())
                    ->subject("Счет на оплату")
                    ->line("Сгенерирован счет по договору {$this->contract->id}.")
                    ->line("Счет во вложении, также его можно скачать:")
                    ->action('Скачать', route('invoice.pdf', $this->payment->id))
                    ->attachData($pdf, $name, ['mime' => 'application/pdf']);
    }
}
