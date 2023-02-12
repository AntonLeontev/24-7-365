<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateRoleRequest;
use App\Http\Requests\UserCreateRequest;
use App\Models\User;

class UserController extends Controller
{
    public function profile()
    {
        return view('users.profile', ['user' => auth()->user()]);
    }

    public function index(User $user)
    {
        $users = $user->with(['roles', 'organization', 'account'])
            ->whereNot('email', 'superuser@test.ru')
            ->orderByDesc('created_at')
            ->paginate();

        return view('users.index', compact('users'));
    }

    public function show(User $user)
    {
        return view('users.profile', ['user' => $user->load(['roles', 'organization'])]);
    }

    public function updateRole(User $user, UpdateRoleRequest $request)
    {
        $user->syncRoles($request->validated());
        return response()->json();
    }

    public function create(UserCreateRequest $request)
    {
        $user = User::create($request->except(['roles', '_token', 'password_confirmation']));

        $user->assignRole($request->roles);

        return back();
    }

    public function blockUser(User $user)
    {
        $user->update(['status' => User::BANNED]);

        return back();
    }

    public function unblockUser(User $user)
    {
        $user->update(['status' => User::ACTIVE]);

        return back();
    }
}
