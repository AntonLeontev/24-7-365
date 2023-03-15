<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Payment;
use App\Models\Tariff;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function invoice(Payment $payment)
    {
        if (request()->is('invoices/{\d}/pdf/get')) {
            return view('pdf.invoice', compact('payment'));
        }
        return Pdf::loadView('pdf.invoice', compact('payment'))->download('invoice.pdf');
    }

    public function contract(Contract $contract)
    {
        $tariffs = Tariff::where('status', Tariff::ACTIVE)->get()->groupBy('title');
        if (request()->is('contracts/pdf/get')) {
			return view('pdf.contract', compact('contract', 'tariffs'));
		}
		return Pdf::loadView('pdf.contract', compact('contract', 'tariffs'))->download('contract.pdf');
        
    }
}
