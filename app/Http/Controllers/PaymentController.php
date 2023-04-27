<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Profitability;
use App\Notifications\NewInvoice;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function indexForUser()
    {
        $contractsIds = auth()->user()->contracts->pluck('id');
        $profitabilities = Profitability::query()
            ->with(['payment', 'contract'])
            ->whereIn('contract_id', $contractsIds)
            ->get()
            ->groupBy(function ($operation) {
                return $operation->accrued_at->format('Y-m');
            })
            ->sortKeysUsing(function ($a, $b) {
                return Carbon::parse($a) <=> Carbon::parse($b);
            });

        return view('users.payments', compact('profitabilities'));
    }

    public function sendInvoice(Payment $payment)
    {
        auth()->user()->notify(new NewInvoice($payment));

        return response()->json(['ok' => true]);
    }
}
