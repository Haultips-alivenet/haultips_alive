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
use App\UserVehicleDetails;
use App\UserVerification;
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
       if(Auth::check()) return redirect(session()->get('home_page_link'));
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
            $string = '123456789';
            $string_shuffled = str_shuffle($string);
            $otp = substr($string_shuffled, 1, 5);
            $otpMsg = 'Your Otp is '.$otp;
            
            $userverify= new UserVerification;
            $userverify->user_id = $insertedId;
            $userverify->otp = $otp;
            $userverify->email = $email;
            $userSucess1 = $userverify->save();
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
            return redirect(url('user/verifyotp/'.urlencode(base64_encode($insertedId))));
    }
    public function partner_registration(Request $request){
      // print_r($_POST);die;
        $this->validate($request, [
               'firstName' => 'required|min:3|max:255|Regex:/^[a-z-.]+( [a-z-.]+)*$/i',
               'lastName' => 'required|min:3|max:255|Regex:/^[a-z-.]+( [a-z-.]+)*$/i',
               'email' => 'required|email|unique:users|max:100',
               'mobile' => 'required|min:10|max:10|Regex: /^[0-9]{1,45}$/',
               'password' => 'required|min:6|max:12',
               'cpassword' => 'required|min:6|same:password',
               'state' => 'required|max:255',
               'city' => 'required|max:255',
               'total_vehicle' => 'required',
               'attached_vehicle' => 'required',
               //'carrer_type2' => 'required'
            ]);
         
            $firstName = $request->firstName;
            $lastName = $request->lastName;
            $email = $request->email;
            $mobile = $request->mobile;
            $password = bcrypt($request->password);
            //$password = md5($request->password);
            $userType = $request->userType;
            $carrer_type1 = $request->carrer_type1;
            $carrer_type2 = $request->carrer_type2;
            $state = $request->state;
            $city = $request->city;
            $total_vehicle = $request->total_vehicle;
            $attached_vehicle = $request->attached_vehicle;
            if($carrer_type1 && $carrer_type2){
                $carrer_type='3';
            } else if($carrer_type1){
                 $carrer_type='1';
            } else if($carrer_type2){
                $carrer_type='2';
            }
              try{
                    DB::beginTransaction();
            $user= new User;
            $user->user_type_id = $userType;
            $user->first_name = $firstName;
            $user->last_name = $lastName;
            $user->email = $email;
            $user->password = $password;
            $user->mobile_number = $mobile;
            $user->country_code = '+91';
            $user->carrier_type_id = $carrer_type;
            $user->state = $state;
            $user->city = $city;
            $user->total_vehicle = $total_vehicle;
            $user->attached_vehicle = $attached_vehicle;
            
            $userSucess = $user->save();  
            $insertedId = $user->id;

           
            //save with transporter
             $trucktype = $request->trucktype;
            
            if($carrer_type2==2) {
             for($i=0;$i<count($trucktype);$i++){
                
                    $a='trucklength_'.$trucktype[$i];
                     $trucklength = $request->$a;
                      for($j=0;$j<count($trucklength);$j++){  
                          $b='truckcapacity_'.$trucklength[$j];
                           $truckcapacity = $request->$b;
                            for($k=0;$k<count($truckcapacity);$k++){  
                               
                                $data[] = array(
                                    'user_id' => $insertedId, 
                                    'vehicle_category_id' => '1',
                                    'vehicle_subcategory_id' => $trucktype[$i],
                                    'vehicle_length_id' => $trucklength[$j],
                                    'vehicle_capacity_id' => $truckcapacity[$k],
                                   
                                  );
                            }
                      }
                      
                    }
                  //  print_r($data);die;
                  $u= UserVehicleDetails::insert($data);
            }
                    DB::commit();
                    $success = true;
            }
            catch(\Exception $e){

                $success = false;
                DB::rollback();
            }
            
            $string = '123456789';
            $string_shuffled = str_shuffle($string);
            $otp = substr($string_shuffled, 1, 5);
            $otpMsg = 'Your Otp is '.$otp;
            
            $userverify= new UserVerification;
            $userverify->user_id = $insertedId;
            $userverify->otp = $otp;
            $userverify->email = $email;
            
            $userSucess1 = $userverify->save();
            
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
           
            
            return redirect(url('user/verifyotp/'.urlencode(base64_encode($insertedId))));
    }
    
    public function verifyotp($id){
        $data["userid"]=base64_decode(urldecode($id));
         return view('user.login.veryfyotp',$data);
    }
    
    public function resendotp($id){
        
        $string = '123456789';
        $string_shuffled = str_shuffle($string);
        $otp = substr($string_shuffled, 1, 5);
        $otpMsg = 'Your Otp is '.$otp;
        $user = User::find($id); 
        $mobile= $user->mobile_number;
        
        DB::table('user_verifications')->where('user_id', $id)->update(['otp' => $otp]);
        
        $smsObj = new Smsapi();
        $smsObj->sendsms_api('+91'.$mobile,$otpMsg); 
        Session::flash('success', 'Otp Send successfully On '.$mobile);  
        return redirect(url('user/verifyotp/'.urlencode(base64_encode($id))));
    }
    
    public function checkotp(Request $request){
       
         $data =   DB::table('user_verifications')
                        ->where('user_id',$request->userid)
                        ->where('otp',$request->userotp)
                        ->select('id')
                        ->first();
         
         if($data) {
        $user = User::find($request->userid); 
        $user->status=1;
        $userSucess = $user->save(); 
        Session::flash('success', 'User Verify successfully'); 
        return redirect(url('user/login'));
        } else {
             Session::flash('success', 'Error occur ! Wrong Otp!'); 
              return redirect(url('user/verifyotp/'.urlencode(base64_encode($request->userid))));
         }
       
        
    }
}