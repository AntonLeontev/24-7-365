<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrganizationValidateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'inn' => [
                'required',
                'numeric',
                'digits_between:10,12',
                Rule::unique('organizations')->ignore(auth()->user()->organization),
            ],
            'kpp' => ['numeric', 'nullable', 'digits:9'],
            'title' => ['required', 'string', 'max:100'],
            'ogrn' => ['numeric', 'digits_between:13,15'],
            'legal_address' => ['required', 'string', 'nullable', 'max:255'],
            'bik' => ['required', 'numeric', 'digits:9'],
            'bank' => ['required', 'string', 'max:100'],
            'correspondent_account' => ['required', 'numeric', 'digits:20'],
            'payment_account' => ['required', 'numeric', 'digits:20'],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Поле обязательно',
            'inn.numeric' => 'Допустимы только цифры',
            'inn.digits_between' => 'Можно от :min до :max цифр',
            'inn.unique' => 'Такой ИНН уже занят',
            'kpp.numeric' => 'Допустимы только цифры',
            'kpp.digits' => 'Должно быть :digits цифр',
            'title.max' => 'Максимум :max символов',
            'bik.numeric' => 'Допустимы только цифры',
            'bik.digits' => 'Должно быть :digits цифр',
            'bank.max' => 'Максимум :max символов',
            'correspondent_account.numeric' => 'Допустимы только цифры',
            'correspondent_account.digits' => 'Должно быть :digits цифр',
            'payment_account.numeric' => 'Допустимы только цифры',
            'payment_account.digits' => 'Должно быть :digits цифр',
        ];
    }

    public function attributes()
    {
        return [
            'inn' => 'ИНН',
            'kpp' => 'КПП',
            'title' => 'Наименование организации',
            'ogrn' => 'ОГРН',
            'bik' => 'БИК',
            'bank' => 'Банк',
            'correspondent_account' => 'Корреспондентский счет',
            'payment_account' => 'Расчетный счет',
        ];
    }
}
