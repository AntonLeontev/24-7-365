<?php

namespace App\Http\Controllers;

use App\Actions\GetUserFromSocialsAction;
use DomainException;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class SocialsController extends Controller
{
    public function redirect(string $driver)
    {
        if (request()->has('error') && request('error') === 'access_denied') {
            return back();
        }

        if (request()->has('error')) {
            throw new DomainException(request('error_description'), 1);
        }

        return Socialite::driver($driver)->redirect();
    }

    public function callback(string $driver, GetUserFromSocialsAction $action)
    {
        try {
            $user = $action($driver);
        } catch (InvalidStateException $e) {
            return back();
        }

        auth()->login($user, true);

        return to_route('home');
    }
}
