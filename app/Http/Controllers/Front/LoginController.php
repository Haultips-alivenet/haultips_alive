<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\FrontController;
use Auth;
use Session;
use App\VehicleCategory;
use App\library\Smsapi;
use Mail;
use App\User;
use App\TruckLengths;
use App\AdminBedroom;
use App\AdminBox;
use DB;

class LoginController extends FrontController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
      //  return view('welcome');
        return view('user.login.login');
       
    }
    public function signup()
    {
       
      //  return view('welcome');
        return view('user.login.signup');
       
    }
     public function customer()
    {
       
      //  return view('welcome');
        return view('user.login.customer');
       
    }
     public function partner()
    {
         $data["categoryname"] =   DB::table('vehicle_categories')
                            ->where('parent_id',1)
                            ->select('category_name','id')
                            ->get();
        $data["carrier_types"] =   DB::table('carrier_types')->select('*')->get();
        return view('user.login.partner',$data);
       
    }
    
    public function customer_registration(Request $request){
       
        $this->validate($request, [
               'firstName' => 'required|min:3|max:255|Regex:/^[a-z-.]+( [a-z-.]+)*$/i',
               'lastName' => 'required|min:3|max:255|Regex:/^[a-z-.]+( [a-z-.]+)*$/i',
               'email' => 'required|email|unique:users|max:100',
               'mobile' => 'required|min:10|max:10|Regex: /^[0-9]{1,45}$/',
               'password' => 'required|min:6|max:12',
               'cpassword' => 'required|min:6|same:password'
            ]);
         
            $firstName = $request->firstName;
            $lastName = $request->lastName;
            $email = $request->email;
            $mobile = $request->mobile;
            $password = bcrypt($request->password);
            $userType = $request->userType;

            $user= new User;
            $user->user_type_id = $userType;
            $user->first_name = $firstName;
            $user->last_name = $lastName;
            $user->email = $email;
            $user->password = $password;
            $user->mobile_number = $mobile;
            $user->country_code = '+91';
            $userSucess = $user->save();  
            $insertedId = $user->id;
            $string = '1234567890';
            $string_shuffled = str_shuffle($string);
            $otp = substr($string_shuffled, 1, 5);
            $otpMsg = 'Your Otp is '.$otp;
            
            if($userSucess == 1){
                  

                $smsObj = new Smsapi();
                $smsObj->sendsms_api('+91'.$mobile,$otpMsg);  
                 $user = User::findOrFail($insertedId);
                Mail::send('layouts.adminAppointment', ['user' => $user], function ($m) use ($user) {
                $m->from('richalive158@gmail.com', 'Your Application');
                $m->to($user->email, $user->name)->subject('Your Reminder!');
                });
                Session::flash('success', 'User created successfully');                
            }else{
               Session::flash('success', 'Error occur ! Please try again.');
            }            
            return redirect(url('user/login'));
    }
    public function partner_registration(Request $request){
        print_r($_POST);die;
    }
}