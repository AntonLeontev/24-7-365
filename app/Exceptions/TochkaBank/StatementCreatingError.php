<?php

namespace App\Exceptions\TochkaBank;

class StatementCreatingError extends TochkaBankException
{
	public function __construct(string | int $statementId)
	{
		$this->message = "Ошибка создания выписки в Точка банке. ID выписки $statementId";
	}
}
