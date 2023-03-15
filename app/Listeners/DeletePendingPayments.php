<?php

namespace App\Listeners;

use App\Events\CanceledUnconfirmedContractChange;
use App\Models\Payment;

class DeletePendingPayments
{
    public function handle($event)
    {
        $contract = $event->contract ?? $event->payment->contract;

        $paymentsIds = $contract->payments
            ->where('status', Payment::STATUS_PENDING)
            ->when(! is_null($contract->paid_at), function ($query) use ($contract) {
                $query->where('planned_at', '>', $contract->paid_at->addMonths($contract->duration()));
            })
            ->when($event instanceof CanceledUnconfirmedContractChange, function ($query) {
                $query->where('type', Payment::TYPE_DEBET);
            })
            ->pluck('id');

        Payment::whereIn('id', $paymentsIds)->delete();
    }
}
