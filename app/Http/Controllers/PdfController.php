<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function invoice(Payment $payment)
    {
        //TODO delete
        if (request()->is('invoices/{\d}/pdf/get')) {
            return view('pdf.invoice', compact('payment'));
        }
        return Pdf::loadView('pdf.invoice', compact('payment'))->download('Счет.pdf');
    }

    public function contract()
    {
        // return view('pdf.contract.index');
        return Pdf::loadView('pdf.contract.index')->download('Оферта.pdf');
    }
}
