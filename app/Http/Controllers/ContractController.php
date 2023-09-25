<?php

namespace App\Http\Controllers;

use App\Enums\ContractStatus;
use App\Enums\PaymentType;
use App\Events\ContractCanceled;
use App\Events\ContractChangeCanceled;
use App\Events\ContractChangingWithIncreasingAmount;
use App\Events\ContractCreated;
use App\Events\ContractTariffChanging;
use App\Http\Requests\CancelContractRequest;
use App\Http\Requests\ContractUpdateRequest;
use App\Http\Requests\StoreContractRequest;
use App\Http\Resources\ContractCollection;
use App\Models\Contract;
use App\Models\Profitability;
use App\ValueObjects\Amount;
use DomainException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ContractController extends Controller
{
    public function index()
    {
        $contracts = auth()->user()->contracts->load('tariff', 'payments', 'contractChanges')->reverse();

        return view('users.contracts.contracts_list', compact('contracts'));
    }

    public function adminIndex()
    {
        if (! request()->ajax()) {
            return view('contracts.index');
        }

        $contracts = Contract::query()
            ->select([
                'contracts.id',
                'contracts.organization_id',
                'contracts.tariff_id',
                'contracts.amount',
                'contracts.status',
                'contracts.paid_at',
                DB::raw('organizations.title AS organization'),
                DB::raw('tariffs.title AS tariff_title'),
                DB::raw('tariffs.duration AS tariff_duration'),
            ])
            ->leftJoin('organizations', 'contracts.organization_id', 'organizations.id')
            ->leftJoin('tariffs', 'contracts.tariff_id', 'tariffs.id')
            ->when(! request()->has('sort'), function (Builder $query) {
                $query->orderByDesc('contracts.created_at');
            })
            ->when(request()->has(['sort', 'order']), function (Builder $query) {
                $query->orderBy(request()->sort, request()->order);
            })
            ->when(request()->has('search'), function (Builder $query) {
                $query->where(function (Builder $query) {
                    $query->where('contracts.id', request()->search)
                        ->orWhere('organizations.title', 'like', '%'.request()->search.'%');
                });
            })
            ->when(request()->has(['filter']), function (Builder $query) {
                if (request()->filter === 'paid') {
                    $query->where('contracts.paid_at', '>', 0);
                }

                if (request()->filter === 'pending') {
                    $query->whereNull('paid_at');
                }
            })
            ->simplePaginate()
            ->withQueryString();

        return (new ContractCollection($contracts))->response()->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    public function show(Contract $contract)
    {
        $contract->load(['tariff', 'contractChanges']);

        if (is_null($contract->paid_at)) {
            return view('users.contracts.contract', compact('contract'));
        }

        $profitabilities = Profitability::query()
            ->with('payment')
            ->where('contract_id', $contract->id)
            ->orderBy('accrued_at')
            ->get();

        $operations = $profitabilities;

        $totalProfitabilities = $contract->profitabilities->reduce(function ($carry, $item) {
            return $carry + $item->amount->raw();
        }, 0);
        $totalProfitabilities = new Amount($totalProfitabilities);

        $totalPayments = new Amount($contract->outgoing());

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

        if ($contract->currentTariffDuration() + 1 === $contract->tariff->duration) {
            return response()->json(['ok' => false, 'message' => 'Договор на последнем периоде. Изменения не будут применены']);
        }

        if ($request->addedAmount > 0) {
            event(new ContractChangingWithIncreasingAmount($contract, $request->addedAmount, $request->tariff_id));

            $payment = $contract->payments->where('type', PaymentType::debet)->last();

            return response()->json(['ok' => true, 'payment_id' => $payment->id]);
        }

        if ($contract->id !== $request->contract_id) {
            event(new ContractTariffChanging($contract, $request->tariff_id));

            return response()->json(['ok' => true]);
        }

        throw new DomainException('Неизвестное изменение');
    }

    public function cancelChange(Contract $contract)
    {
        event(new ContractChangeCanceled($contract));

        return back();
    }

    public function cancelProlongation(Contract $contract)
    {
        $contract->update(['prolongate' => false]);

        return back();
    }
}
