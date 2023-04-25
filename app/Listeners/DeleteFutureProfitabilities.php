<?php

namespace App\Listeners;

use App\Enums\ContractStatus;
use App\Events\ContractTariffChanging;
use App\Events\PaymentReceived;
use App\Models\Profitability;
use App\Support\Managers\ProfitabilityManager;

class DeleteFutureProfitabilities
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(private ProfitabilityManager $manager)
    {
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(PaymentReceived | ContractTariffChanging $event)
    {
        $contract = $event->contract ?? $event->payment->contract;
		$contract->refresh();

        if ($contract->status === ContractStatus::init) {
            return;
        }

        $profitabilitiesIds = $contract->profitabilities
            ->filter(function ($profitability) use ($contract) {
                return $profitability->accrued_at > $contract->periodEnd();
            })
            ->pluck('id');
        
        Profitability::whereIn('id', $profitabilitiesIds)->delete();
    }
}
