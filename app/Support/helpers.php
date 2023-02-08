<?php

use Spatie\Permission\Models\Role;

if (! function_exists('roles')) {
    function roles()
    {
        return Role::whereNot('name', 'Superuser')->get()->pluck('name');
    }
}
