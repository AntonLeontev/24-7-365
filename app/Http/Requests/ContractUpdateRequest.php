<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContractUpdateRequest extends FormRequest
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
        return [
            'addedAmount' => ['integer', 'min:1000', 'nullable'],
            'tariff_id' => ['required', 'exists:tariffs,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'addedAmount' => 'Сумма',
            'tariff_id' => 'Тариф',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->addedAmount === 0) {
			$this->merge(['addedAmount' => null]);
		}
    }

    protected function passedValidation(): void
    {
        $amount = $this->addedAmount ?? 0;
        $this->merge(['addedAmount' => $amount * 100,]);
    }
}
