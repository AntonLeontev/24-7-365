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

class ContractAmountIncreased
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


	public Amount $amount;
	

    public function __construct(
		public Contract $contract,
		int $amount,
	)
    {
        $this->amount = new Amount($amount);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
