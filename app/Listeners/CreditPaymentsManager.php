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

        $oldProfitPerMonth = $contract->amount->raw() * $contract->tariff->annual_rate / 100 / 12;
        $newProfitPerMonth = $contract->amount->raw() * $newTariff->annual_rate / 100 / 12;

        # Delete credit payments
        $paymentsIds = $contract->payments
            ->where('type', PaymentType::credit)
            ->where('status', PaymentStatus::pending)
            ->filter(function ($payment) use ($contract) {
                return $payment->planned_at->greaterThan($contract->paid_at->addMonths($contract->duration() + 1)->format('Y-m-d'));
            })
            ->pluck('id');

        Payment::whereIn('id', $paymentsIds)->delete();

        if ($contract->tariff->getting_profit === Tariff::MONTHLY) {
            if ($newTariff->getting_profit === Tariff::AT_THE_END) {
				//TODO Уточнить что делать с первыми начисленияеми доходности
                $newPayment = Payment::create([
                    'account_id' => $contract->organization->accounts->first()->id,
                    'contract_id' => $contract->id,
                    'amount' => (int) ($newProfitPerMonth * $newTariff->duration + $contract->amount->raw()),
                    'type' => PaymentType::credit,
                    'status' => PaymentStatus::pending,
                    'planned_at' => $contract->paid_at->addMonths($contract->duration() + 1 + $newTariff->duration),
                ]);

            	# Изменить дату выплат по старым доходностям
                $profitIds = $contract->profitabilities->pluck('id');

                Profitability::whereIn('id', $profitIds)->update([
                    'payment_id' => $newPayment->id,
                ]);
            }

            if ($newTariff->getting_profit === Tariff::MONTHLY) {
                # Create new credit payment
                


                if ($contract->duration() + 1 < settings()->payments_start) {
                    $firstPaymentAmount = ($contract->duration() + 1) * $oldProfitPerMonth +
                        (settings()->payments_start - $contract->duration() - 1) * $newProfitPerMonth;

                    $firstPayment = Payment::create([
                        'account_id' => $contract->organization->accounts->first()->id,
                        'contract_id' => $contract->id,
                        'amount' => $firstPaymentAmount,
                        'type' => PaymentType::credit,
                        'planned_at' => $contract->paid_at->addMonths(settings()->payments_start),
                    ]);
                }

                $start = settings()->payments_start - $contract->duration();
                $start = $start < 1 ? 1 : $start;

                foreach (range($start, $newTariff->duration) as $month) {
                    // Last payment with body
                    if ($month === $newTariff->duration) {
                        Payment::create([
                            'account_id' => $contract->organization->accounts->first()->id,
                            'contract_id' => $contract->id,
                            'amount' => $newProfitPerMonth + $contract->amount->raw(),
                            'type' => PaymentType::credit,
                            'planned_at' => $contract->paid_at->addMonths($contract->duration() + 1 + $month),
                        ]);
                        continue;
                    }

                    // Regular payments
                    Payment::create([
                        'account_id' => $contract->organization->accounts->first()->id,
                        'contract_id' => $contract->id,
                        'amount' => $newProfitPerMonth,
                        'type' => PaymentType::credit,
                        'planned_at' => $contract->paid_at->addMonths($contract->duration() + 1 + $month),
                    ]);
                }

                # Изменить дату выплат по старым доходностям
                if ($contract->duration() + 1 < settings()->payments_start) {
                    foreach ($contract->profitabilities as $profitability) {
                        $profitability->update(['payment_id' => $firstPayment->id]);
                    }
                }
            }
        }

        if ($contract->tariff->getting_profit === Tariff::AT_THE_END) {
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
    
            # Доначислить доходность по новому тарифу (Это делаем в конце периода)
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
