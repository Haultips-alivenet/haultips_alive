<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\FrontController;
use Auth;
use Session;
use App\User;
use App\ShippingQuote;
use App\ShippingDetail;
use App\VehicleCategory;
use DB;

class FindDeliveriesController extends FrontController
{
    
    public function index(Request $request,$categoryId=null)
    {
       //echo $id;die;
        $i=0;
        $categoryId=$request->categoryIdAll;
        $orderBy=$request->orderByAll;
        $pickupaddress=$request->pickupaddressAll;
        $deliveryaddress=$request->deliveryaddress;
        $catType = array('1','2','3','4');
        $shippingData = ShippingDetail::select(DB::raw('shipping_details.id, table_name, vc.category_name, shipping_details.created_at, spd.pickup_address, spd.pickup_date,  sdd.delivery_date, spd.latitude as pickupLat, spd.longitutde as pickupLong, sdd.delivery_address, sdd.latitude as deliveryLat, sdd.longitutde as deliveryLong'))
                                    ->leftJoin('shipping_pickup_details as spd','spd.shipping_id','=','shipping_details.id')
                                    ->leftJoin('shipping_delivery_details as sdd','sdd.shipping_id','=','shipping_details.id')
                                    ->leftJoin('vehicle_categories as vc','vc.id','=','shipping_details.category_id');
                    
            if($categoryId != ''){    
               
                     $categoryId = explode(",",$categoryId);
                     $shippingData = $shippingData->whereIn('shipping_details.category_id', $categoryId);
                      $msg["cid"]=$categoryId;
                }
               
//                if($categoryId_all[1]!=0){
//                  $subcategoryId = explode(",",$categoryId_all[1]);
//                  $shippingData = $shippingData->whereIn('shipping_details.subcategory_id', $subcategoryId);
//                  $msg["sub_cid"]=$subcategoryId;
//                }
                
            
           $msg['orderBy'] = $orderBy;
            if($orderBy == 'origin_asc'){
                $shippingData = $shippingData->orderBy('spd.pickup_address', 'ASC');
            }elseif($orderBy == 'origin_desc'){
                $shippingData = $shippingData->orderBy('spd.pickup_address', 'DESC');
            }elseif($orderBy == 'destination_asc'){
                $shippingData = $shippingData->orderBy('sdd.delivery_address', 'ASC');
            }elseif($orderBy == 'destination_desc'){
                $shippingData = $shippingData->orderBy('sdd.delivery_address', 'DESC');
            }
            
            if($pickupaddress) {
                $shippingData = $shippingData->where('spd.pickup_address', 'like', "%$pickupaddress%");
                 $msg['p_add'] = $pickupaddress;
            }
            if($deliveryaddress) {
                 $shippingData = $shippingData->where('sdd.delivery_address', 'like', "%$deliveryaddress%");
                  $msg['d_add'] = $deliveryaddress;
            }
            $shippingData = $shippingData->whereIn('shipping_details.category_id', $catType);
            $shippingData = $shippingData->where('shipping_details.status', 1);
            $shippingData = $shippingData->where('shipping_details.quote_status', 0);
            $shippingData = $shippingData->paginate(10);
           $msg["pagess"]=$shippingData;
            //('carrier_id',$userId)->where line no 54
            $info = $shippingData->toArray();
            $msg['page'] = $info;
            $data=array();
                    if(count($info) > 0){
                        foreach($shippingData as $detail){
                            $shippingData = DB::table($detail->table_name)->select('delivery_title','item_image')->where('shipping_id',$detail->id)->first();
                            $shippingQuote = ShippingQuote::where('shipping_id',$detail->id)->select('quote_price')->first();
                            $miid = ShippingQuote::where('shipping_id',$detail->id)->select((DB::raw('min(quote_price) as minimumBid')))->first();
                            
                            $image = explode(',',$shippingData->item_image);

                            $data[$i]['shippingId'] = $detail['id'];
                            $data[$i]['image'] = $image[0];
                            $data[$i]['title'] = $shippingData->delivery_title;
                            $data[$i]['categoryName'] = $detail->category_name;
                            $data[$i]['pickupAddress'] = $detail['pickup_address'];
                            $data[$i]['deliveryAddress'] = $detail['delivery_address'];
                            $data[$i]['minimumBid'] = ($miid) ? $miid->minimumBid : '0'; 
                            $data[$i]['partnerQuote'] = ($shippingQuote) ? $shippingQuote->quote_price : '';
                            $data[$i]['distance'] = ShippingDetail::distance($detail['pickupLat'],$detail['pickupLong'],$detail['deliveryLat'],$detail['deliveryLong'], "K"). ' km'; 
                            $data[$i]['postingDate'] = date('d-m-Y', strtotime($detail['created_at'])); 
                            $data[$i]['pickupDate'] = date('d-m-Y', strtotime($detail['pickup_date']));
                            $data[$i]['deliveryDate'] = date('d-m-Y', strtotime($detail['delivery_date']));
                            $i++;
                        }
                        
                    }
            $msg['diliveries'] = $data; 
            $msg["categories"] = VehicleCategory::where('status',1)->where('parent_id',0)->select('id','category_name','category_image')->get();
           
            return view('user.finddeliveries',$msg);
       
    }
    public function getsubcategory(Request $request){
        
       $data["subcategory"]  = DB::table('vehicle_categories')
                    ->where('parent_id',$request->id)
                    ->select('category_name','id')
                    ->get();
      echo json_encode($data);
    }
    
