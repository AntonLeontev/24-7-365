<?php

namespace App\Actions;

use App\Models\User;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class GetUserFromSocialsAction
{
    private function createUser(SocialiteUser $socialiteUser): User
    {
        return User::create(
            [
                'email' => $socialiteUser->getEmail(),
                'email_verified_at' => now(),
                'first_name' => $socialiteUser->user['first_name'] ?? $socialiteUser->getName() .
				' ' . $socialiteUser->user['last_name'] ?? '',
                'password' => bcrypt(str()->random(10))
            ]
        );
    }

    public function __invoke(string $driver): User
    {
        try {
            $socialiteUser = Socialite::driver($driver)->user();
        } catch (InvalidStateException $e) {
            return to_route('register');
        }

        $user = User::where('email', $socialiteUser->getEmail())->first();

        if (! is_null($user)) {
            return $user;
        }

        $user = $this->createUser($socialiteUser);

        $user->assignRole('Клиент');

        return $user;
    }
}
