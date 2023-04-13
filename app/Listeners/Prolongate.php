<?php

namespace App\Listeners;

use App\Enums\ContractChangeStatus;
use App\Enums\ContractChangeType;
use App\Enums\PaymentType;
use App\Events\BillingPeriodEnded;
use App\Models\ContractChange;
use App\Models\Tariff;
use App\Support\UpdateCreditPaymentsManager;

class Prolongate
{
    public function __construct(private UpdateCreditPaymentsManager $manager)
    {
    }

    /**
     * Автоматически продлевает договор на новый срок
     */
    public function handle(BillingPeriodEnded $event): void
    {
        $contract = $event->contract;
        $prolongate = $contract->prolongate;

        if (! $prolongate && ! is_null($prolongate)) {
            return;
        }

        if ($contract->end() >= $contract->paid_at->addMonths($contract->duration())) {
            return;
        }

        $contract->contractChanges
            ->where('status', ContractChangeStatus::actual)
            ->last()
            ->update(['status' => ContractChangeStatus::past]);

        ContractChange::create([
            'contract_id' => $contract->id,
            'type' => ContractChangeType::prolongation,
            'tariff_id' => $contract->tariff_id,
            'status' => ContractChangeStatus::actual,
            'amount' => $contract->amount->raw(),
            'starts_at' => $contract->paid_at->addMonths($contract->duration()),
            'duration' => 0,
        ]);

        //sub body from last payment
        $lastPayment = $contract->payments
            ->where('type', PaymentType::credit)
            ->last();

        $lastPayment->update(['amount' => $lastPayment->amount->raw() - $contract->amount->raw()]);

        //Create new payments
        if ($contract->tariff->getting_profit === Tariff::MONTHLY) {
            $this->manager->fromMonthlyToMonthlyTariff($contract);
            return;
        }

        if ($contract->tariff->getting_profit === Tariff::AT_THE_END) {
            $this->manager->fromAtTheEndToAtTheEndTariff($contract);
            return;
        }


    }
}
