<?php

namespace App\Listeners;

use App\Events\ContractTariffChanging;
use App\Events\PaymentReceived;
use App\Models\Contract;
use App\Models\Payment;
use App\Models\Profitability;
use App\Models\Tariff;
use App\Support\CreateCreditPaymentsManager;
use App\Support\UpdateCreditPaymentsManager;

class GenerateCreditPayments
{
    private Contract $contract;


    public function __construct(
        public UpdateCreditPaymentsManager $updateManager,
        public CreateCreditPaymentsManager $createManager
    ) {
    }

    public function handle(PaymentReceived | ContractTariffChanging $event)
    {
        $this->contract = $event->contract ?? $event->payment->contract;
        $this->contract->refresh();
        
        if ($this->contract->contractChanges->count() === 1) {
            $this->create($this->contract);
            return;
        }
        
        $this->update($this->contract);
    }

    public function create(Contract $contract): void
    {
        if ($contract->tariff->getting_profit === Tariff::MONTHLY) {
            $this->createManager->createPaymentsForMonthlyTariff($contract);
        }

        if ($contract->tariff->getting_profit === Tariff::AT_THE_END) {
            $this->createManager->createPaymentsForAtTheEndTariff($contract);
        }
    }

    public function update(Contract $contract): void
    {
        $newTariff = Tariff::find($contract->contractChanges->last()->tariff_id);

        if ($newTariff->id === $contract->tariff->id) {
            if ($contract->tariff->getting_profit === Tariff::MONTHLY) {
                $profitPayment = $this->updateManager->increaseAmountOnMonthlyTariff($contract);
            }

            if ($contract->tariff->getting_profit === Tariff::AT_THE_END) {
                $profitPayment = $this->updateManager->increaseAmountEndToEndTariff($contract);
            }

            if (! is_null($profitPayment)) {
                $this->bindNewPaymentToProfitability($contract, $profitPayment);
            }
            return;
        }

        if ($contract->tariff->getting_profit === Tariff::MONTHLY) {
            if ($newTariff->getting_profit === Tariff::MONTHLY) {
                $profitPayment = $this->updateManager->fromMonthlyToMonthlyTariff($contract);
            }

            if ($newTariff->getting_profit === Tariff::AT_THE_END) {
                $profitPayment = $this->updateManager->fromMonthlyToAtTheEndTariff($contract);
            }
        }

        // Если старый тариф с выплатой в конце, то прошедший срок идет в зачет новому тарифу
        if ($contract->tariff->getting_profit === Tariff::AT_THE_END) {
            $profitPayment = $this->updateManager->fromAtTheEndToAtTheEndTariff($contract);
        }

        // Изменить дату выплат по старым доходностям
        if (! is_null($profitPayment)) {
            $this->bindNewPaymentToProfitability($contract, $profitPayment);
        }
    }

    private function bindNewPaymentToProfitability(Contract $contract, Payment $newPayment): void
    {
        $profitIds = $contract->profitabilities->load('payment')->whereNull('payment')->pluck('id');

        Profitability::whereIn('id', $profitIds)->update([
            'payment_id' => $newPayment->id,
        ]);
    }
}
