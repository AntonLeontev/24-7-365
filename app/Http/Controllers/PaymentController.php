<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Profitability;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function indexForUser()
    {
        $contractsIds = auth()->user()->contracts->pluck('id')->toArray();
        $profitabilities = Profitability::query()
            ->with('payment')
            ->whereIn('contract_id', $contractsIds)
            ->get();
        
        $payments = Payment::query()
            ->whereIn('contract_id', $contractsIds)
            ->where('type', Payment::TYPE_CREDIT)
			->orderBy('planned_at')
            ->get();

        $operations = $profitabilities->merge($payments)
            ->groupBy(function ($operation) {
                return $operation->planned_at->format('Y-m');
            })
            ->sortKeysUsing(function ($a, $b) {
                return Carbon::parse($a) <=> Carbon::parse($b);
            });

        // dd($operations);

        return view('users.payments', compact('operations'));
    }
}
