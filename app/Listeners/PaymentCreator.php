<?php

namespace App\Listeners;

use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Events\ContractAmountIncreasing;
use App\Events\ContractCreated;
use App\Models\Contract;
use App\Models\Payment;
use App\ValueObjects\Amount;

class PaymentCreator
{
    public function createInitialPayment(ContractCreated $event)
    {
        $this->createPayment($event->contract, $event->contract->amount);
    }

    public function createAdditionalPayment(ContractAmountIncreasing $event)
    {
        $this->createPayment($event->contract, $event->amount);
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
