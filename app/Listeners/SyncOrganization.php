<?php

namespace App\Listeners;

use App\Contracts\AccountingSystemContract;
use App\Events\ContractCreated;

class SyncOrganization
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
        if (app()->isProduction()) {
            $organization = $event->contract->organization;

            $this->service->syncOrganization($organization);
        }
    }
}
