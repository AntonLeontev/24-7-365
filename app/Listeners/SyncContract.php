<?php

namespace App\Listeners;

use App\Contracts\AccountingSystemContract;
use App\Events\ContractCreated;

class SyncContract
{
    /**
     * Create the event listener.
     */
    public function __construct(private AccountingSystemContract $service)
    {
    }

    /**
     * Handle the event.
     */
    public function handle(ContractCreated $event): void
    {
		$this->service->syncContract($event->contract);
    }
}
