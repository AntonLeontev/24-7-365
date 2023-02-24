<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Smscode;

class SmscodeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(Request $request)
    {
      
        if($request->user == Auth::user()->id){
            return true;
        }
        else{
            return false;
        }
    }
    

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'code'=> ['required', 'numeric', 'max:'.Smscode::CODE_LENGTH],
            'operation_type'=> ['required', 'numeric', 'max:2'],
        ];
    }
}
