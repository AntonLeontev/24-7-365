<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateRoleRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;

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
            ->orderBy('created_at')
            ->paginate();

        return view('users.index', compact('users'));
    }

    public function show(User $user)
    {
        return view('users.profile', ['user' => $user->load('roles')]);
    }

    public function updateRole(User $user, UpdateRoleRequest $request)
    {
        $user->syncRoles($request->validated());
        return response()->json();
    }
}
