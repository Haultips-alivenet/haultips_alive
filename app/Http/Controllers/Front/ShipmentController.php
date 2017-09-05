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
    
    public function homeCategory(){

        try {
            $category_id = $_POST['catId'];
            $custid = $_POST['userId'];
            $subCatId = $_POST['subCatId'];
            $title = $_POST['itemTitle'];
            $preShippingId = $_POST['shippingId'];
            $imageCount = $_POST['imageCount'];
            $required = array('catId','userId','subCatId','itemTitle','imageCount');

            $error = false;
            foreach($required as $field) {
              if (empty($_POST[$field])) {
                $error = true;
                $fieldName = $field;
                break;
              }
            }

            if($error) {
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "$fieldName required.";
            }else{
                $dining = $_POST['dining'];
                $living = $_POST['living'];
                $kitchen = $_POST['kitchen'];
                $home = $_POST['homeOffice'];
                $garage = $_POST['garage'];
                $outdoor = $_POST['outdoor'];
//                $bedroom = $_POST['bedroom'];
//                $box = $_POST['box'];
                $misc = $_POST['misc'];
                $homeImages = '';

                 for($i=1;$i<=$imageCount;$i++){
                    $pic=Input::file('image'.$i);

                    $extension = $pic->getClientOriginalExtension(); // getting image extension
                    $name = time() . rand(111, 999) . '.' . $extension; // renameing image                
                    $pic->move(public_path().'/uploads/userimages/',$name);
                    
                    if($homeImages != ''){
                        $homeImages.= ','.$name;
                    }else{
                        $homeImages = $name;
                    }
                }

                if ($preShippingId=='') {                    

                    $shipping= new ShippingDetail;
                    $shipping->user_id = $custid;
                    $shipping->category_id = $category_id;
                    $shipping->subcategory_id = $subCatId;
                    $shipping->table_name = 'shipment_listing_homes';
                    $shipping->status = 0;
                    $shipping->save(); 
                    $shippingId= $shipping->id;
                    
                    $shipmentList= new ShipmentListingHome;
                    $shipmentList->shipping_id = $shippingId;
                    $shipmentList->residence_type = $_POST['residenceType'];
                    $shipmentList->no_of_room = $_POST['no_of_room'];
                    $shipmentList->collection_story = '';
                    $shipmentList->delivery_story = '';
                    $shipmentList->delivery_title = $title;
                    $shipmentList->dining_room = $dining;
                    $shipmentList->living_room = $living;
                    //$shipmentList->bedroom = $bedroom;
                    $shipmentList->kitchen = $kitchen;
                    $shipmentList->home_office = $home;
                    $shipmentList->garage = $garage;
                    $shipmentList->outdoor = $outdoor;
                    $shipmentList->miscellaneous = $misc;
                    //$shipmentList->boxes = $box;
                    $shipmentList->item_image = $homeImages;
                    $shipmentList->item_detail = $_POST['additionalDetail'];
                    $shipmentList->save(); 
                    
                }

                else{
                   ShipmentListingHome::where('shipping_id ',$preShippingId)
                                       ->update([
                                            'residence_type'=>$_POST['residenceType'],
                                            'no_of_room'=>$_POST['no_of_room'],
                                            'collection_story'=>'',
                                            'delivery_story'=>'',                                           
                                            'delivery_title' => $title,
                                            'dining_room' => $dining,
                                            'living_room' => $living,
                                            //'bedroom' => $bedroom,
                                            'kitchen' => $kitchen,
                                            'home_office' => $home,
                                            'garage' => $garage,
                                            'outdoor' => $outdoor,
                                            'miscellaneous' => $misc,
                                            //'boxes' => $box,
                                            'item_image' => $homeImages,
                                            'item_detail' => $_POST['additionalDetail']
                                        ]);         
                   $shippingId = $preShippingId;
                }

                $msg['responseCode'] = "200";
                $msg['responseMessage'] = "Data successfully Saved.";
                $msg['shippingId'] = $shippingId;
            }
        }

        catch (\Exception $e){

            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "Some Error Occur";
            $msg['technicalError'] = $e->getMessage();
        }

        finally {
            $result = json_encode($msg);
            echo $result;
        }
    }
    
    public function officeCategory(){
        try {
            $category_id = $_POST['catId'];
            $custid = $_POST['userId'];
            $subCatId = $_POST['subCatId'];
            $title = $_POST['deliveryTitle'];
            $preShipId = $_POST['shippingId'];
            $imageCount = $_POST['imageCount'];
            $required = array('catId','userId','subCatId','deliveryTitle','imageCount');

            $error = false;
            foreach($required as $field) {
              if (empty($_POST[$field])) {
                $error = true;
                $fieldName = $field;
                break;
              }
            }

            if($error) {
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "$fieldName required.";
            }else{

                $general = $_POST['general'];
                $equipment = $_POST['equipment'];
                $box = $_POST['box'];
                $officeImages = '';
                
                for($i=1;$i<=$imageCount;$i++){
                    $pic=Input::file('image'.$i);

                    $extension = $pic->getClientOriginalExtension(); // getting image extension
                    $name = time() . rand(111, 999) . '.' . $extension; // renameing image                
                    $pic->move(public_path().'/uploads/userimages/',$name);
                    
                    if($officeImages != ''){
                        $officeImages.= ','.$name;
                    }else{
                        $officeImages = $name;
                    }
                }

                if ($preShipId=='') {

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
                    $shipmentList->collection_floor = $_POST['collectionFloor'];
                    $shipmentList->delivery_floor = $_POST['deliveryFloor'];
                    $shipmentList->lift_elevator = $_POST['liftElevator'];
                    $shipmentList->delivery_title = $title;
                    $shipmentList->general_shipment_inventory = $general;
                    $shipmentList->equipment_shipment_inventory = $equipment;
                    $shipmentList->boxes = $box;
                    $shipmentList->item_image = $officeImages;
                    $shipmentList->save();
                }
                else{

                    ShipmentListingOffice::where('shipping_id ',$preShipId)
                                       ->update([
                                            'collection_floor'=>$_POST['collectionFloor'],
                                            'delivery_floor'=>$_POST['deliveryFloor'],
                                            'lift_elevator'=>$_POST['liftElevator'],
                                            'delivery_title'=>$title,                                           
                                            'general_shipment_inventory' => $general,
                                            'equipment_shipment_inventory' => $equipment,
                                            'boxes' => $box,
                                            'item_image' => $officeImages
                                        ]);         
                   $shippingId = $preShipId;
                }

                $msg['responseCode'] = "200";
                $msg['responseMessage'] = "Data successfully Saved";
                $msg['shippingId'] = $shippingId;
            }
        }

        catch (Exception $e){

            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "Some Error Occur";
            $msg['technicalError'] = $e->getMessage();
        }

        finally {
            $result = json_encode($msg);
            echo $result;
        }
    }
    
    public function office(){
        $general = AdminGeneralShipment::where('status','1')->select('id','name')->get();
        $equipment = AdminEquipment::where('status','1')->select('id','name')->get();
        $miscellaneous = AdminMiscellaneous::where('status','1')->select('id','name')->get();
        return View('user.shipment.office',['general'=>$general, 'equipment'=>$equipment, 'miscellaneous'=>$miscellaneous]);
    }
    
    public function houseHoldGoodsCategory(){
        try{

            $category_id = $_POST['catId'];
            $custid = $_POST['userId'];
            $subCatId = $_POST['subCatId'];
            $preShipId = $_POST['shippingId'];
            $imageCount = $_POST['imageCount'];
            $deliveryTitle = $_POST['deliveryTitle'];
            $required = array('catId','userId','subCatId','imageCount','deliveryTitle');

            $error = false;
            foreach($required as $field) {
              if (empty($_POST[$field])) {
                $error = true;
                $fieldName = $field;
                break;
              }
            }

            if($error) {
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "$fieldName required.";
            }else{
                $otherImages = '';                
                for($i=1;$i<=$imageCount;$i++){
                    $pic=Input::file('image'.$i);

                    $extension = $pic->getClientOriginalExtension(); // getting image extension
                    $name = time() . rand(111, 999) . '.' . $extension; // renameing image                
                    $pic->move(public_path().'/uploads/userimages/',$name);
                    
                    if($otherImages != ''){
                        $otherImages.= ','.$name;
                    }else{
                        $otherImages = $name;
                    }
                }
                
                if ($preShipId=='') {

                    $shipping= new ShippingDetail;
                    $shipping->user_id = $custid;
                    $shipping->category_id = $category_id;
                    $shipping->subcategory_id = $subCatId;
                    $shipping->table_name = 'shipment_listing_householdgoods';
                    $shipping->status = 0;
                    $shipping->save(); 
                    $shippingId= $shipping->id;
                    
                    $shipmentList= new ShipmentListingHouseholdgood;
                    $shipmentList->shipping_id = $shippingId;
                    $shipmentList->delivery_title = $deliveryTitle;
                    $shipmentList->additional_detail = $_POST['additionalDetail'];
                    $shipmentList->item_image = $otherImages;
                    $shipmentList->save();
                }
                else{

                    ShipmentListingHouseholdgood::where('shipping_id ',$preShipId)
                                       ->update([
                                            'additional_detail'=>$_POST['additionalDetail'],
                                            'item_image'=>$otherImages                                            
                                        ]);         
                   $shippingId = $preShipId;
                }

                $msg['responseCode'] = "200";
                $msg['responseMessage'] = "Data successfully Saved";
                $msg['shippingId'] = $shippingId;
            }
        }

        catch (Exception $e){

            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "Some Error Occur";
            $msg['technicalError'] = $e->getMessage();
        }

        finally {
            $result = json_encode($msg);
            echo $result;
        }
    }
    
    public function vehicleShifting(){
        try{

            $category_id = $_POST['catId'];
            $custid = $_POST['userId'];
            $subCatId = $_POST['subCatId'];
            $title = $_POST['deliveryTitle'];
            $vehicleName = $_POST['vehicleName'];
            $preShipId = $_POST['shippingId'];
            $imageCount = $_POST['imageCount'];
            $required = array('catId','userId','subCatId','deliveryTitle','vehicleName','imageCount');

            $error = false;
            foreach($required as $field) {
              if (empty($_POST[$field])) {
                $error = true;
                $fieldName = $field;
                break;
              }
            }

            if($error) {
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "$fieldName required.";
            }else{
                $otherImages = '';                
                for($i=1;$i<=$imageCount;$i++){
                    $pic=Input::file('image'.$i);

                    $extension = $pic->getClientOriginalExtension(); // getting image extension
                    $name = time() . rand(111, 999) . '.' . $extension; // renameing image                
                    $pic->move(public_path().'/uploads/userimages/',$name);
                    
                    if($otherImages != ''){
                        $otherImages.= ','.$name;
                    }else{
                        $otherImages = $name;
                    }
                }
                
                if ($preShipId=='') {

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
                    $shipmentList->delivery_title = $title;
                    $shipmentList->vehicle_name = $vehicleName;
                    $shipmentList->item_image = $otherImages;
                    $shipmentList->save();
                }
                else{

                    ShipmentListingVehicleShifing::where('shipping_id ',$preShipId)
                                       ->update([
                                            'delivery_title'=>$title,
                                            'vehicle_name'=>$vehicleName,
                                            'item_image'=>$otherImages                                            
                                        ]);         
                   $shippingId = $preShipId;
                }

                $msg['responseCode'] = "200";
                $msg['responseMessage'] = "Data successfully Saved";
                $msg['shippingId'] = $shippingId;
            }
        }

        catch (Exception $e){

            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "Some Error Occur";
            $msg['technicalError'] = $e->getMessage();
        }

        finally {
            $result = json_encode($msg);
            echo $result;
        }
    }
    
    public function partLoad(){
        try{
            $msg = array();
            $category_id = $_POST['catId'];
            $custid = $_POST['userId'];
            $weight = $_POST['weight'];
            $remark = $_POST['remark'];
            $preShipId = $_POST['shippingId'];
            $materialId = $_POST['materialId'];
            $deliveryTitle = $_POST['deliveryTitle'];
            $required = array('catId','userId','weight','materialId','deliveryTitle');

            $error = false;
            foreach($required as $field) {
              if (empty($_POST[$field])) {
                $error = true;
                $fieldName = $field;
                break;
              }
            }

            if($error) {
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "$fieldName required.";
            }else{
                if ($preShipId=='') {

                    $shipping= new ShippingDetail;
                    $shipping->user_id = $custid;
                    $shipping->category_id = $category_id;
                    $shipping->table_name = 'shipment_listing_materials';
                    $shipping->status = 0;
                    $shipping->save(); 
                    $shippingId= $shipping->id;
                    
                    $shipmentList= new ShipmentListingMaterial;
                    $shipmentList->shipping_id = $shippingId;
                    $shipmentList->material_id = $materialId;
                    $shipmentList->delivery_title = $deliveryTitle;
                    $shipmentList->weight = $weight;
                    $shipmentList->item_image = '';
                    $shipmentList->remarks = $remark;
                    $shipmentList->save();
                }
                else{

                    ShipmentListingMaterial::where('shipping_id ',$preShipId)
                                       ->update([
                                            'material_id'=>$materialId,
                                            'weight'=>$weight,
                                            'remarks'=>$remark                                            
                                        ]);         
                   $shippingId = $preShipId;
                }

                $msg['responseCode'] = "200";
                $msg['responseMessage'] = "Data successfully Saved";
                $msg['shippingId'] = $shippingId;
            }
        }

        catch (Exception $e){

            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "Some Error Occur";
            $msg['technicalError'] = $e->getMessage();
        }

        finally {
            $result = json_encode($msg);
            echo $result;
        }
    }
    
    public function truckBooking(){
        try{
            $msg = array();
            $category_id = $_POST['catId'];
            $custid = $_POST['userId'];
            $subCatId = $_POST['subCatId'];
            $preShipId = $_POST['shippingId'];
            $truckTypeId = $_POST['truckSubCatId'];
            $truckLengthId = $_POST['truckLengthId'];
            $truckCapacityId = $_POST['truckCapacityId'];
            $pickupLocation = $_POST['pickupLocation'];
            $pickupLat = $_POST['pickupLat'];
            $pickupLong = $_POST['pickupLong'];
            $dropLocation = $_POST['dropLocation'];
            $dropLat = $_POST['dropLat'];
            $dropLong = $_POST['dropLong'];
            $pickupDate = $_POST['pickupDate'];
            $deliveryDate = $_POST['deliveryDate'];
            $materialId = $_POST['materialId'];
            $deliveryTitle = $_POST['deliveryTitle'];
            $remarks = $_POST['remarks'];
            $required = array('catId','userId','subCatId','truckSubCatId','truckLengthId','truckCapacityId','pickupLocation','pickupLat','pickupLong','dropLocation','dropLat','dropLong','pickupDate','deliveryTitle','materialId');

            $error = false;
            foreach($required as $field) {
              if (empty($_POST[$field])) {
                $error = true;
                $fieldName = $field;
                break;
              }
            }

            if($error) {
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "$fieldName required.";
            }else{
                if ($preShipId=='') {

                    $shipping= new ShippingDetail;
                    $shipping->user_id = $custid;
                    $shipping->category_id = $category_id;
                    $shipping->subcategory_id = $subCatId;
                    $shipping->table_name = 'shipment_listing_truck_bookings';
                    $shipping->estimated_price = '7000';
                    $shipping->status = 0;
                    $shipping->save(); 
                    $shippingId= $shipping->id;
                    
                    $shipmentList= new shipmentListingTruckBooking;
                    $shipmentList->shipping_id = $shippingId;
                    $shipmentList->truck_type_id = $truckTypeId;
                    $shipmentList->truck_length_id = $truckLengthId;
                    $shipmentList->truck_capacity_id = $truckCapacityId;
                    $shipmentList->material_id = $materialId;
                    $shipmentList->delivery_title = $deliveryTitle;
                    $shipmentList->item_image = '';
                    $shipmentList->remarks = $remarks;
                    $shipmentList->save();
                    
                    $pickupDetail = new ShippingPickupDetail;
                    $pickupDetail->shipping_id = $shippingId;
                    $pickupDetail->pickup_address = $pickupLocation;
                    $pickupDetail->latitude = $pickupLat;
                    $pickupDetail->longitutde = $pickupLong;
                    $pickupDetail->pickup_date = $pickupDate;
                    $pickupDetail->save();
                    
                    $deliveryDetail = new ShippingDeliveryDetail;
                    $deliveryDetail->shipping_id = $shippingId;
                    $deliveryDetail->delivery_address = $dropLocation;
                    $deliveryDetail->latitude = $dropLat;
                    $deliveryDetail->longitutde = $dropLong;
                    $deliveryDetail->delivery_date = $deliveryDate;
                    $deliveryDetail->save();
                }
                else{

                    shipmentListingTruckBooking::where('shipping_id ',$preShipId)
                                       ->update([
                                            'truck_type_id'=>$truckTypeId,
                                            'truck_length_id'=>$truckLengthId,
                                            'truck_capacity_id'=>$truckCapacityId,
                                            'material_id'=>$materialId,
                                            'remarks'=>$remarks
                                        ]); 
                    
                    ShippingPickupDetail::where('shipping_id ',$preShipId)
                                       ->update([
                                            'pickup_address'=>$pickupLocation,
                                            'latitude'=>$pickupLat,
                                            'longitutde'=>$pickupLong,
                                            'pickup_date'=>$pickupDate
                                        ]); 
                    
                    ShippingDeliveryDetail::where('shipping_id ',$preShipId)
                                       ->update([
                                            'delivery_address'=>$dropLocation,
                                            'latitude'=>$dropLat,
                                            'longitutde'=>$dropLong,
                                            'delivery_date'=>$deliveryDate
                                        ]);
                    
                   $shippingId = $preShipId;
                }

                $msg['responseCode'] = "200";
                $msg['responseMessage'] = "Data successfully Saved";
                $msg['shippingId'] = $shippingId;
                $msg['estimatedPrice'] = '7000';
            }
        }
        catch (Exception $e){
            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "Some Error Occur";
            $msg['technicalError'] = $e->getMessage();
        }
        finally {
            $result = json_encode($msg);
            echo $result;
        }
    }    
    
    public function confirmAddress(){
        try{   
            $msg =array();
            $shippingId = $_POST['shippingId'];           
            $pickupLocation = $_POST['pickupLocation'];
            $pickupLat = $_POST['pickupLat'];
            $pickupLong = $_POST['pickupLong'];
            $dropLocation = $_POST['dropLocation'];
            $dropLat = $_POST['dropLat'];
            $dropLong = $_POST['dropLong'];
            $pickupDate = $_POST['pickupDate'];
            $deliveryDate = $_POST['deliveryDate'];
            $required = array('shippingId','pickupLocation','pickupLat','pickupLong','dropLocation','dropLat','dropLong','pickupDate');

            $error = false;
            foreach($required as $field) {
              if (empty($_POST[$field])) {
                $error = true;
                $fieldName = $field;
                break;
              }
            }

            if($error) {
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "$fieldName required.";
            }else{
                   $shippingPickupData = ShippingPickupDetail::where('shipping_id',$shippingId)->select('id')->first();
                 //  $shippingPickupData = $shippingPickupData->toArray();
                if ($shippingPickupData){                    
                    ShippingPickupDetail::where('shipping_id',$shippingId)
                                       ->update([
                                            'pickup_address'=>$pickupLocation,
                                            'latitude'=>$pickupLat,
                                            'longitutde'=>$pickupLong,
                                            'pickup_date'=>$pickupDate
                                        ]); 
                    
                    ShippingDeliveryDetail::where('shipping_id',$shippingId)
                                       ->update([
                                            'delivery_address'=>$dropLocation,
                                            'latitude'=>$dropLat,
                                            'longitutde'=>$dropLong,
                                            'delivery_date'=>$deliveryDate
                                        ]);
                    $addressMsg = 'Address successfully Changed.';
                }
                else{                    
                    $pickupDetail = new ShippingPickupDetail;
                    $pickupDetail->shipping_id = $shippingId;
                    $pickupDetail->pickup_address = $pickupLocation;
                    $pickupDetail->latitude = $pickupLat;
                    $pickupDetail->longitutde = $pickupLong;
                    $pickupDetail->pickup_date = $pickupDate;
                    $pickupDetail->save();
                    
                    $deliveryDetail = new ShippingDeliveryDetail;
                    $deliveryDetail->shipping_id = $shippingId;
                    $deliveryDetail->delivery_address = $dropLocation;
                    $deliveryDetail->latitude = $dropLat;
                    $deliveryDetail->longitutde = $dropLong;
                    $deliveryDetail->delivery_date = $deliveryDate;
                    $deliveryDetail->save();
                    
                    $addressMsg = 'Address successfully saved.';
                }

                $msg['responseCode'] = "200";
                $msg['responseMessage'] = $addressMsg;
            }
        }
        catch (Exception $e){
            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "Some Error Occur";
            $msg['technicalError'] = $e->getMessage();
        }
        finally {
            $result = json_encode($msg);
            echo $result;
        }
    }
    
    public function shipmentPost(){
        try{   
            $msg = array();
            $shippingId = $_POST['shippingId']; 
            
            if(empty($shippingId)) {
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "shippingId required.";
            }else{
                    $string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
                    $string_shuffled = str_shuffle($string);
                    $orderId = substr($string_shuffled, 1, 10);
                
                   ShippingDetail::where('id',$shippingId)
                                       ->update([
                                            'status'=>1,
                                            'order_id'=>$orderId
                                        ]); 
                   
                   $shippingData = ShippingDetail::where('id',$shippingId)->select('category_id')->first();
                   if($shippingData->category_id == 2 || $shippingData->category_id == 3){
                       $carrierType = array('1','3');
                   }elseif($shippingData->category_id == 1 || $shippingData->category_id == 4){
                       $carrierType = array('2','3');
                   }
                   
                   $notificationData = User::whereIn('carrier_type_id',$carrierType)->where('user_type_id',2)->select('device_token')->get();
                   foreach($notificationData as $nData){
                       $push = new PushNotification('fcm');
                        $response =  $push->setMessage([
                                'notification' => [
                                        'title'=>'Shipment Post',
                                        'body'=>'A new Shipment has arrived for delivery from Haulitps.',
                                        'sound' => 'default'
                                        ]
                                ])                            
                             ->setDevicesToken($nData->device_token)
                             ->send();
                   }
                   

                $msg['responseCode'] = "200";
                $msg['responseMessage'] = "Shipment Succesfully Post";
            }
        }
        catch (Exception $e){
            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "Some Error Occur";
            $msg['technicalError'] = $e->getMessage();
        }
        finally {
            $result = json_encode($msg);
            echo $result;
        }
    }
    
    public function myDeliveries(){
        try{
            $msg = array();
            $shippingDetails = array();
            $i = 0;
            $userId = $_POST['userId'];
            $shiipings = ShippingDetail::where('user_id',$userId)->get();
            $shipArray = $shiipings->toArray();
            if(count($shipArray) > 0){
               foreach($shiipings as $shipping){
                  $shippingId = $shipping->id; 
                  $shippingData = DB::table($shipping->table_name)->select('delivery_title','item_image')->where('shipping_id',$shippingId)->first();

                  $image = explode(',',$shippingData->item_image);
                  $status = array("Inactive","Active","Process","Complete");

                 $shippingDetails[$i]['shippingId'] = $shippingId;
                  $shippingDetails[$i]['title'] = $shippingData->delivery_title;
                  $shippingDetails[$i]['image'] = $image[0];
                  $shippingDetails[$i]['price'] = $shipping->shipping_price;
                  $shippingDetails[$i]['status'] = $status[$shipping->status];
                  $shippingDetails[$i]['postDate'] = date('d-F-Y', strtotime($shipping->created_at)); 
                  $i++;
               }

               $msg['responseCode'] = "200";
               $msg['responseMessage'] = "Shipment Details get successfully";
               $msg['shippingDetails'] = $shippingDetails; 
            }else{
               $msg['responseCode'] = "200";
               $msg['responseMessage'] = "Shipment Details get successfully";
               $msg['shippingDetails'] = "No Data foud"; 
            }
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        finally {
            $result = json_encode($msg);
            echo $result;
        }
    }
    
    public function deliveryDetail(){
        try{
            $msg = array();
            $shipmentId = $_POST['shippingId'];
            
            if(empty($shipmentId)) {
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "shippingId required.";
            }else{            
                $shipmentDetail = ShippingDetail::select('shipping_price as price','shipping_details.created_at as published', 'spd.pickup_address', 'spd.pickup_date','sdd.delivery_address', 'sdd.delivery_date', 'pm.method as paymnentType')
                            ->leftJoin('shipping_pickup_details as spd','spd.shipping_id','=','shipping_details.id')
                            ->leftJoin('shipping_delivery_details as sdd','sdd.shipping_id','=','shipping_details.id')
                            ->leftJoin('payment_methods as pm','pm.id','=','shipping_details.payment_method_id')
                            ->where('shipping_details.id', $shipmentId)->first();
                
               $msg['responseCode'] = "200";
               $msg['responseMessage'] = "Shipment Detail get successfully";
               $msg['shippingDetails'] = $shipmentDetail; 
            }                    
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        finally {
            $result = json_encode($msg);
            echo $result;
        }
    }
    
    public function quotationOffer(){
        try{
            $msg = array();
            $data = array();
            $i =0;
            $shippingId = $_POST['shippingId'];
            
            if(empty($shippingId)) {
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "shippingId required.";
            }else{            
                    $quoteDetails = ShippingQuote::select('shipping_quotes.id as quoteId','u.first_name','u.last_name', 'quote_price')
                            ->leftJoin('users as u','u.id','=','shipping_quotes.carrier_id')                            
                            ->where('shipping_quotes.shipping_id',$shippingId)->get();
                    $quoteArray = $quoteDetails->toArray();
                
                    if(count($quoteArray) > 0){
                        foreach($quoteDetails as $quotes){
                            $data[$i]['quoteId'] = $quotes['quoteId'];
                            $data[$i]['carrier'] = $quotes['first_name'].' '.$quotes['last_name'];
                            $data[$i]['price'] = $quotes['quote_price'];
                            $i++;
                        }
                        $msg['responseCode'] = "200";
                        $msg['responseMessage'] = "Quotation Details get successfully";               
                        $msg['quotationDetails'] = $data; 
                    }else{
                        $msg['responseCode'] = "200";
                        $msg['responseMessage'] = "Quotation Details get successfully";               
                        $msg['quotationDetails'] = 'No Data found'; 
                    }
                }
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        finally {
            $result = json_encode($msg);
            echo $result;
        }
    }
    
    public function notification(){
        try{
            $msg = array();
            $data = array();
            $i =0;
            $userId = $_POST['userId'];
            
            if(empty($userId)) {
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "userId required.";
            }else{            
                    $quoteDetails = ShippingDetail::select('shipping_details.id','u.first_name','u.last_name', 'sq.quote_price','sq.created_at','shipping_details.table_name')                            
                            ->leftJoin('shipping_quotes as sq','sq.shipping_id','=','shipping_details.id')
                            ->leftJoin('users as u','u.id','=','sq.carrier_id')
                            ->where('shipping_details.user_id',$userId)->get();
                    $quoteArray = $quoteDetails->toArray();
                
                    if(count($quoteArray) > 0){
                        foreach($quoteDetails as $quotes){
                            $shippingData = DB::table($quotes->table_name)->select('delivery_title')->where('shipping_id',$quotes->id)->first();
                            
                            $data[$i]['partnerName'] = $quotes['first_name'].' '.$quotes['last_name'];
                            $data[$i]['title'] = $shippingData->delivery_title;
                            $data[$i]['price'] = $quotes['quote_price'];
                            $data[$i]['time'] = date('d-F-Y h:i:s', strtotime($quotes['created_at'])); 
                            $i++;
                        }
                        $msg['responseCode'] = "200";
                        $msg['responseMessage'] = "Notifications get successfully";               
                        $msg['notifications'] = $data; 
                    }else{
                        $msg['responseCode'] = "200";
                        $msg['responseMessage'] = "Notifications get successfully";               
                        $msg['notifications'] = 'No Data found'; 
                    }
                }
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        finally {
            $result = json_encode($msg);
            echo $result;
        }
    }
    
    public function addNewAccount(){
        try{
            $msg = array();
            $userId = $_POST['userId'];
            $userName = $_POST['userName'];
            $accountNumber = $_POST['acNumber'];
            $ifscCode = $_POST['ifscCode'];            
            $required = array('userId','userName','acNumber','ifscCode');

            $error = false;
            foreach($required as $field) {
              if (empty($_POST[$field])) {
                $error = true;
                $fieldName = $field;
                break;
              }
            }

            if($error) {
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "$fieldName required.";
            }else{           
                    $data = new PayInfo;
                    $data->user_id = $userId;
                    $data->name = $userName;
                    $data->number = $accountNumber;
                    $data->code = $ifscCode;
                    $data->save();
                    
                    $msg['responseCode'] = "200";
                    $msg['responseMessage'] = "Information saved successfully";    
                }
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        finally {
            $result = json_encode($msg);
            echo $result;
        }
    }
    
    public function bankInfo(){
        try{
            $msg = array();
            $userId = $_POST['userId'];
            
            if(empty($userId)) {
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "userId required.";
            }else{           
                    $data = PayInfo::where('user_id',$userId)->select('id','name as userName', 'number as accountNumber', 'code as ifscCode')->get();                    
                    $info = $data->toArray();
                    if(count($info) > 0){
                        $msg['responseCode'] = "200";
                        $msg['responseMessage'] = "Information get successfully"; 
                        $msg['details'] = $data; 
                    }else{
                        $msg['responseCode'] = "200";
                        $msg['responseMessage'] = "Information get successfully";  
                        $msg['details'] = 'No Data found'; 
                    }                      
                }
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        finally {
            $result = json_encode($msg);
            echo $result;
        }
    }
    
    public function deleteBankInfo(){
        try{
            $msg = array();
            $bankInfoId = $_POST['bankInfoId'];
            
            if(empty($bankInfoId)) {
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "bankInfoId required.";
            }else{           
                    $data = PayInfo::where('id',$bankInfoId)->delete();                    
                    
                    if($data){
                        $msg['responseCode'] = "200";
                        $msg['responseMessage'] = "Information successfully Deleted"; 
                    }else{
                        $msg['responseCode'] = "0";
                        $msg['responseMessage'] = "Some Error occur ! Please Try again";  
                    }                      
                }
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        finally {
            $result = json_encode($msg);
            echo $result;
        }
    }
    
    public function transactionDetail(){
        try{
            $msg = array();
            $i = 0;
            $userId = $_POST['userId'];
            
            if(empty($userId)) {
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "userId required.";
            }else{           
                    $tdetails = ShippingDetail::select('shipping_details.id', 'shipping_details.order_id', 'shipping_details.table_name','pd.created_at','pd.amount','pd.status')                            
                                    ->leftJoin('payment_details as pd','pd.shipping_id','=','shipping_details.id')
                                    ->where('shipping_details.user_id',$userId)->get();                
                    $info = $tdetails->toArray();
                    if(count($info) > 0){
                        foreach($tdetails as $detail){
                            $shippingData = DB::table($detail->table_name)->select('delivery_title')->where('shipping_id',$detail->id)->first();
                            
                            $data[$i]['orderId'] = $detail['order_id'];
                            $data[$i]['title'] = $shippingData->delivery_title;
                            $data[$i]['status'] = $detail['status'];
                            $data[$i]['amount'] = $detail['amount'];
                            $data[$i]['paymentDate'] = date('d-F-Y h:i:s', strtotime($detail['created_at'])); 
                            $i++;
                        }
                        $msg['responseCode'] = "200";
                        $msg['responseMessage'] = "Information get successfully"; 
                        $msg['details'] = $data; 
                    }else{
                        $msg['responseCode'] = "200";
                        $msg['responseMessage'] = "Information get successfully";  
                        $msg['details'] = 'No Data found';
                    }                      
                }
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        finally {
            $result = json_encode($msg);
            echo $result;
        }
    }
    
    
    public function changePassword(Request $request)
    {
       try
       {
           $userId=$request->userId;
           $oldPassword=$request->currentPwd;
           $newPassword=$request->newPwd;
           $required = array('userId','currentPwd','newPwd');

            $error = false;
            foreach($required as $field) {
              if (empty($_POST[$field])) {
                $error = true;
                $fieldName = $field;
                break;
              }
            }

            if($error) {
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "$fieldName required.";
            }else{
           
           $user=User::select('password')->where('id',$userId)->first();
           if(Hash::check($oldPassword,$user->password))
           {
               User::where('id',$userId)->update(['password'=>bcrypt($newPassword)]);
               $msg['responseCode']="200";
               $msg["responseMessage"]='Password changed successfully';
           }
           else
           {
               $msg['responseCode']="0";
               $msg["responseMessage"]='Current Password is incorrect';
           }
         }
       }
       catch(Exception $e)
       {
           $msg['responseCode']="0";
           $msg["responseMessage"]='Failed,Please Try Again';
           $msg['error']=$e;
       }
       finally
       {
           return $msg;
       }
    }
    
    public function editProfile(){
        try{
            $msg = array();
            $userId = $_POST['userId'];
            $fName = $_POST['fName'];
            $lName = $_POST['lName'];
            $email = $_POST['email'];
            $street = $_POST['street'];    
            $city = $_POST['city']; 
            $location = $_POST['location']; 
            $pincode = $_POST['pincode']; 
            $country = $_POST['country']; 
            
            $required = array('userId','fName','lName','email');

            $error = false;
            foreach($required as $field) {
              if (empty($_POST[$field])) {
                $error = true;
                $fieldName = $field;
                break;
              }
            }

            if($error) {
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "$fieldName required.";
            }else{   
                
                $userDetails = UserDetail::where('user_id',$userId)->first();
                
                $pic=Input::file('image');
                if($pic){
                $extension = $pic->getClientOriginalExtension(); // getting image extension
                $userImage = time() . rand(111, 999) . '.' . $extension; // renameing image                
                $pic->move(public_path().'/uploads/user/',$userImage);
                }else{
                    $userImage = (!empty($userDetails->image))? $userDetails->image : "user_icon.png";
                }
                
                User::where('id',$userId)
                    ->update([
                        'first_name'=>$fName,
                        'last_name'=>$lName,
                        'email'=>$email
                        ]);
                
                $userDetails = UserDetail::where('user_id',$userId)->first();
               
                if($userDetails){
                    UserDetail::where('user_id',$userId)
                    ->update([
                        'image'=>$userImage,
                        'street'=>$street,
                        'location'=>$location,
                        'city'=>$city,
                        'pincode'=>$pincode,
                        'country'=>$country
                        ]);
                }else{
                    $data = new UserDetail;
                    $data->user_id = $userId;
                    $data->image = $userImage;
                    $data->street = $street;
                    $data->location = $location;
                    $data->city = $city;
                    $data->pincode = $pincode;
                    $data->country = $country;
                    $data->save();
                }
                    
                    
                $msg['responseCode'] = "200";
                $msg['responseMessage'] = "Data saved successfully";    
                }
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        finally {
            $result = json_encode($msg);
            echo $result;
        }
    }
    
    public function viewProfile(){
        try{
            $msg = array();
            $i = 0;
            $userId = $_POST['userId'];
            
            if(empty($userId)) {
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "userId required.";
            }else{           
                    $userdetails = User::select('first_name', 'last_name','email','mobile_number','ud.*')                            
                                    ->leftJoin('user_details as ud','ud.user_id','=','users.id')
                                    ->where('users.id',$userId)->first();                
                   
                    if($userdetails){
                        $msg['responseCode'] = "200";
                        $msg['responseMessage'] = "Information get successfully"; 
                        $msg['details'] = $userdetails; 
                    }else{
                        $msg['responseCode'] = "200";
                        $msg['responseMessage'] = "Information get successfully";  
                        $msg['details'] = 'No Data found';
                    }                      
                }
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        finally {
            $result = json_encode($msg);
            echo $result;
        }
    }
    
    public function userQuestions(){
        try{
            $msg = array();
            $data = array();
            $i = 0;
            $userId = $_POST['userId'];
            
            if(empty($userId)) {
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "userId required.";
            }else{           
                    $quesdetails = TblQuesMaster::select('tq.question', 'u.first_name','u.last_name','ud.image','sd.table_name','sd.id as shippingId','tq.id as quesId')                            
                                    ->leftJoin('tbl_questions as tq','tq.ques_master_id','=','tbl_ques_masters.id')
                                    ->leftJoin('users as u','u.id','=','tbl_ques_masters.carrier_id')
                                    ->leftJoin('user_details as ud','ud.user_id','=','tbl_ques_masters.carrier_id')
                                    ->leftJoin('shipping_details as sd','sd.id','=','tbl_ques_masters.shipping_id')                                   
                                    ->orderBy('tq.id', 'desc')
                                    ->groupBy('tbl_ques_masters.carrier_id')
                                    ->where('tq.status',1)
                                    ->where('tbl_ques_masters.user_id',$userId)->get();                
                   
                    $info = $quesdetails->toArray();
                    if(count($info) > 0){
                        foreach($quesdetails as $detail){
                            $shippingData = DB::table($detail->table_name)->select('delivery_title')->where('shipping_id',$detail->shippingId)->first();
                            
                            $data[$i]['quesId'] = $detail['quesId'];
                            $data[$i]['shippingId'] = $detail['shippingId'];
                            $data[$i]['title'] = $shippingData->delivery_title;
                            $data[$i]['partnerName'] = $detail['first_name'].' '.$detail['last_name'];
                            $data[$i]['partnerImage'] = $detail['image'];
                            $data[$i]['question'] = $detail['question']; 
                            $i++;
                        }
                        $msg['responseCode'] = "200";
                        $msg['responseMessage'] = "Information get successfully"; 
                        $msg['quesDetails'] = $data; 
                    }else{
                        $msg['responseCode'] = "200";
                        $msg['responseMessage'] = "Information get successfully";  
                        $msg['quesDetails'] = 'No Data found';
                    }                     
                }
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        finally {
            $result = json_encode($msg);
            echo $result;
        }
    }
    
    public function findDelivery(){
        try{
            $msg = array();
            $data = array();
            $i = 0;            
            $condition = '';
            $userId = $_POST['userId'];
            $categoryId = $_POST['categoryId'];
            $orderBy = $_POST['orderBy'];      
            
            if(empty($userId)) {
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "userId required.";
            }else{           
                    $userdetails = User::select('carrier_type_id')->where('id',$userId)->first();        
                    $userType = $userdetails->carrier_type_id; 
                    $catType = array('1','2','3','4');
                    if($userType == 1){
                        $catType = array('2','3');
                    }
                    if($userType == 2){
                        $catType = array('1','4');
                    }
                     
                    $shippingData = ShippingDetail::select(DB::raw('shipping_details.id, table_name, vc.category_name, shipping_details.created_at, spd.pickup_address, spd.pickup_date,  sdd.delivery_date, spd.latitude as pickupLat, spd.longitutde as pickupLong, sdd.delivery_address, sdd.latitude as deliveryLat, sdd.longitutde as deliveryLong'))
                                    ->leftJoin('shipping_pickup_details as spd','spd.shipping_id','=','shipping_details.id')
                                    ->leftJoin('shipping_delivery_details as sdd','sdd.shipping_id','=','shipping_details.id')
                                    ->leftJoin('vehicle_categories as vc','vc.id','=','shipping_details.category_id');
                    
                    if($categoryId != ''){                        
                        $categoryId = explode(",",$categoryId);
                        $shippingData = $shippingData->whereIn('shipping_details.category_id', $categoryId);
                    }
                    
                    if($orderBy == 'origin asc'){
                        $shippingData = $shippingData->orderBy('spd.pickup_address', 'ASC');
                    }elseif($orderBy == 'origin desc'){
                        $shippingData = $shippingData->orderBy('spd.pickup_address', 'DESC');
                    }elseif($orderBy == 'destination asc'){
                        $shippingData = $shippingData->orderBy('sdd.delivery_address', 'ASC');
                    }elseif($orderBy == 'destination desc'){
                        $shippingData = $shippingData->orderBy('sdd.delivery_address', 'DESC');
                    }
                    
                    $shippingData = $shippingData->whereIn('shipping_details.category_id', $catType);
                    $shippingData = $shippingData->where('shipping_details.status', 1);
                    $shippingData = $shippingData->where('shipping_details.quote_status', 0);
                    $shippingData = $shippingData->get();
                    
                     $info = $shippingData->toArray();
                    if(count($info) > 0){
                        foreach($shippingData as $detail){
                            $shippingData = DB::table($detail->table_name)->select('delivery_title','item_image')->where('shipping_id',$detail->id)->first();
                            $shippingQuote = ShippingQuote::where('carrier_id',$userId)->where('shipping_id',$detail->id)->select('quote_price')->first();
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
                        $msg['responseCode'] = "200";
                        $msg['responseMessage'] = "Information get successfully"; 
                        $msg['diliveries'] = $data; 
                    }else{
                        $msg['responseCode'] = "200";
                        $msg['responseMessage'] = "Information get successfully";  
                        $msg['details'] = 'No Data found';
                    }                     
                }
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        finally {
            $result = json_encode($msg);
            echo $result;
        }
    }
    
    public function findDetail(){
        try{
            $msg = array();
            $data = array();
            $i = 0;
            
            $shippingId = $_POST['shippingId'];
            
            if(empty($shippingId)) {
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "shippingId required.";
            }else{           
                    
                     
                    $shippingData = ShippingDetail::select('shipping_details.id', 'table_name', 'u.id as userId', 'u.first_name', 'u.last_name' , 'shipping_details.created_at', 'spd.pickup_address', 'spd.pickup_date',  'sdd.delivery_date', 'spd.latitude as pickupLat', 'spd.longitutde as pickupLong', 'sdd.delivery_address', 'sdd.latitude as deliveryLat', 'sdd.longitutde as deliveryLong', 'vc.category_name as category', 'vsc.category_name as subCategory')
                                    ->leftJoin('users as u','u.id','=','shipping_details.user_id')                            
                                    ->leftJoin('shipping_pickup_details as spd','spd.shipping_id','=','shipping_details.id')                            
                                    ->leftJoin('shipping_delivery_details as sdd','sdd.shipping_id','=','shipping_details.id')
                                    ->leftJoin('vehicle_categories as vc','vc.id','=','shipping_details.category_id')
                                    ->leftJoin('vehicle_categories as vsc','vsc.id','=','shipping_details.subcategory_id')
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
                    $data['distance'] = ShippingDetail::distance($shippingData->pickupLat, $shippingData->pickupLong, $shippingData->deliveryLat, $shippingData->deliveryLong, "K"). ' km'; 
                    
                    $shippingDetail = DB::table($shippingData->table_name)->where('shipping_id',$shippingId)->first();
                     if($shippingData->table_name == 'shipment_listing_homes'){
                         $data['residenceType'] = $shippingDetail->residence_type;
                         $data['no_ofRooms'] = $shippingDetail->no_of_room;
                         $data['deliveryTitle'] = $shippingDetail->delivery_title;
                         $data['dining_room'] = (empty($shippingDetail->dining_room)) ? 'N/A' : ShippingDetail::getDineInData($shippingDetail->dining_room, 'admin_dinning_rooms');
                         $data['living_room'] = (empty($shippingDetail->living_room)) ? 'N/A' : ShippingDetail::getDineInData($shippingDetail->living_room, 'admin_living_rooms');
                         $data['kitchen'] = (empty($shippingDetail->kitchen)) ? 'N/A' : ShippingDetail::getDineInData($shippingDetail->kitchen, 'admin_kitchens');
                         $data['home_office'] = (empty($shippingDetail->home_office)) ? 'N/A' : ShippingDetail::getDineInData($shippingDetail->home_office, 'admin_home_offices');
                         $data['garage'] = (empty($shippingDetail->garage)) ? 'N/A' : ShippingDetail::getDineInData($shippingDetail->garage, 'admin_garages');
                         $data['living_room'] = (empty($shippingDetail->outdoor)) ? 'N/A' : ShippingDetail::getDineInData($shippingDetail->outdoor, 'admin_outdoors');
                         $data['miscellaneous'] = (empty($shippingDetail->miscellaneous)) ? 'N/A' : ShippingDetail::getDineInData($shippingDetail->miscellaneous, 'admin_miscellaneouses');
                         $data['itemImage'] = $shippingDetail->item_image;
                         $data['additionalDetail'] = $shippingDetail->item_detail;
                     }
                     else if($shippingData->table_name == 'shipment_listing_offices'){
                         $data['collectionFloor'] = $shippingDetail->collection_floor;
                         $data['deliveryFloor'] = $shippingDetail->delivery_floor;
                         $data['deliveryTitle'] = $shippingDetail->delivery_title;
                         $data['liftElevator'] = ($shippingDetail->lift_elevator == 0) ? 'No' : 'Yes';
                         $data['general'] = (empty($shippingDetail->general_shipment_inventory)) ? 'N/A' : ShippingDetail::getDineInData($shippingDetail->general_shipment_inventory, 'admin_general_shipments');
                         $data['equipment'] = (empty($shippingDetail->equipment_shipment_inventory)) ? 'N/A' : ShippingDetail::getDineInData($shippingDetail->equipment_shipment_inventory, 'admin_equipments');
                         $data['boxes'] = (empty($shippingDetail->boxes)) ? 'N/A' : ShippingDetail::getDineInData($shippingDetail->boxes, 'admin_boxes');
                         $data['itemImage'] = $shippingDetail->item_image;
                         $data['additionalDetail'] = $shippingDetail->item_detail;
                     }
                     else if($shippingData->table_name == 'shipment_listing_householdgoods' || $shippingData->table_name == 'shipment_listing_others'){                        
                         $data['deliveryTitle'] = $shippingDetail->delivery_title;
                         $data['itemImage'] = $shippingDetail->item_image;
                         $data['additionalDetail'] = $shippingDetail->additional_detail;
                     }
                     else if($shippingData->table_name == 'shipment_listing_truck_bookings'){                        
                         $data['truckType'] = (empty($shippingDetail->truck_type_id)) ? 'N/A' : ShippingDetail::getCategoryName($shippingDetail->truck_type_id, 'id', 'category_name','vehicle_categories');
                         $data['truckLength'] = (empty($shippingDetail->truck_length_id)) ? 'N/A' : ShippingDetail::getCategoryName($shippingDetail->truck_length_id, 'id', 'truck_length','truck_lengths');
                         $data['truckCapacity'] = (empty($shippingDetail->truck_capacity_id)) ? 'N/A' : ShippingDetail::getCategoryName($shippingDetail->truck_capacity_id, 'id', 'truck_capacity','truck_capacities');
                         $data['material'] = (empty($shippingDetail->material_id)) ? 'N/A' : ShippingDetail::getCategoryName($shippingDetail->material_id, 'id', 'name','materials');
                         $data['deliveryTitle'] = $shippingDetail->delivery_title;
                         $data['itemImage'] = $shippingDetail->item_image;
                     }
                     else if($shippingData->table_name == 'shipment_listing_vehicle_shifings'){                        
                         $data['deliveryTitle'] = $shippingDetail->delivery_title;
                         $data['itemImage'] = $shippingDetail->item_image;
                     }
                     else if($shippingData->table_name == 'shipment_listing_materials'){      
                         $data['material'] = (empty($shippingDetail->material_id)) ? 'N/A' : ShippingDetail::getCategoryName($shippingDetail->material_id, 'id', 'name','materials');
                         $data['deliveryTitle'] = $shippingDetail->delivery_title;
                         $data['itemImage'] = $shippingDetail->item_image;
                         $data['weight'] = $shippingDetail->weight;
                         $data['remarks'] = $shippingDetail->remarks;
                     }
                        $msg['responseCode'] = "200";
                        $msg['responseMessage'] = "Information get successfully"; 
                        $msg['details'] = $data;     
                }
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        finally {
            $result = json_encode($msg);
            echo $result;
        }
    }
    
    public function categoryFilter(){
        try{
            $msg = array();
            $data = array();
            $i = 0;
            
            $userId = $_POST['userId'];
            
            if(empty($userId)){
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "userId required.";
            }else{           
                $userData = User::where('id',$userId)->select('carrier_type_id')->first();
                $userType = $userData->carrier_type_id;
                $catType = array('1','2','3','4');
                if($userType == 1){
                    $catType = array('2','3');
                }
                if($userType == 2){
                    $catType = array('1','4');
                }
                
                $categories = VehicleCategorie::where('status',1)->whereIn('id',$catType)->select('id','category_name','category_image')->get();
                $msg['responseCode'] = "200";
                $msg['responseMessage'] = "Categories get successfully";
                $msg['categories'] = $categories;     
            }
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        finally {
            $result = json_encode($msg);
            echo $result;
        }
    }
    
    public function minimumBid(){
        try{
            $msg = array();
            $data = array();
            $i = 0;
            
            $shippingId = $_POST['shippingId'];
            
            if(empty($shippingId)){
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "shippingId required.";
            }else{           
                
                $miid = ShippingQuote::where('shipping_id',$shippingId)->select((DB::raw('min(quote_price) as minimumBid')))->first();
                
                $msg['responseCode'] = "200";
                $msg['responseMessage'] = "Minimum Bid get successfully";
                $msg['minimumBid'] = ($miid->minimumBid == '') ? '0' : $miid->minimumBid;      
            }
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        finally {
            $result = json_encode($msg);
            echo $result;
        }
    }
    
    public function submitOffer(){
        try{
            $msg = array();
            $data = array();
            $i = 0;
            
            $shippingId = $_POST['shippingId'];
            $userId = $_POST['userId'];
            $quotation = $_POST['quotation'];
            $minimumBid = $_POST['minimumBid'];            
            $quotationId = $_POST['quotationId'];  
            $required = array('shippingId','userId','quotation','minimumBid');

            $error = false;
            foreach($required as $field) {
              if (empty($_POST[$field])) {
                $error = true;
                $fieldName = $field;
                break;
              }
            }

            if($error) {
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "$fieldName required.";
            }else{           
                if($quotationId == ''){
                    $quote = new ShippingQuote;
                    $quote->shipping_id = $shippingId;
                    $quote->carrier_id = $userId;
                    $quote->quote_price = $quotation;
                    $quote->lowest_quote_price = $minimumBid;
                    $quote->quote_status = 0;
                    $quote->save();
                    $quotationId= $quote->id;
                }else{
                    ShippingQuote::where('id',$quotationId)
                            ->update([
                                    'quote_price'=>$quotation,
                                    'lowest_quote_price'=>$minimumBid
                                ]);
                }
                
                $offerNotification = ShippingDetail::select('u.device_token','u.first_name','u.last_name')
                                ->leftJoin('users as u','u.id','=','shipping_details.user_id')
                                ->where('shipping_details.id',$shippingId)->first();
                
                $push = new PushNotification('fcm');
                $response =  $push->setMessage([
                        'notification' => [
                                'title'=>'Get Offer',
                                'body'=>'A new Quotation has arrived on your post from Haulitps.',
                                'sound' => 'default'
                                ]
                        ])                            
                     ->setDevicesToken($offerNotification->device_token)
                     ->send();
                
                $msg['responseCode'] = "200";
                $msg['responseMessage'] = "Quotation post successfully";
                $msg['quotationId'] = $quotationId;
            }
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        finally {
            $result = json_encode($msg);
            echo $result;
        }
    }
    
    public function myOffers(){
        try{
            $msg = array();
            $offerDetails = array();
            $i = 0;
            $userId = $_POST['userId'];
            if(empty($userId)){
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "userId required.";
            }else{
                $offerData = ShippingQuote::select('shipping_quotes.id','shipping_quotes.shipping_id','shipping_quotes.quote_status','shipping_quotes.quote_price','sd.table_name','sd.subcategory_id','sd.category_id')
                            ->leftJoin('shipping_details as sd','sd.id','=','shipping_quotes.shipping_id')
                            ->where('shipping_quotes.carrier_id', $userId)->get();
                
                
                $offerArray = $offerData->toArray();
                if(count($offerArray) > 0){
                  foreach($offerData as $offer){ 
                     $shippingId = $offer->shipping_id;
                     $shippingData = DB::table($offer->table_name)->select('delivery_title','item_image')->where('shipping_id',$shippingId)->first();

                     $image = explode(',',$shippingData->item_image);
                     $status = array("Pending","Accepted","Rejected");

                     $offerDetails[$i]['quoteId'] = $offer->id;
                     $offerDetails[$i]['shippingId'] = $shippingId;
                     $offerDetails[$i]['title'] = $shippingData->delivery_title;
                     $offerDetails[$i]['image'] = $image[0];
                     $offerDetails[$i]['category'] = (empty($offer->category_id)) ? 'N/A' : ShippingDetail::getCategoryName($offer->category_id, 'id', 'category_name','vehicle_categories');
                     $offerDetails[$i]['subcategory'] = (empty($offer->subcategory_id)) ? 'N/A' : ShippingDetail::getCategoryName($offer->subcategory_id, 'id', 'category_name','vehicle_categories');
                     $offerDetails[$i]['status'] = $status[$offer->quote_status];
                     $offerDetails[$i]['quotePrice'] = $offer->quote_price;
                     $i++;
                  }

                  $msg['responseCode'] = "200";
                  $msg['responseMessage'] = "Offer Details get successfully";
                  $msg['offers'] = $offerDetails; 
                }else{
                  $msg['responseCode'] = "200";
                  $msg['responseMessage'] = "Shipment Details get successfully";
                  $msg['offers'] = "No Data foud"; 
                }
            }
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        finally {
            $result = json_encode($msg);
            echo $result;
        }
    }
    
    public function myOfferDetail(){
        try{
            $msg = array();
            $offerDetails = array();
            $shippingId = $_POST['shippingId'];
            $quoteId = $_POST['quoteId'];
            $required = array('shippingId','quoteId');

            $error = false;
            foreach($required as $field) {
              if (empty($_POST[$field])) {
                $error = true;
                $fieldName = $field;
                break;
              }
            }

            if($error) {
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "$fieldName required.";
            }else{
                $offerData = ShippingQuote::select('shipping_quotes.shipping_id','shipping_quotes.quote_status','sd.order_id','sd.category_id','sd.subcategory_id','sd.order_id','sd.table_name','sd.created_at','u.first_name','u.last_name','u.email','spd.pickup_date','spd.pickup_address','sdd.delivery_address')
                            ->leftJoin('shipping_details as sd','sd.id','=','shipping_quotes.shipping_id')
                            ->leftJoin('users as u','u.id','=','sd.user_id')
                            ->leftJoin('shipping_pickup_details as spd','spd.shipping_id','=','shipping_quotes.shipping_id')
                            ->leftJoin('shipping_delivery_details as sdd','sdd.shipping_id','=','shipping_quotes.shipping_id')
                            ->where('shipping_quotes.id', $quoteId)->first();
                
               
                if($offerData){
                     $shippingId = $offerData->shipping_id;
                     $shippingData = DB::table($offerData->table_name)->select('delivery_title')->where('shipping_id',$shippingId)->first();

                     $status = array("Pending","Accepted","Rejected");

                     $offerDetails['customer'] = $offerData->first_name. ' '.$offerData->last_name;
                     $offerDetails['email'] = $offerData->email;
                     $offerDetails['validTill'] = date('d-m-Y', strtotime($offerData->pickup_date));
                     $offerDetails['developed'] = date('d-m-Y', strtotime($offerData->created_at));
                     $offerDetails['status'] = $status[$offerData->quote_status];
                     $offerDetails['category'] = (empty($offerData->category_id)) ? 'N/A' : ShippingDetail::getCategoryName($offerData->category_id, 'id', 'category_name','vehicle_categories');
                     $offerDetails['subcategory'] = (empty($offerData->subcategory_id)) ? 'N/A' : ShippingDetail::getCategoryName($offerData->subcategory_id, 'id', 'category_name','vehicle_categories');
                     $offerDetails['orderNo'] = $offerData->order_id;
                     $offerDetails['title'] = $shippingData->delivery_title;
                     $offerDetails['takeAway'] = $offerData->pickup_address;
                     $offerDetails['deliver'] = $offerData->delivery_address;
                    
                  $msg['responseCode'] = "200";
                  $msg['responseMessage'] = "Offer Details get successfully";
                  $msg['offers'] = $offerDetails; 
                }else{
                  $msg['responseCode'] = "200";
                  $msg['responseMessage'] = "Shipment Details get successfully";
                  $msg['offers'] = "No Data foud"; 
                }
            }
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        finally {
            $result = json_encode($msg);
            echo $result;
        }
    }
    
    public function partnerNotification(){
        try{
            $msg = array();
            $data = array();
            $i =0;
            $userId = $_POST['userId'];
            
            if(empty($userId)) {
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "userId required.";
            }else{            
                    $quoteDetails = ShippingQuote::select('sd.id','sd.table_name','shipping_quotes.quote_status','shipping_quotes.quote_price')
                                    ->leftJoin('shipping_details as sd','sd.id','=','shipping_quotes.shipping_id')
                                    ->whereIn('shipping_quotes.quote_status',array('1','2'))
                                    ->where('shipping_quotes.carrier_id',$userId)->get();
                    $quoteArray = $quoteDetails->toArray();
                
                    if(count($quoteArray) > 0){
                        foreach($quoteDetails as $quotes){
                            $shippingData = DB::table($quotes->table_name)->select('delivery_title')->where('shipping_id',$quotes->id)->first();
                            $status = array("Pending","Accepted","Rejected");
                            
                            $data[$i]['title'] = $shippingData->delivery_title;
                            $data[$i]['price'] = $quotes['quote_price'];
                            $data[$i]['status'] = $status[$quotes['quote_status']]; 
                            $i++;
                        }
                        $msg['responseCode'] = "200";
                        $msg['responseMessage'] = "Notifications get successfully";               
                        $msg['notifications'] = $data; 
                    }else{
                        $msg['responseCode'] = "200";
                        $msg['responseMessage'] = "Notifications get successfully";               
                        $msg['notifications'] = 'No Data found'; 
                    }
                }
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        finally {
            $result = json_encode($msg);
            echo $result;
        }
    }
    
    public function partnerTransactionDetail(){
        try{
            $msg = array();
            $i = 0;
            $userId = $_POST['userId'];
            
            if(empty($userId)) {
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "userId required.";
            }else{           
                    $tdetails = ShippingQuote::select('sd.id', 'shipping_quotes.quote_price', 'sd.order_id', 'sd.table_name','pd.created_at','pd.amount','pd.status')                            
                                    ->leftJoin('shipping_details as sd','sd.id','=','shipping_quotes.shipping_id')
                                    ->leftJoin('payment_details as pd','pd.shipping_id','=','sd.id')
                                    ->where('shipping_quotes.carrier_id',$userId)
                                    ->where('shipping_quotes.quote_status','1')->get();                
                    $info = $tdetails->toArray();
                    if(count($info) > 0){
                        foreach($tdetails as $detail){
                            $shippingData = DB::table($detail->table_name)->select('delivery_title')->where('shipping_id',$detail->id)->first();
                            
                            $data[$i]['orderId'] = $detail['order_id'];
                            $data[$i]['title'] = $shippingData->delivery_title;
                            $data[$i]['bidPrice'] = $detail['quote_price'];
                            $data[$i]['status'] = $detail['status'];
                            $data[$i]['amount'] = $detail['amount'];
                            $data[$i]['paymentDate'] = date('d-F-Y h:i:s', strtotime($detail['created_at'])); 
                            $i++;
                        }
                        $msg['responseCode'] = "200";
                        $msg['responseMessage'] = "Information get successfully"; 
                        $msg['details'] = $data; 
                    }else{
                        $msg['responseCode'] = "200";
                        $msg['responseMessage'] = "Information get successfully";  
                        $msg['details'] = 'No Data found';
                    }                      
                }
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        finally {
            $result = json_encode($msg);
            echo $result;
        }
    }
    
    public function quotationOfferDetail(){
        try{
            $msg = array();
            $offerDetails = array();
            $quoteId = $_POST['quoteId'];
            $required = array('quoteId');

            $error = false;
            foreach($required as $field) {
              if (empty($_POST[$field])) {
                $error = true;
                $fieldName = $field;
                break;
              }
            }

            if($error) {
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "$fieldName required.";
            }else{
                $offerData = ShippingQuote::select('shipping_quotes.quote_status','sd.created_at','sd.id as shippingId','spd.pickup_date','shipping_quotes.quote_price')
                            ->leftJoin('shipping_details as sd','sd.id','=','shipping_quotes.shipping_id')
                            ->leftJoin('shipping_pickup_details as spd','spd.shipping_id','=','shipping_quotes.shipping_id')
                            ->where('shipping_quotes.id', $quoteId)->first();
                
               
                if($offerData){
                     $status = array("Pending","Accepted","Rejected");
                     
                     $offerDetails['quoteId'] = $offerData->quoteId;
                     $offerDetails['shippingId'] = $offerData->shippingId;
                     $offerDetails['price'] = $offerData->quote_price;
                     $offerDetails['validTill'] = date('d-m-Y', strtotime($offerData->pickup_date));
                     $offerDetails['developed'] = date('d-m-Y', strtotime($offerData->created_at));
                     $offerDetails['status'] = $status[$offerData->quote_status];
                     
                    
                  $msg['responseCode'] = "200";
                  $msg['responseMessage'] = "Offer Details get successfully";
                  $msg['detail'] = $offerDetails; 
                }else{
                  $msg['responseCode'] = "200";
                  $msg['responseMessage'] = "Offer Details get successfully";
                  $msg['detail'] = "No Data foud"; 
                }
            }
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        finally {
            $result = json_encode($msg);
            echo $result;
        }
    }
    
    public function acceptOffer(){
        try{
            $msg = array();
            $offerDetails = array();
            $quoteId = $_POST['quoteId'];
            $shippingId = $_POST['shippingId'];
            $required = array('quoteId','shippingId');

            $error = false;
            foreach($required as $field) {
              if (empty($_POST[$field])) {
                $error = true;
                $fieldName = $field;
                break;
              }
            }

            if($error) {
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "$fieldName required.";
            }else{
                
                $rejectOther = ShippingQuote::where('shipping_id',$shippingId)->update(['quote_status'=>2]);
                $acceptBid = ShippingQuote::where('id',$quoteId)->update(['quote_status'=>1]);
                
                #Accept offer Notification
                $carrierData = ShippingQuote::select('u.device_token','u.first_name','u.last_name')
                                ->leftJoin('users as u','u.id','=','shipping_quotes.carrier_id')
                                ->where('shipping_quotes.id',$quoteId)->first();
                
                $push = new PushNotification('fcm');
                $response =  $push->setMessage([
                        'notification' => [
                                'title'=>'Accept your Offer',
                                'body'=>'Your Offer has been accepted by the '.$carrierData->first_name.' '.$carrierData->last_name,
                                'sound' => 'default'
                                ]
                        ])                            
                     ->setDevicesToken($carrierData->device_token)
                     ->send();
                
                #Reject offer Notification
                $rejectUsers = ShippingQuote::select('u.device_token','u.first_name','u.last_name')
                                ->leftJoin('users as u','u.id','=','shipping_quotes.carrier_id')
                                ->where('shipping_quotes.shipping_id',$shippingId)
                                ->where('shipping_quotes.id','!=',$quoteId)->get();
                
                foreach($rejectUsers as $rejectData){
                    $response =  $push->setMessage([
                        'notification' => [
                                'title'=>'Reject your Offer',
                                'body'=>'Your Offer has been rejected by the '.$rejectData->first_name.' '.$rejectData->last_name,
                                'sound' => 'default'
                                ]
                        ])                            
                     ->setDevicesToken($rejectData->device_token)
                     ->send();
                }
                
                
                                
                if($acceptBid){                                        
                  $msg['responseCode'] = "200";
                  $msg['responseMessage'] = "Offer Accepted successfully";
                }else{
                  $msg['responseCode'] = "0";
                  $msg['responseMessage'] = "Some Error occur! Please try again";
                }
            }
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        finally {
            $result = json_encode($msg);
            echo $result;
        }
    }
    
    public function rejectOffer(){
        try{
            $msg = array();
            $offerDetails = array();
            $quoteId = $_POST['quoteId'];
            $required = array('quoteId');

            $error = false;
            foreach($required as $field) {
              if (empty($_POST[$field])) {
                $error = true;
                $fieldName = $field;
                break;
              }
            }

            if($error) {
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "$fieldName required.";
            }else{
                
                $rejectOffer = ShippingQuote::where('id',$quoteId)->update(['quote_status'=>2]);
                
                $carrierData = ShippingQuote::select('u.device_token','u.first_name','u.last_name')
                                ->leftJoin('users as u','u.id','=','shipping_quotes.carrier_id')
                                ->where('shipping_quotes.id',$quoteId)->first();
                
                $push = new PushNotification('fcm');
                $response =  $push->setMessage([
                        'notification' => [
                                'title'=>'Accept your Offer',
                                'body'=>'Your Offer has been rejected by the '.$carrierData->first_name.' '.$carrierData->last_name,
                                'sound' => 'default'
                                ]
                        ])                            
                     ->setDevicesToken($carrierData->device_token)
                     ->send();
                
                
                if($rejectOffer){                                        
                  $msg['responseCode'] = "200";
                  $msg['responseMessage'] = "Offer Rejected successfully";
                }else{
                  $msg['responseCode'] = "0";
                  $msg['responseMessage'] = "Some Error occur! Please try again";
                }
            }
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        finally {
            $result = json_encode($msg);
            echo $result;
        }
    }
    
    public function payment(){
        try{
            $msg = array();
            $offerDetails = array();
            $quoteId = $_POST['quoteId'];
            $paymentType = $_POST['paymentType'];
            $shippingId = $_POST['shippingId'];
            $amount = $_POST['amount'];
            $required = array('quoteId','paymentType','shippingId','amount');

            $error = false;
            foreach($required as $field) {
              if (empty($_POST[$field])) {
                $error = true;
                $fieldName = $field;
                break;
              }
            }

            if($error) {
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "$fieldName required.";
            }else{
                
                $payment = ShippingDetail::where('id',$shippingId)->update(['payment_method_id'=>$paymentType, 'shipping_price'=>$amount, 'quote_status'=>1]);
                
                if($payment){                                        
                  $msg['responseCode'] = "200";
                  $msg['responseMessage'] = "Sucess";
                }else{
                  $msg['responseCode'] = "0";
                  $msg['responseMessage'] = "Some Error occur! Please try again";
                }
            }
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        finally {
            $result = json_encode($msg);
            echo $result;
        }
    }
    
    public function askQuestion(){
        try{
            $msg = array();
            $offerDetails = array();
            $userId = $_POST['userId'];
            $shippingId = $_POST['shippingId'];
            $question = $_POST['question'];
            $required = array('userId','shippingId','question');

            $error = false;
            foreach($required as $field) {
              if (empty($_POST[$field])) {
                $error = true;
                $fieldName = $field;
                break;
              }
            }

            if($error) {
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "$fieldName required.";
            }else{
                
                $userDetail = ShippingDetail::where('id',$shippingId)->select('user_id')->first();
                $customerId = $userDetail->user_id;
                
                $queMaster = new TblQuesMaster;
                $queMaster->shipping_id = $shippingId;
                $queMaster->user_id = $customerId;
                $queMaster->carrier_id = $userId;
                $queMaster->save();
                
                $masterQuesId = $queMaster->id;
                
                $ques = new TblQuestion;
                $ques->ques_master_id = $masterQuesId;
                $ques->question = $question;
                $ques->status =1;
                $ques->save();
                
                if($masterQuesId){                                        
                  $msg['responseCode'] = "200";
                  $msg['responseMessage'] = "Question Successfully asked.";
                }else{
                  $msg['responseCode'] = "0";
                  $msg['responseMessage'] = "Some Error occur! Please try again";
                }
            }
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        finally {
            $result = json_encode($msg);
            echo $result;
        }
    }
    
    public function getAnswer(){
        try{
            $msg = array();
            $offerDetails = array();
            $quesId = $_POST['quesId'];
            $answer = $_POST['answer'];
            $required = array('quesId','answer');

            $error = false;
            foreach($required as $field) {
              if (empty($_POST[$field])) {
                $error = true;
                $fieldName = $field;
                break;
              }
            }

            if($error) {
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "$fieldName required.";
            }else{
                
                $qmDetail = TblQuestion::where('id',$quesId)->select('ques_master_id')->first();
                $qmId = $qmDetail->ques_master_id;
              // echo $qmId; die;
                $data = new TblAnswer();
                $data->ques_master_id = $qmId;
                $data->ques_id = $quesId;
                $data->answer = $answer;
                $data->save();
                
                $answerId = $data->id;
                
                if($answerId){                                        
                  $msg['responseCode'] = "200";
                  $msg['responseMessage'] = "Answer Successfully post.";
                }else{
                  $msg['responseCode'] = "0";
                  $msg['responseMessage'] = "Some Error occur! Please try again";
                }
            }
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        finally {
            $result = json_encode($msg);
            echo $result;
        }
    }
    
    public function quesAnswer(){
        try{
            $msg = array();
            $data = array();
            $i = 0;
            $shippingId = $_POST['shippingId'];
            $required = array('shippingId');

            $error = false;
            foreach($required as $field) {
              if (empty($_POST[$field])) {
                $error = true;
                $fieldName = $field;
                break;
              }
            }

            if($error) {
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "$fieldName required.";
            }else{
                $quesDetail = TblQuesMaster::select('tq.question','tq.created_at as quesTime','u.first_name as cfname','u.last_name as clname','ta.answer','ta.created_at as ansTime','uc.first_name as ufname','uc.last_name as ulname')
                                    ->leftJoin('tbl_questions as tq','tq.ques_master_id','=','tbl_ques_masters.id')
                                    ->leftJoin('users as u','u.id','=','tbl_ques_masters.carrier_id')
                                    ->leftJoin('tbl_answers as ta','ta.ques_id','=','tq.id')
                                    ->leftJoin('users as uc','uc.id','=','tbl_ques_masters.user_id')
                                    ->where('tbl_ques_masters.shipping_id', $shippingId)->get();
                
                $quesData = $quesDetail->toArray();
                if(count($quesData) > 0){
                    foreach($quesDetail as $detail){
                        $data[$i]['partnerName'] = $detail['cfname']. ' '.$detail['clname'];
                        $data[$i]['quesTime'] = $detail['quesTime'];
                        $data[$i]['question'] = $detail['question'];
                        $data[$i]['userName'] = $detail['ufname']. ' '.$detail['ulname'];
                        $data[$i]['ansTime'] = $detail['ansTime'];
                        $data[$i]['answer'] = $detail['answer'];
                        $i++;
                    }
                    
                    $msg['responseCode'] = "0";
                    $msg['responseMessage'] = "Details get successfully";
                    $msg['data'] = $data;
                }
                else{
                  $msg['responseCode'] = "0";
                  $msg['responseMessage'] = "Some Error occur! Please try again";
                  $msg['data'] = $data;
                }
            }
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        finally {
            $result = json_encode($msg);
            echo $result;
        }
    }
    
    public function deleteShipment(){
        try{
            $msg = array();
            $offerDetails = array();
            $shippingId = $_POST['shippingId'];
            $required = array('shippingId');

            $error = false;
            foreach($required as $field) {
              if (empty($_POST[$field])) {
                $error = true;
                $fieldName = $field;
                break;
              }
            }

            if($error) {
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "$fieldName required.";
            }else{
                
                ShippingDetail::where('id',$shippingId)->update(['status'=>0]);
                                                     
                  $msg['responseCode'] = "200";
                  $msg['responseMessage'] = "Shipment Successfully deleted.";
                
            }
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        finally {
            $result = json_encode($msg);
            echo $result;
        }
    }
    
    public function test(){
        
        $push = new PushNotification('fcm');
        $response =  $push->setMessage([
                'notification' => [
                        'title'=>'This is the title',
                        'body'=>'background',
                        'sound' => 'default'
                        ]
                ])
           //  ->setApiKey('AAAAZLnw4BA:APA91bEt7-Hts8J2v8yecvCwOtscsEZLW9p92Ew0E6dxF-QNandDuY_Qde0OPP1m4aCXqgD4EXjaqlXIBpK2bGWBodjXsW8Lh8VUJnXbbFFZPa0ij4VWvjy9dO4QCaPejBuqBY_0HfUZ')
             ->setDevicesToken('eoXQObvvoNU:APA91bF0HF1lK0qwGWm_lssH6zEqm3GA-kAPPAq6M0RlAH5E3HwsbAOqGrP4qNM6APHebLdD7WW7KjttBviXKpBKIUjg2N5QmPxlwJVja3MJFmfDLZFQgqz-ItJnrbKRAVSxmj4EK17s')
             ->send();
      print_r($response); die;
    }
    
    public function partnerProfileEdit(){
        try{
            $msg = array();
            $userId = $_POST['userId'];
            $fName = $_POST['fName'];
            $lName = $_POST['lName'];
            $email = $_POST['email'];
            $street = $_POST['street'];    
            $city = $_POST['city']; 
            $location = $_POST['address']; 
            $pincode = $_POST['pincode']; 
            $country = $_POST['country']; 
            $carrierType = $_POST['carrierType'];
            $totalVehicle = ($_POST['totalVehicle'])? $_POST['totalVehicle'] : '';
            $attachedVehicle = ($_POST['attachedVehicle'])? $_POST['attachedVehicle'] : '';
            
            $required = array('userId','fName','lName','email');

            $error = false;
            foreach($required as $field) {
              if (empty($_POST[$field])) {
                $error = true;
                $fieldName = $field;
                break;
              }
            }

            if($error) {
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "$fieldName required.";
            }else{   
                
                 $userDetails = UserDetail::where('user_id',$userId)->first();
                
                $pic=Input::file('image'); 
                if($pic){
                $extension = $pic->getClientOriginalExtension(); // getting image extension
                $userImage = time() . rand(111, 999) . '.' . $extension; // renameing image                
                $pic->move(public_path().'/uploads/user/',$userImage);
                }else{
                    $userImage = (!empty($userDetails->image))? $userDetails->image : "user_icon.png";
                }
                
                User::where('id',$userId)
                    ->update([
                        'first_name'=>$fName,
                        'last_name'=>$lName,
                        'email'=>$email,
                        'carrier_type_id'=>$carrierType,                        
                        'total_vehicle'=>$totalVehicle,
                        'attached_vehicle'=>$attachedVehicle
                        ]);
                
               
               
                if($userDetails){
                    UserDetail::where('user_id',$userId)
                    ->update([
                        'image'=>$userImage,
                        'street'=>$street,
                        'location'=>$location,
                        'city'=>$city,
                        'pincode'=>$pincode,
                        'country'=>$country
                        ]);
                }else{
                    $data = new UserDetail;
                    $data->user_id = $userId;
                    $data->image = $userImage;
                    $data->street = $street;
                    $data->location = $location;
                    $data->city = $city;
                    $data->pincode = $pincode;
                    $data->country = $country;
                    $data->save();
                }
                
                if($carrierType == 2 || $carrierType == 3){
                    $vehicleData = $_POST['vehicleCategory'];
                    $vehicleDetails = UserVehicleDetail::where('user_id',$userId)->delete();
                    
                    foreach($vehicleData as $vData){
                        $userVehicleDetail = new UserVehicleDetail;
                        $userVehicleDetail->user_id  = $userId;
                        $userVehicleDetail->vehicle_category_id = $categoryId;
                        $userVehicleDetail->vehicle_subcategory_id = $vData['subCategoryId'];
                        $userVehicleDetail->vehicle_length_id = $vData['vehicleLength'];
                        $userVehicleDetail->save();
                    }                    
                }
                    
                    
                $msg['responseCode'] = "200";
                $msg['responseMessage'] = "Data saved successfully";    
                }
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        finally {
            $result = json_encode($msg);
            echo $result;
        }
    }
    
    public function kycUpdate(){
        try{
            $msg = array();
            $userId = $_POST['userId'];
            
            $required = array('userId');

            $error = false;
            foreach($required as $field) {
              if (empty($_POST[$field])) {
                $error = true;
                $fieldName = $field;
                break;
              }
            }

            if($error) {
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "$fieldName required.";
            }else{   
                
                $rcpic=Input::file('rc');  
                if($rcpic){
                $extension = $rcpic->getClientOriginalExtension(); // getting image extension
                $rcImage = time() . rand(111, 999) . '.' . $extension; // renameing image                
                $rcpic->move(public_path().'/admin/kyc/',$rcImage);
                }else{
                    $rcImage = '';
                }
                $pic=Input::file('panCard');    
                if($pic){
                $extension = $pic->getClientOriginalExtension(); // getting image extension
                $panCardImage = time() . rand(111, 999) . '.' . $extension; // renameing image                
                $pic->move(public_path().'/admin/kyc/',$panCardImage);
                }else{
                    $panCardImage ="";
                }
                
                $bcpic=Input::file('businessCard');   
                if($bcpic){
                $extension = $bcpic->getClientOriginalExtension(); // getting image extension
                $businessCardImage = time() . rand(111, 999) . '.' . $extension; // renameing image                
                $bcpic->move(public_path().'/admin/kyc/',$businessCardImage);
                }else{
                    $businessCardImage = '';
                }
                $kycDetails = PartnerKyc::where('user_id',$userId)->first();
               
                if($kycDetails){
                    PartnerKyc::where('user_id',$userId)
                    ->update([
                        'rc_photo'=>$rcImage,
                        'pancart'=>$panCardImage,
                        'business_card'=>$businessCardImage
                        ]);
                }else{
                    $data = new PartnerKyc;
                    $data->user_id = $userId;
                    $data->rc_photo = $rcImage;
                    $data->pancart = $panCardImage;
                    $data->business_card = $businessCardImage;
                    $data->save();
                }
                
                User::where('id',$userId)
                    ->update([
                        'documents_status'=>0
                        ]);
                
                $msg['responseCode'] = "200";
                $msg['responseMessage'] = "Kyc updated successfully";    
                }
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        finally {
            $result = json_encode($msg);
            echo $result;
        }
    }
    
    public function viewKYC(){
        try{
            $msg = array();
            $userId = $_POST['userId'];
            
            $required = array('userId');

            $error = false;
            foreach($required as $field) {
              if (empty($_POST[$field])) {
                $error = true;
                $fieldName = $field;
                break;
              }
            }

            if($error) {
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "$fieldName required.";
            }else{   
               
                $kycDetails = PartnerKyc::where('user_id',$userId)->first();
               
                if($kycDetails){
                    $msg['responseCode'] = "200";
                    $msg['responseMessage'] = "Kyc Data get successfully";
                    $msg['data'] = $kycDetails;
                }else{
                    $msg['responseCode'] = "200";
                    $msg['responseMessage'] = "Kyc Data get successfully";
                    $msg['data'] = "No Data found.";
                }
                
                  
            }
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        finally {
            $result = json_encode($msg);
            echo $result;
        }
    }
    
    public function partnerProfileView(){
        try{
            $msg = array();
            $i = 0;
            $userId = $_POST['userId'];
            
            if(empty($userId)) {
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "userId required.";
            }else{           
                    $userdetails = User::select('first_name', 'last_name','email','mobile_number','ud.*','vc.vehicle_category_id','vc.vehicle_subcategory_id','vc.vehicle_length_id')                            
                                    ->leftJoin('user_details as ud','ud.user_id','=','users.id')
                                    ->leftJoin('user_vehicle_details as vc','vc.user_id','=','users.id')
                                    ->where('users.id',$userId)->first();                
                   
                    if($userdetails){
                        $msg['responseCode'] = "200";
                        $msg['responseMessage'] = "Information get successfully"; 
                        $msg['details'] = $userdetails; 
                    }else{
                        $msg['responseCode'] = "200";
                        $msg['responseMessage'] = "Information get successfully";  
                        $msg['details'] = 'No Data found';
                    }                      
                }
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        finally {
            $result = json_encode($msg);
            echo $result;
        }
    }
}
    
