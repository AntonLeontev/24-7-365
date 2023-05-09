<?php

namespace App\Console\Commands;

use App\Jobs\GetTransactions;
use Illuminate\Console\Command;

class CheckPayments extends Command
{
    protected $signature = '24:check-payments';

    protected $description = 'Запускает проверку новых транзакций в банке';
	

    public function handle()
    {
        dispatch(new GetTransactions());

		return Command::SUCCESS;
    }
}
