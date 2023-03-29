<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrganizationValidateRequest;
use App\Models\Account;
use App\Models\Organization;

class NewContractController extends Controller
{
    public function saveRequesites(OrganizationValidateRequest $request)
    {
        $organization = Organization::updateOrCreate(
            ['user_id' => auth()->id()],
            $request->safe(['inn', 'kpp', 'ogrn', 'title', 'director', 'directors_post', 'legal_address'])
        );

        Account::updateOrCreate(
            ['organization_id' => $organization->id],
			$request->safe(['bik', 'bank', 'payment_account', 'correspondent_account'])
        );

        return response()->json(['ok' => true]);
    }
}
