<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Session;
use App\VehicleCategory;
use App\User;
use App\TruckLengths;
use App\AdminBedroom;
use App\ShippingQuote;
use App\PaymentDetail;
use App\AdminBox;
use App\ShippingDetail;
use DB;

class ShipmentReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$id=null)
    {
      
        $data["categoryname"] =   DB::table('vehicle_categories')
                            ->where('parent_id',$id)
                            ->select('category_name','id')
                            ->get();
        
        //echo $tbl->table_name;die;
         if($request->category){
        $data["shiping_details"] =   DB::table('shipping_details as s')
                             ->leftjoin('vehicle_categories as v','v.id', '=', 's.category_id')
                             ->leftjoin('vehicle_categories as v1','v1.id', '=', 's.subcategory_id')
                              ->leftjoin('payment_methods as p','p.id', '=', 's.payment_method_id')
                              ->leftjoin('shipping_quotes as q','q.shipping_id', '=', 's.id')
                            ->where('s.subcategory_id',$request->category)
                            ->where('s.status',1)
                            ->orderBy('s.created_at',"desc") 
                            ->select('s.id','s.estimated_price','s.table_name','s.status','s.payments_status','v.category_name as categoty_name','v1.category_name as subcategory_name','p.method','q.quote_status')
                            ->paginate(10);
       
         } if($request->category && $request->delivery_title) {
             $tbl =  DB::table('shipping_details')
                            ->where('status',1)
                            ->where('subcategory_id',$request->category)
                            ->select('table_name')
                            ->first();
             
             $data["shiping_details"] =   DB::table('shipping_details as s')
                             ->leftjoin('vehicle_categories as v','v.id', '=', 's.category_id')
                             ->leftjoin('vehicle_categories as v1','v1.id', '=', 's.subcategory_id')
                              ->leftjoin('payment_methods as p','p.id', '=', 's.payment_method_id')
                              ->leftjoin('shipping_quotes as q','q.shipping_id', '=', 's.id')
                              ->leftjoin($tbl->table_name.' as tblName','tblName.shipping_id', '=','s.id')
                            ->where('s.subcategory_id',$request->category)
                            ->where('tblName.delivery_title','like',"%$request->delivery_title%")
                            ->where('s.status',1)
                            ->orderBy('s.created_at',"desc") 
                            ->select('s.id','s.estimated_price','s.table_name','s.status','s.payments_status','v.category_name as categoty_name','v1.category_name as subcategory_name','p.method','q.quote_status')
                            ->paginate(10);
             
         } else {
         
           $data["shiping_details"] =   DB::table('shipping_details as s')
                             ->leftjoin('vehicle_categories as v','v.id', '=', 's.category_id')
                             ->leftjoin('vehicle_categories as v1','v1.id', '=', 's.subcategory_id')
                              ->leftjoin('payment_methods as p','p.id', '=', 's.payment_method_id')
                              ->leftjoin('shipping_quotes as q','q.shipping_id', '=', 's.id')
                            ->where('s.category_id',$id)                            
                            ->where('s.status',1) 
                            ->orderBy('s.created_at',"desc") 
                            // ->where('q.quote_status',0)->orWhere('q.quote_status','1')
                            ->select('s.id','s.estimated_price','s.table_name','s.status','s.payments_status','v.category_name as categoty_name','v1.category_name as subcategory_name','p.method','q.quote_status')
                           ->paginate(10);
        }
        $data["page"] = $data["shiping_details"]->toArray(); 
        $data["id"]=$id;
       return view('admin.shipmentReport.shipmentreport',$data); 
    }
    
    public function details_report($id){
        
        $data = array();
        $commanData = array();
            $i = 0;
            
            $shippingId = $id;
                     
            $shippingData = ShippingDetail::select('shipping_details.id', 'table_name', 'shipping_details.payment_method_id', 'shipping_details.quote_status', 'u.id as userId', 'u.first_name', 'u.last_name' , 'shipping_details.created_at', 'spd.pickup_address', 'spd.pickup_date',  'sdd.delivery_date', 'spd.latitude as pickupLat', 'spd.longitutde as pickupLong', 'sdd.delivery_address', 'sdd.latitude as deliveryLat', 'sdd.longitutde as deliveryLong', 'vc.category_name as category', 'vsc.category_name as subCategory')
                            ->leftJoin('users as u','u.id','=','shipping_details.user_id')                            
                            ->leftJoin('shipping_pickup_details as spd','spd.shipping_id','=','shipping_details.id')                            
                            ->leftJoin('shipping_delivery_details as sdd','sdd.shipping_id','=','shipping_details.id')
                            ->leftJoin('vehicle_categories as vc','vc.id','=','shipping_details.category_id')
                            ->leftJoin('vehicle_categories as vsc','vsc.id','=','shipping_details.subcategory_id')
                            ->where('shipping_details.id', $shippingId)->first(); 
            $paymentType = array('NA','Cash on Delivery','Online');
           
            $commanData['userName'] = $shippingData->first_name. ' '.$shippingData->last_name;
            
            $miid = ShippingQuote::where('shipping_id',$shippingId)->select((DB::raw('min(quote_price) as minimumBid')))->first();
                  
            $shippingDetail = DB::table($shippingData->table_name)->where('shipping_id',$shippingId)->first();
            if($shippingData->table_name == 'shipment_listing_homes'){
                $commanData['deliveryTitle'] = $shippingDetail->delivery_title;
                $data['residenceType'] = $shippingDetail->residence_type;
                $data['no_ofRooms'] = $shippingDetail->no_of_room;               
                $data['dining_room'] = (empty($shippingDetail->dining_room) || $shippingDetail->dining_room == 'null') ? 'N/A' : ShippingDetail::getDineInData($shippingDetail->dining_room, 'admin_dinning_rooms');
                $data['living_room'] = (empty($shippingDetail->living_room) || $shippingDetail->living_room == 'null') ? 'N/A' : ShippingDetail::getDineInData($shippingDetail->living_room, 'admin_living_rooms');
                $data['kitchen'] = (empty($shippingDetail->kitchen) || $shippingDetail->kitchen == 'null') ? 'N/A' : ShippingDetail::getDineInData($shippingDetail->kitchen, 'admin_kitchens');
                $data['home_office'] = (empty($shippingDetail->home_office) || $shippingDetail->home_office == 'null') ? 'N/A' : ShippingDetail::getDineInData($shippingDetail->home_office, 'admin_home_offices');
                $data['garage'] = (empty($shippingDetail->garage) || $shippingDetail->garage == 'null') ? 'N/A' : ShippingDetail::getDineInData($shippingDetail->garage, 'admin_garages');
                $data['living_room'] = (empty($shippingDetail->outdoor) || $shippingDetail->outdoor == 'null') ? 'N/A' : ShippingDetail::getDineInData($shippingDetail->outdoor, 'admin_outdoors');
                $data['miscellaneous'] = (empty($shippingDetail->miscellaneous) || $shippingDetail->miscellaneous == 'null') ? 'N/A' : ShippingDetail::getDineInData($shippingDetail->miscellaneous, 'admin_miscellaneouses');
                $data['itemImage'] = $shippingDetail->item_image;
                $data['additionalDetail'] = $shippingDetail->item_detail;
            }
            else if($shippingData->table_name == 'shipment_listing_offices'){
                $commanData['deliveryTitle'] = $shippingDetail->delivery_title;
                $data['collectionFloor'] = $shippingDetail->collection_floor;
                $data['deliveryFloor'] = $shippingDetail->delivery_floor;                
                $data['liftElevator'] = ($shippingDetail->lift_elevator == 0) ? 'No' : 'Yes';
                $data['general'] = (empty($shippingDetail->general_shipment_inventory) || $shippingDetail->general_shipment_inventory == 'null') ? 'N/A' : ShippingDetail::getDineInData($shippingDetail->general_shipment_inventory, 'admin_general_shipments');
                $data['equipment'] = (empty($shippingDetail->equipment_shipment_inventory) || $shippingDetail->equipment_shipment_inventory == 'null') ? 'N/A' : ShippingDetail::getDineInData($shippingDetail->equipment_shipment_inventory, 'admin_equipments');
                $data['boxes'] = (empty($shippingDetail->boxes) || $shippingDetail->boxes == 'null') ? 'N/A' : ShippingDetail::getDineInData($shippingDetail->boxes, 'admin_miscellaneouses');
                $data['itemImage'] = $shippingDetail->item_image;
                $data['additionalDetail'] = $shippingDetail->item_detail;
            }
            else if($shippingData->table_name == 'shipment_listing_householdgoods' || $shippingData->table_name == 'shipment_listing_others'){                        
                $commanData['deliveryTitle'] = $shippingDetail->delivery_title;
                $data['itemImage'] = $shippingDetail->item_image;
                $data['additionalDetail'] = $shippingDetail->additional_detail;
            }
            else if($shippingData->table_name == 'shipment_listing_truck_bookings'){      
                $commanData['deliveryTitle'] = $shippingDetail->delivery_title;
                $data['material'] = (empty($shippingDetail->material_id)) ? 'N/A' : ShippingDetail::getCategoryName($shippingDetail->material_id, 'id', 'name','materials');
                $data['truckLength'] = (empty($shippingDetail->truck_length_id)) ? 'N/A' : ShippingDetail::getCategoryName($shippingDetail->truck_length_id, 'id', 'truck_length','truck_lengths');
                $data['truckCapacity'] = (empty($shippingDetail->truck_capacity_id)) ? 'N/A' : ShippingDetail::getCategoryName($shippingDetail->truck_capacity_id, 'id', 'truck_capacity','truck_capacities');
                $data['itemImage'] = $shippingDetail->item_image;
                $data['additionalDetail'] = $shippingDetail->remarks;
            }
            else if($shippingData->table_name == 'shipment_listing_vehicle_shifings'){                        
                $commanData['deliveryTitle'] = $shippingDetail->delivery_title;
                $data['itemImage'] = $shippingDetail->item_image;
            }
            else if($shippingData->table_name == 'shipment_listing_materials'){     
                $commanData['deliveryTitle'] = $shippingDetail->delivery_title;
                $data['material'] = (empty($shippingDetail->material_id)) ? 'N/A' : ShippingDetail::getCategoryName($shippingDetail->material_id, 'id', 'name','materials');
                $data['weight'] = $shippingDetail->weight;
                $data['itemImage'] = $shippingDetail->item_image;                
                $data['additionalDetail'] = $shippingDetail->remarks;
            }
            $commanData['category'] = $shippingData->category;
            $commanData['subCategory'] = $shippingData->subCategory;            
            $commanData['pickupDate'] = date('d-m-Y', strtotime($shippingData->pickup_date));
            $commanData['deliveryDate'] = date('d-m-Y', strtotime($shippingData->delivery_date));
            $commanData['pickupAddress'] = $shippingData->pickup_address;
            $commanData['deliveryAddress'] = $shippingData->delivery_address;            
            $commanData['distance'] = ShippingDetail::distance($shippingData->pickupLat, $shippingData->pickupLong, $shippingData->deliveryLat, $shippingData->deliveryLong, "K"). ' km'; 
            $commanData['publishDate'] = date('d-m-Y', strtotime($shippingData->created_at)); 
            $commanData['expireDate'] = date('d-m-Y', strtotime($shippingData->pickup_date));
            $data['minimumBid'] = ($miid) ? $miid->minimumBid : '0'; 
            $data['paymentType'] = $paymentType[$shippingData->payment_method_id];
            $data['bidStatus'] = ($shippingData->quote_status == 1) ? 'Accepted' : 'Pending';
          
        return view('admin/shipmentReport/view')->with([
                    'data' => $data,
                    'commanData' => $commanData]);
    }
    public function bids_report($id){
        
        
        
        $data["shipping_quotes"] =   DB::table('shipping_quotes as s')
                                    ->leftjoin('users as u','s.carrier_id','=','u.id')
                                    ->where('shipping_id',$id)
                                    ->select('s.quote_price','s.quote_status','s.lowest_quote_price','u.first_name','u.last_name')
                                    ->get();
        
        return view('admin/shipmentReport/bidview',$data);
    }
    
    public function cod_payments($ids){
         
        $idd=explode("_",$ids);
        $id=$idd[0];
        $data["category_id"]=$idd[1];
        $data["shipping_quotes"] =   DB::table('shipping_details as s')
                                    ->leftjoin('shipping_quotes as sq','s.id','=','sq.shipping_id')
                                    ->where('sq.shipping_id',$id)
                                     ->where('sq.quote_status',1)
                                    ->select('sq.quote_price','sq.quote_status','s.table_name','s.id')
                                    ->first();
        return view('admin/shipmentReport/cod_payments',$data);
    }
    
    public function cod_payments_save($ids){
        $idd=explode("_",$ids);
        $id=$idd[0];
        $category_id=$idd[1];
         if(Auth::user()->user_type_id <> 1 || !Auth::check()) return redirect('shipment/'.$category_id.'/report');

         $amt =  DB::table('shipping_quotes')
                    ->where('shipping_id',$id)
                     ->where('quote_status',1)
                     ->select('quote_price')
                     ->first();
          // Update shipping details
          ShippingDetail::where('id', $id)->update(['payments_status' => 1]);
           $transaction_id=$this->random_num(20);
          // insert payment details
          $pay_det = new PaymentDetail;
          $pay_det->shipping_id = $id;
          $pay_det->transaction_id = $transaction_id;
          $pay_det->amount = $amt->quote_price;
          $pay_det->card_type = '';
          $pay_det->name_on_card = '';
          $pay_det->card_number = '';
          $pay_det->expiry_date = '';
          $pay_det->account_number = '';
          $pay_det->status = 'cod success';
          //$pay_det->created_at = $response['addedon'];
          $pay_det->save();
          return redirect('shipment/'.$category_id.'/report');
         
    }
    protected function random_num($size) {
	$alpha_key = '';
	$keys = range('a', 'z');

	for ($i = 0; $i < 2; $i++) {
		$alpha_key .= $keys[array_rand($keys)];
	}

	$length = $size - 2;

	$key = '';
	$keys = range(0, 9);

	for ($i = 0; $i < $length; $i++) {
		$key .= $keys[array_rand($keys)];
	}

	return $alpha_key . $key;
    }
    
}
