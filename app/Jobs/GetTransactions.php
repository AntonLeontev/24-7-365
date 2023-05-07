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


    public $tries = 3;
    public $timeout = 30;


    public function __construct(private ?string $query = null)
    {
    }

    /**
	* Calculate the number of seconds to wait before retrying the job.
	*
	* @return array<int, int>
	*/
    public function backoff(): array
    {
        return [5, 25];
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
