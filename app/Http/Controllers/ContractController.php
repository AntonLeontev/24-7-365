<?php

namespace App\Http\Controllers;

use App\Enums\ContractStatus;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Events\ContractAmountIncreasing;
use App\Events\ContractCanceled;
use App\Events\ContractChangeCanceled;
use App\Events\ContractCreated;
use App\Http\Requests\CancelContractRequest;
use App\Http\Requests\ContractUpdateRequest;
use App\Http\Requests\StoreContractRequest;
use App\Models\Contract;
use App\Models\Payment;
use App\Models\Profitability;
use App\ValueObjects\Amount;
use DomainException;

class ContractController extends Controller
{
    public function index()
    {
        $contracts = auth()->user()->contracts->load('tariff');
        
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
            ->where('type', PaymentType::credit)
            ->get();


        $operations = $profitabilities->mergeRecursive($payments)->sortBy('planned_at');

        $totalProfitabilities = $contract->profitabilities->reduce(function ($carry, $item) {
            return $carry + $item->amount->raw();
        }, 0);
        $totalProfitabilities = new Amount($totalProfitabilities);

        $totalPayments = $payments->where('status', PaymentStatus::processed)->reduce(function ($carry, $item) {
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
        $contract->updateOrFail(['status' => ContractStatus::canceled->value]);

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
        if ($contract->isChanging()) {
            return response()->json(['ok' => false, 'message' => 'Договор в процессе изменений. Новые изменения применить нельзя']);
        }

        if ($request->addedAmount > 0 && $contract->tariff_id !== $request->tariff_id) {
            //TODO
            return response()->json(['ok' => false, 'message' => 'not ready mixed']);
        }

        if ($request->addedAmount > 0) {
            event(new ContractAmountIncreasing($contract, $request->addedAmount));

            $payment = $contract->payments->where('type', PaymentType::debet)->last();

            return response()->json(['ok' => true, 'payment_id' => $payment->id]);
        }

        if ($contract->id !== $request->contract_id) {
            //TODO
            return response()->json(['ok' => false, 'message' => 'not ready contract change']);
        }

        throw new DomainException('Неизвестное изменение');
    }

    public function cancelChange(Contract $contract)
    {
        event(new ContractChangeCanceled($contract));

        return back();
    }
}
