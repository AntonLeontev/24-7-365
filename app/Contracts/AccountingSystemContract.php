<?php

namespace App\Contracts;

use App\Models\Contract;
use App\Models\Organization;
use App\Models\Payment;

interface AccountingSystemContract
{
    public function syncOrganization(Organization $organization): void;

    public function syncContract(Contract $contract): void;

    public function syncPayment(Payment $payment): void;
}
