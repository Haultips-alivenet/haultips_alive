<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Response;

use Auth;
use Session;


abstract class FrontController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function __construct(){      
        //$this->middleware('auth');
        
        if(Auth::User())
        {
            $currentUser= Auth::User()->toArray();
            Session::put('currentUser',$currentUser);
        }
    }
}
