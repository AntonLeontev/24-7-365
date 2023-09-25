<?php

namespace App\Console\Commands;

use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Events\PaymentSent;
use App\Models\Payment;
use Illuminate\Console\Command;

class PayOutgoingPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '24:pay-out {contract?} {--take=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Имитирует оплату по банку всех исходящих платежей. Выставляет им статус PROCESSED и запускает событие оплаты. Можно передать contract_id и будут оплачены только платежи этого контракта. Если передать опцию --take то будет оплачено столько платежей.';

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

        $payments = Payment::query()
            ->whereStatus(PaymentStatus::pending)
            ->whereType(PaymentType::credit)
            ->when($this->argument('contract'), function ($query) {
                $query->where('contract_id', $this->argument('contract'));
            })
            ->when($this->option('take'), function ($query) {
                $query->take($this->option('take'));
            })
            ->get('id')
            ->pluck('id')
            ->toArray();

        foreach ($payments as $id) {
            $payment = Payment::find($id);
            $payment->updateOrFail([
                'status' => PaymentStatus::processed,
                'paid_at' => now(),
            ]);

            event(new PaymentSent($payment));
        }

        return Command::SUCCESS;
    }
}
