<?php

namespace App\Enums;

enum PaymentStatus: string
{
    /**
     * Платеж ожидается
     */
    case pending = 'pending';

    /**
     * Платеж проведен банком
     */
    case processed = 'processed';
}
