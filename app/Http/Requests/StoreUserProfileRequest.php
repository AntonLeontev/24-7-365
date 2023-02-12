<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;



class StoreUserProfileRequest extends FormRequest
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
    public function rules(Request $request)
    {
        $user = Auth::user();
        
   
        return [
            'email' => [
                'required',
                Rule::unique('users')->ignore($user->id)
            ],
            'phone' => [
                Rule::unique('users')->ignore($user->id)
            ],
        ];
   
           
      
    }
}
