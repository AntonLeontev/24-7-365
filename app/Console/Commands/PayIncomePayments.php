<?php

namespace App\Console\Commands;

use App\Events\PaymentReceived;
use App\Models\Payment;
use Illuminate\Console\Command;

class PayIncomePayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '24:pay-income';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Меняет статус всех неоплаченных платежей на PROCCESSED';


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (app()->isProduction()) {
            echo 'На проде нельзя!' . PHP_EOL;
            return Command::FAILURE;
        }

        $payments = Payment::query()
            ->whereStatus(Payment::STATUS_PENDING)
            ->get('id')
            ->pluck('id')
            ->toArray();

        foreach ($payments as $id) {
            $payment = Payment::find($id);
            $payment->updateOrFail(['status' => Payment::STATUS_PROCESSED]);
            
            event(new PaymentReceived($payment));
        }
        return Command::SUCCESS;
    }
}
