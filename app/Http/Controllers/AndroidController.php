<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Session;
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
use APp\AdminLivingRoom;
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
use DB;

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

                    //$smsObj = new Smsapi();
                    //$smsObj->sendsms_api('+91'.$mobileNumber,$otpMsg);            

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
                    $shipping->status = 1;
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
                    $shipmentList->bedroom = $bedroom;
                    $shipmentList->kitchen = $kitchen;
                    $shipmentList->home_office = $home;
                    $shipmentList->garage = $garage;
                    $shipmentList->outdoor = $outdoor;
                    $shipmentList->miscellaneous = $misc;
                    $shipmentList->boxes = $box;
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
                                            'bedroom' => $bedroom,
                                            'kitchen' => $kitchen,
                                            'home_office' => $home,
                                            'garage' => $garage,
                                            'outdoor' => $outdoor,
                                            'miscellaneous' => $misc,
                                            'boxes' => $box,
                                            'item_image' => $homeImages,
                                            'item_detail' => $_POST['additionalDetail']
                                        ]);         
                   $shippingId = $preShippingId;
                }

                $msg['responseCode'] = "200";
                $msg['responseMessage'] = "Shipment successfully post.";
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
                    $shipping->status = 1;
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
                $msg['responseMessage'] = "Shipment successfully post";
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
                    $shipping->status = 1;
                    $shipping->save(); 
                    $shippingId= $shipping->id;
                    
                    $shipmentList= new ShipmentListingOther;
                    $shipmentList->shipping_id = $shippingId;
                    $shipmentList->delivery_title = $title;
                    $shipmentList->additional_detail = $_POST['additionalDetail'];
                    $shipmentList->image = $otherImages;
                    $shipmentList->save();
                }
                else{

                    ShipmentListingOther::where('shipping_id ',$preShipId)
                                       ->update([
                                            'delivery_title'=>$title,
                                            'additional_detail'=>$_POST['additionalDetail'],
                                            'image'=>$otherImages                                            
                                        ]);         
                   $shippingId = $preShipId;
                }

                $msg['responseCode'] = "200";
                $msg['responseMessage'] = "Shipment successfully post";
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
            $required = array('catId','userId','subCatId','imageCount');

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
                    $shipping->status = 1;
                    $shipping->save(); 
                    $shippingId= $shipping->id;
                    
                    $shipmentList= new ShipmentListingHouseholdgood;
                    $shipmentList->shipping_id = $shippingId;
                    $shipmentList->additional_detail = $_POST['additionalDetail'];
                    $shipmentList->image = $otherImages;
                    $shipmentList->save();
                }
                else{

                    ShipmentListingHouseholdgood::where('shipping_id ',$preShipId)
                                       ->update([
                                            'additional_detail'=>$_POST['additionalDetail'],
                                            'image'=>$otherImages                                            
                                        ]);         
                   $shippingId = $preShipId;
                }

                $msg['responseCode'] = "200";
                $msg['responseMessage'] = "Shipment successfully post";
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
                    $shipping->status = 1;
                    $shipping->save(); 
                    $shippingId= $shipping->id;
                    
                    $shipmentList= new ShipmentListingVehicleShifing;
                    $shipmentList->shipping_id = $shippingId;
                    $shipmentList->delivery_title = $title;
                    $shipmentList->vehicle_name = $vehicleName;
                    $shipmentList->image = $otherImages;
                    $shipmentList->save();
                }
                else{

                    ShipmentListingVehicleShifing::where('shipping_id ',$preShipId)
                                       ->update([
                                            'delivery_title'=>$title,
                                            'vehicle_name'=>$vehicleName,
                                            'image'=>$otherImages                                            
                                        ]);         
                   $shippingId = $preShipId;
                }

                $msg['responseCode'] = "200";
                $msg['responseMessage'] = "Shipment successfully post";
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
            $subCatId = $_POST['subCatId'];
            $weight = $_POST['weight'];
            $remark = $_POST['remark'];
            $preShipId = $_POST['shippingId'];
            $materialId = $_POST['materialId'];
            $required = array('catId','userId','subCatId','weight','materialId');

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
                    $shipping->table_name = 'shipment_listing_materials';
                    $shipping->status = 1;
                    $shipping->save(); 
                    $shippingId= $shipping->id;
                    
                    $shipmentList= new ShipmentListingMaterial;
                    $shipmentList->shipping_id = $shippingId;
                    $shipmentList->material_id = $materialId;
                    $shipmentList->weight = $weight;
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
                $msg['responseMessage'] = "Shipment successfully post";
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
}
