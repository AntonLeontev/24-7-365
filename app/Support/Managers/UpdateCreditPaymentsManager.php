<?php

namespace App\Support\Managers;

use App\Enums\ContractChangeType;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Events\PaymentsDeleted;
use App\Models\Contract;
use App\Models\Payment;
use App\Models\Tariff;

class UpdateCreditPaymentsManager
{
    public function __construct(public PaymentCreator $creator)
    {
    }

    /**
     * Удаляет исходящие платежи после текущего отчетного периода
     */
    public function deletePendingPayments(Contract $contract): void
    {
        $payments = $contract->payments
            ->where('type', PaymentType::credit)
            ->where('status', PaymentStatus::pending)
            ->filter(function ($payment) use ($contract) {
                return $payment->planned_at->greaterThan($contract->paid_at->addMonths($contract->duration() + 1)->format('Y-m-d'));
            });

        $paymentsIds = $payments->pluck('id');

        Payment::whereIn('id', $paymentsIds)->delete();

        event(new PaymentsDeleted($payments));
    }

    /**
     * Создает выплату при смене тарифа с помесячной выплатой,
     * на тариф тариф того же типа, с большей доходностью
     */
    public function fromMonthlyToMonthlyTariff(Contract $contract): ?Payment
    {
        $newTariff = Tariff::find($contract->refresh()->contractChanges->last()->tariff_id);
        $newAmount = $contract->contractChanges->last()->amount;

        $oldProfitPerMonth = $contract->amount->raw() * $contract->tariff->annual_rate / 100 / 12;
        $newProfitPerMonth = $newAmount->raw() * $newTariff->annual_rate / 100 / 12;

        // Create new credit payment
        //$contract->duration() + 1 потому что добавляется текущий период
        if ($contract->duration() + 1 < settings()->payments_start) {
            $firstPaymentAmount = ($contract->duration() + 1) * $oldProfitPerMonth +
                (settings()->payments_start - $contract->duration() - 1) * $newProfitPerMonth;

            $payDay = $contract->paid_at->addMonths(settings()->payments_start);
            $periodStart = $contract->paid_at;
            $periodEnd = $contract->paid_at->addMonths(settings()->payments_start);

            $firstPayment = $this->creator
                ->createProfitOutcomePayment($firstPaymentAmount, $contract, $payDay, $periodStart, $periodEnd);
        }

        $start = settings()->payments_start - $contract->duration();
        $start = $start < 1 ? 1 : $start;

        // Отступ даты выплат в месяцах от начала действия договора. Если меняется тариф,
        // то добавляем 1 месяц в зачет текущего месяца
        // Платежи по новому тарифу начнутся со второго периода от текущего
        $delay = $contract->currentTariffDuration() + 1;

        // Но если продление договора, то платежи начнутся с 1 месяца
        if ($contract->contractChanges->last()->type === ContractChangeType::prolongation) {
            $delay = 0;
        }

        foreach (range($start, $newTariff->duration) as $month) {
            // Regular payments

            $payDay = $contract->currentTariffStart()->addMonths($delay + $month);
            $periodStart = $contract->currentTariffStart()->addMonths($delay + $month - 1);
            $periodEnd = $contract->currentTariffStart()->addMonths($delay + $month);

            $this->creator
                ->createProfitOutcomePayment($newProfitPerMonth, $contract, $payDay, $periodStart, $periodEnd);

            if ($month === $newTariff->duration) {
                $this->creator
                    ->createBodyOutcomePayment($newAmount->raw(), $contract, $payDay);
            }
        }

        if ($contract->duration() + 1 < settings()->payments_start) {
            return $firstPayment;
        }

        return null;
    }

