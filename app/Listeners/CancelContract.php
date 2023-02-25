<?php

namespace App\Listeners;

use App\Events\ContractTerminated;
use App\Models\Contract;
use App\Models\Payment;

class CancelContract
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
		//TODO Проверка банка на оплаты, перед расторжением

        // Если договор не был оплачен
        if ($event->contract->income() === 0) {
            $event->contract->updateOrFail(['status' => Contract::TERMINATED]);
            // $event->contract->delete();
            event(new ContractTerminated($event->contract));
            return;
        }

        // Если оплачен, но выплат не было
        if ($event->contract->outgoing() === 0) {
			$paymentsIds = $event->contract->payments
				->where('type', Payment::TYPE_CREDIT)
				->where('status', Payment::STATUS_PENDING)
				->pluck('id')
				->toArray();

			Payment::whereIn('id', $paymentsIds)->delete();

            Payment::create([
                'account_id' => $event->contract->organization->accounts->first()->id,
                'contract_id' => $event->contract->id,
                'amount' => $event->contract->amount,
                'type' => Payment::TYPE_CREDIT,
				'planned_at' => now()->addDays(5),
            ]);
			
			return;
        }

        //TODO Когда были входящие платежи и выплаты
    }
}
