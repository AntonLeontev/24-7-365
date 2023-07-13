<?php

namespace App\Listeners;

use App\Enums\ContractStatus;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Events\ContractTerminated;
use App\Events\PaymentsDeleted;
use App\Models\Contract;
use App\Models\Payment;
use App\Models\Profitability;

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
        $contract = $event->contract;
        // Если договор не был оплачен
        if ($contract->income() === 0) {
            $this->terminate($contract);
            return;
        }

        // Удаляем все будущие исходящие платежи
        $payments = $contract->payments
            ->where('type', PaymentType::credit)
            ->where('status', PaymentStatus::pending)
            ->filter(function ($payment) use ($contract) {
                return $payment->planned_at->gt($contract->paid_at->addMonths($contract->duration()));
            });

        $paymentsIds = $payments->pluck('id');

        Payment::whereIn('id', $paymentsIds)->delete();

        event(new PaymentsDeleted($payments));

        //Удаляем доходности без платежей
        $profitabilitiesIds = $contract->profitabilities
            ->load('payment')
            ->where('payment', null)
            ->pluck('id');

		Profitability::whereIn('id', $profitabilitiesIds)->delete();

        // Считаем сумму выплаты
        $outgoingPaymentsSum = $contract->refresh()->outPaymentsSumFromStart();

        $receivedMoney = $contract->income();

        // Если выплачено больше тела договора, то сразу закрыть договор
        if ($outgoingPaymentsSum >= $receivedMoney) {
            $this->terminate($contract);
            return;
        }


        $payment = Payment::create([
            'account_id' => $contract->organization->accounts->first()->id,
            'contract_id' => $contract->id,
            'amount' => $receivedMoney - $outgoingPaymentsSum,
            'type' => PaymentType::credit,
            // 'planned_at' => now()->addMonths(2),
            // TODO flip
            'planned_at' => $contract->paid_at->addMonths($contract->duration() + 2),
            'description' => "Выплата тела договора №{$contract->id} от {$contract->paid_at->format('d.m.Y')} при досрочном расторжении"
        ]);

        Profitability::create([
            'payment_id' => $payment->id,
            'contract_id' => $contract->id,
            'amount' => $receivedMoney - $outgoingPaymentsSum,
			'accrued_at' => $payment->planned_at,
        ]);

        //TODO Механизм определения выплат, если договор продлевался или менялся тариф
    }

    private function terminate(Contract $contract): void
    {
        $contract->updateOrFail(['status' => ContractStatus::terminated]);
        event(new ContractTerminated($contract));
    }
}
