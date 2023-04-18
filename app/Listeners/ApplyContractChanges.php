<?php

namespace App\Listeners;

use App\Enums\ContractChangeStatus;
use App\Events\BillingPeriodEnded;
use Illuminate\Support\Facades\Log;

class ApplyContractChanges
{
    /**
     * Меняет статусы в ContractChanges и переносит данные в договор
     */
    public function handle(BillingPeriodEnded $event): void
    {
        $contract = $event->contract->refresh();
        $changes = $contract->contractChanges->load('tariff');
        
        $waiting = $changes->where('status', ContractChangeStatus::waitingPeriodEnd);

        if ($waiting->isEmpty()) {
            return;
        }

        $prevContractChange = $changes
            ->where('status', ContractChangeStatus::actual)
            ->first();
        $newContractChange = $changes
            ->where('status', ContractChangeStatus::waitingPeriodEnd)
            ->first();
        $prevContractChange->update(['status' => ContractChangeStatus::past]);
        $newContractChange->update([
            'status' => ContractChangeStatus::actual,
            'starts_at' => $event->contract->paid_at->addMonths($event->contract->duration()),
            //TODO blink
            // 'starts_at' => now(),

        ]);

        $contract->update([
            'amount' => $newContractChange->amount,
            'tariff_id' => $newContractChange->tariff_id,
        ]);
        Log::debug('контракт обновлен', [$newContractChange->tariff_id]);
    }
}
