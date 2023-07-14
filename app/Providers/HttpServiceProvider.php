<?php

namespace App\Providers;

use App\Support\Services\Planfact\Exceptions\PlanfactBadRequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class HttpServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Http::macro('planfact', function () {
            return Http::withHeaders(['X-ApiKey' => config('services.planfact.key')])
                ->timeout(8)
                ->asJson()
                ->acceptJson()
                ->baseUrl('https://api.planfact.io')
                ->throw(function (Response $response) {
                    throw new PlanfactBadRequestException($response);
                });
        });
    }
}
