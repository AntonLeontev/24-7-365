<?php

namespace Tests\Feature;
use Illuminate\Http\Request;
use App\Http\Controllers\SmscodeController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SmscodeTest extends TestCase
{
    
    
    public static $user;
    public static $code;
    
    protected function getUser(){
        if(empty(self::$user)){
            self::$user = User::factory()->create();
        }
        return self::$user;
    }
    
    
    public function testCodeGeneration(): void
    {
        
 
        $this->actingAs($this->getUser());
        
        $request = new Request(['operation_type' => 1]);
        
           $Smscode = new SmscodeController();
         
           $code = $Smscode->createCode($request);
           
           $this->assertMatchesRegularExpression('/^\d{5}$/', $code);
           
    }
    

}
