<?php

namespace App\Http\Controllers;

use App\Support\Services\Telegram\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class TochkaBankController extends Controller
{
    public function incomingPayment(Request $request, TelegramService $telegram)
    {
        $arr = explode('.', $request->getContent());
        $str = json_decode(base64_decode($arr[1]));
        
        $message = "Поступила оплата {$str->SidePayer->amount} от {$str->SidePayer->name}";
        
        Log::channel('telegram')->debug($message);
        $telegram->sendSilentText($message, config('services.telegram.amount_chat'));

        Artisan::call('24:check-payments');
    }
}
