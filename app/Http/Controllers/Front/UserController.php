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
use App\ShippingPickupDetail;
use App\ShippingDeliveryDetail;
use App\PayInfo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\library\Smsapi;
use Response;
use DB;
use Helper;


class UserController extends FrontController
{

    public function profile(){
      if((Auth::user()->user_type_id <> 3 && Auth::user()->user_type_id <> 2)  || !Auth::check()) return redirect('/');

      $data['user'] = Auth::User();
      $data['user_detail'] = UserDetail::where('user_id', Auth::User()->id)->first();
      return view('user.profile', $data);
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
      return view('user.changepassword');
    }

    public function myDeliveries($status){
      if(Auth::user()->user_type_id <> 3 || !Auth::check()) return redirect('/');

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
        $shipmentDetail->image = $image[0];
        $shipmentDetail->status = $status[$shipmentDetail->status];
        $shipmentDetail->postDate = date('d-F-Y', strtotime($shipmentDetail->published));
      }

      $data['quotation_count'] = ShippingQuote::where('shipping_id', $id)->count();
      $data['shippingDetail'] = $shipmentDetail; 
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
        return redirect('user/home');

      // Quote Details
      $quoteDetails = ShippingQuote::select('shipping_quotes.id as quoteId','u.first_name','u.last_name', 'quote_price', 'quote_status')
              ->leftJoin('users as u','u.id','=','shipping_quotes.carrier_id')
              ->where('shipping_quotes.shipping_id', $id)->get();
  
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
        return redirect('user/home');

      $status = array("Pending","Accepted","Rejected");
      $offerData->quoteId = $quoteId;
      $offerData->shippingId = $offerData->shippingId;
      $offerData->price = $offerData->quote_price;
      $offerData->validTill = date('d-F-Y', strtotime($offerData->pickup_date));
      $offerData->developed = date('d-F-Y', strtotime($offerData->created_at));
      $offerData->status = $status[$offerData->quote_status];

      $data['offerData'] = $offerData;
      return view('user.quotation-detail', $data);
    }

    public function quotationStatusChange(Request $request, $quot_id){
      if(Auth::user()->user_type_id <> 3 || !Auth::check()) return redirect('/');

      $shipping_quote = ShippingQuote::where('id', $quot_id);
      
      // check shipping id belongs to logged in user
      $sq = $shipping_quote->first();
      if(!ShippingDetail::isShippingIdBelongsToLoggedInUser($sq->shipping_id, Auth::User()->id))
        return redirect('user/home');

      if($shipping_quote->count()){
        if($request->get('quot_sts') == 1){
          $rejectOther = ShippingQuote::where('shipping_id', $sq->shipping_id)->update(['quote_status'=>2]);
          $sts_updt = $shipping_quote->update(['quote_status' => 1]);

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
        }

        if($request->get('quot_sts') == 2){
          $sts_updt = $shipping_quote->update(['quote_status' => 2]);

          $carrierData = ShippingQuote::select('u.device_token','u.first_name','u.last_name','u.mobile_number')
                          ->leftJoin('users as u','u.id','=','shipping_quotes.carrier_id')
                          ->where('shipping_quotes.id', $quot_id)->first();
          
          $otpMsg = 'Your Offer has been rejected by the '.$carrierData->first_name.' '.$carrierData->last_name;
          // send sms
          $smsObj = new Smsapi();
          $smsObj->sendsms_api('+91'.$carrierData->mobile_number, $otpMsg);
        }

        if($sts_updt){
          $status = array("pending", "accepted", "rejected");
          $sts_title = $status[$request->get('quot_sts')];
          $request->session()->flash('alert_type', 'success');
          $request->session()->flash('alert_msg', "Quotation is $sts_title successfully!");
        }

      } 
      return redirect("user/quotation-offer/$quot_id");
    }

    public function relistShipment(Request $request, $shipping_id){
      if(Auth::user()->user_type_id <> 3 || !Auth::check()) return redirect('/');

      // check shipping id belongs to logged in user
      if(!ShippingDetail::isShippingIdBelongsToLoggedInUser($shipping_id, Auth::User()->id))
        return redirect('user/home');

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
        //return redirect('user/relist-shipment/' . $shipping_id);
      }

      // Get shipment details
      $data['shipPickDetail'] = ShippingPickupDetail::where('shipping_id', $shipping_id)->first();
      $data['shipDelivDetail'] = ShippingDeliveryDetail::where('shipping_id', $shipping_id)->first();
      if(count($data['shipPickDetail']) <= 0 || count($data['shipDelivDetail']) <= 0)
        return redirect('user/delivery-detail/' . $shipping_id);
      return view('user.relist-shipment', $data);
    }

    // Get Bank Info
    public function bankInformation(){
      if((Auth::user()->user_type_id <> 3 && Auth::user()->user_type_id <> 2)  || !Auth::check()) return redirect('/');

      $data['bank_infos'] = PayInfo::where('user_id', Auth::User()->id)
                                      ->orderBy('id', 'desc')->get();
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

      return view('user.bank-information-add');
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

          $user_detail_updt = UserDetail::where('user_id', Auth::User()->id)
                              ->update([
                                    'street' => $request->get('street'),
                                    'location' => $request->get('location'),
                                    'city' => $request->get('city'),
                                    'pincode' => $request->get('pincode'),
                                    'country' => $request->get('country'),
                                  ]);
          
          if($user->save() && $user_detail_updt){
            /*$request->session()->flash('alert_type', 'success');
            $request->session()->flash('alert_msg', 'Profile is updated successfully!');*/
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
      return view('user.transactionhistory', $data);
    }

    public function myOffer(){
      if(Auth::user()->user_type_id <> 2  || !Auth::check()) return redirect('/');

      $offerData = ShippingQuote::select('shipping_quotes.id','shipping_quotes.shipping_id','shipping_quotes.quote_status','shipping_quotes.quote_price','sd.table_name','sd.subcategory_id','sd.category_id')
                            ->leftJoin('shipping_details as sd','sd.id','=','shipping_quotes.shipping_id')
                            ->where('shipping_quotes.carrier_id', Auth::User()->id)->get();
                
      if(count($offerData) > 0){
        foreach($offerData as $key=>$offer){ 
           $shippingId = $offer->shipping_id;
           $shippingData = DB::table($offer->table_name)->select('delivery_title','item_image')->where('shipping_id', $shippingId)->first();

           $image = explode(',', $shippingData->item_image);
           $status = array("Pending", "Accepted", "Rejected");

           $offerData[$key]->quoteId = $offer->id;
           $offerData[$key]->shippingId = $shippingId;
           $offerData[$key]->title = $shippingData->delivery_title;
           $offerData[$key]->image = Helper::setDefaultImage('public/uploads/userimages/', $image[0], 'n');
           $offerData[$key]->category = (empty($offer->category_id)) ? 'N/A' : ShippingDetail::getCategoryName($offer->category_id, 'id', 'category_name','vehicle_categories');
           $offerData[$key]->subcategory = (empty($offer->subcategory_id)) ? 'N/A' : ShippingDetail::getCategoryName($offer->subcategory_id, 'id', 'category_name','vehicle_categories');
           $offerData[$key]->status = $status[$offer->quote_status];
           $offerData[$key]->quotePrice = $offer->quote_price;
        }
      }

      $data['offers'] = $offerData;

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
      return view('user.my-offer-detail', $data);
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

}

