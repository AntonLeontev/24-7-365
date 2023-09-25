<?php

namespace App\Enums;

use App\Traits\GivesEnum;

enum PaymentType: string
{
    use GivesEnum;

    /**
     * Входящий платеж
     */
    case debet = 'debet';

    /**
     * Исходящий платеж
     */
    case credit = 'credit';
}
