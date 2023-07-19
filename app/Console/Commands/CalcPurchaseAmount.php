<?php

namespace App\Console\Commands;

use App\Contracts\AccountingSystemContract;
use App\Support\Services\Telegram\TelegramService;
use Illuminate\Console\Command;

class CalcPurchaseAmount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '24:purchase';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Вычисляет на основе учетной системы доступные средства для закупа и отправляет в телеграм чат';

    /**
     * Execute the console command.
     */
    public function handle(AccountingSystemContract $service, TelegramService $telegram)
    {
        $one = $service->getPurchasesAmount(now()->addMonths(1));
        $two = $service->getPurchasesAmount(now()->addMonths(2));
        $three = $service->getPurchasesAmount(now()->addMonths(3));

        $message = $this->renderMessage($one, $two, $three);

        $telegram->sendSilentText($message, config('services.telegram.amount_chat'));
    }

    private function renderMessage(...$amounts): string
    {
        $result = '';

        foreach ($amounts as $amount) {
            $sum = number_format($amount->amount, 0, ',', ' ');
            $result .= "На {$amount->date->translatedFormat('d F Y')} остаток {$sum} р\n\n";
        }

        return $result;
    }
}
