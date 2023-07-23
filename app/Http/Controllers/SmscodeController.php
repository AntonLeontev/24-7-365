<?php

namespace App\Http\Controllers;

use App\Http\Requests\SmscodeCreateRequest;
use App\Http\Requests\SmscodeRequest;
use App\Models\Smscode;
use App\Notifications\SmsCodeNotification;

class SmscodeController extends Controller
{
    public function checkCode(SmscodeRequest $request, string $type)
    {
        $type = $this->type($type);

        $code = Smscode::query()
            ->where('code', $request->code)
            ->where('user_id', auth()->id())
            ->where('operation_type', $type)
            ->where('status', Smscode::STATUS_PENDING)
            ->first();

        if (is_null($code)) {
            return response()->json(['ok' => false, 'message' => 'Код не верный']);
        }

        $code->update(['status' => Smscode::STATUS_PROCCESSED]);

        return response()->json(['ok' => true]);
    }

    public function createCode(SmscodeCreateRequest $request, string $type)
    {
        $type = $this->type($type);

        $code = Smscode::updateOrCreate([
            'user_id' => auth()->id(),
            'operation_type' => $type,
        ], [
            'code' => $this->generateCode(),
            'phone' => $request->phone,
            'status' => Smscode::STATUS_PENDING,
        ]);

        auth()->user()->notify(new SmsCodeNotification($code->code, $request->phone));

        return response()->json(['ok' => true, 'code' => $code->code]);
    }

    protected function generateCode(): int
    {
        $min = (int) str_repeat('1', Smscode::CODE_LENGTH);
        $max = (int) str_repeat('9', Smscode::CODE_LENGTH);

        return random_int($min, $max);
    }

    private function type(string $type): int
    {
        return match ($type) {
            'phone_confirmation' => Smscode::PHONE_CONFIRMATION,
            'contract_creating' => Smscode::CONTRACT_CREATING,
        };
    }
}
