<?php

namespace App\Listeners;

use App\Events\PaymentReceived;
use App\Models\Payment;
use App\Models\Tariff;
use App\ValueObjects\Amount;

class SchedulePayments
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
    public function handle(PaymentReceived $event)
    {
        //TODO Создание платежей для изменения договора
        if ($event->payment->contract->changes->count() > 1) {
            return;
        }
        
        if ($event->payment->contract->tariff->getting_profit === Tariff::AT_THE_END) {
            $this->generateAtTheEndTariffPayments($event);

            return;
        }

        $this->generateMonthlyTariffPayments($event);
    }

    private function generateMonthlyTariffPayments(PaymentReceived $event)
    {
        $contract = $event->payment->contract;
        $tariff = $contract->tariff;

        $profitPerMonth = $contract->amount->raw() * $tariff->annual_rate / 100 / 12;
        $firstPaymentScheduled = false;
        $paymentAmount = $profitPerMonth;

        //Формируем выплаты на период договора
        foreach (range(1, $tariff->duration) as $month) {
            if (! $firstPaymentScheduled) {
                // Накапливаем сумму для первой выплаты доходности
                if ($month < settings()->payments_start) {
                    $paymentAmount += $profitPerMonth;
                    continue;
                }
                
                // Формируем первую выплату доходности с месяца, который указан в настройках
                $this->createPayment($event, $paymentAmount, $month);

                $firstPaymentScheduled = true;
                continue;
            }

            if ($month === $tariff->duration) {
                //Выплата тела договора с доходностью в конце срока
                $this->createPayment($event, $contract->amount->raw() + $profitPerMonth, $tariff->duration);
                continue;
            }

            // Формируем оставшиеся выплаты доходности
            $this->createPayment($event, $profitPerMonth, $month);
        }
    }

    private function generateAtTheEndTariffPayments(PaymentReceived $event)
    {
        $contract = $event->payment->contract;
        $tariff = $contract->tariff;

        $profit = $contract->amount->raw() * $tariff->annual_rate / 100 / 12 * $tariff->duration;
    
        $amount = $contract->amount->raw() + $profit;

        $this->createPayment($event, $amount, $tariff->duration);
    }

    private function createPayment(PaymentReceived $event, int | Amount $amount, int $addMonth): void
    {
        Payment::create([
            'account_id' => $event->payment->account_id,
            'contract_id' => $event->payment->contract_id,
            'amount' => $amount,
            'type' => Payment::TYPE_CREDIT,
            'planned_at' => now()->addMonths($addMonth),
        ]);
    }
}
