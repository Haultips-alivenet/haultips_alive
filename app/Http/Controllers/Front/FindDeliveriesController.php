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

    
}