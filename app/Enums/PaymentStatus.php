<?php

namespace App\Enums;

use App\Traits\GivesEnum;

enum PaymentStatus: string
{
	use GivesEnum;



    /**
     * Платеж ожидается
     */
    case pending = 'pending';

    /**
     * Платеж проведен банком
     */
    case processed = 'processed';
}
