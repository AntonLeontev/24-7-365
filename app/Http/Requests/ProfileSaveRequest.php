<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileSaveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->route('user')->id === auth()->id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'first_name' => ['string', 'nullable', 'max:100'],
            'phone' => ['string', 'nullable', Rule::unique('users')->ignore(auth()->id()), 'size:11'],
            'email' => ['required', 'email', Rule::unique('users')->ignore(auth()->user()), 'max:100'],
            'inn' => [
                'numeric',
                'nullable',
                'digits_between:10,12',
                Rule::unique('organizations')->ignore(auth()->user()->organization),
            ],
            'kpp' => ['numeric', 'nullable', 'digits:9'],
            'title' => ['string', 'nullable', 'max:100', 'required_with:inn'],
            'ogrn' => ['numeric', 'nullable', 'digits_between:13,15'],
            'legal_address' => ['string', 'nullable', 'max:255'],
            'bik' => ['numeric', 'nullable', 'digits:9', 'required_with:inn'],
            'bank' => ['string', 'nullable', 'max:100', 'required_with:inn'],
            'correspondent_account' => ['numeric', 'nullable', 'digits:20', 'required_with:inn'],
            'payment_account' => ['numeric', 'nullable', 'digits:20', 'required_with:inn'],
            'password' => ['confirmed', Password::default(), 'nullable'],
        ];
    }

    public function messages()
    {
        return [
            'phone.size' => 'Номер телефона должен состоять из :size цифр'
        ];
    }

    public function attributes()
    {
        return [
            'phone' => 'Телефон',
            'inn' => 'ИНН',
            'kpp' => 'КПП',
            'title' => 'Наименование организации',
            'ogrn' => 'ОГРН',
            'bik' => 'БИК',
            'bank' => 'Банк',
            'correspondent_account' => 'Корреспондентский счет',
            'payment_account' => 'Расчетный счет',
            'password' => 'Пароль',
        ];
    }
}
