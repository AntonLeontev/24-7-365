<?php

namespace App\Actions;

use App\Exceptions\Socials\EmailIsNullException;
use App\Models\User;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;

class GetUserFromSocialsAction
{
    private function createUser(SocialiteUser $socialiteUser): User
    {
        return User::create(
            [
                'email' => $socialiteUser->getEmail(),
                'email_verified_at' => now(),
                'first_name' => $socialiteUser->user['first_name'] ?? $socialiteUser->getName(),
                'password' => bcrypt(str()->random(10))
            ]
        );
    }

    public function __invoke(string $driver): User
    {
        $socialiteUser = Socialite::driver($driver)->user();

        if (is_null($socialiteUser->getEmail())) {
            throw new EmailIsNullException("В профиле не указан email", 1);
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
