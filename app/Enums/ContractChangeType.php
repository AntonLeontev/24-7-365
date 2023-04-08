<?php

namespace App\Enums;

enum ContractChangeType: string
{
    /**
     * Первое состояние. Используется при поступлении первой оплаты
     */
    case init = 'init';

    /**
     * Увеличение суммы договора или смена тарифа
     */
    case change = 'change';

    /**
     * Автопродление
     */
    case prolongation = 'prolongation';
}
