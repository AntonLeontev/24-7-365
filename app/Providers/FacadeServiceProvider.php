<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Dadata\DadataClient;

class FacadeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('dadata.client', fn () => new DadataClient(config('dadata-config.token'),config('dadata-config.secret')));
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
