<?php

namespace App\Listeners;

use App\Enums\ContractChangeStatus;
use App\Enums\PaymentType;
use App\Events\BillingPeriodEnded;
use App\Models\Profitability;
use App\Models\Tariff;

class AccrueAdditionalProfitability
{
    /**
     * Начисляет доп доходность при смене тарифа с выплатой в конце. 
     *
     * @param  object  $event
     * @return void
     */
    public function handle(BillingPeriodEnded $event): void
    {
        $contract = $event->contract;
        $changes = $contract->contractChanges->load('tariff');

        $waiting = $changes->where('status', ContractChangeStatus::waitingPeriodEnd);

        if ($waiting->isEmpty()) {
            return;
        }

        $prevContractChange = $changes
            ->where('status', ContractChangeStatus::actual)
            ->first();
        $newContractChange = $changes
            ->where('status', ContractChangeStatus::waitingPeriodEnd)
            ->first();

        if (
            $prevContractChange->tariff->getting_profit === Tariff::AT_THE_END &&
            $prevContractChange->tariff_id !== $newContractChange->tariff_id
        ) {
            // Доначислить доходность по новому тарифу
            $profitAmout = $contract->amount->raw() * ($newContractChange->tariff->annual_rate - $contract->tariff->annual_rate) / 12 / 100 * $contract->duration();

            Profitability::create([
                'contract_id' => $contract->id,
                'payment_id' => $contract->payments->where('type', PaymentType::credit)->last()->id,
                'amount' => $profitAmout,
                'accrued_at' => $event->contract->paid_at->addMonths($event->contract->duration()),
                //TODO blink
                // 'accrued_at' => now(),
            ]);
        }
    }
}
