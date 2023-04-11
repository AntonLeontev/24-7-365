<?php

namespace App\Support;

use App\Enums\PaymentType;
use App\Models\Contract;
use App\Models\Payment;
use App\ValueObjects\Amount;

class CreateCreditPaymentsManager
{
    public function createPaymentsForMonthlyTariff(Contract $contract)
    {
        $tariff = $contract->tariff;

        $profitPerMonth = $contract->amount->raw() * $tariff->annual_rate / 100 / 12;
        $firstPaymentScheduled = false;
        $paymentAmount = $profitPerMonth;

        //Формируем выплаты на период договора
        foreach (range(1, $tariff->duration) as $month) {
            if (! $firstPaymentScheduled) {
                if ($month < settings()->payments_start) {
                    $paymentAmount += $profitPerMonth;
                    continue;
                }
                
                $firstPaymentScheduled = true;
            }

            if ($month === $tariff->duration) {
                $paymentAmount += $contract->amount->raw();
            }

            Payment::create([
                'account_id' => $contract->organization->accounts->first()->id,
                'contract_id' => $contract->id,
                'amount' => $paymentAmount,
                'type' => PaymentType::credit,
                'planned_at' => $contract->paid_at->addMonths($month),
            ]);

            $paymentAmount = $profitPerMonth;
        }
    }

    public function createPaymentsForAtTheEndTariff(Contract $contract)
    {
        $tariff = $contract->tariff;

        $profit = $contract->amount->raw() * $tariff->annual_rate / 100 / 12 * $tariff->duration;
    
        Payment::create([
            'account_id' => $contract->organization->accounts->first()->id,
            'contract_id' => $contract->id,
            'amount' => $contract->amount->raw() + $profit,
            'type' => PaymentType::credit,
            'planned_at' => $contract->paid_at->addMonths($tariff->duration),
        ]);
    }
}
