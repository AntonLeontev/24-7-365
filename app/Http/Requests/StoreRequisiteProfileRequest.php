<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StoreRequisiteProfileRequest extends FormRequest
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
            'payment_account' => ['required', 'string', 'size:20'],
            'correspondent_account' => ['required', 'string', 'size:20'],
            'bik' => ['required', 'string', 'size:9'],
            'bank' => ['required', 'string',  'max:250'],
        ];
    }



    public function attributes()
    {
        return [
            'payment_account' => 'Расчетный счёт',
            'correspondent_account' => 'Корреспондентский счёт',
            'bik' => 'БИК',
            'bank' => 'Банк',
        ];
    }







}
