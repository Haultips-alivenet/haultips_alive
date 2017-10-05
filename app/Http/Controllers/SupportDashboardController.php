<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
use App\ShippingDetail;
use Session;
use DB;

class SupportDashboardController extends Controller
{
   
    public function index()
    {
       
        return view('support.dashboard');
    }
    public function inbox()
    {
       
        return view('support.inbox');
    }
    
}
