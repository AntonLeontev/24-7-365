<?php

namespace App\Jobs;

use App\Exceptions\Sber\TransactionsNotReadyException;
use App\Support\Services\Sber\SberBusinessApiService;
use App\Support\Services\TochkaBank\TochkaBankService;
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

    public function handle(TochkaBankService $service): void
    {
		$service->getTransactions();
    }
}
