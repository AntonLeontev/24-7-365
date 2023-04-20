<?php

namespace App\Http\Controllers;

use App\Enums\PaymentType;
use App\Models\Payment;
use App\Models\Profitability;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function indexForUser()
    {
        $contractsIds = auth()->user()->contracts->pluck('id')->toArray();
        $profitabilities = Profitability::query()
            ->with(['payment', 'contract'])
            ->whereIn('contract_id', $contractsIds)
            ->get();
        
        $payments = Payment::query()
            ->whereIn('contract_id', $contractsIds)
            ->where('type', PaymentType::credit)
            ->orderBy('planned_at')
            ->with('contract')
            ->get()
            ->filter(function ($payment) {
                $contract = $payment->contract->load('contractChanges');

                return $payment->planned_at > $contract->paid_at->addMonths($contract->duration());
            });

        $operations = $profitabilities->merge($payments)
            ->groupBy(function ($operation) {
                if (isset($operation->planned_at)) {
                    return $operation->planned_at->format('Y-m');
                }

                return $operation->accrued_at->format('Y-m');
            })
            ->sortKeysUsing(function ($a, $b) {
                return Carbon::parse($a) <=> Carbon::parse($b);
            });


        return view('users.payments', compact('operations'));
    }
}
