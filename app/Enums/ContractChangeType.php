<?php

namespace App\Enums;

enum ContractChangeType: string
{
    /**
     * Первое состояние. Используется при поступлении первой оплаты
     */
    case init = 'init';

    /**
     * Увеличение суммы договора
     */
    case increaseAmount = 'increaseAmount';

    /**
     * Изменение тарифа
     */
    case changeTariff = 'changeTariff';

    /**
     * Изменение тарифа и суммы одновременно
     */
    case mixed = 'mixed';

    /**
     * Автопродление
     */
    case prolongation = 'prolongation';
}
