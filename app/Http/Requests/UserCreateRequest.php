<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('create users');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => ['required', 'unique:users', 'email:rfc,dns'],
            'first_name' => ['required', 'string', 'min:1', 'max:50'],
            'phone' => ['nullable', 'string', 'max:50'],
            'roles' => ['required', 'array', 'size:1'],
            'roles.*' => ['exists:roles,name', 'not_in:Superuser,superuser'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }

    public function attributes()
    {
        return [
            'first_name' => 'Имя',
            'phone' => 'Телефон',
            'roles' => 'Роль',
            'roles.*' => 'Роль',
            'password' => 'Пароль',
        ];
    }

    protected function passedValidation()
    {
        $this->merge(['password' => bcrypt($this->password)]);
    }
}
