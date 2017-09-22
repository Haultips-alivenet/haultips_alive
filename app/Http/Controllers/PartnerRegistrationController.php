<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Mail;
use Session;
use App\User;
use App\UserVerification;
use App\CarrierTypes;
use App\VehicleCategorie;
use App\UserVehicleDetails;
use App\PartnerKyc;
use App\library\Smsapi;
use DB;

class PartnerRegistrationController extends Controller
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
        
          
        $user = $user->where('user_type_id',2)->where('is_deleted',0)->paginate(10);
        $page = $user->toArray();
        return view('admin.partner.index')->with([
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
        $data["categoryname"] =   DB::table('vehicle_categories')
                            ->where('parent_id',1)
                            ->select('category_name','id')
                            ->get();
       $data["carrier_types"] =   DB::table('carrier_types')->select('*')->get();
       return view('admin.partner.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   //print_r($_POST);die;
        date_default_timezone_set("Asia/Kolkata");
        $this->validate($request, [
               'firstName' => 'required|min:3|max:255|Regex:/^[a-z-.]+( [a-z-.]+)*$/i',
               'lastName' => 'required|min:3|max:255|Regex:/^[a-z-.]+( [a-z-.]+)*$/i',
               'email' => 'required|email|unique:users|max:100',
               'mobile' => 'required|min:10|max:10|Regex: /^[0-9]{1,45}$/',
               'password' => 'required|min:6|max:12',
               'cpassword' => 'required|min:6|same:password',
               'state' => 'required|min:2|max:255|Regex:/^[a-z-.]+( [a-z-.]+)*$/i',
               'city' => 'required|min:2|max:255|Regex:/^[a-z-.]+( [a-z-.]+)*$/i',
               'total_vehicle' => 'required',
               'attached_vehicle' => 'required',
               //'carrer_type2' => 'required'
            ]);
         
            $firstName = $request->firstName;
            $lastName = $request->lastName;
            $email = $request->email;
            $mobile = $request->mobile;
            $password = bcrypt($request->password);
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
            
            $string = '1234567890';
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
            return redirect(url('admin/partnerList'));
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
        return view('admin/partner/view')->with([
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
        $data["user"] = User::find($id);
        $data["UserVehicleDetails"] =DB::table('user_vehicle_details')->select('*')
                                    ->where('user_id',$id)
                                    ->get();
        $data["carrier_types"] =   DB::table('carrier_types')->select('*')->get();
        $data["categoryname"] =   DB::table('vehicle_categories')
                            ->where('parent_id',1)
                            ->select('category_name','id')
                            ->get();
       return view('admin/partner/edit',$data);
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
        //print_r($_POST);die;
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
        $carrer_type1 = $request->carrer_type1;
        $state = $request->state;
        $city = $request->city;
        $total_vehicle = $request->total_vehicle;
        $attached_vehicle = $request->attached_vehicle;
        $carrer_type2 = $request->carrer_type2;
            if($carrer_type1 && $carrer_type2){
                $carrer_type='3';
            } else if($carrer_type1){
                 $carrer_type='1';
            } else if($carrer_type2){
                $carrer_type='2';
            }    
        $user = User::find($id); 
        $user->first_name = $firstName;
        $user->last_name = $lastName;
        $user->email = $email;
        $user->mobile_number = $mobile;
        $user->carrier_type_id=$carrer_type;
        $user->state = $state;
        $user->city = $city;
        $user->total_vehicle = $total_vehicle;
        $user->attached_vehicle = $attached_vehicle;
        $userSucess = $user->save(); 
        
        
        //save with transporter
        $data=array();
        $d=1;
            $trucktype = $request->trucktype;
            if(count($trucktype)>0) {
            
            if($carrer_type2==2) {
             for($i=0;$i<count($trucktype);$i++){
                
                    $a='trucklength_'.$trucktype[$i];
                     $trucklength = $request->$a;
                      for($j=0;$j<count($trucklength);$j++){  
                          $b='truckcapacity_'.$trucklength[$j];
                           $truckcapacity = $request->$b;
                            for($k=0;$k<count($truckcapacity);$k++){  
                               if($d==1){
                                   $query = DB::table('user_vehicle_details')->where('user_id', $id)->delete(); 
                                }
                                $data[] = array(
                                    'user_id' => $id, 
                                    'vehicle_category_id' => '1',
                                    'vehicle_subcategory_id' => $trucktype[$i],
                                    'vehicle_length_id' => $trucklength[$j],
                                    'vehicle_capacity_id' => $truckcapacity[$k],
                                   
                                  );
                                $d=0;
                            }
                      }
                      
                    }
                  //  print_r($data);die;
                    if($data) {
                  $u= UserVehicleDetails::insert($data);
                    }
            }
            }
        
        
        if($userSucess == 1){
            Session::flash('success', 'User Updated successfully');            
        }else{
           Session::flash('success', 'Error occur ! Please try again.');
        } 
        return redirect(url('admin/partnerList'));
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
        $query1 = DB::table('user_vehicle_details')->where('user_id', $id)->delete(); 
        if($query == 1){
            Session::flash('success', 'Partner deleted successfully');            
        }else{
           Session::flash('success', 'Error occur ! Please try again.');
        } 
        return redirect(url('admin/partnerList'));
    }
    public function gettransporterData(Request $request){
      
        $data =DB::table('user_vehicle_details as uv')
                    ->join('vehicle_categories as vc','uv.vehicle_subcategory_id','=','vc.id')
                    ->join('truck_lengths as l','uv.vehicle_length_id','=','l.id')
                    ->join('truck_capacities as c','uv.vehicle_capacity_id','=','c.id')
                   ->where('user_id',$request->id)
                   ->select('uv.id','vc.category_name','l.truck_length','c.truck_capacity')
                   ->get();
            $a='';
            $a='<table class="table table-striped">';
            $a.='<thead>';
            $a.='<tr class="warning">';
            $a.='<th>#</th>';
            $a.='<th>Truck Tupe</th>';
            $a.='<th>Truck Length</th>';
            $a.='<th>Truck Capacity</th>';
            $a.='</tr>';
            $a.='</thead>';
            $a.='<tbody>';
            $i=1;
            foreach($data as $value) {
            $a.='<tr>';
            $a.='<td>'.$i++.'</td>';
            $a.='<td>'.$value->category_name.'</td>';
            $a.='<td>'.$value->truck_length.'</td>';
            $a.='<td>'.$value->truck_capacity.'</td>';
            $a.='</tr>'; 
            
            }
            $a.='</tbody>';
            $a.='</table>';
            echo $a;
        //print_r($data);
    }
    public function approve($id){
        $data["user_id"]=$id;
        $data["kycdata"] = DB::table('partner_kycs')
                            ->join('users','partner_kycs.user_id','=','users.id')    
                            ->where('user_id', $id)
                            ->select('partner_kycs.*','users.documents_status')
                            ->first();
        return view('admin/partner/kyc',$data);
    }
    public function DocumentsUpload(Request $request){
        
       $destinationPath=public_path()."/admin/kyc/"; 
       $user_id=$request->user_id;
       $rc_photo = $request->file('rc_photo');
       $pancard = $request->file('pancard');
       $businesscard = $request->file('businesscard');
       $kycdata = DB::table('partner_kycs')->where('user_id', $user_id)->first();
      
      if($rc_photo) {
            
            $filename=$rc_photo->getClientOriginalName();
            $t=time();
            $rc_photofilename="RC_".$user_id.'_'.$t.'_'.$filename; 
            $request->file('rc_photo')->move($destinationPath,$rc_photofilename);
        } else if(@$kycdata->rc_photo){
            $rc_photofilename=$kycdata->rc_photo;
        } else {
            $rc_photofilename='';
        }
        if($pancard) {
            
            $filename=$pancard->getClientOriginalName();
            $t=time();
            $pancard_filename="Pan_".$user_id.'_'.$t.'_'.$filename; 
            $request->file('pancard')->move($destinationPath,$pancard_filename);
        } else if(@$kycdata->pancart){
            $pancard_filename=$kycdata->rc_photo;
        }else {
            $pancard_filename='';
        }
        if($businesscard) {
            
            $filename=$businesscard->getClientOriginalName();
            $t=time();
            $businesscard_filename="Business_".$user_id.'_'.$t.'_'.$filename; 
            $request->file('businesscard')->move($destinationPath,$businesscard_filename);
        } else if(@$kycdata->business_card){
            $businesscard_filename=$kycdata->business_card;
        } else {
            $businesscard_filename='';
        }
        
        $kyc1 = PartnerKyc::find(@$kycdata->id); 
        if($kyc1){
            
            $kyc1->user_id = $user_id;
            $kyc1->rc_photo = $rc_photofilename;
            $kyc1->pancart = $pancard_filename;
            $kyc1->business_card = $businesscard_filename;
            $userSucess = $kyc1->save(); 
        } else {
            $kyc = new PartnerKyc; 
            $kyc->user_id = $user_id;
            $kyc->rc_photo = $rc_photofilename;
            $kyc->pancart = $pancard_filename;
            $kyc->business_card = $businesscard_filename;
            $userSucess = $kyc->save(); 
        }
        $status=$request->status;
        //if($status==1){
        $user = User::find($user_id); 
        $user->documents_status = $status;
        $statusupdate = $user->save(); 
       // }
         if($userSucess == 1){
            Session::flash('success', 'Documents Uploaded successfully!');            
        }else{
           Session::flash('success', 'Error occur ! Please try again.');
        }
        return redirect(url('admin/partner/'.$user_id.'/approve'));
    }
    
}
