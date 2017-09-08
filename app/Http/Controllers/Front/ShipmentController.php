<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\FrontController;
use Session;
use Input;
use App\library\Smsapi;
use App\AdminBedroom;
use App\AdminDinningRoom;
use App\AdminGarage;
use App\AdminBox;
use App\AdminHomeOffice;
use App\AdminKitchen;
use App\AdminLivingRoom;
use App\AdminMiscellaneous;
use App\AdminOutdoor;
use App\AdminGeneralShipment;
use App\AdminEquipment;
use App\Material;
use App\ShippingDetail;
use App\ShipmentListingHome;
use App\ShipmentListingOffice;
use App\ShipmentListingOther;
use App\ShipmentListingHouseholdgood;
use App\ShipmentListingVehicleShifing;
use App\ShipmentListingMaterial;
use App\shipmentListingTruckBooking;
use App\ShippingDeliveryDetail;
use App\ShippingPickupDetail;
use App\TruckLength;
use App\TruckCapacity;
use DB;

class ShipmentController extends FrontController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    
    public function index(Request $request)
    {
       
    }
    
    public function office(Request $request){
        $general = AdminGeneralShipment::where('status','1')->select('id','name')->get();
        $equipment = AdminEquipment::where('status','1')->select('id','name')->get();
        $miscellaneous = AdminMiscellaneous::where('status','1')->select('id','name')->get();
       
        if($request->mode == 'office-details'){ 
           $result = json_encode('$request->mode');
            return $result;
        }else{  
            return View('user.shipment.office',['general'=>$general, 'equipment'=>$equipment, 'miscellaneous'=>$miscellaneous]);
        }
    }
     public function twowheeler(Request $request){
         
         if($_POST){
            
                    
            $category_id = $request->session()->get('category_id');
            $subCatId = base64_decode(urldecode($request->subcategory_id));
            $title = $request->delivery_title;
            $vehicle_model = $request->vehicle_model;
            $vehicle_color = $request->vehicle_color;
            $vehicleName = $request->vehicle_name;
            $pickupLocation=$request->pickupaddress;
            $pickupLat="0";
            $pickupLong="0";
            $pickupDate=$request->pickupdate;
            $dropLocation=$request->deliveryaddress;
            $dropLat="0";
            $dropLong="0";
            $deliveryDate=$request->deliverydate;
            $additional_detail=$request->additional_detail;
            
            $tempArr = Session::get('currentUser');
            if($tempArr["id"]!="") {
                $custid = $tempArr["id"];
            } else {
                $custid = "0";
            }
            try{
             DB::beginTransaction();
             
                $pic=Input::file('vehicle_image');
                $extension = $pic->getClientOriginalExtension(); // getting image extension
                $name = time() . rand(111, 999) . '.' . $extension; // renameing image                
                $pic->move(public_path().'/uploads/userimages/',$name);

                $shipping= new ShippingDetail;
                $shipping->user_id = $custid;
                $shipping->category_id = $category_id;
                $shipping->subcategory_id = $subCatId;
                $shipping->table_name = 'shipment_listing_vehicle_shifings';
                $shipping->status = 0;
                $shipping->save(); 
                $shippingId= $shipping->id;

                $shipmentList= new ShipmentListingVehicleShifing;
                $shipmentList->shipping_id = $shippingId;
                $shipmentList->delivery_title = trim($title);
                $shipmentList->vehicle_name = trim($vehicleName);
                $shipmentList->vehicle_colour = trim($vehicle_color);
                $shipmentList->vehicle_mode = trim($vehicle_model);
                $shipmentList->additional_detail = $additional_detail;
                $shipmentList->item_image = $name;
                $shipmentList->save();
                
                $pickupDetail = new ShippingPickupDetail;
                $pickupDetail->shipping_id = $shippingId;
                $pickupDetail->pickup_address = trim($pickupLocation);
                $pickupDetail->latitude = $pickupLat;
                $pickupDetail->longitutde = $pickupLong;
                $pickupDetail->pickup_date = trim($pickupDate);
                $pickupDetail->save();

                $deliveryDetail = new ShippingDeliveryDetail;
                $deliveryDetail->shipping_id = $shippingId;
                $deliveryDetail->delivery_address = trim($dropLocation);
                $deliveryDetail->latitude = $dropLat;
                $deliveryDetail->longitutde = $dropLong;
                $deliveryDetail->delivery_date = trim($deliveryDate);
                $deliveryDetail->save();
                
                
                DB::commit();
                $success = "1";
            }
            catch(\Exception $e){

                $success = "0";
                DB::rollback();
            }      
            if($tempArr["id"]=="" && $custid=="0") {
               $request->session()->put('shiping_id', $shippingId); 
               return redirect(url('user/login'));
            } else {
                 return redirect(url('user/getoffer'));
            }
         }else{
             
              return view('user/shipment/vehicle',['subcategory_id'=>$request->id]);
         }
    }
    
    public function getoffer(Request $request){
        
        $shiping_id=$request->session()->get('shiping_id');
        $tempArr = Session::get('currentUser');
        if($shiping_id!="") {
        $shipping = ShippingDetail::find($shiping_id); 
        $shipping->user_id = $tempArr["id"];
        $shipping->status = 1;
        $shippingSucess = $shipping->save(); 
        } else {
        DB::table('shipping_details')->where('id', $tempArr["id"])->update(['status' => 1]);
        }
        $request->session()->forget('shiping_id');
        return view('user/shipment/getoffer');
    }
    
   
}
    
