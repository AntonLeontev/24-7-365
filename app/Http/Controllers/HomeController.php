<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
       $fixit_please = 1;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        
                  
        
        
        
        
        
        
        $someWrongPlacedCode = 1 + 1 ;
                   
                   
                   
                   
    }



    public wrongPlacedMethod(){
        
        
        
        
        
        
        
        
        
        
        
        
        
                  }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
}
