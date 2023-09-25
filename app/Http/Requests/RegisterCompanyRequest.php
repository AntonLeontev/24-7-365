<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'org_form' => ['required', 'in:ltd,fe'],
            'inn' => ['nullable', 'numeric', 'digits:12'],
            'snils' => ['nullable', 'numeric', 'digits:11'],
            'last_name' => ['nullable', 'string', 'max:30'],
            'first_name' => ['nullable', 'string', 'max:30'],
            'second_name' => ['nullable', 'string', 'max:30'],
            'birthday' => ['nullable', 'date', 'date_format:Y-m-d'],
            'telephone' => ['required', 'numeric', 'digits:11'],
            'typeDoc' => ['nullable', 'in:21,22,10,14,12'],
            'serial' => ['nullable', 'numeric'],
            'number' => ['nullable'],
            'dateStart' => ['nullable', 'date', 'date_format:Y-m-d'],
            'dateEnd' => ['date', 'date_format:Y-m-d', 'nullable'],
            'issuedBy' => ['nullable'],
        ];
    }

    public function attributes(): array
    {
        return [
            'telephone' => 'Номер телефона',
            'inn' => 'ИНН',
            'snils' => 'СНИЛС',
            'last_name' => 'Фамилия',
            'first_name' => 'Имя',
            'second_name' => 'Отчество',
            'birthday' => 'Дата рождения',
            'typeDoc' => 'Тип документа',
            'serial' => 'Серия документа',
            'number' => 'Номер документа',
            'dateStart' => 'Дата выдачи',
            'dateEnd' => 'Дата окончания действия',
            'issuedBy' => 'Кем выдан',
        ];
    }

    protected function prepareForValidation(): void
    {
        $snils = str($this->snils)->replaceMatches('~\D+~', '')->value();
        $telephone = str($this->telephone)->replaceMatches('~\D+~', '')->value();
        $telephone[0] = '7';
        $this->merge([
            'snils' => $snils,
            'telephone' => $telephone,
        ]);

        if (is_null($this->number)) {
            $this->merge([
                'typeDoc' => null,
            ]);
        }
    }
}
