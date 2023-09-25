<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileSaveRequest;
use App\Models\Account;
use App\Models\Organization;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    use ResetsPasswords;

    public function profile()
    {
        $user = auth()->user();

        return view('users.profile', compact('user'));
    }

    public function storeProfile(ProfileSaveRequest $request)
    {
        $user = Auth::user();

        $user->first_name = $request->first_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->save();

        if ($request->inn) {
            $id = Organization::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'inn' => $request->inn,
                    'user_id' => auth()->id(),
                    'kpp' => $request->kpp,
                    'title' => $request->title,
                    'ogrn' => $request->ogrn,
                    'legal_address' => $request->legal_address,
                    'director' => $request->director,
                    'directors_post' => $request->directors_post,
                ]
            )->id;

            Account::updateOrCreate(
                ['organization_id' => $id],
                [
                    'bik' => $request->bik,
                    'bank' => $request->bank,
                    'payment_account' => $request->payment_account,
                    'correspondent_account' => $request->correspondent_account,
                ]
            );
        }

        if (! empty($request->password)) {
            $user->updateOrFail(['password' => bcrypt($request->password)]);
        }

        if (request()->ajax()) {
            return response()->json(['ok' => true, 'message' => 'Данные сохранены']);
        }

        return back();
    }

    public function checkProfileInput(ProfileSaveRequest $request)
    {
        return response()->json(['ok' => true]);
    }
}
