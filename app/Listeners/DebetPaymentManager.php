<?php

namespace App\Listeners;

use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Events\ContractChangeCanceled;
use App\Events\ContractChangingWithIncreasingAmount;
use App\Events\ContractCreated;
use App\Models\Contract;
use App\Models\Payment;
use App\ValueObjects\Amount;

class DebetPaymentManager
{
    public function createInitialPayment(ContractCreated $event)
    {
        $description = "Платеж на закупку товара по Договору (Оферта) №{$event->contract->id}. НДС не облагается";

        $this->createPayment($event->contract, $event->contract->amount, $description);
    }

    public function createAdditionalPayment(ContractChangingWithIncreasingAmount $event)
    {
        $description = "Платеж на закупку товара по Договору (Оферта) №{$event->contract->id} от {$event->contract->paid_at->format('d.m.Y')}. НДС не облагается";

        $this->createPayment($event->contract, $event->amount, $description);
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

    private function createPayment(Contract $contract, Amount $amount, string $description)
    {
        Payment::create([
            'account_id' => $contract->organization->accounts->first()->id,
            'contract_id' => $contract->id,
            'amount' => $amount,
            'type' => PaymentType::debet,
            'status' => PaymentStatus::pending,
            'description' => $description,
        ]);
    }
}
