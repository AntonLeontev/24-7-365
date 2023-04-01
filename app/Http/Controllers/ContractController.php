<?php

namespace App\Http\Controllers;

use App\Events\CanceledUnconfirmedContractChange;
use App\Events\ContractAmountIncreased;
use App\Events\ContractCanceled;
use App\Events\ContractCreated;
use App\Http\Requests\CancelContractRequest;
use App\Http\Requests\ContractUpdateRequest;
use App\Http\Requests\StoreContractRequest;
use App\Models\Contract;
use App\Models\Payment;
use App\Models\Profitability;
use App\ValueObjects\Amount;

class ContractController extends Controller
{
    public function index()
    {
        
        $organization = auth()->user()->organization;
        
       // $organization= NULL;
        
        if (is_null($organization)) {
            return redirect()
            ->route('users.profile')
            ->withErrors('Укажите данные орагнизации, чтобы просматривать раздел Активные Договора');
        }
        
        $contracts = Contract::with('tariff')->where('organization_id', $organization->id)
            ->whereIn('status', [Contract::ACTIVE, Contract::PENDING, Contract::CANCELED])
            ->where('deleted_at', null)
            ->orderByDesc('created_at')
            ->paginate();
        
       
        return view('users.contracts.contracts_list', compact('contracts'));
    }

    public function show(Contract $contract)
    {
        $contract->load(['tariff', 'contractChanges']);

        $profitabilities = Profitability::query()
            ->with('payment')
            ->where('contract_id', $contract->id)
            ->get();
        
        $payments = Payment::query()
            ->where('contract_id', $contract->id)
            ->where('type', Payment::TYPE_CREDIT)
            ->get();

        $operations = $profitabilities->mergeRecursive($payments)->sortBy('planned_at');

        $totalProfitabilities = $contract->profitabilities->reduce(function ($carry, $item) {
            return $carry + $item->amount->raw();
        }, 0);
        $totalProfitabilities = new Amount($totalProfitabilities);

        $totalPayments = $payments->where('status', Payment::STATUS_PROCESSED)->reduce(function ($carry, $item) {
            return $carry + $item->amount->raw();
        }, 0);
        $totalPayments = new Amount($totalPayments);

        return view('users.contracts.contract', compact('contract', 'operations', 'totalProfitabilities', 'totalPayments'));
    }

    public function agree()
    {
        return view('users.contracts.agree');
    }
    
    public function create()
    {
        return view('users.contracts.add_contract');
    }
    
    public function store(StoreContractRequest $request)
    {
        $contract = Contract::create($request->except(['_token']));

        event(new ContractCreated($contract));

        $paymentId = $contract->payments->first()->id;

        return response()->json(['ok' => true, 'paymentId' => $paymentId]);
    }

    public function cancel(Contract $contract, CancelContractRequest $request)
    {
        $contract->updateOrFail(['status' => $contract::CANCELED]);

        event(new ContractCanceled($contract));

        return to_route('users.contracts');
    }

    public function edit(Contract $contract)
    {
        $contract->load(['tariff']);
        
        return view('users.contracts.edit', compact('contract'));
    }

    public function update(Contract $contract, ContractUpdateRequest $request)
    {
        if ($request->addedAmount > 0) {
            event(new ContractAmountIncreased($contract, $request->addedAmount));

            return response()->json(['ok' => true]);
        }

        return to_route('users.contract_show', $contract->id);
    }

    public function cancelChange(Contract $contract)
    {
        event(new CanceledUnconfirmedContractChange($contract));

        return back();
    }
}
