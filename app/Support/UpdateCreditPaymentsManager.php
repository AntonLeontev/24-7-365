<?php

namespace App\Support;

use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Models\Contract;
use App\Models\Payment;
use App\Models\Tariff;

class UpdateCreditPaymentsManager
{
    /**
     * Удаляет исходящие платежи после текущего отчетного периода
     */
    public function deletePendingPayments(Contract $contract): void
    {
        $paymentsIds = $contract->payments
            ->where('type', PaymentType::credit)
            ->where('status', PaymentStatus::pending)
            ->filter(function ($payment) use ($contract) {
                return $payment->planned_at->greaterThan($contract->paid_at->addMonths($contract->duration() + 1)->format('Y-m-d'));
            })
            ->pluck('id');

        Payment::whereIn('id', $paymentsIds)->delete();
    }

    /**
     * Создает выплату при смене тарифа с помесячной выплатой,
     * на тариф тариф того же типа, с большей доходностью
     */
    public function fromMonthlyToMonthlyTariff(Contract $contract): ?Payment
    {
        $newTariff = Tariff::find($contract->contractChanges->last()->tariff_id);
        $newAmount = $contract->contractChanges->last()->amount;

        $oldProfitPerMonth = $contract->amount->raw() * $contract->tariff->annual_rate / 100 / 12;
        $newProfitPerMonth = $newAmount->raw() * $newTariff->annual_rate / 100 / 12;

        # Create new credit payment
        if ($contract->duration() + 1 < settings()->payments_start) {
            $firstPaymentAmount = ($contract->duration() + 1) * $oldProfitPerMonth +
                (settings()->payments_start - $contract->duration() - 1) * $newProfitPerMonth;

            $firstPayment = Payment::create([
                'account_id' => $contract->organization->accounts->first()->id,
                'contract_id' => $contract->id,
                'amount' => $firstPaymentAmount,
                'type' => PaymentType::credit,
                'planned_at' => $contract->paid_at->addMonths(settings()->payments_start),
            ]);
        }

        $start = settings()->payments_start - $contract->duration();
        $start = $start < 1 ? 1 : $start;

        foreach (range($start, $newTariff->duration) as $month) {
            // Last payment with body
            if ($month === $newTariff->duration) {
                Payment::create([
                    'account_id' => $contract->organization->accounts->first()->id,
                    'contract_id' => $contract->id,
                    'amount' => $newProfitPerMonth + $contract->amount->raw(),
                    'type' => PaymentType::credit,
                    'planned_at' => $contract->paid_at->addMonths($contract->duration() + 1 + $month),
                ]);
                continue;
            }

            // Regular payments
            Payment::create([
                'account_id' => $contract->organization->accounts->first()->id,
                'contract_id' => $contract->id,
                'amount' => $newProfitPerMonth,
                'type' => PaymentType::credit,
                'planned_at' => $contract->paid_at->addMonths($contract->duration() + 1 + $month),
            ]);
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
            Payment::create([
                'account_id' => $contract->organization->accounts->first()->id,
                'contract_id' => $contract->id,
                'amount' => $oldProfitPerMonth * ($contract->duration() + 1),
                'type' => PaymentType::credit,
                'status' => PaymentStatus::pending,
                'planned_at' => $contract->paid_at->addMonths(settings()->payments_start),
            ]);
        }

        return Payment::create([
            'account_id' => $contract->organization->accounts->first()->id,
            'contract_id' => $contract->id,
            'amount' => (int) ($newProfitPerMonth * $newTariff->duration + $contract->amount->raw()),
            'type' => PaymentType::credit,
            'status' => PaymentStatus::pending,
            'planned_at' => $contract->paid_at->addMonths($contract->duration() + 1 + $newTariff->duration),
        ]);
    }

    /**
     * Создает выплату при смене тарифа с выплатой в конце,
     * на тариф того же типа, но с большим сроком
     */
    public function fromAtTheEndToAtTheEndTariff(Contract $contract): Payment
    {
        $newTariff = Tariff::find($contract->contractChanges->last()->tariff_id);
        $newAmount = $contract->contractChanges->last()->amount;

        $oldProfit = $contract->amount->raw() * $newTariff->annual_rate / 100 / 12 * ($contract->duration() + 1);
        $newProfit = $newAmount->raw() * $newTariff->annual_rate / 100 / 12 * ($newTariff->duration - $contract->duration() - 1);
        
        return Payment::create([
            'account_id' => $contract->organization->accounts->first()->id,
            'contract_id' => $contract->id,
            'amount' => $newAmount->raw() + $oldProfit + $newProfit,
            'type' => PaymentType::credit,
            'status' => PaymentStatus::pending,
            'planned_at' => $contract->paid_at->addMonths($newTariff->duration),
        ]);
    }
}
