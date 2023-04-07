<?php

namespace App\Listeners;

use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Events\ContractAmountIncreasing;
use App\Events\ContractChangeCanceled;
use App\Events\ContractCreated;
use App\Models\Contract;
use App\Models\Payment;
use App\ValueObjects\Amount;

class PaymentManager
{
    public function createInitialPayment(ContractCreated $event)
    {
        $this->createPayment($event->contract, $event->contract->amount);
    }

    public function createAdditionalPayment(ContractAmountIncreasing $event)
    {
        $this->createPayment($event->contract, $event->amount);
    }

    public function deleteDebetPendingPayments(ContractChangeCanceled $event)
    {
        $contract = $event->contract;

        $paymentsIds = $contract->payments
            ->where('status', PaymentStatus::pending)
            ->where('type', PaymentType::debet)
            ->pluck('id');

        Payment::whereIn('id', $paymentsIds)->delete();
    }

    private function createPayment(Contract $contract, Amount $amount)
    {
        Payment::create([
            'account_id' => $contract->organization->accounts->first()->id,
            'contract_id' => $contract->id,
            'amount' => $amount,
            'type' => PaymentType::debet,
            'status' => PaymentStatus::pending,
        ]);
    }
}
