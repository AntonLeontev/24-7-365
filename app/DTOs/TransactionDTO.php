<?php

namespace App\DTOs;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\ValueObjects\Amount;
use Carbon\Carbon;

class TransactionDTO
{
    public function __construct(
        readonly public string $id,
        readonly public TransactionType $type,
        readonly public TransactionStatus $status,
        readonly public Carbon $date,
        readonly public Amount $amount,
        readonly public string $description,
        readonly public string $contrAgentInn,
        readonly public string $contrAgentTitle,
        readonly public string $contrAgentKpp,
        readonly public string $contrAgentAccount,
        readonly public string $contrAgentBic,
        readonly public string $contrAgentCorrespondent,
        readonly public string $contrAgentBank,
    ) {
    }

    public function isCredit(): bool
    {
        return $this->type === TransactionType::credit;
    }

    public function isDebet(): bool
    {
        return $this->type === TransactionType::debet;
    }
}
