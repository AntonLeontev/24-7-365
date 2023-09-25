<?php

namespace App\Support\Managers;

use App\Enums\PaymentType;
use App\Models\Contract;
use App\Models\Payment;
use Carbon\Carbon;

class CreateCreditPaymentsManager
{
    public function __construct(public PaymentCreator $creator)
    {
    }

    public function createPaymentsForMonthlyTariff(Contract $contract): void
    {
        $tariff = $contract->tariff;

        $profitPerMonth = $contract->amount->raw() * $tariff->annual_rate / 100 / 12;
        $firstPaymentScheduled = false;
        $paymentAmount = $profitPerMonth;

        //Формируем выплаты на период договора
        foreach (range(1, $tariff->duration) as $month) {
            if ($firstPaymentScheduled) {
                $paymentAmount = $profitPerMonth;
                $periodStart = $contract->paid_at->addMonths($month - 1);
                $periodEnd = $contract->paid_at->addMonths($month);
            } else {
                if ($month < settings()->payments_start) {
                    $paymentAmount += $profitPerMonth;

                    continue;
                }

                $firstPaymentScheduled = true;
                $periodStart = $contract->paid_at;
                $periodEnd = $contract->paid_at->addMonths(settings()->payments_start);
            }

            $payDay = $contract->paid_at->addMonths($month);

            $this->creator->createProfitOutcomePayment($paymentAmount, $contract, $payDay, $periodStart, $periodEnd);

            if ($month !== $tariff->duration) {
                continue;
            }

            $this->creator->createBodyOutcomePayment($contract->amount->raw(), $contract, $payDay);
        }
    }

    public function createPaymentsForAtTheEndTariff(Contract $contract): void
    {
        $tariff = $contract->tariff;

        $profit = $contract->amount->raw() * $tariff->annual_rate / 100 / 12 * $tariff->duration;

        $payDay = $contract->paid_at->addMonths($tariff->duration);

        $this->creator->createProfitOutcomePayment($profit, $contract, $payDay, $contract->paid_at, $contract->paid_at->addMonths($tariff->duration));

        $this->creator->createBodyOutcomePayment($contract->amount->raw(), $contract, $payDay);
    }

    private function createProfitOutcomePayment(int $amount, Contract $contract, Carbon $payDay, ?Carbon $periodStart = null, ?Carbon $periodEnd = null)
    {
        $description = "Выплата доходности по договору №{$contract->id} от {$contract->paid_at->format('d.m.Y')}";

        if (! is_null($periodStart) && ! is_null($periodEnd)) {
            $description .= " за период {$periodStart->format('d.m.Y')} - {$periodEnd->format('d.m.Y')}";
        }

        $this->createPayment($amount, $contract, $payDay, $description);
    }

    private function createBodyOutcomePayment(int $amount, Contract $contract, Carbon $payDay)
    {
        $description = "Возврат тела по договору №{$contract->id} от {$contract->paid_at->format('d.m.Y')}";

        $this->createPayment($amount, $contract, $payDay, $description);
    }

    private function createPayment(int $amount, Contract $contract, Carbon $payDay, string $description): void
    {
        Payment::create([
            'account_id' => $contract->organization->accounts->first()->id,
            'contract_id' => $contract->id,
            'amount' => $amount,
            'type' => PaymentType::credit,
            'planned_at' => $payDay,
            'description' => $description,
        ]);
    }
}
