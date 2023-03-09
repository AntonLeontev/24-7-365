<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CanSeeContract
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->can('see other profiles')) {
            return $next($request);
        }

        if ($request->route('contract')->user_id === auth()->id()) {
            return $next($request);
        }

        abort(403, 'Доступ запрещен');
    }
}
