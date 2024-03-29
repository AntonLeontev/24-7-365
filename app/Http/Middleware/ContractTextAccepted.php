<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ContractTextAccepted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->boolean('read') && $request->boolean('understood') && $request->boolean('agreed')) {
            return $next($request);
        }

        return to_route('contracts.agree');
    }
}
