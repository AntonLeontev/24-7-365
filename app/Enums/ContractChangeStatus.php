<?php

namespace App\Enums;

enum ContractChangeStatus: string
{
    /**
     * Актуальное состояние договора, которое исполняется
     */
    case actual = 'actual';

    /**
     * Изменения инициированы пользователем, но оплаты не было (либо другого подтверждения)
     */
    case pending = 'pending';

    /**
     * Пользователь подвердил изменения (доплата получена), но расчетный период не завершен
     */
    case waitingPeriodEnd = 'waitingPeriodEnd';

    /**
     * Прошедшее состояние
     */
    case past = 'past';


    public function getName()
    {
        return match ($this) {
            self::actual => 'Актуально',
            self::pending => 'Ожидает подтверждения',
            self::waitingPeriodEnd => 'Ждет конца периода',
            self::past => 'Прошедшее',
        };
    }
}
