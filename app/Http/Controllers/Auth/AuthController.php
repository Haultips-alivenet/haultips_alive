<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\UserDetail;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Session;


class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;
    
//    protected $redirectPath = '/login';
    protected $loginPath = 'admin/login';
    protected $redirectAfterLogout = 'admin/login';
    
    public function authenticated($request , $user){
        $user_detail = UserDetail::select('image')->where('user_id', $user->id)->first();
        if($user_detail) {
        $request->session()->put('userimage', $user_detail->image);
        } else {
            $request->session()->put('userimage', "");
        }
        $getoffer=$request->session()->get('check_getofferpage');
        $finddelivery=$request->session()->get('check_findDelivery');
        if($getoffer!="") {
            if($user->user_type_id=='1') {
            return redirect(url('user/getoffer'));  
            } else {
               Session::flash('success', 'You are Registered as a partner,so You can not Post Shipment!'); 
               return redirect(url('user/my-offers'));  
            }
        } else if($finddelivery!=""){
            return redirect(url('user/find/deliveries/details/'.$finddelivery)); 
        } else {    
            if($user->user_type_id=='1'){            
                return redirect('admin/dashboard') ;
            }elseif($user->user_type_id=='2'){
                return redirect(url('user/my-offers'));
            }elseif($user->user_type_id=='3'){
                //return redirect()->route('user/dashboard') ;
                 return redirect(url('user/my-deliveries/all-status'));
            }
        } 
    }
    
    
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
