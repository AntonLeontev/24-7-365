<?php

namespace App\Listeners;

use App\Events\PaymentReceived;
use App\Support\Managers\ProfitabilityManager;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GenerateProfitabilities
{
    public function __construct(private ProfitabilityManager $manager)
    {
        //
    }

    public function handle(PaymentReceived $event): void
    {
        $contract = $event->payment->contract->refresh()->load('payments');

		$this->manager->createInitialProfitabilities($contract);
    }
}
