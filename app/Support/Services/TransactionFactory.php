<?php

namespace App\Support\Services;

use App\DTOs\TransactionDTO;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\ValueObjects\Amount;
use Carbon\Carbon;

class TransactionFactory
{
    public function fromTochkaTransaction(array $transaction): TransactionDTO
    {
        $type = $transaction['creditDebitIndicator'] === 'Debit'
            ? TransactionType::debet
            : TransactionType::credit;

        $status = $transaction['status'] === 'Pending'
            ? TransactionStatus::pending
            : TransactionStatus::booked;

        $contrAgent = $type === TransactionType::credit
            ? 'Debtor'
            : 'Creditor';


        return new TransactionDTO(
            $transaction['transactionId'],
            $type,
            $status,
            Carbon::parse($transaction['documentProcessDate']),
            new Amount($transaction['Amount']['amount'] * 100, $transaction['Amount']['currency']),
            $transaction['description'],
            $transaction["{$contrAgent}Party"]['inn'],
            $transaction["{$contrAgent}Party"]['name'],
            $transaction["{$contrAgent}Party"]['kpp'] ?? '',
            $transaction["{$contrAgent}Account"]['identification'],
            $transaction["{$contrAgent}Agent"]['identification'],
            $transaction["{$contrAgent}Agent"]['accountIdentification'],
            $transaction["{$contrAgent}Agent"]['name'],
        );
    }
}
