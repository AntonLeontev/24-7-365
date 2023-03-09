<?php

namespace App\Listeners;

use App\Models\ContractChange;

class DeletePendingContractChanges
{
    public function handle($event)
    {
        $changes = $event->contract->changes
            ->where('status', ContractChange::STATUS_PENDING);

        foreach ($changes as $change) {
			$change->delete();
		}
    }
}
