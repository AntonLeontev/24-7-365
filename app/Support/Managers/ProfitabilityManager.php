<?php

namespace App\Support\Managers;

use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Models\Contract;
use App\Models\Payment;
use App\Models\Profitability;
use App\ValueObjects\Amount;
use Carbon\Carbon;
use DomainException;

class ProfitabilityManager
{
    /**
     * Создает доходности для начала договора и продления договора
     */
    public function createInitialProfitabilities(Contract $contract)
    {
        $start = $contract->currentTariffDuration() + 1;
        $lastChange = $contract->contractChanges->last()->load('tariff');
        $amount = $this->monthlyProfit($lastChange->amount, $lastChange->tariff->annual_rate);

        foreach (range($start, $contract->tariff->duration) as $month) {
            $accruedAt = $contract->currentTariffStart()->addMonths($month);

            $payment = $this->findPayment($accruedAt, $contract);

            Profitability::create([
                'contract_id' => $contract->id,
                'payment_id' => $payment->id,
                'amount' => $amount,
                'accrued_at' => $accruedAt,
            ]);
        }
    }

    public function changeProfitabilities(Contract $contract): void
    {
        $start = $contract->currentTariffDuration() + 2;

        $lastChange = $contract->contractChanges->last()->load('tariff');
        $tariff = $lastChange->tariff;
        $amount = $this->monthlyProfit($lastChange->amount, $tariff->annual_rate);

        foreach (range($start, $tariff->duration + 100) as $month) {
            $accruedAt = $contract->currentTariffStart()->addMonths($month);

            try {
                $payment = $this->findPayment($accruedAt, $contract);
            } catch (DomainException $e) {
                return;
            }

            Profitability::create([
                'contract_id' => $contract->id,
                'payment_id' => $payment->id,
                'amount' => $amount,
                'accrued_at' => $accruedAt,
            ]);
        }
    }

    public function updateEndToEndTariffProfitabilities(Contract $contract): void
    {
        $start = $contract->currentTariffDuration() + 2;

        $lastChange = $contract->contractChanges->last()->load('tariff');
        $tariff = $lastChange->tariff;
        
        $lastEndTariffStart = $contract->lastEndTariffStart();

        $profitabilities = $contract->profitabilities
            ->filter(function ($profitability) use ($lastEndTariffStart) {
                return $profitability->accrued_at > $lastEndTariffStart;
            });

        foreach ($profitabilities as $profitability) {
            $payment = $this->findPayment($profitability->accrued_at, $contract);
            $amount = $this->monthlyProfit($contract->amountOnDate($profitability->accrued_at->subDay()), $tariff->annual_rate);
            
            $profitability->update([
                'amount' => $amount,
                'payment_id' => $payment->id,
            ]);
        }

        $amount = $this->monthlyProfit($lastChange->amount, $tariff->annual_rate);

        foreach (range($start, $tariff->duration + 100) as $month) {
            $accruedAt = $contract->currentTariffStart()->addMonths($month);

            try {
                $payment = $this->findPayment($accruedAt, $contract);
            } catch (DomainException $e) {
                return;
            }

            Profitability::create([
                'contract_id' => $contract->id,
                'payment_id' => $payment->id,
                'amount' => $amount,
                'accrued_at' => $accruedAt,
            ]);
        }
    }

    private function monthlyProfit(Amount $amount, int $annualRate): int
    {
        return $amount->raw() * $annualRate / 100 / 12;
    }

    private function findPayment(Carbon $date, Contract $contract): Payment
    {
        $paymentKey = $contract->payments
                ->sortBy('planned_at')
                ->search(function ($payment) use ($date) {
                    if ($payment->type === PaymentType::debet) {
                        return false;
                    }

                    if ($payment->status === PaymentStatus::processed) {
                        return false;
                    }

                    if ($payment->planned_at >= $date) {
                        return true;
                    }
                });

        if (!$paymentKey) {
            throw new DomainException("Not found payment for profitability on {$date}", 1);
        }

        return $contract->payments->get($paymentKey);
    }
}
