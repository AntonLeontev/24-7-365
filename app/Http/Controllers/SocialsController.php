<?php

namespace App\Http\Controllers;

use App\Actions\GetUserFromSocialsAction;
use Laravel\Socialite\Facades\Socialite;

class SocialsController extends Controller
{
    public function redirect($driver)
    {
        return Socialite::driver($driver)->redirect();
    }

    public function callback(string $driver, GetUserFromSocialsAction $action)
    {
        $user = $action($driver);

        auth()->login($user, true);

        return match (true) {
            $user->hasRole('Клиент') => to_route('users.profile'),
            $user->hasRole('Админ') => to_route('users.index'),
			default => '/'
        };
    }
}
