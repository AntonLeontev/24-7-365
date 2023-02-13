<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Contract;
use Illuminate\Support\Facades\Auth;

class ContractController extends Controller
{
    public function index()
    {
        
        $organization = User::with('organization')->find(Auth::user()->id)->organization;
        
       // $organization= NULL;
        
        if(is_null($organization)){
           
            return redirect()
            ->route('users.profile')
            ->withErrors('Укажите данные орагнизации, чтобы просматривать раздел Активные Договора');
        }
        
        $contracts = Contract::with('tariff')->where('organization_id',  $organization->id)
        ->where('status','1')
        ->where('deleted_at',NULL)
        ->orderByDesc('created_at')
        ->paginate();
        
       
        return view('users.contracts.contracts_list', compact('contracts'));
    }
}
