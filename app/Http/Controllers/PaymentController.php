<?php

namespace App\Http\Controllers;

use App\Enums\PaymentType;
use App\Http\Resources\PaymentCollection;
use App\Models\Payment;
use App\Models\Profitability;
use App\Notifications\NewInvoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

    public function invoicesIndex()
    {
        if (! request()->ajax()) {
            return view('invoices.index');
        }

        $payments = Payment::query()
            ->select([
                'payments.number',
                'payments.status',
                'payments.created_at',
                'payments.amount',
                DB::raw('organizations.title AS organization_title'),
            ])
            ->leftJoin('accounts', 'payments.account_id', 'accounts.id')
            ->leftJoin('organizations', 'accounts.organization_id', 'organizations.id')
            ->where('type', PaymentType::debet)
            ->orderByDesc('created_at')
            ->simplePaginate(10)
            ->withQueryString();
        
        return (new PaymentCollection($payments))->response()->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }
}
