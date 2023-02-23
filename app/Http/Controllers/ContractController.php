<?php

namespace App\Http\Controllers;

use App\Events\ContractCreated;
use App\Http\Requests\StoreContractRequest;
use App\Models\Contract;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContractController extends Controller
{
    public function index()
    {
        
        $organization = User::with('organization')->find(Auth::user()->id)->organization;
        
       // $organization= NULL;
        
        if (is_null($organization)) {
            return redirect()
            ->route('users.profile')
            ->withErrors('Укажите данные орагнизации, чтобы просматривать раздел Активные Договора');
        }
        
        $contracts = Contract::with('tariff')->where('organization_id', $organization->id)
        ->where('status', '1')
        ->where('deleted_at', null)
        ->orderByDesc('created_at')
        ->paginate();
        
       
        return view('users.contracts.contracts_list', compact('contracts'));
    }

    public function show(Contract $contract)
    {
        $contract->load('tariff');
        
        $payments = Payment::where('contract_id', $contract->id)
			->orderByDesc('created_at')
			->paginate();
        
        return view('users.contracts.contract', compact('contract', 'payments'));
    }
    
    public function create()
    {
        //TODO Механизм реализации условий договоров ..
        $termsOfContract = "Тестовые условия договора";
        
        return view('users.contracts.add_contract', compact('termsOfContract'));
    }
    
    public function store(StoreContractRequest $request)
    {
        $contract = Contract::create($request->except(['_token']));

		event(new ContractCreated($contract));

        return to_route('users.contract_show', $contract->id);
    }
}
