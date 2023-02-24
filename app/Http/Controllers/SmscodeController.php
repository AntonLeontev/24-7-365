<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SmscodeRequest;
use App\Models\Smscode;
use Illuminate\Support\Facades\Auth;

class SmscodeController extends Controller
{
    //
    
    
   
                              
    public function phoneConfirmation(SmscodeRequest $request){
        
    return Smscode::where('code',$request->code)
    ->where('user_id',Auth::user()->id)
    ->where('operation_type',Smscode::PHONE_CONFIRMATION)
    ->where('status',Smscode::STATUS_PENDING)
    ->exists();
      
    }

    public function createCode(Request $request){

     $code = Smscode::create([
         'code'=> $this->generateCode(),
         'operation_type'=>$request->operation_type,
         'user_id'=>Auth::user()->id,
         'phone'=>Auth::user()->phone,
         
     ]);
     
    // отправка запроса до апи с кодом  
     
    return view('users.code_test', compact( 'code'));
    }
    
    
    protected function generateCode(){
       
   
        return substr(str_shuffle("123456789"), 0, Smscode::CODE_LENGTH);
      
         
    }
    
    
    protected function  eraseExpiredCodes(){

       
    }
    
    
}
