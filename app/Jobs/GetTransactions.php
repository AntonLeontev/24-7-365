<?php

namespace App\Jobs;

use App\Exceptions\Sber\TransactionsNotReadyException;
use App\Support\Services\Sber\SberBusinessApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetTransactions implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;


    public function __construct(private ?string $query = null)
    {
    }

    public function handle(SberBusinessApiService $service): void
    {
        try {
            $response = $service->transactions($this->query);
        } catch (TransactionsNotReadyException $e) {
            $this->release(30);
        }

        if (isset($response->_links)) {
            foreach ($response->_links as $link) {
                if ($link->rel === 'next') {
                    dispatch(new GetTransactions(ltrim($link->href, '?')));
                }
            }
        }

        if (!isset($response->transactions)) {
            return;
        }

        foreach ($response->transactions as $transaction) {
            dispatch(new FindPaymentByTransaction($transaction));
        }
    }
}
