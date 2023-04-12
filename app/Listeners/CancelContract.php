<?php

namespace App\Listeners;

use App\Enums\ContractStatus;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Events\ContractTerminated;
use App\Models\Payment;

class CancelContract
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        //TODO Проверка банка на оплаты, перед расторжением

        // Если договор не был оплачен
        if ($event->contract->income() === 0) {
            $event->contract->updateOrFail(['status' => ContractStatus::terminated->value]);
            // $event->contract->delete();
            event(new ContractTerminated($event->contract));
            return;
        }

        // Если оплачен, то выплачиваем тело, за минусом исходящих платежей
        $paymentsIds = $event->contract->payments
            ->where('type', PaymentType::credit)
            ->where('status', PaymentStatus::pending)
            ->pluck('id')
            ->toArray();

        Payment::whereIn('id', $paymentsIds)->delete();

        // TODO Через какой строк ставить выплату на возврат тела
        Payment::create([
            'account_id' => $event->contract->organization->accounts->first()->id,
            'contract_id' => $event->contract->id,
            'amount' => $event->contract->amount->raw() - $event->contract->outgoing(),
            'type' => PaymentType::credit,
            'planned_at' => now()->addMonths(2),
            'description' => "Выплата тела договора №{$event->contract->id} от {$event->contract->paid_at->format('d.m.Y')}"
        ]);

        //TODO Механизм определения выплат, если договор продлевался или менялся тариф
    }
}
