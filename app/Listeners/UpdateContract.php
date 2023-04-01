<?php

namespace App\Listeners;

use App\Models\Contract;

class UpdateContract
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $contract = $event->payment->contract;

        if ($contract->contractChanges->count() === 1) {
            $contract->updateOrFail(['status' => Contract::ACTIVE, 'paid_at' => now()]);
            return;
        }

        //TODO Эти изменения должны произойти в конце отчетного периода
        // $contractChange = $contract->contractChanges->last();
        
        // $contract->updateOrFail([
        //  'tariff_id' => $contractChange->tariff_id,
        //  'amount' => $contractChange->amount,
        // ]);
    }
}
