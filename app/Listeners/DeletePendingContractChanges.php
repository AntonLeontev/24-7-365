<?php

namespace App\Listeners;

class DeletePendingContractChanges
{
    public function handle($event)
    {
        $changes = $event->contract->changes
            ->where('status', ContractChangeStatus::pending->value);

        foreach ($changes as $change) {
            $change->delete();
        }
    }
}
