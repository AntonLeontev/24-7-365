<?php

namespace App\Listeners;

use App\Enums\PaymentStatus;
use App\Events\PaymentSentToBank;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MarkPaymentSentToBank
{
    public function handle(PaymentSentToBank $event): void
    {
        $event->payment->update(['status' => PaymentStatus::sent_to_bank]);
    }
}
