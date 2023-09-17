<?php

namespace App\Console\Commands;

use App\Actions\Reports\ProfitReport\ProfitReportMaker;
use App\Mail\MonthProfitReport;
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
    public function handle(ProfitReportMaker $maker)
    {
        $date = is_null($this->argument('date')) ? now()->subMonth() : Carbon::parse($this->argument('date'));

		$period = CarbonPeriod::since($date->startOfMonth())
			->until($date->endOfMonth());

        $report = $maker->make($period);
		$path = $report->toExcel();

		Mail::to(['aner-anton@ya.ru'])->send(new MonthProfitReport($period, $path));
		
		Storage::delete($path);

		return Command::SUCCESS;
    }
}
