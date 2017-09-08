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
        
        return view('user/shipment/vehicle');
    }
    
    public function fourwheeler(Request $request){
        
        return view('user/shipment/vehicle');
    }
}
    
