<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function get()
    {
		$sum = 550000;
        return Pdf::loadView('pdf.invoice', compact('sum'))->download('invoice.pdf');
    }
}
