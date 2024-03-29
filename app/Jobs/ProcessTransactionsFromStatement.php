<?php

namespace App\Jobs;

use App\Support\Services\TochkaBank\TochkaBankService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessTransactionsFromStatement implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private string|int $id)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(TochkaBankService $service): void
    {
        $service->getTransactions($this->id);
    }
}
