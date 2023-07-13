<?php

namespace App\Exceptions;

use App\Support\Services\Planfact\Exceptions\PlanfactBadRequestException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (RuntimeException $e) {
            if (str_starts_with($e->getMessage(), 'Telegram API error')) {
                Log::error($e->getMessage());
                return;
            }
        });

        $this->reportable(function (Throwable $e) {
            Log::channel('telegram')->error($e->getMessage(), ['exception' => $e]);
        });
    }
}
