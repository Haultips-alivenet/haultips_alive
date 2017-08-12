<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Session;
use App\User;
use DB;

class AndroidController extends AppController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       
    }
    
    public function login(Request $request){
        $credentials = $request->only('email','password');
        try{
            if(! $token = JWTAuth::attempt($credentials)){
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "User credentials are not valid.";
                return $msg;
            }
        } catch(JWTException $ex){
            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "Something went worng.";
            return $msg;
        }
        $msg['responseCode'] = "200";
        $msg['productImages'] = compact('token');
        return $msg;
    }


}
