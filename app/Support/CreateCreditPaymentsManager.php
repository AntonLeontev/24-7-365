<?php

namespace App\Support;

use App\Enums\PaymentType;
use App\Models\Contract;
use App\Models\Payment;

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
                $description = "Выплата доходности по договору №{$contract->id} от {$contract->paid_at->format('d.m.Y')} за период {$contract->paid_at->format('d.m.Y')} - {$contract->paid_at->addMonths(settings()->payments_start)->format('d.m.Y')}";
            }

            if ($month === $tariff->duration) {
                $paymentAmount += $contract->amount->raw();
                
                $description = "Выплата тела договора и доходности по договору №{$contract->id} от {$contract->paid_at->format('d.m.Y')} за период {$contract->paid_at->addMonths($month - 1)->format('d.m.Y')} - {$contract->paid_at->addMonths($month)->format('d.m.Y')}";
            }

            Payment::create([
                'account_id' => $contract->organization->accounts->first()->id,
                'contract_id' => $contract->id,
                'amount' => $paymentAmount,
                'type' => PaymentType::credit,
                'planned_at' => $contract->paid_at->addMonths($month),
                'description' => $description,
            ]);

            $paymentAmount = $profitPerMonth;
            $description = "Выплата доходности по договору №{$contract->id} от {$contract->paid_at->format('d.m.Y')} за период {$contract->paid_at->addMonths($month - 1)->format('d.m.Y')} - {$contract->paid_at->addMonths($month)->format('d.m.Y')}";
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
            'description' => "Выплата тела и доходности по договору №{$contract->id} от {$contract->paid_at->format('d.m.Y')} за период {$contract->paid_at->format('d.m.Y')} - {$contract->paid_at->addMonths($tariff->duration)->format('d.m.Y')}",
        ]);
    }
}
