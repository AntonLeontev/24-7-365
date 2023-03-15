<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View ;
use Illuminate\Support\Facades\Auth;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        View::composer('layouts.part.header', function($view) {
            $view->with(['user' => Auth::user()]);
        });
        
            View::composer('layouts.part.top_greetings', function($view) {
                $view->with(['user' => Auth::user()]);
            });
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
