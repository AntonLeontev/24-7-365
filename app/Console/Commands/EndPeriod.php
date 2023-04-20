<?php

namespace App\Console\Commands;

use App\Events\BillingPeriodEnded;
use App\Models\Contract;
use Illuminate\Console\Command;

class EndPeriod extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '24:period {contract} {--number=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ends billing period for contract';


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (app()->isProduction()) {
            $this->error('На проде нельзя');
            return Command::FAILURE;
        }

        $contract = Contract::find($this->argument('contract'));

        $number = 1;

        if (! is_null($this->option('number'))) {
            $number = $this->option('number');
        }

        foreach (range(1, $number) as $key => $value) {
            event(new BillingPeriodEnded($contract));
        }

        return Command::SUCCESS;
    }
}
