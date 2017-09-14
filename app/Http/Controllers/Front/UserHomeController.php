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
             
     $categories = VehicleCategory::where('status',1)->where('parent_id',0)->select('id','category_name','category_image')->get();
        return view('user.index',['categories'=>$categories]);
       
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
        } else {
        return view('user/subCategory/index', ['category'=>$category, 'subCategories'=>$subCategories]);
        }
    }

    public function myDeliveries($status){
      $sts_arr = array("active" => "1", "deleted" => "2");
      $sts_label_arr = array("all-status" => "All Status", "active" => "Active", "deleted" => "Deleted", "delivered" => "Delivered");
      $data['st_label'] = $sts_label_arr[$status];
      $userId = Auth::User()->id;
      $shiipings = ShippingDetail::where('user_id', $userId);
      if($status == 'delivered') $shiipings->where('payments_status', 1);
      if (array_key_exists($status, $sts_arr)) $shiipings->where('status', $sts_arr[$status]);
      $shiipings = $shiipings->get();
      if(count($shiipings) > 0){
         foreach($shiipings as $key=>$shipping){
            $shippingId = $shipping->id; 
            $shippingData = DB::table($shipping->table_name)->select('delivery_title','item_image')->where('shipping_id',$shippingId)->first();
            if(count($shippingData)){
              $image = explode(',', $shippingData->item_image);
              $statuss = array("Inactive","Active","Process","Complete");
              $shiipings[$key]->title = $shippingData->delivery_title;
              $shiipings[$key]->image = $image[0];
              $shiipings[$key]->status = $statuss[$shipping->status];
              $shiipings[$key]->postDate = date('d-F-Y', strtotime($shipping->created_at));
            }
         }
      }
      $data['shippings'] = $shiipings;
      return view('user.my-deliveries', $data);
    }
    
    public function deliveryDetail($id){
      $shipmentDetail = ShippingDetail::select('shipping_details.id', 'shipping_details.payments_status', 'shipping_details.status', 'shipping_details.table_name', 'shipping_price as price','shipping_details.created_at as published', 'spd.pickup_address', 'spd.pickup_date','sdd.delivery_address', 'sdd.delivery_date', 'pm.method as paymnentType', 'vcat.category_name', 'vscat.category_name as subcat_name')
                  ->leftJoin('shipping_pickup_details as spd','spd.shipping_id','=','shipping_details.id')
                  ->leftJoin('shipping_delivery_details as sdd','sdd.shipping_id','=','shipping_details.id')
                  ->leftJoin('payment_methods as pm','pm.id','=','shipping_details.payment_method_id')
                  ->leftJoin('vehicle_categories as vcat','vcat.id','=','shipping_details.category_id')
                  ->leftJoin('vehicle_categories as vscat','vscat.id','=','shipping_details.subcategory_id')
                  ->where('shipping_details.id', $id)->first();
      
      $shippingData = DB::table($shipmentDetail->table_name)->select('delivery_title','item_image')->where('shipping_id',$id)->first();
      $data['sts'] = $shipmentDetail->status;
      if(count($shippingData)){
        $image = explode(',', $shippingData->item_image);
        $status = array("Inactive","Active","Process","Complete");
        $shipmentDetail->title = $shippingData->delivery_title;
        $shipmentDetail->image = $image[0];
        $shipmentDetail->status = $status[$shipmentDetail->status];
        $shipmentDetail->postDate = date('d-F-Y', strtotime($shipmentDetail->published));
      }

      $data['quotation_count'] = ShippingQuote::where('shipping_id', $id)->count();
      $data['shippingDetail'] = $shipmentDetail; 
      return view('user.delivery-detail', $data);                    
    }

    public function deliveryDelete($id){
      $shipping_detail = ShippingDetail::where('id', $id)
                                  ->where('user_id', Auth::User()->id);
      if($shipping_detail->count()) $shipping_detail->update(['status' => 2]);
      return redirect('user/my-deliveries/deleted');
    }

    public function allQuotation($id){
      $data = array();
      // check shipping id belongs to logged in user
      if(!ShippingDetail::isShippingIdBelongsToLoggedInUser($id, Auth::User()->id))
        return redirect('user/home');

      // Quote Details
      $quoteDetails = ShippingQuote::select('shipping_quotes.id as quoteId','u.first_name','u.last_name', 'quote_price')
              ->leftJoin('users as u','u.id','=','shipping_quotes.carrier_id')
              ->where('shipping_quotes.shipping_id', $id)->get();
  
      if(count($quoteDetails) > 0){
        foreach($quoteDetails as $key=>$quotes){
            $quoteDetails[$key]->quoteId = $quotes->quoteId;
            $quoteDetails[$key]->carrier = $quotes->first_name.' '.$quotes->last_name;
            $quoteDetails[$key]->price = $quotes->quote_price;
        }
      }
      $data['quoteDetails'] = $quoteDetails;
      return view('user.all-quotation', $data);
    }

    public function quotationDetail($quoteId){
      $data = array();
      $offerData = ShippingQuote::select('shipping_quotes.quote_status','sd.created_at','sd.id as shippingId','spd.pickup_date','shipping_quotes.quote_price')
                  ->leftJoin('shipping_details as sd','sd.id','=','shipping_quotes.shipping_id')
                  ->leftJoin('shipping_pickup_details as spd','spd.shipping_id','=','shipping_quotes.shipping_id')
                  ->where('shipping_quotes.id', $quoteId)->first();
      
      // check shipping id belongs to logged in user
      if(!ShippingDetail::isShippingIdBelongsToLoggedInUser($offerData->shippingId, Auth::User()->id))
        return redirect('user/home');

      $status = array("Pending","Accepted","Rejected");
      $offerData->quoteId = $offerData->quoteId;
      $offerData->shippingId = $offerData->shippingId;
      $offerData->price = $offerData->quote_price;
      $offerData->validTill = date('d-F-Y', strtotime($offerData->pickup_date));
      $offerData->developed = date('d-F-Y', strtotime($offerData->created_at));
      $offerData->status = $status[$offerData->quote_status];

      $data['offerData'] = $offerData;
      return view('user.quotation-detail', $data);
    }

}

