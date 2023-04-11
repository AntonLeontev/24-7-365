<?php

namespace App\Listeners;

use App\Events\ContractTariffChanging;
use App\Models\Contract;
use App\Models\Payment;
use App\Models\Profitability;
use App\Models\Tariff;
use App\Support\CreditPaymentsManager;

class GenerateCreditPayments
{
    public function __construct(public CreditPaymentsManager $manager)
    {
    }

    public function handle(ContractTariffChanging $event)
    {
        $contract = $event->contract->refresh();
		$newTariff = Tariff::find($event->newTariffId);

        $this->manager->deletePendingPayments($contract);
        
        if ($contract->tariff->getting_profit === Tariff::MONTHLY) {
            if ($newTariff->getting_profit === Tariff::MONTHLY) {
                $profitPayment = $this->manager->fromMonthlyToMonthlyTariff($contract);
            }

            if ($newTariff->getting_profit === Tariff::AT_THE_END) {
                $profitPayment = $this->manager->fromMonthlyToAtTheEndTariff($contract);
            }
        }

        // Если старый тариф с выплатой в конце, то прошедший срок идет в зачет новому тарифу
        if ($contract->tariff->getting_profit === Tariff::AT_THE_END) {
            $profitPayment = $this->manager->fromAtTheEndToAtTheEndTariff($contract);
    
            #TODO Доначислить доходность по новому тарифу (Это делаем в конце периода)
            // $profitAmout = $contract->duration->raw() * ($newTariff->annual_rate - $contract->tariff->annual_rate) / 12 / 100;

            // Profitability::create([
            //     'contract_id' => $contract->id,
            //     'payment_id' => $newPayment->id,
            //     'amount' => $profitAmout,
            //     'planned_at' => $newPayment->planned_at,
            // ]);
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
