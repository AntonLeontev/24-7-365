<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrganizationProfileRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:100'],
            'type' => ['required', 'string', 'max:100'],
            'inn' => ['required', 'string', 'size:12'],
            'kpp' => ['required', 'string', 'size:12'],
            'ogrn'=> ['required', 'string', 'size:13'],
            'director' => ['required', 'string', 'max:100'],
            'director_post' => ['required', 'string', 'max:100'],
            'accountant' => ['required', 'string', 'max:100'],
            'legal_address' => ['required', 'string', 'max:255'],
            'actual_address' => ['required', 'string', 'max:255'],
        ];
    }
    
    
    
    
    
    public function attributes()
    {
        return [
            'title' =>'Название',
            'type' => 'Тип',
            'inn' => 'ИНН',
            'kpp' => 'КПП',
            'ogrn'=> 'ОГРН(ИП)',
            'director' => 'Директор',
            'director_post' => 'Пост Директора',
            'accountant' => 'Бухгалтер',
            'legal_address' => 'Юридический адрес',
            'actual_address' => 'Фактический адрес',
        ];
    }
    
    
    
    
    
    
}
