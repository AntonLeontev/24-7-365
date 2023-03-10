<?php

namespace App\Listeners;

use App\Events\PaymentReceived;
use App\Models\Payment;
use App\Models\Profitability;
use App\Models\Tariff;
use App\ValueObjects\Amount;
use DomainException;

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
        $contract = $event->payment->contract;


        //TODO Создание платежей для изменения тарифа

        
        if ($contract->changes->count() > 1) {
            if ($contract->tariff->getting_profit === Tariff::AT_THE_END) {
                $this->updateAtTheEndTariffPayments($event);

                return;
            }

            $this->updateMonthlyTariffPayments($event);
            return;
        }

        if ($contract->changes->count() === 1) {
            if ($contract->tariff->getting_profit === Tariff::AT_THE_END) {
                $this->generateAtTheEndTariffPayments($event);

                return;
            }

            $this->generateMonthlyTariffPayments($event);
            return;
        }

        throw new DomainException('Нет ContractChanges у договора');
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

    private function updateMonthlyTariffPayments(PaymentReceived $event)
    {
        $contract = $event->payment->contract;
        
        $prevContractChange = $contract->changes->slice(- 2, 1)->load('tariff')->first();
        $newContractChange = $contract->changes->load('tariff')->last();

        $newAmount = $newContractChange->amount->raw();

        $oldAmount = $prevContractChange->amount->raw();
        $oldProfitPerMonth = $oldAmount * $contract->tariff->annual_rate / 100 / 12;
        $newProfitPerMonth = $newAmount * $contract->tariff->annual_rate / 100 / 12;

        if ($contract->duration() < settings()->payments_start) {
            //ЗАпланировать выплату за прошедшие начисления + этот период + новая ставка(если есть)
            $sum = $oldProfitPerMonth * ($contract->duration() + 1) +
                $newProfitPerMonth * (settings()->payments_start - $contract->duration() - 1);

            $payment = $this->createPayment($event, $sum, settings()->payments_start);

            $this->bindProfitabilities($payment);
        }

        //Добавлять последний платеж, высчитывать сколько осталось платежей
        $start = match (true) {
            settings()->payments_start < $contract->duration() => $contract->duration() + 1,
            default => settings()->payments_start + 1
        };

        foreach (range($start, $contract->tariff->duration) as $month) {
            if ($month === $start) {
                $this->createPayment($event, $oldProfitPerMonth, $month);
                continue;
            }

            if ($month === $contract->tariff->duration) {
                $newProfitPerMonth = $newProfitPerMonth + $newAmount;
            }

            $this->createPayment($event, $newProfitPerMonth, $month);
        }
    }

    private function updateAtTheEndTariffPayments(PaymentReceived $event)
    {
        $contract = $event->payment->contract;
        
        $prevContractChange = $contract->changes->slice(- 2, 1)->load('tariff')->first();
        $newContractChange = $contract->changes->load('tariff')->last();

        $newAmount = $newContractChange->amount->raw();

        $oldAmount = $prevContractChange->amount->raw();
        $oldProfitPerMonth = $oldAmount * $contract->tariff->annual_rate / 100 / 12;
        $newProfitPerMonth = $newAmount * $contract->tariff->annual_rate / 100 / 12;

        $sum = $oldProfitPerMonth * ($contract->duration() + 1) +
            $newProfitPerMonth * ($newContractChange->tariff->duration - $contract->duration() - 1) +
            $newAmount;

        $payment = $this->createPayment($event, $sum, $newContractChange->tariff->duration);
        $this->bindProfitabilities($payment);
    }

    private function createPayment(PaymentReceived $event, int | Amount $amount, int $addMonth): Payment
    {
        return Payment::create([
            'account_id' => $event->payment->account_id,
            'contract_id' => $event->payment->contract_id,
            'amount' => $amount,
            'type' => Payment::TYPE_CREDIT,
            'planned_at' => $event->payment->contract->paid_at->addMonths($addMonth),
        ]);
    }

    private function bindProfitabilities(Payment $payment): void
    {
        $profitabilities = $payment->contract->profitabilities->where('planned_at', '>', now())->pluck('id');

        Profitability::whereIn('id', $profitabilities)->update(['payment_id' => $payment->id]);
    }
}
