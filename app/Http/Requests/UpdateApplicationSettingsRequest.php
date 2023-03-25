<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateApplicationSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('change settings');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'payments_start' => ['sometimes', Rule::in(config('allowed-settings.payments_start'))],
            'organization_title' => ['sometimes', 'string', 'max:100'],
            'inn' => ['sometimes', 'required', 'digits:10,13'],
            'kpp' => ['sometimes','required_with:organization_title', 'digits:9'],
            'ogrn' => ['sometimes','required_with:organization_title', 'digits_between:12,15'],
            'director' => ['sometimes','required_with:organization_title', 'string', 'max:100'],
            'director_genitive' => ['sometimes','required_with:organization_title', 'string', 'max:100'],
            'accountant' => ['sometimes','required_with:organization_title', 'string', 'max:100'],
            'legal_address' => ['sometimes','required_with:organization_title', 'string', 'max:255'],
            'actual_address' => ['sometimes','required_with:organization_title', 'string', 'max:255'],
            'payment_account' => ['sometimes','required_with:organization_title', 'digits:20'],
            'correspondent_account' => ['sometimes','required_with:organization_title', 'digits:20'],
            'bik' => ['sometimes','required_with:organization_title', 'digits:9'],
            'bank' => ['sometimes','required_with:organization_title', 'string', 'max:255'],
            'phone' => ['sometimes','required_with:organization_title', 'string', 'max:100'],
            'email' => ['sometimes','required_with:organization_title', 'string', 'max:100', 'email'],
        ];
    }
}
