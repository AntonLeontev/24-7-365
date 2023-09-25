<?php

namespace App\Console\Commands;

use App\Actions\Reports\ProfitReport\ProfitReportMaker;
use App\Mail\MonthProfitReport;
use App\Support\Services\Telegram\TelegramService;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class MonthProfitReportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '24:month-profit-report {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates profit report for previous month and sends to email';

    /**
     * Execute the console command.
     */
    public function handle(ProfitReportMaker $maker, TelegramService $telegram)
    {
        $date = is_null($this->argument('date')) ? now()->subMonth() : Carbon::parse($this->argument('date'));

        $period = CarbonPeriod::since($date->startOfMonth())
            ->until($date->endOfMonth());

        $report = $maker->make($period);
        $path = $report->toExcel();

        Mail::to(['viktoriasidikova@mail.ru'])->send(new MonthProfitReport($period, $path));

        $telegram->sendDocument(config('services.telegram.amount_chat'), $path, $report->fileName(), true);

        Storage::delete($path);

        return Command::SUCCESS;
    }
}
