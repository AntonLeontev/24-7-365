<?php

namespace App\Events;

use App\Models\Contract;
use App\Models\ContractChange;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContractCreated
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public Contract $contract)
    {
    }
}
