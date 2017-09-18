<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\FrontController;
use Auth;
use Session;
use App\VehicleCategory;
use App\User;
use App\UserDetail;
use App\TruckLengths;
use App\AdminBedroom;
use App\AdminBox;
use App\TblQuesMaster;
use App\ShippingDetail;
use App\ShippingQuote;
use DB;


class UserHomeController extends FrontController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    $data["latest_post"]  = DB::table('shipping_details as sd')
                    ->leftJoin('shipping_pickup_details as sp','sd.id','=','sp.shipping_id')
                    ->orderBy('sd.created_at', 'desc')
                    ->limit(10)
                    ->select('sd.id','sd.table_name','sd.created_at','sp.pickup_address')
                    ->get();       
     $data["categories"] = VehicleCategory::where('status',1)->where('parent_id',0)->select('id','category_name','category_image')->get();
        return view('user.index',$data);
       
    }
    public function userdashboard()
    {
        return view('user.userdashboard');
    }
    public function faq()
    {
        $tempArr = Session::get('currentUser');
         $data["quesdetails"] = TblQuesMaster::select('tq.question', 'u.first_name','u.last_name','ud.image','sd.table_name','sd.id as shippingId','tq.id as quesId','tbl_ques_masters.carrier_id')                            
                                    ->leftJoin('tbl_questions as tq','tq.ques_master_id','=','tbl_ques_masters.id')
                                    ->leftJoin('users as u','u.id','=','tbl_ques_masters.carrier_id')
                                    ->leftJoin('user_details as ud','ud.user_id','=','tbl_ques_masters.carrier_id')
                                    ->leftJoin('shipping_details as sd','sd.id','=','tbl_ques_masters.shipping_id')                                   
                                    ->orderBy('tq.id', 'desc')
                                    ->groupBy('tbl_ques_masters.carrier_id')
                                    ->groupBy('tbl_ques_masters.shipping_id')
                                    ->where('tq.status',1)
                                    ->where('tbl_ques_masters.user_id',$tempArr["id"])->get();
         
        
        return view('user.faq',$data);
    }
    public function getquesAns(Request $request){
         $quesDetail = TblQuesMaster::select('tq.question','tq.created_at as quesTime','u.first_name as cfname','u.last_name as clname','ta.answer','ta.created_at as ansTime','uc.first_name as ufname','uc.last_name as ulname')
                                    ->leftJoin('tbl_questions as tq','tq.ques_master_id','=','tbl_ques_masters.id')
                                    ->leftJoin('users as u','u.id','=','tbl_ques_masters.carrier_id')
                                    ->leftJoin('tbl_answers as ta','ta.ques_id','=','tq.id')
                                    ->leftJoin('users as uc','uc.id','=','tbl_ques_masters.user_id')
                                    ->where('tbl_ques_masters.shipping_id', $request->id)
                                    ->where('tbl_ques_masters.carrier_id', $request->cid)
                                    ->get();
            $i=1;
            $a='';
            foreach($quesDetail as $detail){
                        
                $a.='<h5><b>'.$i.'. '.$detail['question'].'</b></h5>';
                 $a.='Ans '.$detail['answer'];
                 $a.='<br>';
                 $i++;
            }
        echo $a;
    }

    public function notification()
    {
        return view('user.notification');
    }
    
    public function profile(){
      $data['user'] = Auth::User();
      $data['user_detail'] = UserDetail::where('user_id', Auth::User()->id)->first();

      return view('user.profile', $data);
    }

    public function changepassword(Request $request){
      if($request->isMethod('post')){
        $this->validate($request, [
            'old_password' => 'required',
            'new_password' => 'required|confirmed|min:6',
            'new_password_confirmation' => 'required',
        ]);

        $user = User::find(Auth::User()->id);
        $old_password = bcrypt($request->get('old_password'));

        if (Auth::attempt(array('email' => Auth::User()->email, 'password' => $request->get('old_password')))){
          $user->password = bcrypt($request->get('new_password'));
          if($user->save()){
            $request->session()->flash('alert_type', 'success');
            $request->session()->flash('alert_msg', 'Your password is changed successfully!');
          }
        }else{
          $request->session()->flash('alert_type', 'danger');
          $request->session()->flash('alert_msg', 'Old password is wrong!');
        }

        return redirect('user/changepassword');
      }
      return view('user.changepassword');
    }
     public function transactionhistory()
    {
        return view('user.transactionhistory');
    }
    public function gettrucklength(Request $request){
        
       $data["truck_lengths"]  = DB::table('truck_lengths')
                    ->where('truck_type_id',$request->id)
                    ->select('truck_length','id')
                    ->get();
      echo json_encode($data);
    }
    
     public function gettruckcapacity(Request $request){
        
       $data["truck_capacity"]  = DB::table('truck_capacities')
                    ->where('truck_length_id',$request->id)
                    ->select('truck_capacity','id')
                    ->get();
      echo json_encode($data);
    }
    
    public function subCategory(Request $request){
        $catId = $request->id; 
        
        $request->session()->put('category_id', $catId);
        $category = VehicleCategory::where('status',1)->where('id',$catId)->select('category_name')->first();
        $subCategories = VehicleCategory::where('status',1)->where('parent_id',$catId)->select('id','category_name','category_image')->get();
        if($catId=='4') { 
            $data["materials"]= DB::table('materials')
                    ->select('*')
                    ->get();
             return view('user/shipment/partload',$data);
        } else if($catId=='1') {
           
            $arr = \App\VehicleCategory::where("category_name","=","open")->lists('id')->all(); 
            $arr1 = \App\VehicleCategory::where("category_name","=","Trailer")->lists('id')->all(); 
            $arr2 = \App\VehicleCategory::where("category_name","=","Container")->lists('id')->all(); 
            $arr3 = \App\VehicleCategory::where("category_name","=","Truck Booking")->lists('id')->all(); 
            $data["openlength"]= DB::table('truck_lengths')
                            ->whereIn('truck_type_id',$arr)
                            ->select('id','truck_length')
                            ->get();
            $data["Trailerlength"]= DB::table('truck_lengths')
                            ->whereIn('truck_type_id',$arr1)
                            ->select('id','truck_length')
                            ->get();
             $data["Containerlength"]= DB::table('truck_lengths')
                            ->whereIn('truck_type_id',$arr2)
                            ->select('id','truck_length')
                            ->get();
             $data["trucktype"]= DB::table('vehicle_categories')
                            ->where('parent_id',$arr3)
                            ->select('id','category_name','category_image')
                            ->get();
             return view('user/shipment/truckbooking',$data);
        } else {
        return view('user/subCategory/index', ['category'=>$category, 'subCategories'=>$subCategories]);
        }
    }
   
}

