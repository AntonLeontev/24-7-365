<?php

namespace App\Providers;

use App\Exceptions\TochkaBank\TochkaBankException;
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

        Http::macro('tochka', function () {
            return Http::withHeaders(['Authorization' => 'Bearer ' . config('services.tochka.jwt')])
                ->timeout(20)
                ->asJson()
                ->acceptJson()
                ->baseUrl('https://enter.tochka.com/uapi')
                ->throw(function (Response $response) {
                    throw new TochkaBankException($response);
                });
        });

        Http::macro('tochkatest', function () {
            return Http::withHeaders(['Authorization' => 'Bearer working_token'])
                ->timeout(20)
                ->asJson()
                ->acceptJson()
                ->baseUrl('https://enter.tochka.com/sandbox/v2');
                // ->throw(function (Response $response) {
                //     throw new PlanfactBadRequestException($response);
                // });
        });
    }
}