    /**
     * Создает выплату при смене тарифа с помесячной выплатой,
     * на тариф с выплатой в конце срока
     */
    public function fromMonthlyToAtTheEndTariff(Contract $contract): Payment
    {
        $newTariff = Tariff::find($contract->contractChanges->last()->tariff_id);
        $newAmount = $contract->contractChanges->last()->amount;

        $oldProfitPerMonth = $contract->amount->raw() * $contract->tariff->annual_rate / 100 / 12;
        $newProfitPerMonth = $newAmount->raw() * $newTariff->annual_rate / 100 / 12;

        // Создаем выплату для доходности, которая будет начислена в конце текущего периода
        if ($contract->duration() + 1 < settings()->payments_start) {
            $this->creator
                ->createProfitOutcomePayment(
                    $oldProfitPerMonth * ($contract->duration() + 1),
                    $contract,
                    $contract->paid_at->addMonths(settings()->payments_start),
                    $contract->currentTariffStart(),
                    $contract->paid_at->addMonths($contract->duration() + 1)
                );
        }

        $payDay = $contract->paid_at->addMonths($contract->duration() + 1 + $newTariff->duration);

        $payment = $this->creator
            ->createProfitOutcomePayment(
                $newProfitPerMonth * $newTariff->duration,
                $contract,
                $payDay,
                $contract->paid_at->addMonths($contract->duration() + 1),
                $payDay,
            );

        $this->creator
            ->createBodyOutcomePayment($newAmount->raw(), $contract, $payDay);

        return $payment;

        // return Payment::create([
        //     'account_id' => $contract->organization->accounts->first()->id,
        //     'contract_id' => $contract->id,
        //     'amount' => (int) ($newProfitPerMonth * $newTariff->duration + $newAmount->raw()),
        //     'type' => PaymentType::credit,
        //     'status' => PaymentStatus::pending,
        //     'planned_at' => $contract->paid_at->addMonths($contract->duration() + 1 + $newTariff->duration),
        //     'description' => "Выплата тела и доходности по договору №{$contract->id} от {$contract->paid_at->format('d.m.Y')} за период {$contract->paid_at->addMonths($contract->duration() + 1)->format('d.m.Y')} - {$contract->paid_at->addMonths($newTariff->duration)->format('d.m.Y')}",
        // ]);
    }

    /**
     * Создает выплату при смене тарифа с выплатой в конце,
     * на тариф того же типа, но с большим сроком
     */
    public function fromAtTheEndToAtTheEndTariff(Contract $contract): Payment
    {
        $newTariff = Tariff::find($contract->refresh()->contractChanges->last()->tariff_id);
        $newAmount = $contract->contractChanges->last()->amount;

        // find start atTheEnd contract change
        $lastEndTariffChange = $contract->lastEndTariffChange();

        $endTariffChanges = $contract->contractChanges
            ->filter(function ($change) use ($lastEndTariffChange) {
                return $change->starts_at >= $lastEndTariffChange->starts_at;
            });

        $endTariffDuration = $endTariffChanges
            ->reduce(function ($carry, $change) {
                return $carry + $change->duration;
            }, 0);

        $oldProfit = $endTariffChanges
            ->reduce(function ($carry, $change) use ($newTariff) {
                return $carry + $change->amount->raw() * $newTariff->annual_rate / 100 / 12 * $change->duration;
            }, 0);

        // Добавляем доходность за текущий период
        $oldProfit += $contract->amount->raw() * $newTariff->annual_rate / 100 / 12;

        $newProfit = $newAmount->raw() * $newTariff->annual_rate / 100 / 12 * ($newTariff->duration - $endTariffDuration - 1);

        $payDay = $contract->lastEndTariffChange()->starts_at->addMonths($newTariff->duration);
        $periodStart = $contract->lastEndTariffChange()->starts_at;

        $payment = $this->creator
            ->createProfitOutcomePayment($oldProfit + $newProfit, $contract, $payDay, $periodStart, $payDay);

        $this->creator
            ->createBodyOutcomePayment($newAmount->raw(), $contract, $payDay);

        return $payment;
    }

