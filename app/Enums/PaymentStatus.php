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

    /**
     * В банке создано платежное поручение
     */
    case sent_to_bank = 'sent_to_bank';
}
