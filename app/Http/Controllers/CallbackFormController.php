<?php

namespace App\Http\Controllers;

use App\Mail\CallbackForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CallbackFormController extends Controller
{
    public function __invoke(Request $request)
	{
		Mail::to('info@true-trade.ru')->send(new CallbackForm($request->phone, $request->name));
	}
}
