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
    protected $signature = '24:period {contract}';

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

		event(new BillingPeriodEnded($contract));
		
        return Command::SUCCESS;
    }
}