    /**
     * Создает выплаты при увеличении суммы без смены тарифа - помесячно
     */
    public function increaseAmountOnMonthlyTariff(Contract $contract): ?Payment
    {
        $newAmount = $contract->contractChanges->last()->amount;

        $oldProfitPerMonth = $contract->amount->raw() * $contract->tariff->annual_rate / 100 / 12;
        $newProfitPerMonth = $newAmount->raw() * $contract->tariff->annual_rate / 100 / 12;

        // Create new credit payment
        if ($contract->duration() + 1 < settings()->payments_start) {
            $firstPaymentAmount = ($contract->duration() + 1) * $oldProfitPerMonth +
                (settings()->payments_start - $contract->duration() - 1) * $newProfitPerMonth;

            $payDay = $contract->paid_at->addMonths(settings()->payments_start);
            $periodStart = $contract->paid_at;
            $periodEnd = $contract->paid_at->addMonths(settings()->payments_start);

            $firstPayment = $this->creator
                ->createProfitOutcomePayment($firstPaymentAmount, $contract, $payDay, $periodStart, $periodEnd);
        }

        $start = settings()->payments_start > $contract->duration() + 1 ?
        settings()->payments_start + 1 :
        $contract->currentTariffDuration() + 2;

        foreach (range($start, $contract->tariff->duration) as $month) {
            // Regular payments
            $payDay = $contract->currentTariffStart()->addMonths($month);
            $periodStart = $contract->currentTariffStart()->addMonths($month - 1);
            $periodEnd = $contract->currentTariffStart()->addMonths($month);

            $this->creator
                ->createProfitOutcomePayment($newProfitPerMonth, $contract, $payDay, $periodStart, $periodEnd);

            if ($month === $contract->tariff->duration) {
                $payDay = $contract->currentTariffStart()->addMonths($month);

                $this->creator
                    ->createBodyOutcomePayment($newAmount->raw(), $contract, $payDay);
            }
        }

        if ($contract->duration() + 1 < settings()->payments_start) {
            return $firstPayment;
        }

        return null;
    }

    /**
     * Создает выплату при увеличении суммы без смены тарифа - в конце срока
     */
    public function increaseAmountEndToEndTariff(Contract $contract): Payment
    {
        $newAmount = $contract->contractChanges->last()->amount;

        //Доходность за прошедшие месяцы считается по новому тарифу, но по старой сумме
        // find start atTheEnd contract change
        $lastEndTariffChange = $contract->lastEndTariffChange();

        $endTariffChanges = $contract->contractChanges
            ->filter(function ($change) use ($lastEndTariffChange) {
                return $change->starts_at >= $lastEndTariffChange->starts_at;
            });

        $endTariffDuration = $endTariffChanges
            ->reduce(function ($carry, $change) {
                return $carry + $change->duration;
            }, 0);

        $oldProfit = $endTariffChanges
            ->reduce(function ($carry, $change) use ($contract) {
                return $carry + $change->amount->raw() * $contract->tariff->annual_rate / 100 / 12 * $change->duration;
            }, 0);

        // Добавляем доходность за текущий период
        $oldProfit += $contract->amount->raw() * $contract->tariff->annual_rate / 100 / 12;

        $newProfit = $newAmount->raw() * $contract->tariff->annual_rate / 100 / 12 * ($contract->tariff->duration - $endTariffDuration - 1);

        $payDay = $lastEndTariffChange->starts_at->addMonths($contract->tariff->duration);
        $periodStart = $contract->paid_at;
        $periodEnd = $lastEndTariffChange->starts_at->addMonths($contract->tariff->duration);

        $profitPayment = $this->creator
            ->createProfitOutcomePayment($oldProfit + $newProfit, $contract, $payDay, $periodStart, $periodEnd);

        $this->creator
            ->createBodyOutcomePayment($newAmount->raw(), $contract, $payDay);

        return $profitPayment;
    }
}
