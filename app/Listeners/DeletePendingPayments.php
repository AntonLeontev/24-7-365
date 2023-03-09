<?php

namespace App\Listeners;

use App\Events\CanceledUnconfirmedContractChange;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Collection;

class DeletePendingPayments
{
    public function handle($event)
    {
        $contract = $event->contract ?? $event->payment->contract;

        $paymentsIds = $contract->payments
            ->where('status', Payment::STATUS_PENDING)
            ->when($event instanceof CanceledUnconfirmedContractChange, function (Collection $payments) {
				return $payments->where('type', Payment::TYPE_DEBET);
			})
            ->pluck('id');

        foreach ($paymentsIds as $id) {
            Payment::find($id)->delete();
        }
    }
}
