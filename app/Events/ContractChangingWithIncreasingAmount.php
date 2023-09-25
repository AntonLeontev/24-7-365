<?php

namespace App\Events;

use App\Models\Contract;
use App\ValueObjects\Amount;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContractChangingWithIncreasingAmount
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Amount $amount;

    public function __construct(
        public Contract $contract,
        int $amount,
        public int $newTariffId
    ) {
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
