<?php

namespace App\Listeners;

use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Events\BillingPeriodEnded;
use App\Models\Profitability;

class CreateProfitability
{
    /**
     * Создает доходность за прошедший период
     */
    public function handle(BillingPeriodEnded $event): void
    {
        $duration = $event->contract->duration();
        $plannedAt = $event->contract->paid_at->addMonths($duration)->format('Y-m-d');
        $monthProfit = $event->contract->amount->raw() * $event->contract->tariff->annual_rate  / 100 / 12;

        $payment = $event->contract->payments
            ->where('type', PaymentType::credit)
            ->where('status', PaymentStatus::pending)
            ->where('planned_at', '>=', $plannedAt)
            ->first();

        Profitability::create([
            'contract_id' => $event->contract->id,
            'payment_id' => $payment->id,
            'amount' => $monthProfit,
            'accrued_at' => $plannedAt,
        ]);
    }
}
