<?php

namespace App\Http\Controllers;

use App\Events\UserBlocked;
use App\Events\UserUnblocked;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Requests\UserCreateRequest;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function profile()
    {
        return view('users.profile', ['user' => auth()->user()]);
    }

    public function index(User $user, Request $request)
    {
        $users = $user->query()
            ->select([
                'users.id',
                'users.first_name',
                'users.last_name',
                'users.status',
                DB::raw('organizations.title AS organization'),
                'role_id',
                DB::raw('roles.name AS role'),
                ])
            ->with(['roles', 'account'])
            ->leftJoin('organizations', 'users.id', 'organizations.user_id')
            ->leftJoin('model_has_roles', 'users.id', 'model_has_roles.model_id')
            ->leftJoin('roles', 'role_id', 'roles.id')
            ->whereNot('email', 'superuser@test.ru')
            ->when(!$request->has('sort'), function (Builder $query) {
                $query->orderByDesc('users.created_at');
            })
            ->when($request->has(['sort', 'order']), function (Builder $query) use ($request) {
                if ($request->order === 'ASC') {
                    $query->orderBy($request->sort);
                    return;
                }

                $query->orderByDesc($request->sort);
            })
            ->paginate()
            ->withQueryString();

        if ($request->ajax()) {
            return response()->json($users);
        }

        return view('users.index');
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
        $user->update(['status' => User::BLOCKED]);

        event(new UserBlocked($user));

        return back();
    }

    public function unblockUser(User $user)
    {
        $user->update(['status' => User::ACTIVE]);

        event(new UserUnblocked($user));

        return back();
    }
}
