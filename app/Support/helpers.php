<?php

use App\Models\ApplicationSettings;
use App\Support\AmountToString;
use Spatie\Permission\Models\Role;

if (! function_exists('roles')) {
    function roles()
    {
        return cache()->rememberForever('roles', function () {
            return Role::whereNot('name', 'Superuser')->get()->pluck('name');
        });
    }
}

if (! function_exists('settings')) {
    function settings()
    {
        return cache()->rememberForever('settings', function () {
            return ApplicationSettings::first();
        });
    }
}

if (! function_exists('mb_ucfirst')) {
    function mb_ucfirst($string, $encoding = "UTF-8")
    {
        $firstChar = mb_substr($string, 0, 1, $encoding);
        $then = mb_substr($string, 1, null, $encoding);
        return mb_strtoupper($firstChar, $encoding) . $then;
    }
}

if (! function_exists('amount_to_string')) {
    function amount_to_string(float | int $number)
    {
        return mb_ucfirst(app(AmountToString::class)->do($number));
    }
}
