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

        // Если оплачен, то выплачиваем тело, за минусом исходящих платежей        
		$paymentsIds = $event->contract->payments
			->where('type', Payment::TYPE_CREDIT)
			->where('status', Payment::STATUS_PENDING)
			->pluck('id')
			->toArray();

		Payment::whereIn('id', $paymentsIds)->delete();

		// TODO Через какой строк ставить выплату на возврат тела
		Payment::create([
			'account_id' => $event->contract->organization->accounts->first()->id,
			'contract_id' => $event->contract->id,
			'amount' => $event->contract->amount->raw() - $event->contract->outgoing(),
			'type' => Payment::TYPE_CREDIT,
			'planned_at' => now()->addDays(5),
		]);

		//TODO Механизм определения выплат, если договор продлевался или менялся тариф
    }
}
