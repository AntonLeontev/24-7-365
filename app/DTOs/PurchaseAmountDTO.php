<?php

namespace App\DTOs;

use Carbon\Carbon;

class PurchaseAmountDTO
{
    public function __construct(
        public readonly Carbon $date,
        public readonly int|float $amount
    ) {
    }
}
