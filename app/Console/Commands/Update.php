<?php

namespace App\Console\Commands;

use App\Enums\PaymentType;
use App\Models\Payment;
use Illuminate\Console\Command;

class Update extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
    }
}
