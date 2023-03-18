<?php

namespace App\Http\Controllers;

use App\Contracts\SuggestionsContract;
use Illuminate\Http\Request;

class SuggestionsController extends Controller
{
    public function company(Request $request, SuggestionsContract $service)
    {
        return response()->json($service->company($request->inn));
    }

    public function bank(Request $request, SuggestionsContract $service)
    {
        return response()->json($service->bank($request->bik));
    }
}
