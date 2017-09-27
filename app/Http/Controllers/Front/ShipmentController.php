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
       
        if($_POST){ 
            $category_id = $request->session()->get('category_id');
            $subCatId = base64_decode(urldecode($request->subcategory_id));
            
            $title = $_POST['title'];
            $collectionFloor = $_POST['collectionFloor'];
            $deliveryFloor = $_POST['deliveryFloor'];
            $lift = $_POST['lift'];
            $generalArr = $_POST['general'];
            $equipmentArr = $_POST['equipment'];
            $boxArr = $_POST['box'];
            $imageCount = $_POST['imageCount']; 
            $generalData ="";
            $equipmentData ="";
            $miscData ="";  
            $pickupLocation=$request->pickupaddress;
            $pickupLatlong=$this->getlatlong($request->pickupaddress);
            $pickupLat =$pickupLatlong["lat"];
            $pickupLong=$pickupLatlong["long"];
            $pickupDate=$request->pickupdate;
            $dropLocation=$request->deliveryaddress;
            $dropupLatlong=$this->getlatlong($request->deliveryaddress);
            $dropLat=$dropupLatlong["lat"];
            $dropLong=$dropupLatlong["long"];
            $deliveryDate=$request->deliverydate;
            $additional_detail=$request->additonalInfo;
            $tempArr = Session::get('currentUser');
            if($tempArr["id"]!="") {
                $custid = $tempArr["id"];
            } else {
                $custid = "0";
            }
            if($generalArr){
                $i=0;
                foreach($generalArr as $gData){
                    if($gData!=0){
                        $generalData.= ($generalData == "")? $general[$i]['id'].'-'.$gData : ','.$general[$i]['id'].'-'.$gData;
                       
                    }
                     $i++;
                }
            }
           
            if($equipmentArr){
                $i=0;
                foreach($equipmentArr as $eData){
                    if($eData!=0){
                        $equipmentData.= ($equipmentData == "")? $equipment[$i]['id'].'-'.$eData : ','.$equipment[$i]['id'].'-'.$eData;
                      
                    }
                      $i++;
                }
            }
            
            if($boxArr){
                $i=0;
                foreach($boxArr as $bData){
                    if($bData!=0){
                        $miscData.= ($miscData == "")? $miscellaneous[$i]['id'].'-'.$bData : ','.$miscellaneous[$i]['id'].'-'.$bData;
                       
                    }
                     $i++;
                }
            }
          
            $officeImages = '';
            if ( Input::hasFile('image') ){
            $files = Input::file('image');
            foreach($files as $pic){
                //$pic=Input::file('image'.$i);

                $extension = $pic->getClientOriginalExtension(); // getting image extension
                $name = time() . rand(111, 999) . '.' . $extension; // renameing image                
                $pic->move(public_path().'/uploads/userimages/',$name);

                if($officeImages != ''){
                    $officeImages.= ','.$name;
                }else{
                    $officeImages = $name;
                }
            }
            }
            //try{
                // DB::beginTransaction();
                 
                $shipping= new ShippingDetail;
                $shipping->user_id = $custid;
                $shipping->category_id = $category_id;
                $shipping->subcategory_id = $subCatId;
                $shipping->table_name = 'shipment_listing_offices';
                $shipping->status = 0;
                $shipping->save(); 
                $shippingId= $shipping->id;
                
                $shipmentList= new ShipmentListingOffice;
                $shipmentList->shipping_id = $shippingId;
                $shipmentList->collection_floor = $collectionFloor;
                $shipmentList->delivery_floor = $deliveryFloor;
                $shipmentList->lift_elevator = $lift;
                $shipmentList->delivery_title = $title;
                $shipmentList->general_shipment_inventory = $generalData;
                $shipmentList->equipment_shipment_inventory = $equipmentData;
                $shipmentList->boxes = $miscData;
                $shipmentList->item_image = $officeImages;
                $shipmentList->item_detail = $additional_detail;
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
               
           //}
           // catch(\Exception $e){

                //$success = "0";
               // DB::rollback();
            //}      
            $request->session()->put('shiping_id', $shippingId); 
            if($tempArr["id"]=="" && $custid=="0") {
               $request->session()->put('check_getofferpage', "GetOfferPage"); 
               return redirect(url('user/login'));
            } else {
               
                 return redirect(url('user/getoffer'));
            }
            
            
        }else{  
            return View('user.shipment.office',['general'=>$general, 'equipment'=>$equipment, 'miscellaneous'=>$miscellaneous,'subcategory_id'=>$request->id]);
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
            $pickupLatlong=$this->getlatlong($request->pickupaddress);
            $pickupLat =$pickupLatlong["lat"];
            $pickupLong=$pickupLatlong["long"];
            $pickupDate=$request->pickupdate;
            $dropLocation=$request->deliveryaddress;
            $dropupLatlong=$this->getlatlong($request->deliveryaddress);
            $dropLat=$dropupLatlong["lat"];
            $dropLong=$dropupLatlong["long"];
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
            $request->session()->put('shiping_id', $shippingId); 
            if($tempArr["id"]=="" && $custid=="0") {
               $request->session()->put('check_getofferpage', "GetOfferPage"); 
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
        if($shiping_id!="") {
        return view('user/shipment/getoffer');
        } else {
            return redirect(url('/')); 
        }
    }
      public function getofferprocess(Request $request){
          
        $shiping_id=$request->session()->get('shiping_id');
        $tempArr = Session::get('currentUser');
        if($shiping_id) {
        $shipping = ShippingDetail::find($shiping_id); 
        $shipping->user_id = $tempArr["id"];
        $shipping->status = 1;
        $shippingSucess = $shipping->save(); 
        
        $request->session()->forget('shiping_id');
        $request->session()->forget('check_getofferpage');
        Session::flash('success', 'Data Post successfully');
        } else {
             Session::flash('success', 'Error occur ! Please try again.');
        }
        return view('user/shipment/thankyou');
      }
      
      
      public function partload(Request $request){
         
         
         if($_POST){
            
                    
            $category_id = $request->session()->get('category_id');
            $title = $request->delivery_title;
            $materialId = $request->material_id;
            $weight = $request->weight;
            $pickupLocation=$request->pickupaddress;
            $pickupLatlong=$this->getlatlong($request->pickupaddress);
            $pickupLat =$pickupLatlong["lat"];
            $pickupLong=$pickupLatlong["long"];
            $pickupDate=$request->pickupdate;
            $dropLocation=$request->deliveryaddress;
            $dropupLatlong=$this->getlatlong($request->deliveryaddress);
            $dropLat=$dropupLatlong["lat"];
            $dropLong=$dropupLatlong["long"];
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
             
                

                $shipping= new ShippingDetail;
                $shipping->user_id = $custid;
                $shipping->category_id = $category_id;
                $shipping->subcategory_id = 0;
                $shipping->table_name = 'shipment_listing_materials';
                $shipping->status = 0;
                $shipping->save(); 
                $shippingId= $shipping->id;

                $shipmentList= new ShipmentListingMaterial;
                $shipmentList->shipping_id = $shippingId;
                $shipmentList->material_id = $materialId;
                $shipmentList->delivery_title = $title;
                $shipmentList->weight = $weight;
                $shipmentList->item_image = 'NA';
                $shipmentList->remarks = $additional_detail;
                //print_r($shipmentList);die;
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
            $request->session()->put('shiping_id', $shippingId); 
            if($tempArr["id"]=="" && $custid=="0") {
               $request->session()->put('check_getofferpage', "GetOfferPage"); 
               return redirect(url('user/login'));
            } else {
                 return redirect(url('user/getoffer'));
            }
         }else{
             
              return view('subCategory/4');
         }
    }
    
    public function householdgoods(Request $request){
        //print_r($_POST);die;
        if($_POST){
            
                    
            $category_id = $request->session()->get('category_id');
            $title = $request->delivery_title;
            $materialId = $request->material_id;
            $weight = $request->weight;
            $pickupLocation=$request->pickupaddress;
            $pickupLatlong=$this->getlatlong($request->pickupaddress);
            $pickupLat =$pickupLatlong["lat"];
            $pickupLong=$pickupLatlong["long"];
            $pickupDate=$request->pickupdate;
            $dropLocation=$request->deliveryaddress;
            $dropupLatlong=$this->getlatlong($request->deliveryaddress);
            $dropLat=$dropupLatlong["lat"];
            $dropLong=$dropupLatlong["long"];
            $deliveryDate=$request->deliverydate;
            $additional_detail=$request->additional_detail;
           
            $tempArr = Session::get('currentUser');
            if($tempArr["id"]!="") {
                $custid = $tempArr["id"];
            } else {
                $custid = "0";
            }
            $otherImages = '';
            if ( Input::hasFile('image') ){
            $files = Input::file('image');
            foreach($files as $pic){
                //$pic=Input::file('image'.$i);

                $extension = $pic->getClientOriginalExtension(); // getting image extension
                $name = time() . rand(111, 999) . '.' . $extension; // renameing image                
                $pic->move(public_path().'/uploads/userimages/',$name);

                if($otherImages != ''){
                    $otherImages.= ','.$name;
                }else{
                    $otherImages = $name;
                }
            }
            }
            $add_details="";
            for($i=0;$i<count($additional_detail);$i++){
                
                $add_details.=$additional_detail[$i].',';
            }
            $add_details= rtrim($add_details,",");
            try{
             DB::beginTransaction();
             
                

                $shipping= new ShippingDetail;
                $shipping->user_id = $custid;
                $shipping->category_id = $category_id;
                $subCatId = base64_decode(urldecode($request->subcategory_id));
                $shipping->subcategory_id = $subCatId;
                $shipping->table_name = 'shipment_listing_householdgoods';
                $shipping->status = 0;
                $shipping->save(); 
                $shippingId= $shipping->id;

                $shipmentList= new ShipmentListingHouseholdgood;
                $shipmentList->shipping_id = $shippingId;
                $shipmentList->delivery_title = $title;
                $shipmentList->additional_detail = $add_details;
                $shipmentList->item_image = $otherImages;
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
            $request->session()->put('shiping_id', $shippingId); 
            if($tempArr["id"]=="" && $custid=="0") {
               $request->session()->put('check_getofferpage', "GetOfferPage"); 
               return redirect(url('user/login'));
            } else {
                 return redirect(url('user/getoffer'));
            }
        }else{
        return view('user/shipment/householdgoods',['subcategory_id'=>$request->id]);
       }
    }
    public function others(Request $request){
        //print_r($_POST);die;
        if($_POST){
            
                    
            $category_id = $request->session()->get('category_id');
            $title = $request->delivery_title;
            $materialId = $request->material_id;
            $weight = $request->weight;
            $pickupLocation=$request->pickupaddress;
            $pickupLatlong=$this->getlatlong($request->pickupaddress);
            $pickupLat =$pickupLatlong["lat"];
            $pickupLong=$pickupLatlong["long"];
           
            $pickupDate=$request->pickupdate;
            $dropLocation=$request->deliveryaddress;
            $dropupLatlong=$this->getlatlong($request->deliveryaddress);
            $dropLat=$dropupLatlong["lat"];
            $dropLong=$dropupLatlong["long"];
            $deliveryDate=$request->deliverydate;
            $additional_detail=$request->additional_detail;
           
            $tempArr = Session::get('currentUser');
            if($tempArr["id"]!="") {
                $custid = $tempArr["id"];
            } else {
                $custid = "0";
            }
            $otherImages = '';
            if ( Input::hasFile('image') ){
            $files = Input::file('image');
            foreach($files as $pic){
                //$pic=Input::file('image'.$i);

                $extension = $pic->getClientOriginalExtension(); // getting image extension
                $name = time() . rand(111, 999) . '.' . $extension; // renameing image                
                $pic->move(public_path().'/uploads/userimages/',$name);

                if($otherImages != ''){
                    $otherImages.= ','.$name;
                }else{
                    $otherImages = $name;
                }
            }
            }
            
          
            try{
             DB::beginTransaction();
             
                

                $shipping= new ShippingDetail;
                $shipping->user_id = $custid;
                $shipping->category_id = $category_id;
                $subCatId = base64_decode(urldecode($request->subcategory_id));
                $shipping->subcategory_id = $subCatId;
                $shipping->table_name = 'shipment_listing_others';
                $shipping->status = 0;
                $shipping->save(); 
                $shippingId= $shipping->id;

                $shipmentList= new ShipmentListingOther;
                $shipmentList->shipping_id = $shippingId;
                $shipmentList->delivery_title = $title;
                $shipmentList->additional_detail = $additional_detail;
                $shipmentList->item_image = $otherImages;
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
            $request->session()->put('shiping_id', $shippingId); 
            if($tempArr["id"]=="" && $custid=="0") {
               $request->session()->put('check_getofferpage', "GetOfferPage"); 
               return redirect(url('user/login'));
            } else {
                 return redirect(url('user/getoffer'));
            }
        }else{
        return view('user/shipment/others',['subcategory_id'=>$request->id]);
       }
    }
    public function home(Request $request){
        //print_r($_POST);die;
        $data["dinningRoom"] = AdminDinningRoom::where('status','1')->select('id','name')->get();
        $data["livingRoom"] = AdminLivingRoom::where('status','1')->select('id','name')->get();
        $data["bedRooms"] = AdminBedroom::where('status','active')->select('id','name')->get();
        $data["kitchen"] = AdminKitchen::where('status','1')->select('id','name')->get();
        $data["homeOffice"] = AdminHomeOffice::where('status','1')->select('id','name')->get();
        $data["garage"] = AdminGarage::where('status','1')->select('id','name')->get();
        $data["outdoors"] = AdminOutdoor::where('status','1')->select('id','name')->get();
        $data["boxes"] = AdminBox::where('status','1')->select('id','name')->get();
        $data["miscellaneous"] = AdminMiscellaneous::where('status','1')->select('id','name')->get();
        $data["subcategory_id"] = $request->id;
        if($_POST){
            
                    
            $category_id = $request->session()->get('category_id');
            $title = $request->title;
            $residenceType = $request->residenceType;
            $no_of_room = $request->no_of_room;
            $pickupLocation=$request->pickupaddress;
            $pickupLatlong=$this->getlatlong($request->pickupaddress);
            $pickupLat =$pickupLatlong["lat"];
            $pickupLong=$pickupLatlong["long"];
            
            $pickupDate=$request->pickupdate;
            $dropLocation=$request->deliveryaddress;
            $dropupLatlong=$this->getlatlong($request->deliveryaddress);
            $dropLat=$dropupLatlong["lat"];
            $dropLong=$dropupLatlong["long"];
            $deliveryDate=$request->deliverydate;
            $additional_detail=$request->additonalInfo;
           
            $tempArr = Session::get('currentUser');
            if($tempArr["id"]!="") {
                $custid = $tempArr["id"];
            } else {
                $custid = "0";
            }
            $otherImages = '';
            if ( Input::hasFile('image') ){
            $files = Input::file('image');
            foreach($files as $pic){
                //$pic=Input::file('image'.$i);

                $extension = $pic->getClientOriginalExtension(); // getting image extension
                $name = time() . rand(111, 999) . '.' . $extension; // renameing image                
                $pic->move(public_path().'/uploads/userimages/',$name);

                if($otherImages != ''){
                    $otherImages.= ','.$name;
                }else{
                    $otherImages = $name;
                }
            }
            }
            
            //dinning
            $dinningData="";
            $livingdata="";
            $bedroomgdata="";
            $kitchen="";
            $home="";
            $garage="";
            $outdoor="";
            $box="";
            $misc="";
            $diningArr = $request->dinning;
            $livingArr = $request->living;
            $bedroomsArr = $request->bedrooms;
            $kitchenArr = $request->kitchen;
            $homeOfficeArr = $request->homeOffice;
            $garageArr = $request->garage;
            $outdoorsArr = $request->outdoors;
            $boxesArr = $request->boxes;
            $miscellaneousArr = $request->miscellaneous;
             if($diningArr){
                $i=0;
                foreach($diningArr as $gData){
                    if($gData!=0){
                        $dinningData.= ($dinningData == "")? $data["dinningRoom"][$i]['id'].'-'.$gData : ','.$data["dinningRoom"][$i]['id'].'-'.$gData;
                       
                    }
                $i++;
                }
            }
           
            if($livingArr){
                $i=0;
                foreach($livingArr as $gData){
                    if($gData!=0){
                        $livingdata.= ($livingdata == "")? $data["livingRoom"][$i]['id'].'-'.$gData : ','.$data["livingRoom"][$i]['id'].'-'.$gData;
                       
                    }
                $i++;
                }
            }
           
          if($bedroomsArr){
                $i=0;
                foreach($bedroomsArr as $gData){
                    if($gData!=0){
                        $bedroomgdata.= ($bedroomgdata == "")? $data["bedRooms"][$i]['id'].'-'.$gData : ','.$data["bedRooms"][$i]['id'].'-'.$gData;
                       
                    }
                $i++;
                }
            }
            if($kitchenArr){
                $i=0;
                foreach($kitchenArr as $gData){
                    if($gData!=0){
                        $kitchen.= ($kitchen == "")? $data["kitchen"][$i]['id'].'-'.$gData : ','.$data["kitchen"][$i]['id'].'-'.$gData;
                       
                    }
                $i++;
                }
            }
            if($homeOfficeArr){
                $i=0;
                foreach($homeOfficeArr as $gData){
                    if($gData!=0){
                        $home.= ($home == "")? $data["homeOffice"][$i]['id'].'-'.$gData : ','.$data["homeOffice"][$i]['id'].'-'.$gData;
                       
                    }
                $i++;
                }
            }
            if($garageArr){
                $i=0;
                foreach($garageArr as $gData){
                    if($gData!=0){
                        $garage.= ($garage == "")? $data["garage"][$i]['id'].'-'.$gData : ','.$data["garage"][$i]['id'].'-'.$gData;
                       
                    }
                $i++;
                }
            }
            if($outdoorsArr){
                $i=0;
                foreach($outdoorsArr as $gData){
                    if($gData!=0){
                        $outdoor.= ($outdoor == "")? $data["outdoors"][$i]['id'].'-'.$gData : ','.$data["outdoors"][$i]['id'].'-'.$gData;
                       
                    }
                $i++;
                }
            }
             
            if($boxesArr){
                $i=0;
                foreach($boxesArr as $gData){
                    if($gData!=0){
                        $box.= ($box == "")? $data["boxes"][$i]['id'].'-'.$gData : ','.$data["boxes"][$i]['id'].'-'.$gData;
                       
                    }
                $i++;
                }
            }
            if($miscellaneousArr){
                $i=0;
                foreach($miscellaneousArr as $gData){
                    if($gData!=0){
                        $misc.= ($misc == "")? $data["miscellaneous"][$i]['id'].'-'.$gData : ','.$data["miscellaneous"][$i]['id'].'-'.$gData;
                       
                    }
                $i++;
                }
            }
          
            try{
             DB::beginTransaction();
             
                

                $shipping= new ShippingDetail;
                $shipping->user_id = $custid;
                $shipping->category_id = $category_id;
                $subCatId = base64_decode(urldecode($request->subcategory_id));
                $shipping->subcategory_id = $subCatId;
                $shipping->table_name = 'shipment_listing_homes';
                $shipping->status = 0;
                $shipping->save(); 
                $shippingId= $shipping->id;

                $shipmentList= new ShipmentListingHome;
                $shipmentList->shipping_id = $shippingId;
                $shipmentList->residence_type = $residenceType;
                $shipmentList->no_of_room = $no_of_room;
                $shipmentList->collection_story = '';
                $shipmentList->delivery_story = '';
                $shipmentList->delivery_title = $title;
                $shipmentList->dining_room = $dinningData;
                $shipmentList->living_room = $livingdata;
                $shipmentList->bedroom = $bedroomgdata;
                $shipmentList->kitchen = $kitchen;
                $shipmentList->home_office = $home;
                $shipmentList->garage = $garage;
                $shipmentList->outdoor = $outdoor;
                $shipmentList->miscellaneous = $misc;
                $shipmentList->boxes = $box;
                $shipmentList->item_image = $otherImages;
                $shipmentList->item_detail = $additional_detail;
               
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
            $request->session()->put('shiping_id', $shippingId); 
            if($tempArr["id"]=="" && $custid=="0") {
               $request->session()->put('check_getofferpage', "GetOfferPage"); 
               return redirect(url('user/login'));
            } else {
                 return redirect(url('user/getoffer'));
            }
        }else{
        return view('user/shipment/home',$data);
        } 
    }
    
    public function truckbooking(Request $request){
        // print_r($_POST);die;
         
         if($_POST){
            
                    
            $category_id = $request->session()->get('category_id');
            $title = $request->delivery_title;
            $id=$request->id;
            $truckTypeId=$request->trucktypeid;
            $len="length".$id;
            $cap="capacity".$id;
            $truckLengthId=$request->$len;
            $truckCapacityId=$request->$cap;
            
            
            
            $pickupLocation=$request->pickupaddress;
            $pickupLatlong=$this->getlatlong($request->pickupaddress);
            $pickupLat =$pickupLatlong["lat"];
            $pickupLong=$pickupLatlong["long"];
            $pickupDate=$request->pickupdate;
            $dropLocation=$request->deliveryaddress;
            $dropupLatlong=$this->getlatlong($request->deliveryaddress);
            $dropLat=$dropupLatlong["lat"];
            $dropLong=$dropupLatlong["long"];
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
             
                

                $shipping= new ShippingDetail;
                $shipping->user_id = $custid;
                $shipping->category_id = $category_id;
                $shipping->subcategory_id = $truckTypeId;
                $shipping->table_name = 'shipment_listing_truck_bookings';
                $shipping->status = 0;
                $shipping->save(); 
                $shippingId= $shipping->id;

                $shipmentList= new shipmentListingTruckBooking;
                $shipmentList->shipping_id = $shippingId;
                $shipmentList->truck_type_id = $truckTypeId;
                $shipmentList->truck_length_id = $truckLengthId;
                $shipmentList->truck_capacity_id = $truckCapacityId;
                //$shipmentList->material_id = $materialId;
                $shipmentList->delivery_title = $title;
                $shipmentList->item_image = '';
                $shipmentList->remarks = $additional_detail;
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
            $request->session()->put('shiping_id', $shippingId); 
            if($tempArr["id"]=="" && $custid=="0") {
               $request->session()->put('check_getofferpage', "GetOfferPage"); 
               return redirect(url('user/login'));
            } else {
                 return redirect(url('user/getoffer'));
            }
         }else{
             
              return view('subCategory/4');
         }
    }
    public function getlatlong($address){
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
            $lat = $response_a->results[0]->geometry->location->lat;
            $long = $response_a->results[0]->geometry->location->lng;
        } else {
            $lat=0;
            $long=0;

        }
        return array("lat"=>$lat,"long"=>$long);
    }
}
    
