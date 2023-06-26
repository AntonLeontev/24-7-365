<?php

namespace App\Http\Controllers;

use App\Events\UserBlocked;
use App\Events\UserUnblocked;
use App\Http\Requests\UpdatePhoneRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Resources\UserCollection;
use App\Models\User;
use App\ValueObjects\Amount;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(User $user, Request $request)
    {
        if (!request()->ajax()) {
            return view('users.index');
        }

        $users = $user->query()
            ->select([
                'users.id',
                'users.first_name',
                'users.email',
                'users.is_blocked',
                DB::raw('organizations.title AS organization'),
                'role_id',
                DB::raw('roles.name AS role'),
                DB::raw('(SELECT SUM(amount) FROM contracts WHERE user_id = users.id AND (status = "active" OR status = "canceled")) AS contracts_sum')
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
            ->when(request()->has(['filter']), function (Builder $query) {
                if (request()->filter === 'with_contracts') {
                    $query->having('contracts_sum', '>', 0);
                }
            })
            ->simplePaginate()
            ->withQueryString()
			->through(function ($item) {
                if (!is_null($item->contracts_sum)) {
                    $item->contracts_sum = new Amount($item->contracts_sum);
                }

                return $item;
            });

        return (new UserCollection($users))->response()->header('Cache-Control', 'no-store, no-cache, must-revalidate');
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

    public function updatePhone(UpdatePhoneRequest $request)
    {
        auth()->user()->updateOrFail($request->validated());

        return response()->json(['ok' => true]);
    }

    public function validatePhone(UpdatePhoneRequest $request)
    {
        return response()->json(['ok' => true]);
    }

    public function create(UserCreateRequest $request)
    {
        $user = User::create($request->except(['roles', '_token', 'password_confirmation']));

        $user->assignRole($request->roles);

        return back();
    }

    public function blockUser(User $user)
    {
        $user->update(['is_blocked' => true]);

        event(new UserBlocked($user));

        return back();
    }

    public function unblockUser(User $user)
    {
        $user->update(['is_blocked' => false]);

        event(new UserUnblocked($user));

        return back();
    }
}
