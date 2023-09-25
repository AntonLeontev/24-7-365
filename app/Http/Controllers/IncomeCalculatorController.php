<?php

namespace App\Http\Controllers;

class IncomeCalculatorController extends Controller
{
    public function show()
    {
        return view('tariffs.calculator');
    }
}
