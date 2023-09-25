<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PreparePhoneNumber
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (empty($request->phone)) {
            return $next($request);
        }

        $phone = preg_replace('~\D~', '', $request->phone);
        $phone[0] = '7';
        $request->merge(['phone' => $phone]);

        return $next($request);
    }
}
