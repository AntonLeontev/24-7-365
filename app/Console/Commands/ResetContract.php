<?php

namespace App\Console\Commands;

use App\Enums\ContractChangeStatus;
use App\Enums\ContractStatus;
use App\Enums\PaymentStatus;
use App\Models\Contract;
use App\Models\ContractChange;
use App\Models\Payment;
use App\Models\Profitability;
use Illuminate\Console\Command;

class ResetContract extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '24:rescon  {contract}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Делает контракт свежесозданным';


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (app()->isProduction()) {
            $this->error('На проде нельзя!');
            return Command::FAILURE;
        }

        $payments = Payment::where('contract_id', $this->argument('contract'))
			->get()
			->skip(1)
            ->pluck('id');

        Payment::whereIn('id', $payments)->delete();
        Payment::where('contract_id', $this->argument('contract'))->first()->update(['status' => PaymentStatus::pending]);

        Profitability::where('contract_id', $this->argument('contract'))->delete();

        $changes = ContractChange::where('contract_id', $this->argument('contract'))
			->get()
            ->skip(1)
            ->pluck('id');

        ContractChange::whereIn('id', $changes)->delete();

        $initChange = ContractChange::where('contract_id', $this->argument('contract'))
            ->first();

        $initChange->update([
            'duration' => 0,
            'status' => ContractChangeStatus::pending,
        ]);
        
        Contract::find($this->argument('contract'))
            ->update([
                'tariff_id' => $initChange->tariff_id,
                'amount' => $initChange->amount,
                'status' => ContractStatus::init,
				'paid_at' => null,
				'prolongate' => 1,
            ]);

        return Command::SUCCESS;
    }
}
