<?php

namespace App\Enums;

use App\Traits\GivesEnum;

enum TransactionStatus: string
{
    use GivesEnum;

    case pending = 'pending';

    case booked = 'booked';
}
