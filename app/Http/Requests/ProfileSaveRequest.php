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
        return [                                  // 'Придумайте новый пароль'
            'first_name.max'                      => 'Максимум :max символов',
            'phone.size'                          => 'Должно быть :size цифр',
            'phone.unique'                        => 'Этот номер уже занят',
            'email.required'                      => 'E-mail обязателен',
            'email.email'                         => 'Должен быть e-mail',
            'email.unique'                        => 'Этот e-mail уже занят',
            'inn.numeric'                         => 'Допустимы только цифры',
            'inn.digits_between'                  => 'Можно от :min до :max цифр',
            'inn.unique'                          => 'Такой ИНН уже занят',
            'kpp.numeric'                         => 'Допустимы только цифры',
            'kpp.digits'                          => 'Должно быть :digits цифр',
            'title.max'                           => 'Максимум :max символов',
            'title.required_with'                 => 'Обязательно с ИНН',
            'bik.numeric'                         => 'Допустимы только цифры',
            'bik.digits'                          => 'Должно быть :digits цифр',
            'bik.required_with'                   => 'Обязательно с ИНН',
            'bank.max'                            => 'Максимум :max символов',
            'correspondent_account.required_with' => 'Обязательно с ИНН',
            'correspondent_account.numeric'       => 'Допустимы только цифры',
            'correspondent_account.digits'        => 'Должно быть :digits цифр',
            'payment_account.required_with'       => 'Обязательно с ИНН',
            'payment_account.numeric'             => 'Допустимы только цифры',
            'payment_account.digits'              => 'Должно быть :digits цифр',
            'password.confirmed'                  => 'Не совпадает с повтором',
            'password.min'                        => 'Минимум :min символов',
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
