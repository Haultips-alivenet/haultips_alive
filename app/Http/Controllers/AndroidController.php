<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Session;
use App\library\Smsapi;
use App\User;
use App\UserVerification;
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

     public function sendOtp()
    {
        $mobileNumber = $_POST['mobileNumber'];     
        $msg = array();
        $otpMsg = '';
        if($mobileNumber != "" || !empty($mobileNumber)){
           
            $string = '1234567890';
            $string_shuffled = str_shuffle($string);
            $otp = substr($string_shuffled, 1, 5);
            
            $mobileData=User::where('mobile_number',$mobileNumber)->select('id')->get();
            $mobileExists = $mobileData->toArray();
            
            if(empty($mobileExists)){
                $user = new User;
                $user->mobile_number = $mobileNumber;
                $user->save();
                $userId= $user->id;
            }else{
                $userId = $mobileExists[0]['id'];
            }
            
            $userVerification =new UserVerification;
            $userVerification->user_id = $userId;
            $userVerification->otp = $otp;
            $userVerification->save();
                        
            $otpMsg = 'Your Otp is '.$otp;
            
            $smsObj = new Smsapi();
            $smsObj->sendsms_api('+91'.$mobileNumber,$otpMsg);
        
            
            $msg['responseCode'] = "200";
            $msg['responseMessage'] = "Otp is fetched successfully";
            $msg['userId'] = $userId;
            $msg['otp'] = $otp;            
            
        }else{
            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "Failed.Please enter mobile number with country code";
        }
         return $msg;
    
    }
    
     public function otpVerification(){
        $otp = $_POST['otp'];
        $userId = $_POST['userId'];
        $msg = array();
        
        $required = array('userId', 'otp');
        
        $error = false;
        foreach($required as $field) {
          if (empty($_POST[$field])) {
            $error = true;
            $fieldName = $field;
            break;
          }
        }

        if($error) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "$fieldName required.";
        }else {       
              $userData = UserVerification::where('user_id',$userId)->where('otp',$otp)->select('id')->first();
              if($userData){
                    $verificationId = $userData->id;
                    UserVerification::where('id',$verificationId)->delete();
                    User::where('id',$userId)->update(['otp_verified'=>1 , 'status'=>1]);
                    
                    $msg['responseCode'] = "200";
                    $msg['responseMessage'] = "Mobile number successfully verified.";
                    $msg['userId'] = $userId;
              }else{
                  $msg['responseCode'] = "0";
                 $msg['responseMessage'] = "Failed.Invalid OTP";
              }
        }        
        return $msg;
    }
    
    public function userRegistration(){
        $msg = array();
        $userId = $_POST['userId'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $cpassword = $_POST['cpassword'];
        $required = array('userId','firstName','lastName','email','password','cpassword');
        
        $error = false;
        foreach($required as $field) {
          if (empty($_POST[$field])) {
            $error = true;
            $fieldName = $field;
            break;
          }
        }

        if($error) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "$fieldName required.";
        }else {       
              if($password === $cpassword){
                 $userEmail = User::where('email',$email)->first();
                 if($userEmail){
                    $msg['responseCode'] = "0";
                    $msg['responseMessage'] = "Failed. Email already exist";
                    }else{
                        User::where('id',$userId)
                            ->update([
                                'user_type_id'=>3,
                                'first_name'=>$firstName,
                                'last_name'=>$lastName,
                                'email'=>$email,
                                'password'=>bcrypt($password)
                                ]);
                        
                        $msg['responseCode'] = "200";
                        $msg['responseMessage'] = "Registration is done successfully";
                        $msg['userId'] = $userId;
                        $msg['firstName'] = $firstName;
                        $msg['lastName'] = $lastName;
                        $msg['email'] = $email;
                    } 
              }else{
                  $msg['responseCode'] = "0";
                  $msg['responseMessage'] = "Password and Confirm Password Should be same.";
              }
        }        
        return $msg;
    }
}
