<?php

namespace App\Events;

use App\Models\Contract;
use App\ValueObjects\Amount;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContractTariffChanging
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public function __construct(public Contract $contract, public int $newTariffId)
    {
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
