<?php

namespace App\Listeners;

use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Events\ContractTariffChanging;
use App\Models\Payment;
use App\Models\Profitability;
use App\Models\Tariff;

class CreditPaymentsManager
{
    public function handle(ContractTariffChanging $event)
    {
        $contract = $event->contract;
        $newTariff = Tariff::find($event->newTariffId);

        if ($event->contract->tariff->getting_profit === Tariff::MONTHLY) {
            # code...
        }

        if ($event->contract->tariff->getting_profit === Tariff::AT_THE_END) {
            # Delete credit payments
            $paymentsIds = $contract->payments
                ->where('type', PaymentType::credit)
                ->where('status', PaymentStatus::pending)
                ->pluck('id');
    
            Payment::whereIn('id', $paymentsIds)->delete();
    
            # Create new credit payment
            $profit = $contract->amount->raw() * $newTariff->annual_rate / 100 * $newTariff->duration / 12;
            $amount = $contract->amount->raw() + $profit;
            
            $newPayment = Payment::create([
                'account_id' => $contract->organization->accounts->first()->id,
                'contract_id' => $contract->id,
                'amount' => $amount,
                'type' => PaymentType::credit,
                'status' => PaymentStatus::pending,
                'planned_at' => $contract->paid_at->addMonths($newTariff->duration),
            ]);

            # Изменить дату выплат по старым доходностям
            $profitIds = $contract->profitabilities->pluck('id');

            Profitability::whereIn('id', $profitIds)->update([
                'payment_id' => $newPayment->id,
            ]);
    
            # Доначислить доходность по новому тарифу
            // $profitAmout = $contract->duration->raw() * ($newTariff->annual_rate - $contract->tariff->annual_rate) / 12 / 100;

            // Profitability::create([
            //     'contract_id' => $contract->id,
            //     'payment_id' => $newPayment->id,
            //     'amount' => $profitAmout,
            //     'planned_at' => $newPayment->planned_at,
            // ]);

            return;
        }
    }
}
