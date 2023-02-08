<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function profile()
    {
        return view('users.profile', ['user' => auth()->user()]);
    }

    public function index(User $user)
    {
        $users = $user->with('roles')
            ->whereNot('email', 'superuser@test.ru')
            ->latest()
            ->paginate();

        return view('users.index', compact('users'));
    }

    public function show(User $user)
    {
        return view('users.profile', compact('user'));
    }
}
