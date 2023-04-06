<?php

namespace App\Listeners;

use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Events\ContractChangeCanceled;
use App\Models\Payment;

class DeletePendingPayments
{
    public function handle($event)
    {
        $contract = $event->contract ?? $event->payment->contract;

        $paymentsIds = $contract->payments
            ->where('status', PaymentStatus::pending)
            //TODO вынести в обсервер
            // ->when(! is_null($contract->paid_at), function ($query) use ($contract) {
            //     return $query
            //      ->where('planned_at', '>', $contract->paid_at->addMonths($contract->duration()));
            // })
            ->when($event instanceof ContractChangeCanceled, function ($query) {
                return $query->where('type', PaymentType::debet);
            })
            ->pluck('id');

        Payment::whereIn('id', $paymentsIds)->delete();
    }
}
