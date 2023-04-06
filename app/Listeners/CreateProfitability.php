<?php

namespace App\Listeners;

use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Events\BillingPeriodEnded;
use App\Models\Payment;
use App\Models\Profitability;

class CreateProfitability
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(BillingPeriodEnded $event)
    {
        $duration = $event->contract->contractChanges->sum('duration');
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
            'planned_at' => $plannedAt,
        ]);
    }
}