    public function delivery_details($shippingId){
        
                    $shippingData = ShippingDetail::select('shipping_details.id','shipping_details.order_id', 'table_name', 'u.id as userId', 'u.first_name', 'u.last_name' , 'shipping_details.created_at', 'spd.pickup_address', 'spd.pickup_date',  'sdd.delivery_date', 'spd.latitude as pickupLat', 'spd.longitutde as pickupLong', 'sdd.delivery_address', 'sdd.latitude as deliveryLat', 'sdd.longitutde as deliveryLong', 'vc.category_name as category', 'vsc.category_name as subCategory','pm.method as payment_method')
                                    ->leftJoin('users as u','u.id','=','shipping_details.user_id')                            
                                    ->leftJoin('shipping_pickup_details as spd','spd.shipping_id','=','shipping_details.id')                            
                                    ->leftJoin('shipping_delivery_details as sdd','sdd.shipping_id','=','shipping_details.id')
                                    ->leftJoin('vehicle_categories as vc','vc.id','=','shipping_details.category_id')
                                    ->leftJoin('vehicle_categories as vsc','vsc.id','=','shipping_details.subcategory_id')
                                    ->leftJoin('payment_methods as pm','pm.id','=','shipping_details.payment_method_id')
                                    ->where('shipping_details.id', $shippingId)->first(); 
                    
                    $data['userId'] = $shippingData->userId;
                    $data['userName'] = $shippingData->first_name. ' '.$shippingData->last_name;
                    $data['publishDate'] = date('d-m-Y', strtotime($shippingData->created_at)); 
                    $data['expireDate'] = date('d-m-Y', strtotime($shippingData->pickup_date));
                    $data['pickupDate'] = date('d-m-Y', strtotime($shippingData->pickup_date));
                    $data['deliveryDate'] = date('d-m-Y', strtotime($shippingData->delivery_date));
                    $data['pickupAddress'] = $shippingData->pickup_address;
                    $data['deliveryAddress'] = $shippingData->delivery_address;
                    $data['category'] = $shippingData->category;
                    $data['subCategory'] = $shippingData->subCategory;
                    $data['payment_method'] = $shippingData->payment_method;
                    $data['order_id'] = $shippingData->order_id;
                    $data['distance'] = ShippingDetail::distance($shippingData->pickupLat, $shippingData->pickupLong, $shippingData->deliveryLat, $shippingData->deliveryLong, "K"). ' km'; 
                    
                    $shippingDetail = DB::table($shippingData->table_name)->where('shipping_id',$shippingId)->first();
                     if($shippingData->table_name == 'shipment_listing_homes'){
                         $data1['residenceType'] = $shippingDetail->residence_type;
                         $data1['no_ofRooms'] = $shippingDetail->no_of_room;
                         $data1['deliveryTitle'] = $shippingDetail->delivery_title;
                         $data1['dining_room'] = (empty($shippingDetail->dining_room)) ? 'N/A' : ShippingDetail::getDineInData($shippingDetail->dining_room, 'admin_dinning_rooms');
                         $data1['living_room'] = (empty($shippingDetail->living_room)) ? 'N/A' : ShippingDetail::getDineInData($shippingDetail->living_room, 'admin_living_rooms');
                         $data1['kitchen'] = (empty($shippingDetail->kitchen)) ? 'N/A' : ShippingDetail::getDineInData($shippingDetail->kitchen, 'admin_kitchens');
                         $data1['home_office'] = (empty($shippingDetail->home_office)) ? 'N/A' : ShippingDetail::getDineInData($shippingDetail->home_office, 'admin_home_offices');
                         $data1['garage'] = (empty($shippingDetail->garage)) ? 'N/A' : ShippingDetail::getDineInData($shippingDetail->garage, 'admin_garages');
                         $data1['living_room'] = (empty($shippingDetail->outdoor)) ? 'N/A' : ShippingDetail::getDineInData($shippingDetail->outdoor, 'admin_outdoors');
                         $data1['miscellaneous'] = (empty($shippingDetail->miscellaneous)) ? 'N/A' : ShippingDetail::getDineInData($shippingDetail->miscellaneous, 'admin_miscellaneouses');
                         $data1['itemImage'] = $shippingDetail->item_image;
                         $data1['additionalDetail'] = $shippingDetail->item_detail;
                     }
                     else if($shippingData->table_name == 'shipment_listing_offices'){
                         $data1['collectionFloor'] = $shippingDetail->collection_floor;
                         $data1['deliveryFloor'] = $shippingDetail->delivery_floor;
                         $data1['deliveryTitle'] = $shippingDetail->delivery_title;
                         $data1['liftElevator'] = ($shippingDetail->lift_elevator == 0) ? 'No' : 'Yes';
                         $data1['general'] = (empty($shippingDetail->general_shipment_inventory)) ? 'N/A' : ShippingDetail::getDineInData($shippingDetail->general_shipment_inventory, 'admin_general_shipments');
                         $data1['equipment'] = (empty($shippingDetail->equipment_shipment_inventory)) ? 'N/A' : ShippingDetail::getDineInData($shippingDetail->equipment_shipment_inventory, 'admin_equipments');
                         $data1['boxes'] = (empty($shippingDetail->boxes)) ? 'N/A' : ShippingDetail::getDineInData($shippingDetail->boxes, 'admin_boxes');
                         $data1['itemImage'] = $shippingDetail->item_image;
                         $data1['additionalDetail'] = $shippingDetail->item_detail;
                     }
                     else if($shippingData->table_name == 'shipment_listing_householdgoods' || $shippingData->table_name == 'shipment_listing_others'){                        
                         $data1['deliveryTitle'] = $shippingDetail->delivery_title;
                         $data1['itemImage'] = $shippingDetail->item_image;
                         $data1['additionalDetail'] = $shippingDetail->additional_detail;
                     }
                     else if($shippingData->table_name == 'shipment_listing_truck_bookings'){                        
                         $data1['truckType'] = (empty($shippingDetail->truck_type_id)) ? 'N/A' : ShippingDetail::getCategoryName($shippingDetail->truck_type_id, 'id', 'category_name','vehicle_categories');
                         $data1['truckLength'] = (empty($shippingDetail->truck_length_id)) ? 'N/A' : ShippingDetail::getCategoryName($shippingDetail->truck_length_id, 'id', 'truck_length','truck_lengths');
                         $data1['truckCapacity'] = (empty($shippingDetail->truck_capacity_id)) ? 'N/A' : ShippingDetail::getCategoryName($shippingDetail->truck_capacity_id, 'id', 'truck_capacity','truck_capacities');
                         $data1['material'] = (empty($shippingDetail->material_id)) ? 'N/A' : ShippingDetail::getCategoryName($shippingDetail->material_id, 'id', 'name','materials');
                         $data1['deliveryTitle'] = $shippingDetail->delivery_title;
                         $data1['itemImage'] = $shippingDetail->item_image;
                     }
                     else if($shippingData->table_name == 'shipment_listing_vehicle_shifings'){                        
                         $data1['deliveryTitle'] = $shippingDetail->delivery_title;
                         $data1['itemImage'] = $shippingDetail->item_image;
                     }
                     else if($shippingData->table_name == 'shipment_listing_materials'){      
                         $data1['material'] = (empty($shippingDetail->material_id)) ? 'N/A' : ShippingDetail::getCategoryName($shippingDetail->material_id, 'id', 'name','materials');
                         $data1['deliveryTitle'] = $shippingDetail->delivery_title;
                         $data1['itemImage'] = $shippingDetail->item_image;
                         $data1['weight'] = $shippingDetail->weight;
                         $data1['remarks'] = $shippingDetail->remarks;
                     }
                        
                        $msg['details'] = $data;  
                        $msg['detailsItem'] = $data1;  
        return view('user.finddeliveriesdetails',$msg);
    }

    
}