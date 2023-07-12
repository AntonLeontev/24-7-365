<?php

namespace App\Providers;

use App\Contracts\AccountingSystemContract;
use App\Contracts\SmsService;
use App\Contracts\SuggestionsContract;
use App\Support\Services\DadataService;
use App\Support\Services\Planfact\PlanfactService;
use App\Support\Services\StreamTelecom\StreamTelecomService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(SuggestionsContract::class, DadataService::class);
		$this->app->bind(SmsService::class, StreamTelecomService::class);
		$this->app->bind(AccountingSystemContract::class, PlanfactService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Model::shouldBeStrict(app()->isLocal());

        //Для индексов Laravel-Permission
        Schema::defaultStringLength(125);
        
        Paginator::defaultView('vendor.pagination.bootstrap-5');
        Paginator::defaultSimpleView('vendor.pagination.simple-bootstrap-5');

        Password::defaults(function () {
            $rule = Password::min(8);
 
            return $this->app->isProduction()
                    ? $rule->mixedCase()
                    : $rule;
        });
        
		Carbon::setLocale('ru');
    }
}
