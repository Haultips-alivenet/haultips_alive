<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\FrontController;
use Auth;
use Session;
use App\VehicleCategory;
use App\User;
use App\UserVehicleDetail;
use App\TblAnswer;
use App\UserDetail;
use App\TruckLengths;
use App\AdminBedroom;
use App\AdminBox;
use App\TblQuesMaster;
use App\TblQuestion;
use App\PartnerKyc;
use App\ShippingDetail;
use App\ShippingQuote;
use App\ShippingPickupDetail;
use App\PaymentDetail;
use App\ShippingDeliveryDetail;
use App\PayInfo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\library\Smsapi;
use Indipay;
use Response;
use DB;
use Helper;


class UserController extends FrontController
{

    public function profile(){
      if((Auth::user()->user_type_id <> 3 && Auth::user()->user_type_id <> 2)  || !Auth::check()) return redirect('/');

      $data['user'] = Auth::User();
      $user_detail = UserDetail::where('user_id', Auth::User()->id)->first();
      $data['user_detail'] = $user_detail;
      $data['profile_pic'] = $this->setDefaultImage('public/uploads/userimages/', ($user_detail) ? $user_detail->image : '', 'u');
      return view('user.profile', $data);
    }
    
    public function partner_profile_kyc(){
       if((Auth::user()->user_type_id <> 3 && Auth::user()->user_type_id <> 2)  || !Auth::check()) return redirect('/');
      $data["kycdata"] = DB::table('partner_kycs')->where('user_id', Auth::User()->id)->first();
      $data['user'] = Auth::User();
      $user_detail = UserDetail::where('user_id', Auth::User()->id)->first();
      $data['user_detail'] = $user_detail;
      $data['profile_pic'] = $this->setDefaultImage('public/uploads/userimages/', ($user_detail) ? $user_detail->image : '', 'u');
        return view('user.profile_kyc',$data);
    }

    public function partner_profile_kyc_upload(Request $request){
        
         $destinationPath=public_path()."/admin/kyc/"; 
       $user_id=Auth::User()->id;
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
        $status=0;
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
        return redirect(url('parner/profile/kyc'));
   
        
    }

    public function changepassword(Request $request){
      if((Auth::user()->user_type_id <> 3 && Auth::user()->user_type_id <> 2)  || !Auth::check()) return redirect('/');

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

      $user_detail = UserDetail::where('user_id', Auth::User()->id)->first();
      $data['profile_pic'] = $this->setDefaultImage('public/uploads/userimages/', ($user_detail) ? $user_detail->image : '', 'u');
      return view('user.changepassword', $data);
    }

    public function myDeliveries($status){
      if(Auth::user()->user_type_id <> 3 || !Auth::check()) return redirect('/');

      $sts_arr = array("active" => "1", "deleted" => "2");
      $sts_label_arr = array("all-status" => "All Status", "active" => "Active", "deleted" => "Deleted", "delivered" => "Delivered");
      $data['st_label'] = $sts_label_arr[$status];
      $userId = Auth::User()->id;
      $shiipings = ShippingDetail::where('user_id', $userId)->orderBy('id', 'desc');
      if($status == 'delivered') $shiipings->where('payments_status', 1);
      if (array_key_exists($status, $sts_arr)) $shiipings->where('status', $sts_arr[$status]);
      $shiipings = $shiipings->paginate(10);
      if(count($shiipings) > 0){
         foreach($shiipings as $key=>$shipping){
            $shippingId = $shipping->id; 
            $shippingData = DB::table($shipping->table_name)->select('delivery_title','item_image')->where('shipping_id',$shippingId)->first();
            if(count($shippingData)){
              $image = explode(',', $shippingData->item_image);
              $statuss = array("Inactive","Active","Process","Complete");
              $shiipings[$key]->title = $shippingData->delivery_title;
              $shiipings[$key]->price = ($shipping->shipping_price > 0) ? 'INR ' . $shipping->shipping_price : 'N/A';
              $shiipings[$key]->image = $this->setDefaultImage('public/uploads/userimages/', $image[0], 'n');
              $shiipings[$key]->status = $statuss[$shipping->status];
              $shiipings[$key]->postDate = date('d-F-Y', strtotime($shipping->created_at));
            }
         }
      }
      $data['shippings'] = $shiipings;

      $user_detail = UserDetail::where('user_id', Auth::User()->id)->first();
      $data['profile_pic'] = $this->setDefaultImage('public/uploads/userimages/', ($user_detail) ? $user_detail->image : '', 'u');

      return view('user.my-deliveries', $data);
    }
    
    public function deliveryDetail($id){
      if(Auth::user()->user_type_id <> 3 || !Auth::check()) return redirect('/');

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
        $shipmentDetail->image = $this->setDefaultImage('public/uploads/userimages/', $image[0], 'n');
        $shipmentDetail->status = $status[$shipmentDetail->status];
        $shipmentDetail->postDate = date('d-F-Y', strtotime($shipmentDetail->published));
      }

      $data['quotation_count'] = ShippingQuote::where('shipping_id', $id)->count();
      $data['shippingDetail'] = $shipmentDetail; 

      $user_detail = UserDetail::where('user_id', Auth::User()->id)->first();
      $data['profile_pic'] = $this->setDefaultImage('public/uploads/userimages/', ($user_detail) ? $user_detail->image : '', 'u');

