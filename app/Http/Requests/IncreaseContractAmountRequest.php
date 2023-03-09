<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IncreaseContractAmountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->id() === $this->route('contract')->user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $maxAmount = $this->route('contract')->tariff->max_amount->raw() === 0 ?
            PHP_INT_MAX :
            $this->route('contract')->tariff->max_amount->amount();

        return [
            'amount' => [
                'required',
                'integer',
                'min:' . $this->route('contract')->amount->amount(),
                'max:' . $maxAmount,
            ],
        ];
    }

    protected function passedValidation(): void
    {
        $this->merge(['amount' => (int) $this->amount * 100,]);
    }
}
