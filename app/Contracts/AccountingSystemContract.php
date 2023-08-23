<?php

namespace App\Contracts;

use App\DTOs\PurchaseAmountDTO;
use App\DTOs\TransactionDTO;
use App\Models\Contract;
use App\Models\Organization;
use App\Models\Payment;
use Carbon\Carbon;

interface AccountingSystemContract
{
    public function syncOrganization(Organization $organization): void;

    public function syncContract(Contract $contract): void;

    public function syncPayment(Payment $payment): void;

    public function getPurchasesAmount(Carbon $date): PurchaseAmountDTO;

    public function deletePayment(Payment $payment): void;

    public function createIncomeFromOzon(TransactionDTO $transaction): void;
}
