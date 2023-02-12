<?php

use App\Models\ApplicationSettings;
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
