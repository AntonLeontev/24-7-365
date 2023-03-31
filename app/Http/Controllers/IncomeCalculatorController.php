<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IncomeCalculatorController extends Controller
{
    public function show() {
        return view('tariffs.calculator');
    }
}
