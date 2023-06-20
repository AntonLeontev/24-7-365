<?php

namespace App\Http\Middleware;

use App\Mail\CompanyRegistered;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class SendRegisterCompanyMail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        if ($response->status() >= 300) {
            return;
        }

        $json = json_decode($response->content(), false);

        Mail::to('reg@true-trade.ru')->send(new CompanyRegistered(
            $request->telephone,
            $request->first_name,
            $request->last_name,
            $request->second_name,
            $json->data,
        ));
    }
}
