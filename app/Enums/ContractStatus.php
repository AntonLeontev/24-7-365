<?php

namespace App\Enums;

use App\Traits\GivesEnum;

enum ContractStatus: string
{
	use GivesEnum;


	
    /**
     * Прерван клиентом
     */
    case terminated = 'terminated';

    /**
     * В работе
     */
    case active = 'active';

    /**
     * Клиент нажал отмену, но выплата еще не сделана
     */
    case canceled = 'canceled';

    /**
     * Ожидает доплаты от клиента
     */
    case pending = 'pending';

    /**
     * Успешно выполнен
     */
    case finished = 'finished';
    
    /**
     * Ожидает первой оплаты (еще не был в работе)
     */
    case init = 'init';

	public function getName(): string
	{
		return match ($this) {
			self::terminated => 'Прерван', 
			self::active => 'В работе', 
			self::canceled => 'Отменен', 
			self::pending => 'Ожидает доплаты', 
			self::finished => 'Успешно завершен', 
			self::init => 'Ожидает оплаты', 
		};
	}
}
