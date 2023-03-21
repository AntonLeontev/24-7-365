<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tariff;

class IncomeCalculatorController extends Controller
{
   
    
    public function show(){
        
        $tariffs = Tariff::all()
        ->groupBy('title'); 
        
        return view('tariffs.income_calculator', compact('tariffs'));
    }
}
