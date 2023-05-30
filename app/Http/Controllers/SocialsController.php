<?php

namespace App\Http\Controllers;

use App\Actions\GetUserFromSocialsAction;
use App\Exceptions\Socials\EmailIsNullException;
use DomainException;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class SocialsController extends Controller
{
    public function redirect(string $driver)
    {
        return Socialite::driver($driver)->redirect();
    }

    public function callback(string $driver, GetUserFromSocialsAction $action)
    {
        if (request()->has('error') && request('error') === 'access_denied') {
            return to_route('login');
        }

        if (request()->has('error')) {
            throw new DomainException(request('error_description'), 1);
        }

        try {
            $user = $action($driver);
        } catch (InvalidStateException $e) {
            return back();
        } catch (EmailIsNullException $e) {
            return to_route('register')->with(['email_is_null' => true]);
        }

        auth()->login($user, true);

        return to_route('home');
    }
}
