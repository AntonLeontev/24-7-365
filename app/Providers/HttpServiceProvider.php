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
                ->when(!app()->isProduction(), function ($request) {
                    return $request->withOptions(['verify' => false]);
                })
                ->timeout(8)
                ->asJson()
                ->acceptJson()
                ->baseUrl('https://api.planfact.io')
                ->throw(function (Response $response) {
                    throw new PlanfactBadRequestException($response);
                });
        });

        Http::macro('telegram', function () {
            return Http::timeout(5)
                ->baseUrl('https://api.telegram.org/bot' . config('services.telegram.bot'));
        });

        Http::macro('streamTelecom', function () {
            return Http::timeout(5)
                ->retry(3, 200)
                ->baseUrl('https://gateway.api.sc/get/');
        });
    }
}
