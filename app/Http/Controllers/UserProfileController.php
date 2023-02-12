<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreUserProfileRequest;
use App\Http\Requests\StoreRequisiteProfileRequest;
use App\Http\Requests\StoreOrganizationProfileRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Organization;
use App\Models\Account;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    
    
    
   
    public function __construct(){
        
        //dlya bistrouy avtorizacii,
        Auth::loginUsingId(6);
        
   
    }

    public function profile(){
      
        $user = auth()->user();
        $organization = User::with('organization')->find($user->id)->organization;
        $requisites = Organization::with('accounts')->find($organization->id)->accounts->where('status',1);
        
        //poka smotrim tolko odni rekviziti, realizacii s bolshim kolichestvom obsudim potom 
        if(count($requisites)){
            $requisites = $requisites[0];
        }
        
        return view('users.profile', ['user' => $user])->with('organization',$organization)->with('requisites',$requisites);
    }
    
    public function store_profile(StoreUserProfileRequest $request)
    {
        $user = Auth::user();
        
       
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->patronymic=$request->patronymic;
            $user->email= $request->email;
            $user->phone= $request->phone;
            $user->save();
             
        return response()->json(['success' => true, 'message'=>'Данные сохранены']);
    }
    
    
    public function store_profile_organization (StoreOrganizationProfileRequest $request)
    {
        $user = Auth::user();
        
        $organization = Organization::updateOrCreate([
            'title' => $request->title,
            'user_id'=>$user->id
        ], [
            'type' => $request->type,
            'inn' => $request->inn,
            'ogrn' => $request->ogrn,
            'kpp' => $request->kpp,
            'director' => $request->director,
            'director_post' => $request->director_post,
            'accountant'=>$request->accountant,
            'legal_address'=>$request->legal_address,
            'actual_address'=>$request->actual_address,
            
        ]);
        
        return response()->json(['success' => true, 'message'=>'Данные сохранены']);
        
    }
    
    public function store_profile_requisites(StoreRequisiteProfileRequest $request){
      
        
        $user = Auth::user();
        @$organization = User::with('organization')->find($user->id)->organization;
        
        
        if($organization==NULL){
            return response()->json(['error' => true, 'message'=>'Заполните данные организации вначале']);
        }
        
        $req_id = (isset($request->req_id)) ? $request->req_id : 0;
       
        
        $requisites = Account::updateOrCreate([
            
            'organization_id'=>$organization->id
        ], [
            'id'=>$req_id,
            'payment_account' => $request->payment_account,
            'correspondent_account' => $request->correspondent_account,
            'bik' => $request->bik,
            'bank' => $request->bank,
            'status' =>1,

            
        ]);
        
        return response()->json(['success' => true, 'message'=>'Данные сохранены']);
        
        
    }
    
    public function password_reset(Request $request){
        
        //TODO e-mail notification about operation;
        
        $user = Auth::user();
        
        
        if(!Hash::check($request->password,$user->password) ){
            return response()->json(['error' => true, 'message'=>'Неверный пароль']);
        }
        
        if($request->password1 != $request->password2 ){
            return response()->json(['error' => true, 'message'=>'Пароли несовпадают']);
        }
        
        $user->password = Hash::make($request->password1);
        $user->save();
        
        return response()->json(['success' => true, 'message'=>'Пароль обновлен']);
        
    }
    
    
    
}
