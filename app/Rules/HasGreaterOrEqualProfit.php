<?php

namespace App\Rules;

use App\Models\Tariff;
use Illuminate\Contracts\Validation\InvokableRule;

class HasGreaterOrEqualProfit implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        $newProfit = Tariff::find($value)->annual_rate;
        $oldProfit = Tariff::find(request()->route('contract')->tariff_id)->annual_rate;

        if ($oldProfit > $newProfit) {
            $fail('Переход на менее доходный тариф запрещен');
        }
    }
}
