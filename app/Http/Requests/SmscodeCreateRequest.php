<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SmscodeCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'phone' => ['required', 'digits:11'],
        ];
    }

	public function messages(): array
	{
		return [
			'phone.digits' => 'Должно быть :digits цифр',
			'phone.required' => 'Обязательно',
		];
	}
}
