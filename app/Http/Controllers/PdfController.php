<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function invoice(Payment $payment)
    {
		$number = $payment->number;
		$date = $payment->created_at;
        return Pdf::loadView('pdf.invoice', compact('payment'))->download("Счет №{$number} от {$date->format('d.m.Y')}.pdf");
    }

    public function contract()
    {
        // return view('pdf.contract.index');
        // return Pdf::loadView('pdf.contract.index')->download('Оферта.pdf');
        return redirect(config('app.url') . '/Оферта.pdf');
    }
}
