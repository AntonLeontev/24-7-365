<?php

namespace App\Console;

use App\Console\Commands\CalcPurchaseAmount;
use App\Console\Commands\CheckPayments;
use App\Console\Commands\CheckPeriodEnd;
use App\Console\Commands\MonthProfitReportCommand;
use App\Console\Commands\SendPaymentsToBank;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(CheckPeriodEnd::class)
            ->dailyAt('1:00')
            ->evenInMaintenanceMode()
            ->after(function () {
                Log::channel('schedule')->info('Выполнен перевод конца периода договоров');
            }
            );

        $schedule->command(CheckPayments::class)
            ->hourly()
            ->between('8:00', '23:00')
            ->after(function () {
                Log::channel('schedule')->info('Выполнена проверка транзакций в банке');
            }
            );

        $schedule->command(SendPaymentsToBank::class)
            ->dailyAt('1:30')
            ->after(function () {
                Log::channel('schedule')->info('Выполнена отправка исходящих платежей');
            }
            );

        $schedule->command(CalcPurchaseAmount::class)
            ->dailyAt('12:00')
            ->evenInMaintenanceMode();

        $schedule->command(MonthProfitReportCommand::class)
            ->monthlyOn(1, '5:00')
            ->evenInMaintenanceMode();
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
