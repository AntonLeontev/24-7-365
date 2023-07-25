<?php

namespace App\Support\Managers;

use App\Enums\PaymentType;
use App\Models\Contract;
use App\Models\Payment;
use Carbon\Carbon;

class PaymentCreator
{
    public function createProfitOutcomePayment(
        int $amount,
        Contract $contract,
        Carbon $payDay,
        ?Carbon $periodStart = null,
        ?Carbon $periodEnd = null
    ): Payment {
        $description = "Выплата ожидаемой прибыли по Договору (Оферта) №{$contract->id} от {$contract->paid_at->format('d.m.Y')}. НДС не облагается";

        if (!is_null($periodStart) && !is_null($periodEnd)) {
            $description .= " за период {$periodStart->format('d.m.Y')} - {$periodEnd->format('d.m.Y')}";
        }

        return $this->createPayment($amount, $contract, $payDay, $description, false);
    }

    public function createBodyOutcomePayment(int $amount, Contract $contract, Carbon $payDay): Payment
    {
        $description = "Возврат платежа на закупку товара по Договору (Оферта) №{$contract->id} от {$contract->paid_at->format('d.m.Y')}. НДС не облагается";

        return $this->createPayment($amount, $contract, $payDay, $description, true);
    }

    private function createPayment(int $amount, Contract $contract, Carbon $payDay, string $description, bool $isBody): Payment
    {
        return Payment::create([
            'account_id' => $contract->organization->accounts->first()->id,
            'contract_id' => $contract->id,
            'amount' => $amount,
            'type' => PaymentType::credit,
            'planned_at' => $payDay,
            'description' => $description,
            'is_body' => $isBody,
        ]);
    }
}
