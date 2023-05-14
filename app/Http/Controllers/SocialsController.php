<?php

namespace App\Http\Controllers;

use App\Actions\GetUserFromSocialsAction;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class SocialsController extends Controller
{
    public function redirect($driver)
    {
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
