<?php

namespace App\Http\Requests;

use App\Models\Tariff;
use Illuminate\Foundation\Http\FormRequest;

class StoreContractRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('see own profile');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $tariff = Tariff::select(['min_amount', 'max_amount'])->find($this->tariff_id);
        $minAmount = $tariff->min_amount->amount();
        $maxAmount = $tariff->max_amount->raw() === 0 ?
            PHP_INT_MAX :
            $tariff->max_amount->amount();
            
        return [
            'tariff_id' => ['required', 'exists:tariffs,id'],
            'amount' => [
                'required',
                'integer',
                "min:$minAmount",
                "max:$maxAmount",
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'amount' => 'Сумма закупа'
        ];
    }

    protected function passedValidation(): void
    {
		$this->merge([
			'amount' => (int) $this->amount * 100,
			'user_id' => auth()->id(),
			'organization_id' => auth()->user()->organization->id,
		]);
    }
}
