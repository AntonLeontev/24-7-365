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
    public function createInitialProfitabilities(Contract $contract)
    {
        $start = 1;

        foreach (range($start, $contract->tariff->duration) as $month) {
            $accruedAt = $contract->currentTariffStart()->addMonths($month);

            $payment = $this->findPayment($accruedAt, $contract);

            Profitability::create([
                'contract_id' => $contract->id,
                'payment_id' => $payment->id,
                'amount' => $this->monthlyRate($contract->amount, $contract->tariff->annual_rate),
                'accrued_at' => $accruedAt,
            ]);
        }
    }

    private function monthlyRate(Amount $amount, int $annualRate): int
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
            throw new DomainException("Not found payment for profitability", 1);
        }

        return $contract->payments->get($paymentKey);
    }
}
