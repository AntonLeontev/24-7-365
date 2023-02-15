<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function get(Request $request)
    {
        $sum = 660235.2;
		$data = compact('sum');

        if ($request->is('pdf/get')) {
			return Pdf::loadView('pdf.invoice', $data)->download('invoice.pdf');
        }

		return view('pdf.invoice', $data);
    }
}
