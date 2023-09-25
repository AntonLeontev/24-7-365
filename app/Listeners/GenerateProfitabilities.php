<?php

namespace App\Listeners;

use App\Enums\ContractChangeType;
use App\Events\ContractTariffChanging;
use App\Events\PaymentReceived;
use App\Models\Tariff;
use App\Support\Managers\ProfitabilityManager;

class GenerateProfitabilities
{
    public function __construct(private ProfitabilityManager $manager)
    {
    }

    public function handle(PaymentReceived|ContractTariffChanging $event): void
    {
        $contract = $event->contract ?? $event->payment->contract;
        $contract->refresh()->load(['payments']);

        if (
            $contract->lastChange()->type === ContractChangeType::init ||
            $contract->lastChange()->type === ContractChangeType::prolongation
        ) {
            $this->manager->createInitialProfitabilities($contract);

            return;
        }

        if (
            $contract->tariff->getting_profit === Tariff::AT_THE_END &&
            $contract->tariff_id !== $contract->lastChange()->tariff_id
        ) {
            $this->manager->updateEndToEndTariffProfitabilities($contract);

            return;
        }

        $this->manager->changeProfitabilities($contract);
    }
}
