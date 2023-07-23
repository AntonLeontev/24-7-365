<?php

namespace App\Enums;

use App\Traits\GivesEnum;

enum TransactionType: string
{
    use GivesEnum;

    /**
     * Расход
     */
    case debet = 'debet';

    /**
     * Приход
     */
    case credit = 'credit';
}
