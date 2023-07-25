<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class TochkaBankController extends Controller
{
    public function incomingPayment(Request $request)
    {
        $arr = explode('.', $request->getContent());
        $str = json_decode(base64_decode($arr[1]));
        Log::channel('telegram')->debug("Поступила оплата {$str->SidePayer->amount} от {$str->SidePayer->name}");

		Artisan::call('24:check-payments');
    }
}
