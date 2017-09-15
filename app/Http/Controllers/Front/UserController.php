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
use DB;


class UserController extends FrontController
{
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
      $shipping_quote = ShippingQuote::where('id', $quot_id);
      
      // check shipping id belongs to logged in user
      $sq = $shipping_quote->first();
      if(!ShippingDetail::isShippingIdBelongsToLoggedInUser($sq->shipping_id, Auth::User()->id))
        return redirect('user/home');

      if($shipping_quote->count()){
        $status = array("pending", "accepted", "rejected");
        $sts_title = $status[$request->get('quot_sts')];
        if($shipping_quote->update(['quote_status' => $request->get('quot_sts')])){
          $request->session()->flash('alert_type', 'success');
          $request->session()->flash('alert_msg', "Quotation is $sts_title successfully!");
        }
      } 
      return redirect("user/quotation-offer/$quot_id");
    }

    public function relistShipment(Request $request, $shipping_id){
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
        try{
          DB::beginTransaction();
          $pick_updt = ShippingPickupDetail::where('shipping_id', $shipping_id)->update([
                          'pickup_address' => $request->get('pickup_address'),
                          'pickup_date' => $request->get('pickup_date')
                        ]);
          $ship_updt =  ShippingDeliveryDetail::where('shipping_id', $shipping_id)->update([
                          'delivery_address' => $request->get('delivery_address'),
                          'delivery_date' => $request->get('delivery_date')
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
      return view('user.relist-shipment', $data);
    }

    public function bankInformation(){
      if($request->isMethod('post')){
        // Delete bank info with 
        $pick_updt = PayInfo::where('shipping_id', $shipping_id)->update([
                        'pickup_address' => $request->get('pickup_address'),
                        'pickup_date' => $request->get('pickup_date')
                      ]);
        if($pick_updt && $ship_updt){
          DB::commit();
          $request->session()->flash('alert_type', 'success');
          $request->session()->flash('alert_msg', 'Re-list shipment detail changed successfully!');
        }
        return redirect('user/relist-shipment/' . $shipping_id);
      }

      // Get shipment details
      $data['shipPickDetail'] = ShippingPickupDetail::where('shipping_id', $shipping_id)->first();
      $data['shipDelivDetail'] = ShippingDeliveryDetail::where('shipping_id', $shipping_id)->first();
      if(count($data['shipPickDetail']) <= 0 || count($data['shipDelivDetail']) <= 0)
        return redirect('user/delivery-detail/' . $shipping_id);
      return view('user.relist-shipment', $data);
    }

}

