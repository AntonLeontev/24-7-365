<?php

namespace App\Console;

use App\Console\Commands\CheckPayments;
use App\Console\Commands\CheckPeriodEnd;
use App\Console\Commands\SendPaymentsToBank;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
		$schedule->command(CheckPeriodEnd::class)->dailyAt('1:00')
			->after(function () {
				Log::channel('schedule')->info('Выполнен перевод конца периода договоров');
			}
		);

		$schedule->command(CheckPayments::class)->dailyAt('1:00')
			->after(function () {
				Log::channel('schedule')->info('Выполнена проверка транзакций в банке');
			}
		);

		$schedule->command(SendPaymentsToBank::class)->dailyAt('1:30')
			->after(function () {
				Log::channel('schedule')->info('Выполнена отправка исходящих платежей');
			}
		);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
