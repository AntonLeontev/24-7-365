<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePhoneRequest extends FormRequest
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
        return [
            'phone' => ['required', 'string', Rule::unique('users')->ignore(auth()->id()), 'size:11'],
        ];
    }

	public function messages()
	{
		return [
			'phone.unique' => 'Этот номер телефона пренадлежит другому пользователю'
		];
	}
}
