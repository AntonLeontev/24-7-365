<?php

namespace App\Http\Controllers;

use App\Events\CanceledUnconfirmedContractChange;
use App\Events\ContractAmountIncreased;
use App\Events\ContractCanceled;
use App\Events\ContractCreated;
use App\Http\Requests\CancelContractRequest;
use App\Http\Requests\IncreaseContractAmountRequest;
use App\Http\Requests\StoreContractRequest;
use App\Models\Contract;
use App\Models\Payment;
use App\Models\Profitability;
use App\Models\User;
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
        // ->whereIn('status', [Contract::ACTIVE, Contract::PENDING, Contract::CANCELED])
        ->where('deleted_at', null)
        ->orderByDesc('created_at')
        ->paginate();
        
       
        return view('users.contracts.contracts_list', compact('contracts'));
    }

    public function show(Contract $contract)
    {
        $contract->load('tariff');

        $profitabilities = Profitability::query()
            ->with('payment')
            ->where('contract_id', $contract->id)
            ->get();
        
        $payments = Payment::query()
            ->where('contract_id', $contract->id)
            ->where('type', Payment::TYPE_CREDIT)
            ->get();

        $operations = $profitabilities->mergeRecursive($payments)->sortBy('planned_at');

        return view('users.contracts.contract', compact('contract', 'operations'));
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

    public function cancel(Contract $contract, CancelContractRequest $request)
    {
        $contract->updateOrFail(['status' => $contract::CANCELED]);

        event(new ContractCanceled($contract));

        return to_route('users.contracts');
    }

    public function increaseAmount(Contract $contract, IncreaseContractAmountRequest $request)
    {
        event(new ContractAmountIncreased($contract, $request->amount));

        return to_route('users.contract_show', $contract->id);
    }

    public function cancelChange(Contract $contract)
    {
        event(new CanceledUnconfirmedContractChange($contract));

        return back();
    }
}
