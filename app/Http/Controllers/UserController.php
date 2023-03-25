<?php

namespace App\Http\Controllers;

use App\Events\UserBlocked;
use App\Events\UserUnblocked;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Requests\UserCreateRequest;
use App\Models\User;
use App\ValueObjects\Amount;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(User $user, Request $request)
    {
        $users = $user->query()
            ->select([
                'users.id',
                'users.first_name',
                'users.status',
                DB::raw('organizations.title AS organization'),
                'role_id',
                DB::raw('roles.name AS role'),
                DB::raw('(SELECT SUM(amount) FROM contracts WHERE user_id = users.id AND (status = 1 OR status = 2)) AS contracts_sum')
            ])
            ->leftJoin('organizations', 'users.id', 'organizations.user_id')
            ->leftJoin('model_has_roles', 'users.id', 'model_has_roles.model_id')
            ->leftJoin('roles', 'role_id', 'roles.id')
            ->whereNot('email', 'superuser@test.ru')
            ->when(! request()->has('sort'), function (Builder $query) {
                $query->orderByDesc('users.created_at');
            })
            ->when(request()->has(['sort', 'order']), function (Builder $query) {
                    $query->orderBy(request()->sort, request()->order);
            })
            ->when(request()->has('search'), function (Builder $query) {
                $query->where(function (Builder $query) {
                    $query->where('first_name', 'like', '%' . request()->search . '%')
                        ->orWhere('organizations.title', 'like', '%' . request()->search . '%');
                });
            })
            ->get()
            ->when(request()->has(['filter']), function ($collection, $value) {
                if (request()->filter === 'with_contracts') {
                    return $collection->filter(function ($value) {
                        return $value->contracts_sum > 0;
                    });
                }
            })
            ->transform(function ($item) {
                if (!is_null($item->contracts_sum)) {
                    $item->contracts_sum = new Amount($item->contracts_sum);
                }

                return $item;
            })
            ->paginate()
            ->withQueryString();

        if (request()->ajax()) {
            return response()->json($users)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
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
        return back();
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
