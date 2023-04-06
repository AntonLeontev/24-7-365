<?php

namespace App\Enums;

enum PaymentType: string
{
    /**
     * Входящий платеж
     */
    case debet = 'debet';

    /**
     * Исходящий платеж
     */
    case credit = 'credit';
}
