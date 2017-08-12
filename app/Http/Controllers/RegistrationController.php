<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
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
        
        $user = $user->where('user_type_id',3)->paginate(10);
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
            
            if($userSucess == 1){
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
        $query = User::where('id', $id)->delete();
        if($query == 1){
            Session::flash('success', 'User deleted successfully');            
        }else{
           Session::flash('success', 'Error occur ! Please try again.');
        } 
        return redirect(url('admin/userList'));
    }
}
