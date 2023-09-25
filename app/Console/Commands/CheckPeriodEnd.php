<?php

namespace App\Console\Commands;

use App\Events\BillingPeriodEnded;
use App\Models\Contract;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckPeriodEnd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '24:check-period-end';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Проверяет дату конца периода у договоров и переводит на следующий период';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $promotedContracts = 0;

        $contracts = Contract::whereNotNull('paid_at')->with('user')->lazyById(200, 'id')
            ->each(function ($contract) use (&$promotedContracts) {
                if ($contract->periodEnd() > now()) {
                    return;
                }

                event(new BillingPeriodEnded($contract));

                $promotedContracts++;
            });

        $message = "Конец периода проверен. Всего контрактов проверено: {$contracts->count()}. Контрактов переведено: {$promotedContracts}";
        Log::channel('schedule')->info($message);

        return Command::SUCCESS;
    }
}
