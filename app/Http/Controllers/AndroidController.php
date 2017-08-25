<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Session;
use Input;
use App\library\Smsapi;
use App\User;
use App\UserVerification;
use App\UserVehicleDetail;
use App\VehicleCategorie;
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
use App\ShippingQuote;
use App\PayInfo;
use App\UserDetail;
use App\TblAnswer;
use App\TblQuestion;
use App\TblQuesMaster;
use DB;
use Hash;

class AndroidController extends AppController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    
    public function index(Request $request)
    {
       
    }
    
    public function login(Request $request){
        $credentials = $request->only('email','password');
        try{
            if(! $token = JWTAuth::attempt($credentials)){
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "User credentials are not valid.";
                return $msg;
            }
        } catch(JWTException $ex){
            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "Something went worng.";
            return $msg;
        }
        $msg['responseCode'] = "200";
        $msg['productImages'] = compact('token');
        return $msg;
    }

     public function sendOtp(Request $request)
    {
         try{
             //if($_POST['token'] == Session::get('token')){
                $mobileNumber = $_POST['mobileNumber'];     
                $msg = array();
                $otpMsg = '';
                if($mobileNumber != "" || !empty($mobileNumber)){

                    $string = '1234567890';
                    $string_shuffled = str_shuffle($string);
                    $otp = substr($string_shuffled, 1, 5);

                    $mobileData=User::where('mobile_number',$mobileNumber)->select('id')->get();
                    $mobileExists = $mobileData->toArray();

                    $user = new User;
                    $user->mobile_number = $mobileNumber;

                    if(empty($mobileExists)){               
                        $user->save();
                        $userId= $user->id;
                    }else{
                        $userId = $mobileExists[0]['id'];
                    }
                   // $token = JWTAuth::fromUser($user); 

                    $userVerification =new UserVerification;
                    $userVerification->user_id = $userId;
                    $userVerification->otp = $otp;
                    $userVerification->save();

                    $otpMsg = 'Your Otp is '.$otp;

                   // $smsObj = new Smsapi();
                  //  $smsObj->sendsms_api('+91'.$mobileNumber,$otpMsg);            

                    $msg['responseCode'] = "200";
                    $msg['responseMessage'] = "Otp is fetched successfully";
                    $msg['userId'] = $userId;
                    $msg['otp'] = $otp;  
                }else{
                    $msg['responseCode'] = "0";
                    $msg['responseMessage'] = "Failed.Please enter mobile number with country code";
                } 
//         }else{
//                    $msg['responseCode'] = "0";
//                    $msg['responseMessage'] = "Token Mismatch";
//                } 
         } catch (\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
         return $msg;    
    }
    
     public function otpVerification(){
         try{
            $otp = $_POST['otp'];
            $userId = $_POST['userId'];
            $msg = array();

            $required = array('userId', 'otp');

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
            }else {       
                  $userData = UserVerification::where('user_id',$userId)->where('otp',$otp)->first();
                  $verifiedData = User::where('id',$userId)->first();
                  
                  if($userData){
                        $verificationId = $userData->id;
                        UserVerification::where('id',$verificationId)->delete();
                        User::where('id',$userId)->update(['otp_verified'=>1 , 'status'=>1]);
                        $status = ($verifiedData->first_name != '')?'yes':'No';
                        $msg['responseCode'] = "200";
                        $msg['responseMessage'] = "Mobile number successfully verified.";
                        $msg['userId'] = $userId;
                        $msg['isRegistered'] = $status;
                        $msg['userData'] = $verifiedData;
                  }else{
                      $msg['responseCode'] = "0";
                     $msg['responseMessage'] = "Failed.Invalid OTP";
                  }
            }                    
        } catch (\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        return $msg;
    }
    
    public function userRegistration(Request $request){
        try{
            $msg = array();
            $userId = $_POST['userId'];
            $firstName = $_POST['firstName'];
            $lastName = $_POST['lastName'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $cpassword = $_POST['cpassword'];
            $required = array('userId','firstName','lastName','email','password','cpassword');

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
            }else {       
                  if($password === $cpassword){
                     $userEmail = User::where('email',$email)->first();
                     if($userEmail){
                        $msg['responseCode'] = "0";
                        $msg['responseMessage'] = "Failed. Email already exist";
                        }else{
                            User::where('id',$userId)
                                ->update([
                                    'user_type_id'=>3,
                                    'first_name'=>$firstName,
                                    'last_name'=>$lastName,
                                    'email'=>$email,
                                    'password'=>bcrypt($password)
                                    ]);

                            $msg['responseCode'] = "200";
                            $msg['responseMessage'] = "Registration is done successfully";
                            $msg['userId'] = $userId;
                            $msg['firstName'] = $firstName;
                            $msg['lastName'] = $lastName;
                            $msg['email'] = $email;
                        } 
                  }else{
                      $msg['responseCode'] = "0";
                      $msg['responseMessage'] = "Password and Confirm Password Should be same.";
                  }
            } 
        } catch (\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        return $msg;
    }
    
    public function partnerRegistration(Request $request){
        try{
            $msg = array();
            $userId = $_POST['userId'];
            $firstName = $_POST['firstName'];
            $lastName = $_POST['lastName'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $cpassword = $_POST['cpassword'];
            $city = $_POST['city'];
            $state = $_POST['state'];
            $categoryId = $_POST['categoryId'];
            $carrierType = $_POST['carrierType'];
            $totalVehicle = ($_POST['totalVehicle'])? $_POST['totalVehicle'] : '';
            $attachedVehicle = ($_POST['attachedVehicle'])? $_POST['attachedVehicle'] : '';
              
            $required = array('userId','firstName','lastName','email','password','cpassword');

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
            }else {       
                  if($password === $cpassword){
                     $userEmail = User::where('email',$email)->first();
                     if($userEmail){
                        $msg['responseCode'] = "0";
                        $msg['responseMessage'] = "Failed. Email already exist";
                        }else{
                            
                            User::where('id',$userId)
                                ->update([
                                    'user_type_id'=>2,
                                    'first_name'=>$firstName,
                                    'last_name'=>$lastName,
                                    'email'=>$email,
                                    'password'=>bcrypt($password),
                                    'carrier_type_id'=>$carrierType,
                                    'city'=>$city,
                                    'state'=>$state,
                                    'total_vehicle '=>$totalVehicle,
                                    'attached_vehicle'=>$attachedVehicle
                                    ]);

                            if($carrierType == 2){
                                $vehicleData = $_POST['vehicleCategory'];
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
                            $msg['responseMessage'] = "Registration is done successfully";
                            $msg['userId'] = $userId;
                            $msg['firstName'] = $firstName;
                            $msg['lastName'] = $lastName;
                            $msg['email'] = $email;
                        } 
                  }else{
                      $msg['responseCode'] = "0";
                      $msg['responseMessage'] = "Password and Confirm Password Should be same.";
                  }
            } 
        } catch (\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        return $msg;
    }
    
    public function token(Request $request){
        try{
            $token = $request->session()->token();
           // Session::set('token', $value);
            $msg['responseCode'] = "200";
            $msg['responseMessage'] = "Token get successfully";
            $msg['token'] = $token;            
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        return $msg;
    }
    
    public function getCategories(Request $request){
        try{
            $msg = array();
            $categories = VehicleCategorie::where('status',1)->where('parent_id',0)->select('id','category_name','category_image')->get();
            $msg['responseCode'] = "200";
            $msg['responseMessage'] = "Categories get successfully";
            $msg['categories'] = $categories;            
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        return $msg;
    }
    
    public function getSubCategories(Request $request){
        try{
            $msg = array();
            $catId = $_POST['categoryId'];
            $categories = VehicleCategorie::where('status',1)->where('parent_id',$catId)->select('id','category_name','category_image')->get();
            $msg['responseCode'] = "200";
            $msg['responseMessage'] = "Sub Categories get successfully";
            $msg['subCategories'] = $categories;            
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        return $msg;
    }
    
    public function getDiningData(Request $request){
        try{
            $msg = array();
            $categories = AdminDinningRoom::where('status','1')->select('id','name')->get();
            $msg['responseCode'] = "200";
            $msg['responseMessage'] = "Dining Data get successfully";
            $msg['diningRoom'] = $categories;            
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        return $msg;
    }
    
    public function getLivingRoomData(Request $request){
        try{
            $msg = array();
            $categories = AdminLivingRoom::where('status','1')->select('id','name')->get();
            $msg['responseCode'] = "200";
            $msg['responseMessage'] = "Living Room Data get successfully";
            $msg['livingRoon'] = $categories;            
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        return $msg;
    }
    
    public function getKitchenData(Request $request){
        try{
            $msg = array();
            $categories = AdminKitchen::where('status','1')->select('id','name')->get();
            $msg['responseCode'] = "200";
            $msg['responseMessage'] = "Kitchen Data get successfully";
            $msg['kitchen'] = $categories;            
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        return $msg;
    }
    
    public function getHomeOfficeData(Request $request){
        try{
            $msg = array();
            $categories = AdminHomeOffice::where('status','1')->select('id','name')->get();
            $msg['responseCode'] = "200";
            $msg['responseMessage'] = "Home Office Data get successfully";
            $msg['homeOffice'] = $categories;            
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        return $msg;
    }
    
    public function getGarageData(Request $request){
        try{
            $msg = array();
            $categories = AdminGarage::where('status','1')->select('id','name')->get();
            $msg['responseCode'] = "200";
            $msg['responseMessage'] = "Garage Data get successfully";
            $msg['garage'] = $categories;            
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        return $msg;
    }
    
    public function getOutdoorData(Request $request){
        try{
            $msg = array();
            $categories = AdminOutdoor::where('status','1')->select('id','name')->get();
            $msg['responseCode'] = "200";
            $msg['responseMessage'] = "Outdoor Data get successfully";
            $msg['outdoor'] = $categories;            
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        return $msg;
    }
    
    public function getMiscellaneousData(Request $request){
        try{
            $msg = array();
            $categories = AdminMiscellaneous::where('status','1')->select('id','name')->get();
            $msg['responseCode'] = "200";
            $msg['responseMessage'] = "Miscellaneous Data get successfully";
            $msg['miscellaneous'] = $categories;            
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        return $msg;
    }
    
    public function getBoxesData(Request $request){
        try{
            $msg = array();
            $categories = AdminBox::where('status','1')->select('id','name')->get();
            $msg['responseCode'] = "200";
            $msg['responseMessage'] = "Miscellaneous Box Data get successfully";
            $msg['miscellaneousBox'] = $categories;            
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        return $msg;
    }
    
    public function getEquipmentData(Request $request){
        try{
            $msg = array();
            $categories = AdminEquipment::where('status','1')->select('id','name')->get();
            $msg['responseCode'] = "200";
            $msg['responseMessage'] = "Equipment Data get successfully";
            $msg['equipments'] = $categories;            
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        return $msg;
    }
    
    public function getGeneralShipmentData(Request $request){
        try{
            $msg = array();
            $categories = AdminGeneralShipment::where('status','1')->select('id','name')->get();
            $msg['responseCode'] = "200";
            $msg['responseMessage'] = "General Shipment Data get successfully";
            $msg['generalShipment'] = $categories;            
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        return $msg;
    }
    
    public function getMaterial(Request $request){
        try{
            $msg = array();
            $categories = Material::where('status','1')->select('id','name','image')->get();
            $msg['responseCode'] = "200";
            $msg['responseMessage'] = "Materials get successfully";
            $msg['materials'] = $categories;            
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        return $msg;
    }
    
    public function getTruckLength(Request $request){
        try{
            $msg = array();
            $truckType = $_POST['subCatId'];
            $categories = TruckLength::where('truck_type_id',$truckType)->where('status','1')->select('id','truck_length')->get();
            $msg['responseCode'] = "200";
            $msg['responseMessage'] = "Truck Length get successfully";
            $msg['truckLength'] = $categories;            
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        return $msg;
    }
    
     public function getTruckCapacity(Request $request){
        try{
            $msg = array();
            $lengthId = $_POST['lengthId'];
            $categories = TruckCapacity::where('truck_length_id',$lengthId)->where('status','1')->select('id','truck_capacity')->get();
            $msg['responseCode'] = "200";
            $msg['responseMessage'] = "Truck Capacity get successfully";
            $msg['truckCapacity'] = $categories;            
        }catch(\Exception $e) {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] =$e->getMessage();
        }
        return $msg;
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
                    $pic->move(public_path().'/uploads/home/',$name);
                    
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
                    $pic->move(public_path().'/uploads/office/',$name);
                    
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
    
    public function otherCategory(){
        try{

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

                $otherImages = '';                
                for($i=1;$i<=$imageCount;$i++){
                    $pic=Input::file('image'.$i);

                    $extension = $pic->getClientOriginalExtension(); // getting image extension
                    $name = time() . rand(111, 999) . '.' . $extension; // renameing image                
                    $pic->move(public_path().'/uploads/other/',$name);
                    
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
                    $shipping->table_name = 'shipment_listing_others';
                    $shipping->status = 0;
                    $shipping->save(); 
                    $shippingId= $shipping->id;
                    
                    $shipmentList= new ShipmentListingOther;
                    $shipmentList->shipping_id = $shippingId;
                    $shipmentList->delivery_title = $title;
                    $shipmentList->additional_detail = $_POST['additionalDetail'];
                    $shipmentList->item_image = $otherImages;
                    $shipmentList->save();
                }
                else{

                    ShipmentListingOther::where('shipping_id ',$preShipId)
                                       ->update([
                                            'delivery_title'=>$title,
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
                    $pic->move(public_path().'/uploads/houseHoldGoods/',$name);
                    
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
                    $pic->move(public_path().'/uploads/vehicleShifting/',$name);
                    
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
                   $shippingPickupData = ShippingPickupDetail::where('$shippingId',$shippingId)->select('id')->first();
                if ($shippingPickupData->id != ''){                    
                    ShippingPickupDetail::where('shipping_id ',$shippingId)
                                       ->update([
                                            'pickup_address'=>$pickupLocation,
                                            'latitude'=>$pickupLat,
                                            'longitutde'=>$pickupLong,
                                            'pickup_date'=>$pickupDate
                                        ]); 
                    
                    ShippingDeliveryDetail::where('shipping_id ',$shippingId)
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

                  $shippingDetails[$i]['shippingId'] = $shipping->id;
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
                    $quoteDetails = ShippingQuote::select('u.first_name','u.last_name', 'quote_price')
                            ->leftJoin('users as u','u.id','=','shipping_quotes.carrier_id')                            
                            ->where('shipping_quotes.shipping_id',$shippingId)->get();
                    $quoteArray = $quoteDetails->toArray();
                
                    if(count($quoteArray) > 0){
                        foreach($quoteDetails as $quotes){
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
            
            if(empty('userId')) {
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "userId required.";
            }else{           
                    $data = PayInfo::where('user_id',$userId)->select('name as userName', 'number as accountNumber', 'code as ifscCode')->get();                    
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
    
    public function transactionDetail(){
        try{
            $msg = array();
            $i = 0;
            $userId = $_POST['userId'];
            
            if(empty('userId')) {
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
                
                $pic=Input::file('image');
                $extension = $pic->getClientOriginalExtension(); // getting image extension
                $userImage = time() . rand(111, 999) . '.' . $extension; // renameing image                
                $pic->move(public_path().'/uploads/user/',$userImage);
                
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
            
            if(empty('userId')) {
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
            
            if(empty('userId')) {
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "userId required.";
            }else{           
                    $quesdetails = TblQuesMaster::select('tq.question', 'u.first_name','u.last_name','ud.image','sd.table_name','sd.id','tq.id as quesId')                            
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
                            $shippingData = DB::table($detail->table_name)->select('delivery_title')->where('shipping_id',$detail->id)->first();
                            
                            $data[$i]['quesId'] = $detail['quesId'];
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
            $userId = $_POST['userId'];
            
            if(empty('userId')) {
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
                     
                    $shippingData = ShippingDetail::select(DB::raw('shipping_details.id, table_name, shipping_details.created_at, spd.pickup_address, spd.pickup_date,  sdd.delivery_date, spd.latitude as pickupLat, spd.longitutde as pickupLong, sdd.delivery_address, sdd.latitude as deliveryLat, sdd.longitutde as deliveryLong, min(sq.quote_price) as minimumBid'))
                                    ->leftJoin('shipping_pickup_details as spd','spd.shipping_id','=','shipping_details.id')
                                    ->leftJoin('shipping_delivery_details as sdd','sdd.shipping_id','=','shipping_details.id')
                                    ->leftJoin('shipping_quotes as sq','sq.shipping_id','=','shipping_details.id')
                                    ->whereIn('category_id', $catType)->get();
                    
                     $info = $shippingData->toArray();
                    if(count($info) > 0){
                        foreach($shippingData as $detail){
                            $shippingData = DB::table($detail->table_name)->select('delivery_title','item_image')->where('shipping_id',$detail->id)->first();
                            $shippingQuote = ShippingQuote::where('carrier_id',$userId)->where('shipping_id',$detail->id)->select('quote_price')->first();
                            $image = explode(',',$shippingData->item_image);

                            $data[$i]['ShippingId'] = $detail['id'];
                            $data[$i]['image'] = $image[0];
                            $data[$i]['title'] = $shippingData->delivery_title;
                            $data[$i]['pickupAddress'] = $detail['pickup_address'];
                            $data[$i]['deliveryAddress'] = $detail['delivery_address'];
                            $data[$i]['minimumBid'] = $detail['minimumBid']; 
                            $data[$i]['partnerQuote'] = ($shippingQuote) ? $shippingQuote->quote_price : '';
                            $data[$i]['distance'] = ShippingDetail::distance($detail['pickupLat'],$detail['pickupLong'],$detail['deliveryLat'],$detail['deliveryLong'], "K"); 
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
    
}
