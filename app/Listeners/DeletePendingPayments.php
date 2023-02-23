<?php

namespace App\Listeners;

use App\Models\Payment;

class DeletePendingPayments
{
    public function handle($event)
    {
        $paymentsIds = $event->contract->payments
            ->where('status', Payment::STATUS_PENDING)
            ->pluck('id');

        foreach ($paymentsIds as $id) {
            Payment::find($id)->delete();
        }
    }
}
