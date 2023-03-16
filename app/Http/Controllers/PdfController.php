<?php

namespace App\Http\Controllers;

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

    public function contract()
    {
        $tariffs = Tariff::where('status', Tariff::ACTIVE)->get()->groupBy('title');
        
		return Pdf::loadView('pdf.contract.index', compact('tariffs'))->download('contract.pdf');
    }
}
