<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Session;
use App\User;

class RegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::where('user_type_id',3)->paginate(10);
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
            $password = md5($request->password);
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
        return view('admin/users/view')->with([
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
