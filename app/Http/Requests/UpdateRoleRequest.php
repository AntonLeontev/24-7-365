<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('assign roles');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'roles' => ['required', 'array', 'size:1'],
            'roles.*' => ['exists:roles,name', 'not_in:Superuser,superuser'],
        ];
    }

    public function attributes()
    {
        return [
            'roles' => 'Роль',
            'roles.*' => 'Роль',
        ];
    }
}
