<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Mail;
use App\library\Smsapi;
use Session;
use App\User;
use DB;

class RegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = User::orderBy('id', 'desc');
        if($request->name!=''){
            $user->where(DB::raw('CONCAT_WS(" ", users.first_name, users.last_name)'), 'like', "%$request->name%");
        }
        if($request->email!=''){
            $user->where('users.email', 'like', "%$request->email%");
        }
        if($request->mobile!=''){
            $user->where('users.mobile_number', 'like', "%$request->mobile%");
        }
        if($request->status!=''){
            $user->where('users.status', $request->status);
        }
        if ($request->ToDate != "" && $request->FromDate != "") {
            $user->where(DB::raw('DATE_FORMAT(users.created_at,"%Y-%m-%d")'), '>=', $request->FromDate)->where(DB::raw('DATE_FORMAT(users.created_at,"%Y-%m-%d")'), '<=', $request->ToDate);
        }
        if ($request->ToDate != '' && $request->FromDate == '') {
            $user->where(DB::raw('DATE_FORMAT(users.created_at,"%Y-%m-%d")'), '<=', $request->ToDate);
        }
        if ($request->ToDate == '' && $request->FromDate != '') {
            $user->where(DB::raw('DATE_FORMAT(users.created_at,"%Y-%m-%d")'), '>=', $request->FromDate);
        }
        
        $user = $user->where('user_type_id',3)->where('is_deleted',0)->paginate(10);
        $page = $user->toArray();
        return view('admin.user.index')->with([
                    'users' => $user,
                    'page' => $page]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {     
        date_default_timezone_set("Asia/Kolkata");
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
            return redirect(url('admin/userList'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('admin/user/view')->with([
                    'user' => $user,
                ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       $user = User::find($id);
       return view('admin/user/edit')->with([                    
                    'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'firstName' => 'required|min:3|max:255|Regex:/^[a-z-.]+( [a-z-.]+)*$/i',
            'lastName' => 'required|min:3|max:255|Regex:/^[a-z-.]+( [a-z-.]+)*$/i',
            'email' => 'required|email|max:100|unique:users,email,'.$id,
            'mobile' => 'required|min:10|max:10|Regex: /^[0-9]{1,45}$/'
         ]);
        $firstName = $request->firstName;
        $lastName = $request->lastName;
        $email = $request->email;
        $mobile = $request->mobile;
            
        $user = User::find($id); 
        $user->first_name = $firstName;
        $user->last_name = $lastName;
        $user->email = $email;
        $user->mobile_number = $mobile;
        $userSucess = $user->save(); 
        
        if($userSucess == 1){
            Session::flash('success', 'User Updated successfully');            
        }else{
           Session::flash('success', 'Error occur ! Please try again.');
        } 
        return redirect(url('admin/userList'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $query = User::where('id', $id)->update(['is_deleted'=>1]);
        if($query == 1){
            Session::flash('success', 'User deleted successfully');            
        }else{
           Session::flash('success', 'Error occur ! Please try again.');
        } 
        return redirect(url('admin/userList'));
    }
    
    public function mobileCheck(Request $request){
        $mobile = $request->checkMobile;
        $query = User::where('mobile_number', $mobile)->select('id')->first();
        if($query){
             echo "true";
        } else {
            echo "false";
        }
       
    }
    public function user_active_Inactive($ids){
        $id=explode("_",$ids);
        if($id[1]==1) {
            $status='0';
        } else {
            $status='1';
        }
        $user = User::find($id[0]); 
        $user->status = $status;
        $Sucess = $user->save();  
        if($Sucess == 1){
            Session::flash('success', 'User Status Updated successfully');            
        }else{
           Session::flash('success', 'Error occur ! Please try again.');
        } 
        return redirect(url('admin/userList'));
    }
            
}