      return view('user.delivery-detail', $data);                    
    }

    public function deliveryDelete($id){
      if(Auth::user()->user_type_id <> 3 || !Auth::check()) return redirect('/');

      $shipping_detail = ShippingDetail::where('id', $id)
                                  ->where('user_id', Auth::User()->id);
      if($shipping_detail->count()) $shipping_detail->update(['status' => 2]);
      return redirect('user/my-deliveries/deleted');
    }

    public function allQuotation($id){
      if(Auth::user()->user_type_id <> 3 || !Auth::check()) return redirect('/');

      $data = array();
      // check shipping id belongs to logged in user
      if(!ShippingDetail::isShippingIdBelongsToLoggedInUser($id, Auth::User()->id))
        return redirect('/');

      // Quote Details
      $quoteDetails = ShippingQuote::select('shipping_quotes.id as quoteId','u.first_name','u.last_name', 'quote_price', 'quote_status')
              ->leftJoin('users as u','u.id','=','shipping_quotes.carrier_id')
              ->where('shipping_quotes.shipping_id', $id)->paginate(10);
  
      if(count($quoteDetails) > 0){
        foreach($quoteDetails as $key=>$quotes){
          $status = array("Pending","Accepted","Rejected");
          $quoteDetails[$key]->quote_status = $status[$quotes->quote_status];
          $quoteDetails[$key]->quoteId = $quotes->quoteId;
          $quoteDetails[$key]->carrier = $quotes->first_name.' '.$quotes->last_name;
          $quoteDetails[$key]->price = $quotes->quote_price;
        }
      }
      $data['quoteDetails'] = $quoteDetails;

      $user_detail = UserDetail::where('user_id', Auth::User()->id)->first();
      $data['profile_pic'] = $this->setDefaultImage('public/uploads/userimages/', ($user_detail) ? $user_detail->image : '', 'u');

      return view('user.all-quotation', $data);
    }

    public function quotationDetail($quoteId){
      if(Auth::user()->user_type_id <> 3 || !Auth::check()) return redirect('/');

      $data = array();
      $offerData = ShippingQuote::select('shipping_quotes.quote_status','sd.created_at','sd.id as shippingId','spd.pickup_date','shipping_quotes.quote_price')
                  ->leftJoin('shipping_details as sd','sd.id','=','shipping_quotes.shipping_id')
                  ->leftJoin('shipping_pickup_details as spd','spd.shipping_id','=','shipping_quotes.shipping_id')
                  ->where('shipping_quotes.id', $quoteId)->first();
      
      // check shipping id belongs to logged in user
      if(!ShippingDetail::isShippingIdBelongsToLoggedInUser($offerData->shippingId, Auth::User()->id))
        return redirect('/');

      $status = array("Pending","Accepted","Rejected");
      $offerData->quoteId = $quoteId;
      $offerData->shippingId = $offerData->shippingId;
      $offerData->price = $offerData->quote_price;
      $offerData->validTill = date('d-F-Y', strtotime($offerData->pickup_date));
      $offerData->developed = date('d-F-Y', strtotime($offerData->created_at));
      $offerData->status = $status[$offerData->quote_status];

      $data['offerData'] = $offerData;

      $user_detail = UserDetail::where('user_id', Auth::User()->id)->first();
      $data['profile_pic'] = $this->setDefaultImage('public/uploads/userimages/', ($user_detail) ? $user_detail->image : '', 'u');

      return view('user.quotation-detail', $data);
    }

    public function quotationOfferAccept($quoteId){
      if(Auth::user()->user_type_id <> 3 || !Auth::check()) return redirect('/');

      $data = array();
      $offerData = ShippingQuote::select('shipping_quotes.quote_status','sd.created_at','sd.id as shippingId','spd.pickup_date','shipping_quotes.quote_price')
                  ->leftJoin('shipping_details as sd','sd.id','=','shipping_quotes.shipping_id')
                  ->leftJoin('shipping_pickup_details as spd','spd.shipping_id','=','shipping_quotes.shipping_id')
                  ->where('shipping_quotes.id', $quoteId)->first();
      
      // check shipping id belongs to logged in user
      if(!ShippingDetail::isShippingIdBelongsToLoggedInUser($offerData->shippingId, Auth::User()->id))
        return redirect('/');

      $status = array("Pending","Accepted","Rejected");
      $offerData->quoteId = $quoteId;
      $offerData->shippingId = $offerData->shippingId;
      $offerData->price = $offerData->quote_price;
      $offerData->validTill = date('d-F-Y', strtotime($offerData->pickup_date));
      $offerData->developed = date('d-F-Y', strtotime($offerData->created_at));
      $offerData->status = $status[$offerData->quote_status];

      $data['offerData'] = $offerData;

      $user_detail = UserDetail::where('user_id', Auth::User()->id)->first();
      $data['profile_pic'] = $this->setDefaultImage('public/uploads/userimages/', ($user_detail) ? $user_detail->image : '', 'u');

      return view('user.accept-offer', $data);
    }

    public function quotationOfferReject(Request $request, $quot_id){
      if(Auth::user()->user_type_id <> 3 || !Auth::check()) return redirect('/');

      $shipping_quote = ShippingQuote::where('id', $quot_id);
      
      // check shipping id belongs to logged in user
      $sq = $shipping_quote->first();
      if(!ShippingDetail::isShippingIdBelongsToLoggedInUser($sq->shipping_id, Auth::User()->id))
        return redirect('/');

      if($shipping_quote->count()){
        $sts_updt = $shipping_quote->update(['quote_status' => 2]);

        if($sts_updt){
          $carrierData = ShippingQuote::select('u.device_token','u.first_name','u.last_name','u.mobile_number')
                          ->leftJoin('users as u','u.id','=','shipping_quotes.carrier_id')
                          ->where('shipping_quotes.id', $quot_id)->first();
          
          $otpMsg = 'Your Offer has been rejected by the '.$carrierData->first_name.' '.$carrierData->last_name;
          // send sms
          $smsObj = new Smsapi();
          $smsObj->sendsms_api('+91'.$carrierData->mobile_number, $otpMsg);

          $request->session()->flash('alert_type', 'success');
          $request->session()->flash('alert_msg', "Quotation is rejected successfully!");
        }

      } 
      return redirect("user/quotation-offer/$quot_id");
    }

    public function quotationOfferAcceptCod(Request $request){
      if(Auth::user()->user_type_id <> 3 || !Auth::check()) return redirect('/');
      $quot_id = $request->quot_id;
      $shipping_quote = ShippingQuote::where('id', $quot_id);
      
      // check shipping id belongs to logged in user
      $sq = $shipping_quote->first();
      if(!ShippingDetail::isShippingIdBelongsToLoggedInUser($sq->shipping_id, Auth::User()->id))
        return redirect('/');

      if($shipping_quote->count()){
        $rejectOther = ShippingQuote::where('shipping_id', $sq->shipping_id)->update(['quote_status'=>2]);
        $sts_updt = $shipping_quote->update(['quote_status' => 1]);

        // Update shipping details
        ShippingDetail::where('id', $sq->shipping_id)->update(['payment_method_id' => 1, 'shipping_price' => $sq->quote_price, 'payments_status' => 0]);

        #Accept offer Notification
        $carrierData = ShippingQuote::select('u.device_token','u.first_name','u.last_name','u.mobile_number')
                        ->leftJoin('users as u','u.id','=','shipping_quotes.carrier_id')
                        ->where('shipping_quotes.id', $quot_id)->first();
        $otpMsg = 'Your Offer has been accepted by the '.$carrierData->first_name.' '.$carrierData->last_name;                           
        
        // Send message functionality   
        $smsObj = new Smsapi();
        $smsObj->sendsms_api('+91'.$carrierData->mobile_number, $otpMsg);                  

        #Reject offer Notification
        $rejectUsers = ShippingQuote::select('u.device_token','u.first_name','u.last_name','u.mobile_number')
                        ->leftJoin('users as u','u.id','=','shipping_quotes.carrier_id')
                        ->where('shipping_quotes.shipping_id', $sq->shipping_id)
                        ->where('shipping_quotes.id','!=', $quot_id)->get();
        foreach($rejectUsers as $rejectData){
          $otpMsg = 'Your Offer has been rejected by the '.$rejectData->first_name.' '.$rejectData->last_name;
          // Send message functionality
          $smsObj = new Smsapi();
          $smsObj->sendsms_api('+91'.$rejectData->mobile_number, $otpMsg);

        }
        
        if($sts_updt){
          $request->session()->flash('alert_type', 'success');
          $request->session()->flash('alert_msg', "Quotation is accepted successfully!");
        }

      } 
      return redirect("user/quotation-offer/$quot_id");
    }

    public function relistShipment(Request $request, $shipping_id){
      if(Auth::user()->user_type_id <> 3 || !Auth::check()) return redirect('/');

      // check shipping id belongs to logged in user
      if(!ShippingDetail::isShippingIdBelongsToLoggedInUser($shipping_id, Auth::User()->id))
        return redirect('/');

      if($request->isMethod('post')){
        $this->validate($request, [
            'pickup_address' => 'required',
            'delivery_address' => 'required',
            'pickup_date' => 'required|date|after:yesterday',
            'delivery_date' => 'required|date|after:pickup_date',
        ]);
        $picklatlong = $this->getLatLong($request->get('pickup_address'));
        $delivlatlong = $this->getLatLong($request->get('delivery_address'));
        try{
          DB::beginTransaction();
          $pick_updt = ShippingPickupDetail::where('shipping_id', $shipping_id)->update([
                          'pickup_address' => $request->get('pickup_address'),
                          'pickup_date' => $request->get('pickup_date'),
                          'latitude' => $picklatlong['lat'],
                          'longitutde' => $picklatlong['lng'],
                        ]);
          
          $ship_updt =  ShippingDeliveryDetail::where('shipping_id', $shipping_id)->update([
                          'delivery_address' => $request->get('delivery_address'),
                          'delivery_date' => $request->get('delivery_date'),
                          'latitude' => $picklatlong['lat'],
                          'longitutde' => $picklatlong['lng'],
                        ]);
          if($pick_updt && $ship_updt){
            DB::commit();
            $request->session()->flash('alert_type', 'success');
            $request->session()->flash('alert_msg', 'Re-list shipment detail changed successfully!');
          }
        }
        catch(\Exception $e){
          DB::rollback();
          echo $e->getMessage();
        } 
        return redirect('user/relist-shipment/' . $shipping_id);
      }

      // Get shipment details
      $data['shipPickDetail'] = ShippingPickupDetail::where('shipping_id', $shipping_id)->first();
      $data['shipDelivDetail'] = ShippingDeliveryDetail::where('shipping_id', $shipping_id)->first();
      if(count($data['shipPickDetail']) <= 0 || count($data['shipDelivDetail']) <= 0)
        return redirect('user/delivery-detail/' . $shipping_id);

      $user_detail = UserDetail::where('user_id', Auth::User()->id)->first();
      $data['profile_pic'] = $this->setDefaultImage('public/uploads/userimages/', ($user_detail) ? $user_detail->image : '', 'u');

      return view('user.relist-shipment', $data);
    }

    // Get Bank Info
    public function bankInformation(){
      if((Auth::user()->user_type_id <> 3 && Auth::user()->user_type_id <> 2)  || !Auth::check()) return redirect('/');

      $data['bank_infos'] = PayInfo::where('user_id', Auth::User()->id)
                                      ->orderBy('id', 'desc')->get();

      $user_detail = UserDetail::where('user_id', Auth::User()->id)->first();
      $data['profile_pic'] = $this->setDefaultImage('public/uploads/userimages/', ($user_detail) ? $user_detail->image : '', 'u');

      return view('user.bank-information', $data);
    }

    // Delete bank info with 
    public function bankInformationDelete(Request $request, $bank_info_id){
      if((Auth::user()->user_type_id <> 3 && Auth::user()->user_type_id <> 2)  || !Auth::check()) return redirect('/');

      $bank_info = PayInfo::where('user_id', Auth::User()->id)
                            ->where('id', $bank_info_id);
      if($bank_info->delete()){
        $request->session()->flash('alert_type', 'success');
        $request->session()->flash('alert_msg', 'Bank information is deleted successfully!');
      }
      else{
        $request->session()->flash('alert_type', 'danger');
        $request->session()->flash('alert_msg', 'Error: failed!');
      }
      return redirect('user/bank-infomation');
    }

    public function bankInformationAdd(Request $request){
      if((Auth::user()->user_type_id <> 3 && Auth::user()->user_type_id <> 2)  || !Auth::check()) return redirect('/');

      if($request->isMethod('post')){
        $this->validate($request, [
            'name' => 'required|max:100',
            'number' => 'required|numeric',
            'code' => 'required|max:50',
          ],
          [
            'number.required' => 'The account number is required.',
            'number.numeric' => 'The account number must be numeric.'
          ]
        );
        
        $bank_info = new PayInfo;
        $bank_info->user_id = Auth::User()->id;
        $bank_info->name = $request->get('name');
        $bank_info->number = $request->get('number');
        $bank_info->code = $request->get('code');
        
        if($bank_info->save()){
          $request->session()->flash('alert_type', 'success');
          $request->session()->flash('alert_msg', 'Bank information is added successfully!');
        }
        return redirect('user/bank-infomation');
      }

      $user_detail = UserDetail::where('user_id', Auth::User()->id)->first();
      $data['profile_pic'] = $this->setDefaultImage('public/uploads/userimages/', ($user_detail) ? $user_detail->image : '', 'u');

      return view('user.bank-information-add', $data);
    }

    // Profile edit
    public function profileEdit(Request $request){
      if((Auth::user()->user_type_id <> 3 && Auth::user()->user_type_id <> 2)  || !Auth::check()) return redirect('/');

      $rules = array (
        'first_name' => 'required|max:200',
        'last_name' => 'required|max:200',
        'street' => 'required',
        'city' => 'required',
        'location' => 'required',
        'pincode' => 'required',
        'country' => 'required',
      );
      $validator = Validator::make ( Input::all (), $rules );
      if ($validator->fails ())
          return Response::json ($validator->messages());
      else {
          $user = User::find(Auth::User()->id);
          $user->first_name = $request->get('first_name');
          $user->last_name = $request->get('last_name');

          $user_detail_updt = UserDetail::where('user_id', Auth::User()->id);
          $update_success = 0;
          if($user_detail_updt->count()){
            if($user_detail_updt->update([
                      'street' => $request->get('street'),
                      'location' => $request->get('location'),
                      'city' => $request->get('city'),
                      'pincode' => $request->get('pincode'),
                      'country' => $request->get('country'),
                    ])){
              $update_success = 1;
            }
          }
          else{
            $usrdet = new UserDetail;
            $usrdet->user_id = Auth::User()->id;
            $usrdet->street = $request->get('street');
            $usrdet->location = $request->get('location');
            $usrdet->city = $request->get('city');
            $usrdet->pincode = $request->get('pincode');
            $usrdet->country = $request->get('country');
            if($usrdet->save())
              $update_success = 1;
          }
                              
          if($user->save() && $update_success){
            $user->street = $request->get('street');
            $user->location = $request->get('location');
            $user->city = $request->get('city');
            $user->pincode = $request->get('pincode');
            $user->country = $request->get('country');
            return $user;
          }
          else return 0;
      }
    }

    public function getTransactionHistory(){
      if((Auth::user()->user_type_id <> 3 && Auth::user()->user_type_id <> 2)  || !Auth::check()) return redirect('/');

      $data['transaction_history'] = ShippingDetail::select('shipping_details.id', 'shipping_details.order_id', 'shipping_details.table_name','pd.created_at','pd.amount','pd.status')                            
                                    ->Join('payment_details as pd','pd.shipping_id','=','shipping_details.id')
                                    ->where('shipping_details.user_id', Auth::User()->id)->get();

      $user_detail = UserDetail::where('user_id', Auth::User()->id)->first();
      $data['profile_pic'] = $this->setDefaultImage('public/uploads/userimages/', ($user_detail) ? $user_detail->image : '', 'u');

      return view('user.transactionhistory', $data);
    }

    public function myOffer(){
      if(Auth::user()->user_type_id <> 2  || !Auth::check()) return redirect('/');

      $offerData = ShippingQuote::select('shipping_quotes.id','shipping_quotes.shipping_id','shipping_quotes.quote_status','shipping_quotes.quote_price','sd.table_name','sd.subcategory_id','sd.category_id')
                            ->join('shipping_details as sd','sd.id','=','shipping_quotes.shipping_id')
                            ->where('shipping_quotes.carrier_id', Auth::User()->id)->paginate(10);
                
      if(count($offerData) > 0){
        foreach($offerData as $key=>$offer){ 
           $shippingId = $offer->shipping_id;
           $shippingData = DB::table($offer->table_name)->select('delivery_title','item_image')->where('shipping_id', $shippingId)->first();

           // Edit bid amount permission
           $offerData[$key]->edit_bid = ShippingQuote::where('shipping_id', $offer->shipping_id)
                                      ->where('carrier_id', '<>', Auth::user()->id)
                                      ->where('quote_price', '<', $offer->quote_price)
                                      ->where('quote_status', 0)->count();

           $image = explode(',', $shippingData->item_image);
           $status = array("Pending", "Accepted", "Rejected");

           $offerData[$key]->quoteId = $offer->id;
           $offerData[$key]->shippingId = $shippingId;
           $offerData[$key]->title = ucwords($shippingData->delivery_title);
           $offerData[$key]->image = $this->setDefaultImage('public/uploads/userimages/', $image[0], 'n');
           $offerData[$key]->category = (empty($offer->category_id)) ? 'N/A' : ShippingDetail::getCategoryName($offer->category_id, 'id', 'category_name','vehicle_categories');
           $offerData[$key]->subcategory = (empty($offer->subcategory_id)) ? 'N/A' : ShippingDetail::getCategoryName($offer->subcategory_id, 'id', 'category_name','vehicle_categories');
           $offerData[$key]->status = $status[$offer->quote_status];
           $offerData[$key]->quotePrice = $offer->quote_price;
        }
      }

      $data['offers'] = $offerData;

      $user_detail = UserDetail::where('user_id', Auth::User()->id)->first();
      $data['profile_pic'] = $this->setDefaultImage('public/uploads/userimages/', ($user_detail) ? $user_detail->image : '', 'u');

      return view('user.my-offer', $data);
    }

    public function myOfferDetail($quote_id){
      $offerData = ShippingQuote::select('shipping_quotes.shipping_id','shipping_quotes.quote_status','sd.order_id','sd.category_id','sd.subcategory_id','sd.order_id','sd.table_name','sd.created_at','u.first_name','u.last_name','u.email','u.mobile_number','spd.pickup_date','spd.pickup_address','sdd.delivery_address')
                            ->leftJoin('shipping_details as sd','sd.id','=','shipping_quotes.shipping_id')
                            ->leftJoin('users as u','u.id','=','sd.user_id')
                            ->leftJoin('shipping_pickup_details as spd','spd.shipping_id','=','shipping_quotes.shipping_id')
                            ->leftJoin('shipping_delivery_details as sdd','sdd.shipping_id','=','shipping_quotes.shipping_id')
                            ->where('shipping_quotes.id', $quote_id)
                            ->where('shipping_quotes.carrier_id', Auth::User()->id)->first();
               
      if(count($offerData)){
           $shippingId = $offerData->shipping_id;
           $shippingData = DB::table($offerData->table_name)->select('delivery_title')->where('shipping_id',$shippingId)->first();

           $status = array("Pending","Accepted","Rejected");

           $offerData->customer = $offerData->first_name. ' '.$offerData->last_name;
           $offerData->email = $offerData->email;
           $offerData->mobileNumber = $offerData->mobile_number;
           $offerData->validTill = date('d-F-Y', strtotime($offerData->pickup_date));
           $offerData->developed = date('d-F-Y', strtotime($offerData->created_at));
           $offerData->status = $status[$offerData->quote_status];
           $offerData->category = (empty($offerData->category_id)) ? 'N/A' : ShippingDetail::getCategoryName($offerData->category_id, 'id', 'category_name','vehicle_categories');
           $offerData->subcategory = (empty($offerData->subcategory_id)) ? 'N/A' : ShippingDetail::getCategoryName($offerData->subcategory_id, 'id', 'category_name','vehicle_categories');
           $offerData->orderNo = strtoupper($offerData->order_id);
           $offerData->title = $shippingData->delivery_title;
           $offerData->takeAway = $offerData->pickup_address;
           $offerData->deliver = $offerData->delivery_address;
      }

      $data['offer'] = $offerData;

      $user_detail = UserDetail::where('user_id', Auth::User()->id)->first();
      $data['profile_pic'] = $this->setDefaultImage('public/uploads/userimages/', ($user_detail) ? $user_detail->image : '', 'u');

      return view('user.my-offer-detail', $data);
    }

    public function faq()
    {
        $tempArr = Session::get('currentUser');
         if($tempArr["user_type_id"]==2) {
          $data["quesdetails"] = TblQuesMaster::select('tq.question','u.id as user_id','u.first_name','u.last_name','ud.image','sd.table_name','sd.id as shippingId','tq.id as quesId','tbl_ques_masters.carrier_id')                            
                                    ->leftJoin('tbl_questions as tq','tq.ques_master_id','=','tbl_ques_masters.id')
                                    ->leftJoin('users as u','u.id','=','tbl_ques_masters.user_id')
                                    ->leftJoin('user_details as ud','ud.user_id','=','tbl_ques_masters.carrier_id')
                                    ->leftJoin('shipping_details as sd','sd.id','=','tbl_ques_masters.shipping_id')                                   
                                    ->orderBy('tq.id', 'desc')
                                    ->groupBy('tbl_ques_masters.carrier_id')
                                    ->groupBy('tbl_ques_masters.shipping_id')
                                    ->where('tq.status',1)
                                    ->where('tbl_ques_masters.carrier_id',$tempArr["id"])->get();
        } else {
            $data["quesdetails"] = TblQuesMaster::select('tq.question','u.id as user_id','u.first_name','u.last_name','ud.image','sd.table_name','sd.id as shippingId','tq.id as quesId','tbl_ques_masters.carrier_id')                            
                                    ->leftJoin('tbl_questions as tq','tq.ques_master_id','=','tbl_ques_masters.id')
                                    ->leftJoin('users as u','u.id','=','tbl_ques_masters.carrier_id')
                                    ->leftJoin('user_details as ud','ud.user_id','=','tbl_ques_masters.carrier_id')
                                    ->leftJoin('shipping_details as sd','sd.id','=','tbl_ques_masters.shipping_id')                                   
                                    ->orderBy('tq.id', 'desc')
                                    ->groupBy('tbl_ques_masters.carrier_id')
                                    ->groupBy('tbl_ques_masters.shipping_id')
                                    ->where('tq.status',1)
                                    ->where('tbl_ques_masters.user_id',$tempArr["id"])->get();
        }
        $user_detail = UserDetail::where('user_id', Auth::User()->id)->first();
        $data['profile_pic'] = $this->setDefaultImage('public/uploads/userimages/', ($user_detail) ? $user_detail->image : '', 'u');
        if($tempArr["user_type_id"]==2) {
             return view('user.faq',$data);
        } else {
        return view('user.faq_user',$data);
        }
    }

    public function user_ans_save(Request $request){
      
        $data = new TblAnswer();
        $data->ques_master_id = $request->question_master_id;
        $data->ques_id = $request->question_id;
        $data->answer = $request->answer;
        $data->save();
         Session::flash('success', 'Answer saved successfully'); 
        return redirect('user/faq');
    }

    public function partner_question_save(Request $request){
        $tempArr = Session::get('currentUser');
        $queMaster = new TblQuesMaster;
        $queMaster->shipping_id = $request->shiping_id;
        $queMaster->user_id = $request->user_id;
        $queMaster->carrier_id = $tempArr["id"];
        $queMaster->save();
        $masterQuesId = $queMaster->id;

        $ques = new TblQuestion;
        $ques->ques_master_id = $masterQuesId;
        $ques->question = $request->question;
        $ques->status =1;
        $ques->save();
        $questionId = $ques->id;
        //Session::flash('success', 'Category created successfully'); 
        return redirect('user/faq');
    }

    public function shipmentNew(){
      if((Auth::user()->user_type_id <> 2 && Auth::user()->user_type_id <> 3) || !Auth::check()) return redirect('/');

      $vehicle_cat = VehicleCategory::where('parent_id', 0);

      // Partner category
      if(Auth::user()->user_type_id == 2){
        if(Auth::user()->carrier_type_id == 1) $vehicle_cat->whereIn('id', [2, 3]);
        if(Auth::user()->carrier_type_id == 2) $vehicle_cat->whereIn('id', [1, 4]);
      }

      $data['vehicle_cat'] = $vehicle_cat->get();

      $user_detail = UserDetail::where('user_id', Auth::User()->id)->first();
      $data['profile_pic'] = $this->setDefaultImage('public/uploads/userimages/', ($user_detail) ? $user_detail->image : '', 'u');
      
      return view('user.new-shipment', $data);
    }

    private function getLatLong($address){
        $url = "http://maps.google.com/maps/api/geocode/json?address=".urlencode($address)."&sensor=false";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_a = json_decode($response);
        if($response_a->results){
          return array( 'lat' => $response_a->results[0]->geometry->location->lat,
                      'lng' => $response_a->results[0]->geometry->location->lng);
        }
        else{
          return array( 'lat' => '', 'lng' => '');
        }
        
    }

    public function setDefaultImage($path = '', $file = '', $type = 'n'){
        $filename = base_path($path . $file);
        // n=>Normal no image, u=>User icon image
        $type_arr = array('n' => 'not-available.jpg', 'u' => 'customer_img.png');
        if (file_exists($filename) && !empty($file))
            return url($path . $file);     
        else
            return asset('public/user/img/' . $type_arr[$type]);
    }

    public function payment(Request $request){
      if(Auth::user()->user_type_id <> 3 || !Auth::check()) return redirect('/');

      $parameters = [
        'amount' => $request->amount, 
        'firstname' => Auth::user()->first_name,
        'lastname' => Auth::user()->last_name,
        'email' => Auth::user()->email,
        'phone' => Auth::user()->mobile_number,
        'productinfo' => 'Offer',
        'udf1' => $request->quot_id,
        'allow_repeated_payments' => false
      ];
      $request->session()->put('pay_process', 1);
      $order = Indipay::prepare($parameters);
      return Indipay::process($order);
    }

    public function success(Request $request){
      if(Auth::user()->user_type_id <> 3 || !Auth::check()) return redirect('/');

      // For default Gateway
      $response = Indipay::response($request);
      if($response['status'] == 'success' && $request->session()->has('pay_process')){
        $quot_id = $response['udf1'];
        $shipping_quote = ShippingQuote::where('id', $quot_id);

        // check shipping id belongs to logged in user
        $sq = $shipping_quote->first();
        if(!ShippingDetail::isShippingIdBelongsToLoggedInUser($sq->shipping_id, Auth::User()->id))
          return redirect('/');
          
          // reject other quotes
          $rejectOther = ShippingQuote::where('shipping_id', $sq->shipping_id)->update(['quote_status'=>2]);

          // Update shipping quotes status
          $shipping_quote->update(['quote_status' => 1]);

          // Update shipping details
          ShippingDetail::where('id', $sq->shipping_id)->update(['payment_method_id' => 2, 'shipping_price' => $response['amount'],'payments_status' => 1]);

          // insert payment details
          $pay_det = new PaymentDetail;
          $pay_det->shipping_id = $sq->shipping_id;
          $pay_det->transaction_id = $response['txnid'];
          $pay_det->amount = $response['amount'];
          $pay_det->card_type = '';
          $pay_det->name_on_card = $response['name_on_card'];
          $pay_det->card_number = $response['cardnum'];
          $pay_det->expiry_date = '';
          $pay_det->account_number = '';
          $pay_det->status = $response['status'];
          $pay_det->created_at = $response['addedon'];
          $pay_det->save();

          #Accept offer Notification
          $carrierData = ShippingQuote::select('u.device_token','u.first_name','u.last_name','u.mobile_number')
                          ->leftJoin('users as u','u.id','=','shipping_quotes.carrier_id')
                          ->where('shipping_quotes.id', $quot_id)->first();
          $otpMsg = 'Your Offer has been accepted by the '.$carrierData->first_name.' '.$carrierData->last_name;                           
          
          // Send message functionality   
          $smsObj = new Smsapi();
          $smsObj->sendsms_api('+91'.$carrierData->mobile_number, $otpMsg);                  

          #Reject offer Notification
          $rejectUsers = ShippingQuote::select('u.device_token','u.first_name','u.last_name','u.mobile_number')
                          ->leftJoin('users as u','u.id','=','shipping_quotes.carrier_id')
                          ->where('shipping_quotes.shipping_id', $sq->shipping_id)
                          ->where('shipping_quotes.id','!=', $quot_id)->get();
          foreach($rejectUsers as $rejectData){
            $otpMsg = 'Your Offer has been rejected by the '.$rejectData->first_name.' '.$rejectData->last_name;
            // Send message functionality
            $smsObj = new Smsapi();
            $smsObj->sendsms_api('+91'.$rejectData->mobile_number, $otpMsg);
          }

          // destroy session restrict backend process on refresh
          $request->session()->forget('pay_process');
      }
      
      // For Otherthan Default Gateway
      //$response = Indipay::gateway('payumoney')->response($request);

      //dd($response);
      $user_detail = UserDetail::where('user_id', Auth::User()->id)->first();
      $response['profile_pic'] = $this->setDefaultImage('public/uploads/userimages/', ($user_detail) ? $user_detail->image : '', 'u');
      return view('user/payment-success', $response);
    }

    public function failure(Request $request){
      // For default Gateway
      $response = Indipay::response($request);
      
      
      // For Otherthan Default Gateway
      //$response = Indipay::gateway('payumoney')->response($request);

      dd($response);
    }
    
    public function partner_profile_transporter(){
      
        //if((Auth::user()->user_type_id <> 3 && Auth::user()->user_type_id <> 2)  || !Auth::check()) return redirect('/');
        $data['user'] = Auth::User();
        $user_detail = UserDetail::where('user_id', Auth::User()->id)->first();
        $data['user_detail'] = $user_detail;
        $data['profile_pic'] = $this->setDefaultImage('public/uploads/userimages/', ($user_detail) ? $user_detail->image : '', 'u');
        
        $arr = \App\VehicleCategory::where("category_name","=","open")->lists('id')->all(); 
            $arr1 = \App\VehicleCategory::where("category_name","=","Trailer")->lists('id')->all(); 
            $arr2 = \App\VehicleCategory::where("category_name","=","Container")->lists('id')->all(); 
            $arr3 = \App\VehicleCategory::where("category_name","=","Truck Booking")->lists('id')->all(); 
            $data["openlength"]= DB::table('truck_lengths')
                            ->whereIn('truck_type_id',$arr)
                            ->select('id','truck_length')
                            ->get();
           
            $data["openlength_capacity"]  = DB::table('truck_capacities')
                    ->whereIn('truck_length_id',\App\TruckLength::where("truck_type_id","=",$arr)->lists('id')->all())
                    ->select('truck_capacity','id')
                    ->get();
           
            $data["Trailerlength"]= DB::table('truck_lengths')
                            ->whereIn('truck_type_id',$arr1)
                            ->select('id','truck_length')
                            ->get();
             $data["Trailerlength_capacity"]  = DB::table('truck_capacities')
                    ->whereIn('truck_length_id',\App\TruckLength::where("truck_type_id","=",$arr1)->lists('id')->all())
                    ->select('truck_capacity','id')
                    ->get();
             
             $data["Containerlength"]= DB::table('truck_lengths')
                            ->whereIn('truck_type_id',$arr2)
                            ->select('id','truck_length')
                            ->get();
              $data["Containerlength_capacity"]  = DB::table('truck_capacities')
                    ->whereIn('truck_length_id',\App\TruckLength::where("truck_type_id","=",$arr2)->lists('id')->all())
                    ->select('truck_capacity','id')
                    ->get();
             $data["trucktype"]= DB::table('vehicle_categories')
                            ->where('parent_id',$arr3)
                            ->select('id','category_name','category_image')
                            ->get();
        
        
        
        $data["vechileData"] = UserVehicleDetail::select('vehicle_category_id','vehicle_subcategory_id','vehicle_length_id','vehicle_capacity_id')
                                        ->where('user_id',Auth::User()->id)->get(); 
        return view('user/partner_profile_transporter',$data);
    }
    
     public function gettruckcapacity(Request $request){
        $ids = $request->id;
        $ids=explode(",",$request->id);
       
       $data["truck_capacity"]  = DB::table('truck_capacities')
                    ->whereIn('truck_length_id',$ids)
                    ->select('truck_capacity','id')
                    ->get();
      echo json_encode($data);
    }
    
    public function partner_transporter_update(Request $request){
       // print_r($_POST);die;
        $subcategory_type = $request->subcategory_type;
        if($subcategory_type) {
            $vehicleDetails = UserVehicleDetail::where('user_id',Auth::User()->id)->delete();
        for($i=0;$i<count($subcategory_type);$i++) {
            
            $length="length".$subcategory_type[$i];
            $len=$request->$length;
           
            for($j=0;$j<count($len);$j++) { 
                
                $userVehicleDetail = new UserVehicleDetail;
                $userVehicleDetail->user_id  = Auth::User()->id;
                $userVehicleDetail->vehicle_category_id = 1;
                $userVehicleDetail->vehicle_subcategory_id = $subcategory_type[$i];
                $userVehicleDetail->vehicle_length_id = $len[$j];
                $userVehicleDetail->save(); 
            }
        }
         User::where('id',Auth::User()->id)
                    ->update([            
                        'total_vehicle'=>$request->total_vehicles,
                        'attached_vehicle'=>$request->attched_vehicles
                        ]);
        
        
        }
         Session::flash('success', 'Transporter Updated Sucessfully!'); 
          return redirect(url('parner/profile/transporter'));  
       
    }

    public function profileImageEdit(Request $request){
      $rules = array (
        'profile_pic' => 'required|image|mimes:jpeg,png,jpg|max:2048',
      );
      $validator = Validator::make ( Input::all (), $rules );
      if ($validator->fails ()){
        Session::flash('alert_type', 'danger');
        Session::flash('alert_msg', 'Error: ' . $validator->errors()->first());
      }
      else{
        $destinationPath=public_path()."/uploads/userimages/"; 
         $user_id=Auth::User()->id;
         $profile_pic = $request->file('profile_pic');
         $user_detail = UserDetail::where('user_id', $user_id);
          
          $filename=$profile_pic->getClientOriginalName();
          $t=time();
          $photofilename="prpic_".$user_id.'_'.$t.'_'.$filename; 
          $upload = $request->file('profile_pic')->move($destinationPath, $photofilename);
          
          $success = 0;
          if(count($user_detail)){
              if($user_detail->update(['image' => $photofilename]))
                $success = 1;
          }
          else{
              $user_det = new UserDetail;
              $user_det->user_id =$user_id;
              $user_det->image = $photofilename;
              if($user_det->save())
                $success = 1;
          }
          
          if($success == 1 && $upload){
              Session::flash('alert_type', 'success');
              Session::flash('alert_msg', 'Profile pic updated successfully!');             
          }
          else{
             Session::flash('alert_type', 'danger');
             Session::flash('alert_msg', 'Error: Profile pic update failed!'); 
          }
      }
      return redirect(url('user/profile'));
    }

}


