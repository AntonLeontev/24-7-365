<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (! Auth::guard($guard)->check()) {
                return $next($request);
            }

            if (auth()->user()->hasRole('Клиент')) {
                if (auth()->user()->contracts->isNotEmpty()) {
                    return to_route('users.contracts');
                }

                return to_route('income_calculator');
            }

            if (auth()->user()->hasRole('Админ')) {
                return to_route('users.index');
            }

            if (auth()->user()->hasRole('АСБК')) {
                return to_route('users.index');
            }
        }

        return $next($request);
    }
}
