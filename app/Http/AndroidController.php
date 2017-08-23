<?php

namespace App\Http\Controllers;

use App\GeneralSetting;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\UserOtpTemp;
use App\RoleMaster;
use App\UserRole;
use App\Banner;
use App\Order;
use App\OrderDetail;
use App\ProductMaster;
use App\ServiceCategory;
use App\ProductCategory;
use App\ProductGallary;
use App\PlanMaster;
use App\DrivenKilometerCharge;
use App\Package;
use App\MobileModel;
use App\PackageFeature;
use App\Feature;
use App\AutomobileCoveredFeature;
use App\MobileRedemptionPolicyFault;
use App\MobileRedemptionPolicyDocument;
use App\TimelyCharge;
use App\InspectionCharge;
use App\InspectionQuestion;
use App\InspectionAnswer;
use App\InspectionDocument;
use App\VehicleModel;
use App\MobileRedemptionPolicyRequest;
use App\model\NewSaleRequest;
use App\model\InsuranceRequest;
use App\UserChatHistory;
use App\UserChat;
use App\library\Smsapi;
use Session;
use Hash;
use Illuminate\Support\Facades\Redirect;
use App\Cart;
use App\CartDetail;
use DB;
use App\PolicyRedemptionRequest;
use Exception;
use App\Fault;
use App\InspectionRequest;
use App\Policy;
use Input;
use App\MobileFaultType;
use App\MobileManufacturer;
use App\VehicleManufacturer;
use App\RedemptionPolicyRequest;
use App\UserProfile;
use App\library\DateLibrary;
use App\MobileInsuranceRequest;
use App\MobilePackageUserDocument;
use App\MobilePackageUserInformation;
use Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class AndroidController extends AppController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index(){
        
    }
    
    public function getOtp()
    {
        $mobileNumber = $_POST['mobileNumber'];
        $countryCode = $_POST['countryCode'];        
        $msg = array();
        $otpMsg = '';
        if($mobileNumber != "" || !empty($mobileNumber) || $countryCode != "" || !empty($countryCode)){
            
            if (substr($countryCode, 0, 1) === '+'){
                $countryCode = $countryCode;
            }else{
                $countryCode = '+'.$countryCode;
            }
            
            $mobile =  $countryCode.$mobileNumber; 
            $string = '1234567890';
            $string_shuffled = str_shuffle($string);
            $otp = substr($string_shuffled, 1, 5);
            
            User::where('mobile',$mobileNumber)->update(['otp'=>$otp]); 
            
            $otpMsg = 'Your Otp is'.$otp;
            
            $smsObj = new Smsapi();
            $smsObj->sendsms_api($mobile,$otpMsg);
        
            
            $msg['responseCode'] = "200";
            $msg['responseMessage'] = "Otp is fetched successfully";
            $msg['otp'] = $otp;            
            
        }else{
            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "Failed.Please enter mobile number with country code";
        }
         return $msg;
    
    }
   
    public function otpVerification(){
        $otp = $_POST['otp'];
        $countryCode = $_POST['countryCode'];
        $mobileNumber = $_POST['mobileNumber'];
        $msg = array();
        
        if($mobileNumber != "" || !empty($mobileNumber) || $countryCode != "" || !empty($countryCode)){
            $data=User::where('mobile',$mobileNumber)->where('otp',$otp)->select('id')->first();
        }else{
            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "Failed.Please enter mobile number with country code";
        }
        if($data){
            User::where('id',$data->id)->update(['is_verified'=>1]);             
            $msg['responseCode'] = "200";
            $msg['responseMessage'] = "Mobile number is verified successfully";
            $msg['countryCode'] = $countryCode;
            $msg['mobileNumber'] = $mobileNumber;
        }else{
            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "Failed.Invalid OTP";
        }
        return $msg;
    }
    
    public function registration(){        
        $msg = array();
        $userName = $_POST['userName'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $emailId = $_POST['emailId'];
        $password = $_POST['password'];
        $mobileNumber = $_POST['mobileNo'];
        $countryCode = $_POST['countryCode'];
        $roleId = $_POST['roleId'];
               
        
        if(!empty($userName) || !empty($firstName) || !empty($lastName) || !empty($emailId) || !empty($password) || !empty($mobileNumber) || !empty($roleId) || !empty($countryCode)){
           
            if (substr($countryCode, 0, 1) === '+'){
                $countryCode = $countryCode;
            }else{
                $countryCode = '+'.$countryCode;
            }
            
            $mobile =  $countryCode.$mobileNumber; 
            $userEmail = User::where('email',$emailId)->first();
            $userMobile = User::where('mobile',$mobileNumber)->first();
            if($userEmail){
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "Failed. Email already exist";
            }elseif($userMobile){
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "Failed. Mobile Number already exist";
            }else{
                $string = '1234567890';
                $string_shuffled = str_shuffle($string);
                $otp = substr($string_shuffled, 1, 5);
                
                $user = new User;
                $user->userName = $userName;
                $user->first_name = $firstName;
                $user->last_name = $lastName;
                $user->email = $emailId;
                $user->password = bcrypt($password);
                $user->mobile = $mobileNumber;
                $user->country_code = $countryCode;
                $user->role_master_id = $roleId;
                $user->otp = $otp;
                $user->status = 1;
                $user->is_delete = 0;
                $user->save();
                $id= $user->id;
                
                $userRole = new UserRole;
                $userRole->user_id = $id;
                $userRole->role_master_id = $roleId;
                $userRole->save();
                
                $userProfile = new UserProfile;
                $userProfile->user_id = $id;
                $userProfile->save();
                
                $otpMsg = 'Your Otp is'.$otp;            
                $smsObj = new Smsapi();
                $smsObj->sendsms_api($mobile,$otpMsg);
                
                $msg['responseCode'] = "200";
                $msg['responseMessage'] = "Registration is done successfully";
                $msg['userId'] = $id;
                $msg['userName'] = $userName;
                $msg['firstName'] = $firstName;
                $msg['lastName'] = $lastName;
                $msg['email'] = $emailId;
                $msg['mobileNo'] = $mobileNumber;
                $msg['countryCode'] = $countryCode;
                $msg['otp'] = $otp;
                $msg['role'] = $roleId;
                $msg['image'] = 'avatar.jpg';
            } 
        }else{
            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "Failed. All fields are required";
        }
        return $msg;
    }

    public function login(){ 
        $msg = array();
        $emailId = $_POST['emailId'];
        $password = $_POST['password'];

        if(!empty($emailId) || !empty($password)){
            $userDetails = User::where('email',$emailId)->first();
            
            if($userDetails){
                $role = UserRole::where('user_id',$userDetails->id)->select('role_master_id')->first();
                $profile = UserProfile::where('user_id',$userDetails->id)->select('image')->first();
                
                if(!empty($profile->image)){
                    $image = $profile->image;
                }else{
                     $image = 'avatar.jpg';
                }
                
                if($userDetails->is_verified == 1){ 
                    if(Hash::check($password,$userDetails->password)){
                        $msg['responseCode'] = 200;
                        $msg['responseMessage'] = 'Login is done successfully';
                        $msg['userId'] = $userDetails->id;
                        $msg['userName'] = $userDetails->userName;
                        $msg['firstName'] = $userDetails->first_name;
                        $msg['lastName'] = $userDetails->last_name;
                        $msg['email'] = $userDetails->email;
                        $msg['mobileNo'] = $userDetails->mobile;
                        $msg['roleId'] = $role->role_master_id;
                        $msg['image'] = $image;
                    }else{
                        $msg['responseCode'] = "0";
                        $msg['responseMessage'] = "Incorrect Password";
                        
                    }
                }else{
                    $msg['responseCode'] = "100";
                    $msg['responseMessage'] = "Failed. User Not Vrified";
                    $msg['userId'] = $userDetails->id;
                    $msg['userName'] = $userDetails->userName;
                    $msg['firstName'] = $userDetails->first_name;
                    $msg['lastName'] = $userDetails->last_name;
                    $msg['email'] = $userDetails->email;
                    $msg['mobileNo'] = $userDetails->mobile;
                    $msg['roleId'] = $role->role_master_id;
                }
            }else{
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "Failed. Incorrect Email";
            }
        }else{
            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "Failed. All fields are required";
        }
        return $msg;
    }
    
    public function forgotPassword(){ 
        $msg = array();
        $emailId = $_POST['emailId'];
        if($emailId != "" || !empty($emailId)){
            $userDetails = User::where('email',$emailId)->select('mobile','country_code')->first();       
               
            if($userDetails){
                $mobileNumber = $userDetails->mobile;
                $countryCode = $userDetails->country_code;
                $mobile =  $countryCode.$mobileNumber;

                $string = '1234567890';
                $string_shuffled = str_shuffle($string);
                $otp = substr($string_shuffled, 1, 5);

                User::where('email',$emailId)->update(['otp'=>$otp]); 

                $otpMsg = 'Your Otp is'.$otp;

                $smsObj = new Smsapi();
                $smsObj->sendsms_api($mobile,$otpMsg);

                $msg['responseCode'] = "200";
                $msg['responseMessage'] = "Otp is fetched successfully";
                $msg['otp'] = $otp;
            }else{
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "Failed. Email not registered";
            }
        }else{
            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "Failed. Email id reuired";
        }
        return $msg;
    }
    
    public function resetPwd()
    {
        $msg = array();
        $pwd = $_POST['password'];
        $cpwd = $_POST['cpassword'];
        $otp = $_POST['otp'];
        $emailId = $_POST['emailId'];
        
        $userDetail = User::where('otp',$otp)->where('email',$emailId)->select('id')->first(); 
        
        if($userDetail){
            if($pwd === $cpwd){
                User::where('id',$userDetail->id)->update(['password'=>bcrypt($pwd)]);                
                $msg['responseCode'] = "200";
                $msg['responseMessage'] = "Password Successfully reset.";
                $msg['password'] = $pwd;
            }else{
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "New Password and Confirm Password not matched.";
            }
        }else{
            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "Incorrect OTP";
        }
        
        return $msg;
    }
    
     public function customerDashboard()
    {
        $msg = array();
        $banner = Banner::where('status',1)->select('bannerimage')->get();
        $data = ServiceCategory::where('status',1)->where('is_delete',0)->where('parent_id',0)->select('service_category_id','service_category','image')->get();
        $msg['responseCode'] = "200";
        $msg['responseMessage'] = "Services fetched successfully";
        $msg['banners'] = $banner;
        $msg['servicesCategory'] = $data;
        return $msg;
    }
    
    public function serviceList()
    {
        $msg = array();
        $serviceCatId = $_POST['serviceCatId'];
        if(!empty($serviceCatId) || $serviceCatId != 0){
            $data = ServiceCategory::where('status',1)->where('is_delete',0)->where('parent_id',$serviceCatId)->select('service_category_id','service_category','image')->get();
            $msg['responseCode'] = "200";
            $msg['responseMessage'] = "Sub services fetched successfully";
            $msg['subServices'] = $data;
        }else{
            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "Invalid service Category";
        }
        return $msg;
    }
    
    public function productCategory()
    {
        $msg = array();
        $banner = Banner::where('status',1)->select('bannerimage')->get();
        $data = ProductCategory::where('status',1)->where('is_delete',0)->where('parent_id',0)->select('id','title','image')->get();
        $msg['responseCode'] = "200";
        $msg['responseMessage'] = "Product Category fetched successfully";
        $msg['products'] = $data;
        $msg['banners'] = $banner;
        return $msg;
    }
    
    public function productSubCategory()
    {
        $msg = array();
        $productCatId = $_POST['productCatId'];
        if(!empty($productCatId) || $productCatId != 0){
            $data = ProductCategory::where('status',1)->where('is_delete',0)->where('parent_id',$productCatId)->select('id','title','image')->get();
            $msg['responseCode'] = "200";
            $msg['responseMessage'] = "Product Sub Category fetched successfully";
            $msg['products'] = $data;
        }else{
            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "Invalid Category";
        }
        return $msg;
    }
    
    public function productList()
    {
        $msg = array();
        $searchByProduct = array();
        $totalCartItem = 0;
        
        $productCatId = $_POST['productSubCat'];
        $orderBy = $_POST['orderBy']; // price or title
        $status = $_POST['orderType']; // asc or desc
        $searchByPriceMin = $_POST['priceFrom'];
        $searchByPriceMax = $_POST['priceTo'];
        $searchByProduct = $_POST['category'];
        $productType = explode(",",$searchByProduct);
        $userId = $_POST['userId'];
        
        if(!empty($userId)){
            $cartDetail=Cart::select('cd.product_id')->leftJoin('cart_details as cd','cd.cart_id','=','carts.id')->where(['carts.user_id'=>$userId,'carts.status'=>0])->where('cd.product_id','!=','')->get();
            if (count($cartDetail)>0) {
                $cartDetail = $cartDetail->toArray();
            }
            else{
                $cartDetail = '';
            }
            if(!empty($cartDetail)){
            $totalCartItem = count($cartDetail);
            }
        }
        if(!empty($productCatId) || $productCatId != 0){
            #For sorting
            if($orderBy != '' || $status != ''){
                $product=ProductMaster::orderBy($orderBy, $status);
            }else{
                $product=ProductMaster::orderBy('id', 'asc');
            }
            #for Searching
            $product->where('product_category_id',$productCatId);
            //$product->where(['status'=>'1']);
            if($searchByPriceMin != '' || $searchByPriceMax != ''){
                
                $product->Where(function ($query) use ($searchByPriceMin,$searchByPriceMax)  {
                    $query->whereBetween('price',[$searchByPriceMin,$searchByPriceMax]);
                });
                
                
                
                //$product->whereBetween('price', [$searchByPriceMin,$searchByPriceMax]);
                //$product->where('price','<=',$searchByPriceMax);
            }
            if($searchByProduct != ''){
                $product->WhereIn('product_title',  $productType);
            }
            

            $product->select('id','product_title','price','feature_image');
            $data = $product->get();
            $data = $data->toArray();
            if(!empty($data)){
                $msg['responseCode'] = "200";
                $msg['responseMessage'] = "Products data fetched successfully";
                $msg['products'] = $data;
                $msg['totalCartItem'] = "$totalCartItem";
            }else{
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "Failed ! No product found.";
                $msg['totalCartItem'] = "$totalCartItem";
                $msg['products'] = "No Data";
            }
            
        }else{
            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "Invalid Category";
        }
        return $msg;
    }
    
    public function productDetail(Request $request)
    {
        $productCatId = $request->productId;
        $userId=$request->userId;
        try
        {
            $data = ProductMaster::where('status',1)->where('id',$productCatId)->select('id','product_title','price','short_description','description','feature_image')->get();
            $productImage = ProductGallary::where('status',1)->where('product_master_id',$productCatId)->select('image')->get();
            $totalProduct=Cart::select(DB::raw('count(cd.id) as totalProduct'))->join('cart_details as cd','cd.cart_id','=','carts.id')->where(['user_id'=>$userId,'status'=>0])->first();
            $msg['totalProduct']=$totalProduct->totalProduct;
            $msg['responseCode'] = "200";
            $msg['responseMessage'] = "Products detail fetched successfully";
            $msg['products'] = $data;
            $msg['productImages'] = $productImage;
        }
        catch (Exception $e)
        {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "Invalid Category";
        }
        finally
        {
            return $msg;
        }
    }
    
    public function serviceProduct()
    {
        $msg = array();        
        $data = ProductMaster::where('status',1)->where('product_type','service')->select('id','product_title','price','feature_image')->get();
        $msg['responseCode'] = "200";
        $msg['responseMessage'] = "Service Products fetched successfully";
        $msg['products'] = $data;        
        return $msg;
    }
    
    public function saleRequest()
    {
        $msg = array();  
        $dealerId = $_POST['dealerId'];
        $todayDate = date("Y-m-d"); 
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $phoneNumber = $_POST['phoneNumber'];
        $address = $_POST['address'];
        $zipCode = $_POST['zipcode'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $country = $_POST['country'];
        
        if(!empty($dealerId)){
            if(!empty($firstName) || !empty($lastName) || !empty($phoneNumber) || !empty($address) || !empty($zipCode) || !empty($city) || !empty($state) || !empty($country))
            {       
                $sale = new NewSaleRequest();
                $sale->dealer_id = $dealerId;
                $sale->first_name = $firstName;
                $sale->last_name = $lastName;
                $sale->mobile_number = $phoneNumber;
                $sale->address = $address;
                $sale->zipcode = $zipCode;
                $sale->city = $city;
                $sale->state = $state;
                $sale->country = $country;
                $sale->request_date = $todayDate;
                $sale->status = 0;
                $sale->save();
                
                $msg['responseCode'] = "200";
                $msg['responseMessage'] = "Data saved successfully";
                $msg['message'] = 'Your Details has been send to Admin. We will notify you soon.';
            }else{
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "Failed! All details are required.";
            }            
        }else{
            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "Failed! Dealer Id is missing";
        }
        return $msg;
    }

    public function userDetail(){
        $msg = array();  
        $userId = $_POST['userId'];
        $userDetail = User::where('id',$userId)->select('first_name','last_name','mobile')->get();
        if($userDetail){
            $msg['responseCode'] = "200";
            $msg['responseMessage'] = "Data fetch successfully";
            $msg['userDetail'] = $userDetail;
        }else{
            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "Failed! User Id not found";   
        }
        return $msg;
    }
    
    public function getSaveAddress(){
        $msg = array();  
        $userId = $_POST['userId'];
        $userDetail = NewSaleRequest::where('dealer_id',$userId)->first();
        if($userDetail){
            $msg['responseCode'] = "200";
            $msg['responseMessage'] = "Data fetch successfully";
            $msg['userDetail'] = $userDetail;
        }else{
            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "Failed! User Id not found";   
        }
        return $msg;
    }
    
     public function serviceInsuranceRequest()
    {
        $msg = array();
        $packageAmount = array();
        $customerId = $_POST['customerId'];
        $serviceCatId = $_POST['serviceCatId'];
        $serviceSubCatId = $_POST['serviceSubCatId'];
        $vehicleType = $_POST['vehicleType'];
        $brand = $_POST['brand'];
        $model = $_POST['model'];
        $year = $_POST['year'];
        $price = $_POST['price'];
        $drivenKm = $_POST['drivenKm'];
        $ageType = $_POST['ageType'];
        
        if(!empty($customerId)){
            if(!empty($serviceCatId) || !empty($serviceSubCatId) || !empty($ageType) || !empty($vehicleType) || !empty($brand) || !empty($model) || !empty($year))
                {   
//                $dataEists = InsuranceRequest::where('customer_id',$customerId)->first();
//                if($dataEists){
//                   // InsuranceRequest::where('customer_id',$customerId)->delete();
//                    InsuranceRequest::where('customer_id',$customerId)
//                                    ->update(['service_category_id' => $serviceCatId,
//                                                'service_sub_category_id' => $serviceSubCatId,
//                                                'vehicle_type' => $vehicleType,
//                                                'brand' => $brand,
//                                                'model' => $model,
//                                                'year' => $year,
//                                                'price' => $price,
//                                                'driven_km' => $drivenKm,
//                                                'age_type' => $ageType,
//                                                'status' => 0
//                                        ]);
//                    $insuranceId = $dataEists->id;
//                }else{
                    #save data in temprory Table
                    $sale = new InsuranceRequest();
                    $sale->customer_id = $customerId;
                    $sale->service_category_id = $serviceCatId;
                    $sale->service_sub_category_id = $serviceSubCatId;
                    $sale->vehicle_type = $vehicleType;
                    $sale->brand = $brand;
                    $sale->model = $model;
                    $sale->year = $year;
                    $sale->price = $price;
                    $sale->driven_km = $drivenKm;
                    $sale->age_type = $ageType;
                    $sale->status = 0;
                    $sale->save();
                    $insuranceId = $sale->id;
               // }
                    $packageData ='';
                    $modelId = VehicleModel::where('title',$model)->where('status',1)->select('id')->first();
                    if(!empty($modelId)){
                    $packageData = Package::where('service_category_id',$serviceCatId)
                                        ->where('service_sub_category_id',$serviceSubCatId)
                                        ->where('model_id',$modelId->id)
                                        ->where('status', 1)
                                        ->select('base_price','plan_master_id','id')
                                        ->get();
                    $packageDetail = $packageData->toArray();
                    }
                    //echo'<pre>'; print_r($packageDetail); die;
                    if(empty($packageData) || empty($packageDetail)){
                        $packageData = Package::where('service_category_id',$serviceCatId)
                                            ->where('service_sub_category_id',$serviceSubCatId)
                                            ->where('range_lowerlimit', '<=', $price)
                                            ->where('range_uperlimit', '>=', $price)
                                            ->where('status', 1)
                                            ->select('base_price','plan_master_id','id')
                                            ->get();
                        $packageDetail = $packageData->toArray();
                    }
                     
                    if(!empty($packageDetail)){
                        $i=0;
                         foreach($packageData as $package){
                            $drivenCharge ='';
                            $ageCharge = '';
                            
                                $drivenCharge = DrivenKilometerCharge::where('package_id',$package->id)
                                                    ->where('upper_limit', '>=', $drivenKm)
                                                    ->where('lower_limit', '<=', $drivenKm)
                                                    ->where('status', 1)
                                                    ->select('id','package_id','percentage')
                                                    ->first();
//echo'<pre>'; print_r($drivenCharge); die;
                                $ageCharge = TimelyCharge::where('package_id',$package->id)
                                                           ->where('age_type',$ageType)
                                                            ->where('age',$year)
                                                            ->select('id','package_id','percentage_amount')
                                                            ->first();
  // echo'<pre>'; print_r($ageCharge); die;                             
                                if(!empty($drivenCharge) && !empty($ageCharge)){
                                    $plan = PlanMaster::where('id',$package->plan_master_id)->select('plan_title','image','valid_for')->first();
                                    $planName = $plan->plan_title;
                                    $planBaseAmt = $package->base_price;
                                    $planDrivenAmt = $drivenCharge->percentage;
                                    $planDrivenPrice = ($planBaseAmt * $planDrivenAmt)/100;
                                    $planAgePrice = ($planBaseAmt * $ageCharge->percentage_amount)/100;
                                    $packagePrice = $planBaseAmt+$planDrivenPrice+$planAgePrice;
                                    $packageAmount[$i]['package'] = "$planName";
                                    $packageAmount[$i]["packagePrice"] = "$packagePrice";
                                    $packageAmount[$i]["packageId"] = "$package->id";
                                    $packageAmount[$i]["features"] = "Data Not added";
                                    $packageAmount[$i]["packageValidity"] = "$plan->valid_for";
                                    $packageAmount[$i]["image"] = "$plan->image";                                    
                                    $i++;
                                }
//                                else{
//                                    $packageAmount[$i][] = 'No Package Found.';
//                                }  
                                
                            }           
                            $msg['responseCode'] = "200";
                            $msg['responseMessage'] = "Data saved successfully";
                            $msg['insuranceRequestId'] = $insuranceId;
                            $msg['package'] = $packageAmount;
                    }else{
                            $msg['responseCode'] = "200";
                            $msg['responseMessage'] = "Data saved successfully";
                            $msg['package'] = 'No Package Found.';
                    }
                }else{
                    $msg['responseCode'] = "0";
                    $msg['responseMessage'] = "Failed! All details are required.";
                }            
        }else{
            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "Failed! Please Enter Cudtomer ID.";
        }
        return $msg;
    }
    
    public function automobileSearchquery()
    {
        $msg = array();
        $customerId = $_POST['customerId'];
        $data = InsuranceRequest::where('customer_id',$customerId)->get();
        $msg['responseCode'] = "200";
        $msg['responseMessage'] = "Role Details fetched successfully";
        $msg['data'] = $data;
        return $msg;
    }
    
   public function packageDetails()
    {
        $msg = array();
        $data = array();
        $packageId = $_POST['packageId'];
        $packagePrice = $_POST['packagePrice'];
        $disclaimer = GeneralSetting::select('disclaimer','term_condition')->first();
        $planId = Package::where('id',$packageId)->select('plan_master_id')->first();
        $dataPackage = PlanMaster::where('id',$planId->plan_master_id)->select('id','plan_title','plan_description','valid_for','image')->first();
        if($dataPackage){
            $data['packageName'] = "$dataPackage->plan_title";
            $data['packagePrice'] = "$packagePrice";
            $data['packageId'] = $packageId;
            $data['packageValidity'] = "$dataPackage->valid_for months";
            $data['image'] = "$dataPackage->image";
            $data['pakageDetails'] = "$dataPackage->plan_description";
            $data['Disclamer'] = $disclaimer->disclaimer;
            $data['term&Condtions'] = $disclaimer->term_condition;
        }else{
            $data = "No Details found.";
        }
        $msg['responseCode'] = "200";
        $msg['responseMessage'] = "package’s details has fetched successfully";
        $msg['roles'] = $data;
        return $msg;
    }
    
    public function packageComparision()
    {
        $msg = array();
        $feature = array();
        $userId = $_POST['user_id'];
        $packageIds = $_POST['packageId'];
        $packageId = explode(",",$packageIds);
        $packagePrice = array();
        
        foreach($packageId as $packageId){
           
           $dataPackage = PackageFeature::where('package_id',$packageId)->select('package_id','feature_id')->get();
           $feature[$packageId] = PackageFeature::getfeature($dataPackage);
           $packagePrice[$packageId] = PackageFeature::getPackagePrice($packageId, $userId);
        }      
       
        $msg['responseCode'] = "200";
        $msg['responseMessage'] = "package’s details has fetched successfully";
        $msg['feature'] = $feature;
        $msg['price'] = $packagePrice;
        return $msg;
    }
    
    public function saveCustomerPersonalDetails()
    {
        $msg = array();
        $todayDate = date("Y-m-d");
        $userId = $_POST['userId'];
        $packageId = $_POST['packageId'];
        $name = $_POST['name'];
        $emailId = $_POST['emailId'];
        $mobile = $_POST['mobile'];
        $address = $_POST['address'];
        $inspection_no = $_POST['inspection_no'];
        $imageCount = $_POST['imageCount'];
        
        if(!empty($userId) || !empty($name) || !empty($emailId) || !empty($mobile) || !empty($address) || !empty($inspection_no) || !empty($imageCount))
        {
            $data = new InspectionRequest;
            $data->user_id = $userId;
            $data->package_id = $packageId;
            $data->fname = $name;
            $data->email = $emailId;
            $data->mobile_number = $mobile;
            $data->address = $address;
            $data->inspection_no = $inspection_no;
            $data->policy_request_status = 0;
            //$data->request_date = $todayDate;
            $data->created_at = $todayDate;
            $data->updated_at = $todayDate;
            $data->save();
            
            $inspectionId = $data->id;
            
            for($i=1;$i<=$imageCount;$i++){
                $inspectionDocument = new InspectionDocument;
                $pic=Input::file('image'.$i);
                                   
                $extension = $pic->getClientOriginalExtension(); // getting image extension
                $name = time() . rand(111, 999) . '.' . $extension; // renameing image                                       
                $inspectionDocument->image = $name;
                $pic->move(public_path().'/uploads/inspection/',$name);                                
                $inspectionDocument->inspection_request_id = $inspectionId;                
                $inspectionDocument->created_at = $todayDate;
                $inspectionDocument->updated_at = $todayDate;
                $inspectionDocument->save();
            }
            
            $msg['responseCode'] = "200";
            $msg['responseMessage'] = "Successfully save customer personal details";
            $msg['userId'] = $userId;
            $msg['inspectionId'] = $inspectionId;
        }else{
            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "Failed! All Fields are required";
        }
        return $msg;
    }
    
    public function inspectionCharges()
    {
        $msg = array();        
       
        $data = GeneralSetting::select('inspection_charge')->first();
        if($data){
            $msg['responseCode'] = "200";
            $msg['responseMessage'] = "Charges fetched successfully";
            $msg['inspection_charge'] = $data->inspection_charge;
        }else{
            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "No Data Found";
        }
        
        return $msg;
    }
    
    public function roleList()
    {
        $msg = array();
        $data = RoleMaster::where('status',1)->where('is_delete',0)->select('id','role')->get();
        $msg['responseCode'] = "200";
        $msg['responseMessage'] = "Role Details fetched successfully";
        $msg['roles'] = $data;
        return $msg;
    }
    
    public function testApi()
    {
        $mobile = $_POST['phone'];
        $otpMsg = $_POST['msg'];
        $smsObj = new Smsapi();
        $smsObj->sendsms_api($mobile,$otpMsg);
    }
    
    public function testEmail()
    {
        $email = $_POST['email'];
        $msg = $_POST['msg'];
        
        Mail::send(['email' => $email], function ($m) use ($email) {
            $m->from('hello@app.com', 'Your Application');
            $m->to($email, 'Anjali')->subject('Your Reminder!');
        });
    }

    function addToCart(Request $request)
    {
        $productId=$request->ProductId;
        $quantity=$request->ProductQuntity;
        $userId=$request->userId;
        $productPrice=$request->Price;
        $newPrice=$productPrice*$quantity;
        DB::beginTransaction();
        try
        { 
            $price=Cart::select('total_amount','id')->where(['user_id'=>$userId,'status'=>0])->first();

            if(count($price) && count($price) != 0)
            { 
                $cartID=$price->id;
                $newPrice +=$price->total_amount;
                Cart::where(['user_id'=>$userId,'status'=>0])->update(['total_amount'=>$newPrice]);
            }
            else
            {
                $cart=new Cart;
                $cart->user_id=$userId;
                $cart->total_amount=$newPrice;
                $cart->role_master_id=$request->roleId;
                $cart->save();
                $cartID=$cart->id;
            }

            $quantities=CartDetail::select('quantity')->where(['cart_id'=>$cartID,'product_id'=>$productId])->first();
            if(count($quantities))
            {
                $quantity += $quantities->quantity;
                CartDetail::where(['cart_id' => $cartID, 'product_id' => $productId])->update(['quantity' => $quantity]);
            }
            else
            {
                $cartDetail=new CartDetail;
                $cartDetail->cart_id=$cartID;
                $cartDetail->product_id=$productId;
                $cartDetail->quantity=$quantity;
                $cartDetail->price=$productPrice;
                $cartDetail->save();
            }
            $totalProduct=CartDetail::select(DB::raw('count(id) as totalProduct'))->where(['cart_id'=>$cartID])->first();

            DB::commit();
            $msg['totalProduct']=$totalProduct->totalProduct;
            $msg['responseCode']='200';
            $msg['responseMessage']='Item Successfully added in the cart';
        }
        catch (\Exception $e)
        {
           // print_r($e);
            DB::rollback();
            $msg['responseCode']='0';
            $msg['responseMessage']='Failed.Item not added,Please Try Again';
        }
        return $msg;
    }

    function CartList(Request $request)
    {
        $userId=$request->userId;
        $cartDetail=Cart::select('carts.total_amount','cd.product_id','cd.quantity','cd.price')->leftJoin('cart_details as cd','cd.cart_id','=','carts.id')->where(['carts.user_id'=>$userId,'carts.status'=>0])->get();
        $totalProduct=0;
        $totalAmount=0;
        $cartData=array();        
        if(count($cartDetail))
        { 
            $i=0;
            foreach($cartDetail as $cart):
                if(!empty($cart->product_id)){
                  //  $cartData = array();
                    $cartData[$i]['productId']=$cart->product_id;
                    $cartData[$i]['productName']=$cart->product->product_title;
                    $cartData[$i]['productPrice']=$cart->price;
                    $cartData[$i]['productQuntity']=$cart->quantity;
                    $cartData[$i]['productImage']=$cart->product->feature_image;
                    $totalAmount=$cart->total_amount;
                    $totalProduct +=1;
                    $i++;
                }
            endforeach;
            if(empty($cartData)){
                $cartData = 'No item found';
            }
            $msg['responseCode']='200';
            $msg['responseMessage']='cart list data get successfully';
            $msg['totalProductCount']="$totalProduct";
            $msg['TotalAmount']=$totalAmount;
            $msg['cartList']=$cartData;
        }
        else
        {
            $msg['responseCode']='0';
            $msg['responseMessage']='Failed,No record available, Please Try Again';
        }
        return $msg;
    }

    function updateCart(Request $request)
    {
        $productId=$request->ProductId;
        $quantity=$request->ProductQuntity;
        $userId=$request->userId;
        $roleId=$request->roleId;
        $status=$request->status;
        $cart=Cart::where(['user_id'=>$userId,'status'=>0])->select('id')->first();
        $cartId=$cart->id;
        DB::beginTransaction();
        if($status=='remove')
        {
            try
            {
                CartDetail::where(['cart_id'=>$cartId,'product_id'=>$productId])->delete();
                $price=CartDetail::select('quantity','price')->where(['cart_id'=>$cartId])->get();
                $total=0;
                foreach ($price as $pric)
                {
                    $total +=  $pric->quantity*$pric->price;
                }

                Cart::where(['id'=>$cartId,'status'=>0])->update(['total_amount'=>$total]);
                $totalProduct=CartDetail::select(DB::raw('count(id) as totalProduct'))->where(['cart_id'=>$cartId])->first();
                $msg['totalProduct']=$totalProduct->totalProduct;
                $msg['responseCode']='200';
                $msg['status']='remove';
                $msg['responseMessage']='item deleted  successfully';
                DB::commit();
            }
            catch(\Exception $e)
            {
                DB::rollback();
                $msg['responseCode']='0';
                $msg['status']='remove';
                $msg['responseMessage']='Failed.item not deleted,Please Try Again';
            }
            finally
            {
                return $msg;
            }
        }
        else
        {
            try
            {
                CartDetail::where(['cart_id'=>$cartId,'product_id'=>$productId])->update(['quantity'=>$quantity]);
                $price=CartDetail::select('quantity','price')->where(['cart_id'=>$cartId])->get();
                $total=0;
                foreach ($price as $pric)
                {
                    $total +=  $pric->quantity*$pric->price;
                }

                Cart::where(['id'=>$cartId,'status'=>0])->update(['total_amount'=>$total]);
                $totalProduct=CartDetail::select(DB::raw('count(id) as totalProduct'))->where(['cart_id'=>$cartId])->first();
                $msg['totalProduct']=$totalProduct->totalProduct;
                $msg['responseCode']='200';
                $msg['status']='update';
                $msg['responseMessage']='item updated  successfully';
                DB::commit();
            }
            catch (\Exception $e)
            {
                DB::rollback();
                $msg['responseCode']='0';
                $msg['status']='update';
                $msg['responseMessage']='Failed.item not updated,Please Try Again';
            }
            finally
            {
                return $msg;
            }
        }
    }

    function ReadyToPayment(Request $request)
    {
        //dd($request->product);
        $userId=$request->userId;
        $roleId=$request->roleId;
        $totalAmount=$request->totalAmount;
        $cart=Cart::where(['user_id'=>$userId,'total_amount'=>$totalAmount])->select('id')->first();
        if(count($cart))
        {
            $counter=1;
            foreach ($request->cartList as $product)
            {
                $cartDetail=CartDetail::select('id')->where(['cart_id'=>$cart->id,'product_id'=>$product['productId'],'quantity'=>$product['productQuntity']])->first();
                if(count($cartDetail))
                {
                    $counter=0;
                }
            }
            if($counter)
            {
                $msg['responseCode']='0';
                $msg['responseMessage']='Failed.Pruduct or Quantity not matched,Please Try Again';
            }
            else
            {
                $msg['responseCode']='200';
                $msg['responseMessage']='successfully  save data and waiting for admin approval';
            }
        }
        else
        {
            $msg['responseCode']='0';
            $msg['responseMessage']='Failed.Total amount or user not matched,Please Try Again';
        }
        return $msg;

    }

    function Redemption(Request $request)
    {
        $userId=$request->userId;
        $status=$request->status;//'all','pending','approved','rejected','closed','assigned'
        $where=['dealer_assinged'=>$userId];
        if($status=='all')
        {
            $where=array_merge($where,['status'=>$status]);
        }
        $redemption=PolicyRedemptionRequest::where($where)->get();
        if(count($redemption))
        {
            $i=0;
            foreach($redemption as $redempt)
            {
                $data[$i]['id']=$redempt->id;
                $data[$i]['policyNumber']=$redempt->policyNumber->policy_number;
                $data[$i]['policyName']=$redempt->policyNumber->name;
                $data[$i]['requestDate']=$redempt->request_date;
                $data[$i]['description']=$redempt->description;
                $data[$i]['estimatedAmount']=$redempt->estimated_amount;
                $data[$i]['amountApprovedByAdmin']=$redempt->amount_approved_by_admin;
                $data[$i]['dealerAmount']=$redempt->dealer_amount;
                $data[$i]['status']=$redempt->status;
                $data[$i]['dealerAssignedDate']=$redempt->dealer_assigned_date;
                $data[$i]['adminApprovedDate']=$redempt->admin_approved_date;
                $data[$i++]['DaysRequired']=$redempt->no_of_days_required;
            }
            $msg['redemption']=$data;
            $msg['responseCode']='200';
            $msg['responseMessage']='Redemption list data get successfully';
        }
        else
        {
            $msg['responseCode']='0';
            $msg['responseMessage']='No record Found';
        }
        return $msg;
    }

    function newSaleRequest(Request $request)
    {
        DB::beginTransaction();
        try
        {
            Cart::where('user_id',$request->userId)->update(['status'=>1]);
            $userId=$request->userId;
            $cart=Cart::select('id')->where(['user_id'=>$userId,'status'=>1])->orderBy('id','desc')->first();
            $countryCode = $request->Country;
//             if (substr($countryCode, 0, 1) === '+'){
//                $countryCode = $countryCode;
//            }else{
//                $countryCode = '+'.$countryCode;
//            }
            
            $userDetails = User::where('id',$userId)->select('email')->first();
             $emailId = $userDetails->email;
                       
            $newSale = new NewSaleRequest;
            $newSale->dealer_id =$userId ;
            $newSale->first_name = $request->Fname;
            $newSale->last_name = $request->Lname;
            $newSale->mobile_number = $request->phoneNumber;
            $newSale->address = $request->address;
            $newSale->zipcode = $request->zipCode;
            $newSale->city = $request->city;
            $newSale->state = $request->state;
            $newSale->country = $countryCode;
            $newSale->request_date = date('Y-m-d H:i:s');
            $newSale->cart_id = $cart->id;
            $newSale->status = 0;
            $newSale->save();
            $saleId = $newSale->id;
            
            $from_email = 'admin@wecare.com';
            $from_name = 'Wecare Team';
            $base_host = $_SERVER['SERVER_NAME'];                    
            $uemail = $emailId;
            $data = array(
                'msg' => 'Your request has been sucessfully sent to Admin. We will inform you once approve by admin.',
                'image' => ''
            );

            $pageuser = 'emails.welcome';
            $subject = 'Wecare Policy Confirmation';
            $smsObj = new Smsapi();
            $smsObj->sendusermail($pageuser,$data,$subject,$from_email,$from_name,$uemail);  
            
            DB::commit();
            $msg['responseCode'] = '200';
            $msg['responseMessage'] = 'successfully  save data and wating for admin approval';
            $msg['newSaleRequestId'] = $saleId;
        }
        catch(Exception $e)
        {
            DB::rollback();
            $msg['responseCode']='0';
            $msg['responseMessage']='Failed.Data not save,Please Try Again';
            $msg['error']=$e;
        }
        finally
        {
            return $msg;
        }
    }
    
    

    function RedemptionRequestAgainstFault(Request $request)
    {
        try
        {
            $subcategory=$request->subCategoryId;
            $fault=Fault::select('id','fault','price')->where(['services_category_id'=>$subcategory,'status'=>1,'is_delete'=>0])->get();
            if($fault->count())
            {
                $msg['responseCode']='200';
                $msg['responseMessage']='Fetch Insurance Request Data Successfully';
                $msg['fault']=$fault;
            }
            else
            {
                $msg['responseCode']='0';
                $msg['responseMessage']='No Record available';
            }
        }
        catch(Exception $e)
        {
            $msg['error'] =$e;
            $msg['responseCode']='0';
            $msg['responseMessage']='Failed. Please Try Again';
        }
        finally
        {
            return $msg;
        }
    }

    function InspectionRequest(Request $request)
    {
        try
          {
              $imageCount=$request->imageCount;
              $imagesName='';
              $todayDate = date("Y-m-d");
              $userId=$request->userId;
              $roleId=$request->roleId;
              $emailId = $request->emailId;
              $mobileNumber = $request->mobile;
              
              $generalSetting = GeneralSetting::select('inspection_charge')->first();
              
              if($roleId == 2){
                  
                  $userEmail = User::where('email',$emailId)->first();
                  //$userMobile = User::where('mobile',$mobileNumber)->first();
                 if (count($userEmail)>0){

                      $userEmail = $userEmail->toArray();
                  }
                  else{
                      $userEmail = '';
                  }
                 // $userMobile = $userMobile->toArray();
                  if((empty($userEmail) || $userEmail == '') &&(empty($mobileNumber) || $mobileNumber == ''))
                  {
                    $string = '1234567890';
                    $string_shuffled = str_shuffle($string);
                    $otp = substr($string_shuffled, 1, 5);
                    $mobile = '+91'.$request->mobile;
                    $otpMsg = 'Your Password is'.$otp;

                    $smsObj = new Smsapi();
                    $smsObj->sendsms_api($mobile,$otpMsg);
                  
                    $user = new User;
                    $user->userName = $request->Fname;
                    $user->first_name = $request->Fname;
                    $user->last_name = $request->Lname;
                    $user->email = $request->emailId;
                    $user->password = bcrypt($otp);
                    $user->mobile = $request->mobile;
                    $user->country_code = '+91';
                    $user->otp = $otp;
                    $user->status = 1;
                    $user->is_delete = 0;
                    $user->save();
                    $userId= $user->id;
                                    
                    $userRole = new UserRole;
                    $userRole->user_id = $userId;
                    $userRole->role_master_id = $roleId;
                    $userRole->save();
                  }
              }
              $inspection=new InspectionRequest;
              $inspection->user_id=$userId;
              $inspection->insurance_id=$request->insuranceId;
              $inspection->fname=$request->Fname;
              $inspection->lname=$request->Lname;
              $inspection->email=$request->emailId;
              $inspection->mobile_number=$request->mobile;
              $inspection->address=$request->address;
              $inspection->policy_type=$request->policyType;
              $inspection->inception_for=$request->inspectionFor;
              $inspection->package_id = $request->packageId; 
              $inspection->policy_request_status = 0;
              $inspection->inspection_charges = $generalSetting->inspection_charge!='' ? $generalSetting->inspection_charge : '';
              $inspection->save();
              
              $inspectionId = $inspection->id;
            
            for($i=1;$i<=$imageCount;$i++){
                $inspectionDocument = new InspectionDocument;
                $pic=Input::file('image'.$i);
                     //    echo'<pre>'; print_r($pic);          
                $extension = $pic->getClientOriginalExtension(); // getting image extension
                $name = time() . rand(111, 999) . '.' . $extension; // renameing image                                       
                $inspectionDocument->image = $name;
                $pic->move(public_path().'/uploads/inspection/',$name);                                
                $inspectionDocument->inspection_request_id = $inspectionId;                
                $inspectionDocument->created_at = $todayDate;
                $inspectionDocument->updated_at = $todayDate;
                $inspectionDocument->save();
            }
              
              $msg['responseCode']='200';
              $msg['requestId']=$inspection->id;
              $msg['userId']=$userId;
              $msg['responseMessage']='your request has been sent successfully ';
          }
          catch(Exception $e)
          {
              $msg['error'] =$e;
              $msg['responseCode']='0';
              $msg['responseMessage']='Failed. Please Try Again';
          }
         finally
          {
              return $msg;
          }
    }

    function PurchaseList(Request $request)
    {
        try
        {
            $userId=$request->userId;
            $purchaseList=Cart::select(DB::raw('carts.total_amount as totalAmount'),'nsr.id as requestId',DB::raw('CASE nsr.status WHEN "0" THEN "Pending" WHEN "2" THEN "Rejected" END as status' ) )->join('new_sale_requests as nsr','nsr.cart_id','=','carts.id')->join('cart_details as cd','carts.id','=','cd.cart_id')->where(['carts.user_id'=>$userId,'carts.status'=>1])->where(['nsr.dealer_id'=>$userId])->where('nsr.status','<>',1)->groupBy('nsr.id')->get();
            $orderList = Order::select('orders.id','orders.total_amount','orders.order_status as status')
                        ->where('orders.user_id',$userId)
                        ->get();
            if($purchaseList->count() or $orderList->count())
            {
                $msg['purchaseList']=$purchaseList;
                $msg['orderHistory']=$orderList;
                $msg['responseCode']='200';
                $msg['responseMessage']='puchase list successfully fetched';
            }
            else
            {
                $msg['responseCode']='0';
                $msg['responseMessage']='No record available';
            }
        }
        catch(Exception $e)
        {
            $msg['error'] =$e;
            $msg['responseCode']='0';
            $msg['responseMessage']='Failed. Please Try Again';
        }
        finally
        {
            return $msg;
        }
    }

    function PurchaseDetail(Request $request)
    {
          try
          {
              $userId=$request->userId;
              $requestId=$request->requestId;
              $totalAmt= '';
              $purchaseDetailList=Cart::select('cd.*')->join('new_sale_requests as nsr','nsr.cart_id','=','carts.id')->join('cart_details as cd','carts.id','=','cd.cart_id')->where(['carts.user_id'=>$userId,'carts.status'=>1])->where(['nsr.dealer_id'=>$userId,'nsr.id'=>$requestId])->where('nsr.status','<>',1)->get();
              $i=0;
              foreach ($purchaseDetailList as $list)
              {
                  $data[$i]['productName']=$list->product->product_title;
                  $data[$i]['productQuantity']=$list->quantity;
                  $data[$i]['productPrice']=$list->price;
                  $data[$i]['cartId']=$list->cart_id;
                  $data[$i++]['image']=$list->product->feature_image;
                  
                  $totalAmt = $totalAmt+$list->price;
              }
              $msg['purchaseDetailList']=$data;
              $msg['totalAmt']=$totalAmt;
              $msg['responseCode']='200';
              $msg['responseMessage']='purchase detail list successfully fetched';
          }
          catch(Exception $e)
          {
              $msg['error'] =$e;
              $msg['responseCode']='0';
              $msg['responseMessage']='Failed. Please Try Again';
          }
          finally
          {
              return $msg;
          }
    }

    function insuranceRedemption(Request $request)
    {
        try
        {
            $userId=$request->userId;
            $policyNo=$request->policyNo;
            $date=date('Y-m-d H:i:s');
            $policy=Policy::select('policy_expiry_date')->where(['policy_number'=>$policyNo,'user_id'=>$userId])->first();
//            echo '<pre>';
//            print_r($policy);
//            die;
            if(count($policy))
            {
                if($policy->policy_expiry_date<$date)
                {
                    $msg['responseCode']='0';
                    $msg['responseMessage']='Your Policy has been expired. Please renew your policy';
                }
                else
                {
                    $msg['responseCode']='200';
                    $msg['responseMessage']='Insurance Redemption data sent successfully';
                }
            }
            else
            {
                $msg['responseCode']='0';
                $msg['responseMessage']='Policy does\'nt exist';
            }
        }
        catch(Exception $e)
        {
            $msg['msg'] =$e;
            $msg['responseCode']='0';
            $msg['responseMessage']='Failed. Please Try     Again';
        }
        finally
        {
            return $msg;
        }
    }

    public function sendInspectionOtp()
    {
        $userId = $_POST['userId'];
        $mobileNumber = $_POST['mobileNo'];
        $countryCode = $_POST['countryCode'];
       
        $msg = array();
        $otpMsg = '';
        if($mobileNumber != "" || !empty($mobileNumber) || $countryCode != "" || !empty($countryCode)){
            
             if (substr($countryCode, 0, 1) === '+'){
                $countryCode = $countryCode;
            }else{
                $countryCode = '+'.$countryCode;
            }
            $mobile =  $countryCode.$mobileNumber;
            
            $string = '1234567890';
            $string_shuffled = str_shuffle($string);
            $otp = substr($string_shuffled, 1, 5);

            $data = new UserOtpTemp;
            $data->mobile = $mobile;
            $data->user_id = $userId;
            $data->otp = $otp;
            $data->save();
            
            $otpMsg = 'Your Otp is'.$otp;

            $smsObj = new Smsapi();
            $smsObj->sendsms_api($mobile,$otpMsg);


            $msg['responseCode'] = "200";
            $msg['responseMessage'] = "Otp is fetched successfully";
            $msg['otp'] = $otp;

        }else{
            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "Failed.Please enter mobile number with country code";
        }
        return $msg;

    }
    
    public function inspectionOtpVerification(){
        $otp = $_POST['otp'];
        $userId = $_POST['userId'];
        $msg = array();
        
        if($userId != "" || !empty($userId))
        {
            $data=UserOtpTemp::where('user_id',$userId)->where('otp',$otp)->select('id')->first();
        }
        else
        {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "Failed.Please enter mobile number with country code";
        }
        if($data)
        {
            UserOtpTemp::where('id',$data->id)->delete();             
            $msg['responseCode'] = "200";
            $msg['responseMessage'] = "Verification successfully";
        }
        else
        {
            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "Failed.Invalid OTP";
        }
        return $msg;
    }

    function redemptionWithPolicy(Request $request)
    {
        try
        {
            $userId=$request->userId;
            $serviceCategoryId=$request->serviceCategoryId;
            $policyNo=$request->policyNo;
            $package=Policy::select('pm.plan_title','pm.image','pm.valid_for','p.id','p.service_sub_category_id')->join('packages as p','p.id','=','policies.package_id')->join('plan_masters as pm','pm.id','=','p.plan_master_id')->where('policies.policy_number',$policyNo)->first();
            //echo($package->service_sub_category_id);die;
            $fault=Fault::select('fault','id')->where(['services_category_id'=>$package->service_sub_category_id,'status'=>1,'is_delete'=>0])->get();
            if(count($fault)):
                $msg['responseCode']="200";
                $msg["responseMessage"]='Insurance Redemption data get successfully';
                $msg['packageName']=$package->plan_title;
                $msg['packageId']=$package->id;
                $msg['packageValidity']=$package->valid_for>1 ? $package->valid_for.' Months' : $package->valid_for.' Month';
                $msg['image']=$package->image;
                $msg['faultList']=$fault;
            else:
                $msg['responseCode']="0";
                $msg["responseMessage"]='No Record Found';
            endif;
        }
        catch (Exception $e)
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
    
    # Mobile Insurance API
    /*public function mobileInsuranceRequest()
    { 
        $msg = array();
        $customerId = $_POST['customerId'];
        $serviceCatId = $_POST['serviceCatId'];
        $serviceSubCatId = $_POST['serviceSubCatId'];
        $model = $_POST['model'];
        $year = $_POST['purchaseTime'];
        $price = $_POST['price'];
        $ageType = $_POST['ageType'];
        
        if(!empty($customerId)){ 
            if(!empty($serviceCatId) || !empty($serviceSubCatId) || !empty($ageType) || !empty($model) || !empty($price) || !empty($year))
                {   
                    InsuranceRequest::where('customer_id',$customerId)->delete();
                    #save data in temprory Table
                    $sale = new InsuranceRequest();
                    $sale->customer_id = $customerId;
                    $sale->service_category_id = $serviceCatId;
                    $sale->service_sub_category_id = $serviceSubCatId;
                    $sale->vehicle_type = 'Mobile';
                    $sale->model = $model;
                    $sale->year = $year;
                    $sale->price = $price;
                    $sale->age_type = $ageType;
                    $sale->status = 0;
                    $sale->save();
                
                    $packageData ='';
                    $modelId = MobileModel::where('model',$model)->where('status',1)->select('model_id')->first();
                    if(!empty($modelId)){
                    $packageData = Package::where('service_category_id',$serviceCatId)
                                        ->where('service_sub_category_id',$serviceSubCatId)
                                        ->where('model_id',$modelId->id)
                                        ->where('status', 1)
                                        ->select('base_price','plan_master_id','id')
                                        ->get();
                    $packageDetail = $packageData->toArray();
                    }
                    //echo'<pre>'; print_r($packageDetail); die;
                    if(empty($packageData) || empty($packageDetail)){
                        $packageData = Package::where('service_category_id',$serviceCatId)
                                            ->where('service_sub_category_id',$serviceSubCatId)
                                            ->where('range_lowerlimit', '<=', $price)
                                            ->where('range_uperlimit', '>=', $price)
                                            ->where('status', 1)
                                            ->select('base_price','plan_master_id','id')
                                            ->get();
                        $packageDetail = $packageData->toArray();
                    }
                     //echo'<pre>'; print_r($packageData); die;
                    if(!empty($packageDetail)){
                        $i=0;
                         foreach($packageData as $package)
                         {
                            $ageCharge = '';                            
                            $ageCharge = TimelyCharge::where('package_id',$package->id)
                                                           ->where('age_type',$ageType)
                                                            ->where('age',$year)
                                                            ->select('id','package_id','percentage_amount')
                                                            ->first();
                                if(!empty($ageCharge))
                                {
                                    $plan = PlanMaster::where('id',$package->plan_master_id)->select('plan_title','image','valid_for')->first();
                                    $planName = $plan->plan_title;
                                    $planBaseAmt = $package->base_price;
                                    $planAgePrice = ($planBaseAmt * $ageCharge->percentage_amount)/100;
                                    $packagePrice = $planBaseAmt+$planAgePrice;
                                    $packageAmount[$i]['package'] = "$planName";
                                    $packageAmount[$i]["packagePrice"] = "$packagePrice";
                                    $packageAmount[$i]["packageId"] = "$package->id";
                                    $packageAmount[$i]["features"] = "Data Not added";
                                    $packageAmount[$i]["packageValidity"] = "$plan->valid_for months";
                                    $packageAmount[$i]["image"] = "$plan->image";
                                    $i++;
                                    Session::set($package->id, $packagePrice);
                                }
                                else
                                {
                                    $packageAmount[] = 'No Package Found.';
                                }                
                            }
                            $msg['responseCode'] = "200";
                            $msg['responseMessage'] = "Data saved successfully";
                            $msg['package'] = $packageAmount;
                    }else{
                            $msg['responseCode'] = "200";
                            $msg['responseMessage'] = "Data saved successfully";
                            $msg['package'] = 'No Package Found.';
                    }
                }else{
                    $msg['responseCode'] = "0";
                    $msg['responseMessage'] = "Failed! All details are required.";
                }            
        }else{
            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "Failed! Please Enter Cudtomer ID.";
        }
        return $msg;
    }*/

    function mobilePossibleFaultList(Request $request)
    {
        try
        {
            $policyNo=$request->policyNo;
            $package=Policy::select('pm.plan_title','pm.image','pm.valid_for','p.id')->join('packages as p','p.id','=','policies.package_id')->join('plan_masters as pm','pm.id','=','p.plan_master_id')->where('policies.policy_number',$policyNo)->first();
            $mobileFaultTypes=MobileFaultType::select('id','name')->get();
            if(count($mobileFaultTypes)):
                $i=0;
               foreach ($mobileFaultTypes as $types):
                $data[$i]['faultTypeId']=$types->id;
                $data[$i++]['name']=$types->name;
               endforeach;
                $msg['packageName']=$package->plan_title;
                $msg['packageId']=$package->id;
                $msg['packageValidity']=$package->valid_for>1 ? $package->valid_for.' Months' : $package->valid_for.' Month';
                $msg['image']=$package->image;
                $msg['responseCode']="200";
                $msg["responseMessage"]='Possible fault list get successfully';
                $msg['faultList']=$data;
            else:
                $msg['responseCode']="0";
                $msg["responseMessage"]='No Record Found';
            endif;
        }
        catch(\Exception $e)
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

    function mobileFaultTypeWise(Request $request)
    {
        try
        {
            $faultTypeId=$request->faultTypeId;
            $fault=Feature::where('status',1)->where('is_delete',0)->select('feature_id as fault_id','feature as fault_name' , 'price as charge');
            if($faultTypeId==0)
            {
                $fault->whereNotNull('mobile_fault_type_id');
            }
            elseif($faultTypeId==3)
            {
                $fault->where('mobile_fault_type_id',1);
                $fault->where('mobile_fault_type_id',2);
            }
            else
            {
               $fault->where('mobile_fault_type_id',$faultTypeId);
            }
            $fault=$fault->get();
            if(count($fault)):
                $msg['responseCode']="200";
                $msg["responseMessage"]='Fault List get successfully';
                $msg['faultList']=$fault;
            else:
                $msg['responseCode']="0";
                $msg["responseMessage"]='No Record Found';
            endif;
        }
        catch (\Exception $e)
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

    function brand(Request $request)
    {
          try
          {
              if($request->type=='mobile')//mobile or automobile
              {
                  $brand=MobileManufacturer::select(DB::raw('manufacturer_id as id'),DB::raw('manufacturer as title'))->where(['status'=>1,'is_delete'=>0])->get();
              }
              else
              {
                  $brand=VehicleManufacturer::select('id','title')->where('status',1)->get();
              }
              
              if(count($brand))
              {
                  $msg['responseCode']="200";
                  $msg["responseMessage"]='Brand List get successfully';
                  $msg['brandList']=$brand;  
              }
              else
              {
                  $msg['responseCode']="0";
                  $msg["responseMessage"]='No Record Found';  
              }
          }
          catch (Exception $e)
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

    function model(Request $request)
    {
        try
        {
            if($request->type=='mobile')//mobile or automobile
            {
                $model=MobileModel::select(DB::raw('model_id as id'),DB::raw('model as title'))->where(['status'=>1,'is_delete'=>0,'manufacturer_id'=>$request->id])->get();//brandId
            }
            else
            {
                $model=VehicleModel::select('id','title')->where('status',1)->where('vahicle_manufacturer_id',$request->id)->get();
            }
            if(count($model))
            {
                $msg['responseCode']="200";
                $msg["responseMessage"]='Model List get successfully';
                $msg['modelList']=$model;
            }
            else
            {
                $msg['responseCode']="0";
                $msg["responseMessage"]='No Record Found';
            }
        }
        catch (Exception $e)
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

    function redemptionRequestPolicy(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $faultIds=explode(',',$request->faultId);
            $userId = $request->userId;
            $i=0;
            $date=date('Y-m-d H:i:s');
            $policyNo= $request->policyNo;
            $policies=Policy::select('id')->where('policy_number',$policyNo)->first();
            $policyId = $policies->id;
            $policyRedemptionData =  PolicyRedemptionRequest::where('policy_id',$policyId)->select('id')->first();
            
            $userDetails = User::where('id',$userId)->select('email','mobile','country_code')->first();
            $emailId = $userDetails->email;
            $mobileNumber = $userDetails->country_code.$userDetails->mobile;
            
            if(!empty($policyRedemptionData)){
             $msg['responseCode']="0";
             $msg["responseMessage"]='Insurance Redemtion request already sent.';
            }else{
            $redemdata = new PolicyRedemptionRequest;
            $redemdata->policy_id = $policies->id;
            $redemdata->request_date = $date;
            $redemdata->user_id = $request->userId;
            $redemdata->save();
            $redemPolicyid= $redemdata->id;
            
            foreach ($faultIds as $faultId):
                $data[$i]['policy_redemption_request_id'] = $redemPolicyid;
                $data[$i]['user_id']=$request->userId;
                $data[$i]['package_id']=$request->packageId;
                $data[$i]['policy_no']=$request->policyNo;
                $data[$i]['remarks']=$request->remarks;
                $data[$i]['created_at']=$date;
                $data[$i]['updated_at']=$date;
                $data[$i++]['fault_id']=$faultId;
            endforeach;
            //print_r($data);die;
            RedemptionPolicyRequest::insert($data);
            
            $from_email = 'admin@wecare.com';
            $from_name = 'Wecare Team';
            $base_host = $_SERVER['SERVER_NAME'];                    
            $uemail = $emailId;
            $data = array(
                'msg' => 'Your request has been sent successfully',
                'image' => ''
            );

            $pageuser = 'emails.welcome';
            $subject = 'Automobile Redemtion Request';
            $smsObj = new Smsapi();
            $smsObj->sendusermail($pageuser,$data,$subject,$from_email,$from_name,$uemail);  
            $userMsg = 'Your request has been sent successfully';
            $smsObj->sendsms_api($mobileNumber,$userMsg);
            
            DB::commit();
            $msg['responseCode']="200";
            $msg["responseMessage"]='Insurance Redemption request sent successfully';
           }
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg['responseCode']="0";
            $msg["responseMessage"]='Failed,Please Try Again';
            $msg['error']=$e;
        }
        finally
        {
            return $msg;
        }
    }

    function changePassword(Request $request)
    {
       try
       {
           $userId=$request->userId;
           $oldPassword=$request->oldPassword;
           $newPassword=$request->newPassword;
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
               $msg["responseMessage"]='Old Password is incorrect';
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

    function updateMyAccount(Request $request)
    {
          DB::beginTransaction();
          try
          {
              $userId=$request->userId;
              $set=['userName'=>$request->username,'address'=>$request->address,'mobile'=>$request->mobile];
              if($request->about)
              {
                  $about=['about'=>$request->about];
                  $set=array_merge($set,$about);
              }

              User::where('id',$userId)->update($set);
              $pic=Input::file('profileImag');
             // echo $pic->getClientOriginalExtension();die;
              if($pic)
              {
                  $destinationpath=public_path()."/uploads/profile";
                  $extension = $pic->getClientOriginalExtension(); // getting image extension
                  $filename = time() . rand(111, 999) . '.' . $extension; // renameing image
                  $pic->move($destinationpath, $filename);
                  $userProfile=UserProfile::select('id')->where('user_id',$userId)->first();
                  if(count($userProfile))
                  {
                      UserProfile::where('user_id',$userId)->update(['image'=>$filename]);
                  }
                  else
                  {
                      $user=new UserProfile;
                      $user->user_id=$userId;
                      $user->image=$filename;
                      $user->save();
                  }
              }
              $msg['responseCode']="200";
              $msg["responseMessage"]='Profile Data updated successfully';
              DB::commit();
          }
          catch(Exception $e)
          {
              DB::rollback();
              $msg['responseCode']="0";
              $msg["responseMessage"]='Failed,Please Try Again';
              $msg['error']=$e;
          }
          finally
          {
              return $msg;
          }
    }

    function getMyAccount(Request $request)
    {
        
        try
        {
            $userId=$request->userId;
            $userDetail=User::select('users.first_name','users.last_name','users.userName','users.email','users.mobile','users.address','users.created_at','users.about','uf.image')->leftJoin('user_profiles as uf','uf.user_id','=','users.id')->where('users.id',$userId)->first();
            $policies=Policy::select('policy_number')->where('user_id',$userId)->get();
            $msg['username']=$userDetail->userName!='' ? $userDetail->userName : '';
            $msg['firstName']=$userDetail->first_name;
            $msg['lastName']=$userDetail->last_name;
            $msg['address']=$userDetail->address;
            $msg['mobile']=$userDetail->mobile;
            $msg['about']=!is_null($userDetail->about) ? $userDetail->about : '';
            $msg['profileImag']=$userDetail->image!='' ? $userDetail->image : 'avatar.jpg';
            $msg['email']=$userDetail->email;
            $msg['joined']=date('d/m/Y',strtotime($userDetail->created_at));
            $msg['policyNo']=count($policies) ? $policies : '';
            $msg['responseCode']="200";
            $msg["responseMessage"]='Profile Data get successfully';
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
    
    function redemptionList(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $faultIds=explode(',',$request->faultId);
            $faultTypeId = $request->faultTypeId;
            $i=0;
            $totalAmount=0;
            $date=date('Y-m-d H:i:s');

            $mobileRedem = new MobileRedemptionPolicyRequest;
            $mobileRedem->user_id = $request->userId;
            $mobileRedem->package_id = $request->packageId;
            $mobileRedem->policy_no = $request->policyNo;
            $mobileRedem->remarks = $request->remarks;
            $mobileRedem->save();

            $mobileRedemId = $mobileRedem->id;

            foreach ($faultIds as $faultId):
                $featureData =  Feature::where('feature_id',$faultId)->where('mobile_fault_type_id',$faultTypeId)->select('feature_id as fault_id','feature as fault_name' , 'price as charge')->first();
                $totalAmount = $totalAmount+$featureData->charge;
                $faultData[$i] = $featureData;
                
                $mobileRedemFault = new MobileRedemptionPolicyFault;
                $mobileRedemFault->mobile_redemption_policy_request_id = $mobileRedemId;
                $mobileRedemFault->fault_type_id = $request->faultTypeId;
                $mobileRedemFault->fault_id = $faultId;
                $mobileRedemFault->save();
                
            endforeach;
            //print_r($data);die;
            
            DB::commit();
            $msg['responseCode']="200";
            $msg["responseMessage"]='Fault List get successfully';
            $msg["totalAmount"] = $totalAmount;
            $msg["faultList"] = $faultData;
            $msg["redemptionId"] = $mobileRedemId;
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg['responseCode']="0";
            $msg["responseMessage"]='Failed,Please Try Again';
            $msg['error']=$e;
        }
        finally
        {
            return $msg;
        }
    }
    
    function redemptionsubmit(Request $request)
    {
//        DB::beginTransaction();
//        try
//        {
            $faultIds=explode(',',$request->faultId);
            $faultTypeId = $request->faultTypeId;
            $i=0;
            $totalDamageAmount=0;
            $totalFaultAmount=0;
            $date=date('Y-m-d H:i:s');
            $userId = $request->userId;
            
            $userDetails = User::where('id',$userId)->select('email')->first();
            $emailId = $userDetails->email;
            foreach ($faultIds as $faultId):
                $featureData =  Feature::where('feature_id',$faultId)->select('price','mobile_fault_type_id')->first();
                if($featureData->mobile_fault_type_id == 1){
                    $totalDamageAmount = $totalDamageAmount+$featureData->price;  
                }
                if($featureData->mobile_fault_type_id == 2){
                    $totalFaultAmount = $totalFaultAmount+$featureData->price;  
                }
            endforeach;
            
            $from_email = 'admin@wecare.com';
            $from_name = 'Wecare Team';
            $base_host = $_SERVER['SERVER_NAME'];                    
            $uemail = $emailId;
            $data = array(
                'msg' => 'You have successfully purchased the package.',
                'image' => ''
            );

            $pageuser = 'emails.welcome';
            $subject = 'Wecare Policy Confirmation';
            $smsObj = new Smsapi();
            $smsObj->sendusermail($pageuser,$data,$subject,$from_email,$from_name,$uemail); 
            
            //DB::commit();
            $msg['responseCode']="200";
            $msg["responseMessage"]='Fault List get successfully';
            $msg["totalAmount"] = $totalDamageAmount + $totalFaultAmount;
            $msg["damageAmount"] = $totalDamageAmount;
            $msg["faultAmount"] = $totalFaultAmount;
//        }
//        catch (Exception $e)
//        {
//            DB::rollback();
//            $msg['responseCode']="0";
//            $msg["responseMessage"]='Failed,Please Try Again';
//            $msg['error']=$e;
//        }
//        finally
//        {
            return $msg;
       // }
    }
    
    function proceedToCheckOut(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $redemptionId = $request->redemptionId;
            $imageCount = $request->imageCount;
            $alternatePhone = $request->alternatePhone;
            $pickup = $request->pickup;
            $userId = $request->userId;
            $i=0;
                        
            $generalSetting = GeneralSetting::select('pick_drop_charge','alternate_mobile_charge','inspection_charge')->first();
                        
            $userDetails = User::where('id',$userId)->select('email','mobile','country_code')->first();
            $emailId = $userDetails->email;
            $mobileNumber = $userDetails->country_code.$userDetails->mobile;
            
            for($i=1;$i<=$imageCount;$i++){
                $mobileDocument = new MobileRedemptionPolicyDocument();
                $pic=Input::file('image'.$i);

                $extension = $pic->getClientOriginalExtension(); // getting image extension
                $name = time() . rand(111, 999) . '.' . $extension; // renameing image                
                $pic->move(public_path().'/uploads/mobile_package/',$name);
                $mobileDocument->mobile_redemption_policy_request_id = $redemptionId;
                $mobileDocument->image = $name;
                $mobileDocument->save();
            }
            
            $alternatePhoneCharge = 0;
            $pickupCharge = 0;
            if($alternatePhone == 'yes'){
                $alternatePhoneCharge = $generalSetting->alternate_mobile_charge;
            }
            if($pickup == 'yes'){
                $pickupCharge = $generalSetting->pick_drop_charge;
            }
            
            MobileRedemptionPolicyRequest::where('id',$redemptionId)->update(['pickup_charge'=>$pickupCharge, 'alternate_phone_charge'=>$alternatePhoneCharge,'request_pickup'=>$pickup, 'request_alternate_phone'=>$alternatePhone]);
            
            $from_email = 'admin@wecare.com';
            $from_name = 'Wecare Team';
            $base_host = $_SERVER['SERVER_NAME'];                    
            $uemail = $emailId;
            $data = array(
                'msg' => 'Your request has been sent successfully',
                'image' => ''
            );

            $pageuser = 'emails.welcome';
            $subject = 'Mobile Redemtion Request';
            $smsObj = new Smsapi();
            $smsObj->sendusermail($pageuser,$data,$subject,$from_email,$from_name,$uemail);  
            $userMsg = 'Your request has been sent successfully';
            $smsObj->sendsms_api($mobileNumber,$userMsg);
            
            DB::commit();
            $msg['responseCode']="200";
            $msg["responseMessage"]='Detail Save successfully';
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg['responseCode']="0";
            $msg["responseMessage"]='Failed,Please Try Again';
            $msg['error']=$e;
        }
        finally
        {
            return $msg;
        }
    }
    
    function inspectionRequestDetail(Request $request)
    {
        $inspectorId = $request->userId;
        if(!empty($inspectorId)){
            
            $inspectionDetail = InspectionRequest::select('inspection_requests.id as inspectionId', 'inspection_requests.fname as customerFirstName', 'inspection_requests.lname as customerLastName','ir.brand','ir.model' ,'inspection_requests.created_at as dateOfIssue', 'inspection_requests.inspection_charges as charge','inspection_requests.payment_status','insd.image')
                                ->leftJoin('inspection_documents as insd','inspection_requests.id','=','insd.inspection_request_id')
                                ->leftJoin('insurance_requests as ir','ir.id','=','inspection_requests.insurance_id')
                                ->where('inspector_id',$inspectorId)
                                ->where('inspection_requests.inspection_done','0')
                                ->groupBy('insd.inspection_request_id')
                                ->get();
            $inspectionDetail = $inspectionDetail->toArray();

            if(!empty($inspectionDetail)){            
                $msg['responseCode']="200";
                $msg["responseMessage"]='Inspection request get Successfully ';
                $msg["inspectionList"] = $inspectionDetail;
            }
            else
            {
                $msg['responseCode']="0";
                $msg["responseMessage"]='Failed, No Record Found';
            }
        }else{
            $msg['responseCode']="0";
            $msg["responseMessage"]='Failed, User id is blank';
        }        
        return $msg;        
    }
    
    function inspectionRequestData(Request $request)
    {
        $inspectorId = $request->userId;
        $inspectionId = $request->inspectionId;
        if(!empty($inspectorId)){
            
            $inspectionDetail = InspectionRequest::select('inspection_requests.id as inspectionId', 'inspection_requests.fname as customerFirstName', 'inspection_requests.lname as customerLastName', 'inspection_requests.created_at as dateOfIssue','inspection_requests.address','inspection_requests.mobile_number', 'inspection_requests.inspection_charges as charge','inspection_requests.payment_status','ir.brand','ir.model','ir.driven_km','ir.year','ir.price','pm.plan_title as packageName','pm.valid_for as valid_for_month','pm.image as packageImage','insd.image as productImage')
                                ->leftJoin('inspection_documents as insd','insd.inspection_request_id','=','inspection_requests.id')
                                ->leftJoin('insurance_requests as ir','ir.id','=','inspection_requests.insurance_id')
                                ->leftJoin('packages as pa','pa.id','=','inspection_requests.package_id')
                                ->leftJoin('plan_masters as pm','pm.id','=','pa.plan_master_id')
                                ->where('inspection_requests.inspector_id',$inspectorId)
                                ->where('inspection_requests.id',$inspectionId)
                                ->first();
            $inspectionDetail = $inspectionDetail->toArray();

            if(!empty($inspectionDetail)){            
                $msg['responseCode']="200";
                $msg["responseMessage"]='Inspection request get Successfully ';
                $msg["inspectionList"] = $inspectionDetail;
            }
            else
            {
                $msg['responseCode']="0";
                $msg["responseMessage"]='Failed, No Record Found';
            }
        }else{
            $msg['responseCode']="0";
            $msg["responseMessage"]='Failed, User id is blank';
        }        
        return $msg;        
    }
    
    function inspectionQuestion(Request $request)
    {
       // $inspectorId = $request->userId;
       // $inspectionId = $request->inspectionId;
        $inspectionQues = InspectionQuestion::where('status',1)->select('id','question_title')->get();
        $inspectionDetail = $inspectionQues->toArray();

        if(!empty($inspectionDetail)){            
            $msg['responseCode']="200";
            $msg["responseMessage"]='Inspection request get Successfully ';
            $msg["inspectionList"] = $inspectionQues;
        }
        else
        {
            $msg['responseCode']="0";
            $msg["responseMessage"]='Failed, No Record Found';
        }
               
        return $msg;        
    }
    
    function inspectionQueSubmit(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $userId = $request->userId;  

            $inspectionId = $request->inspectionId;
            $totalQuestion = $request->totalQuestion;
            $positiveAns = 0;
            $testScore = 0;
            
            $user = User::where('id',$userId)->select('country_code','mobile','email')->first();
          
            $emailId = $user->email;
             $uData = $user->toArray();
             
            if($uData){
                    $data = new InspectionAnswer;            
                    foreach($request->question as $ques){
                        $data->inspection_request_id = $inspectionId;
                        $data->inspection_question_id = $ques['questionId'];
                        $data->answer = $ques['answer'];
                        $data->status = 1;
                        $data->save();
                        
                        if($ques['answer'] == 'yes'){
                            $positiveAns++;
                        }
                    }
                    
                    #average score calculation
                    $testScore = ($positiveAns/$totalQuestion)*100;
                    
                    #unique code generation
                    $string = 'ABCDEFGHIJKLMNOPQRSTUVWYZ1234567890';
                    $string_shuffled = str_shuffle($string);
                    $otp = substr($string_shuffled, 1, 5);
                    $otpMsg = 'Your unique code is '.$otp;
                    
                    $mobile = $user->country_code.$user->mobile;
                    
                    InspectionRequest::where('id',$inspectionId)->update(['unique_code'=>$otp,'inspection_done'=>'1', 'payment_status'=>'paid']);

                    $smsObj = new Smsapi();
                    $smsObj->sendsms_api($mobile,$otpMsg);
                    
                    $from_email = 'admin@wecare.com';
                    $from_name = 'Wecare Team';
                    $base_host = $_SERVER['SERVER_NAME'];                    
                    $uemail = $emailId;
                    $data = array(
                        'msg' => $otpMsg,
                        'image' => ''
                    );

                    $pageuser = 'emails.welcome';
                    $subject = 'Wecare Policy Confirmation';

                    $smsObj->sendusermail($pageuser,$data,$subject,$from_email,$from_name,$uemail);


                    DB::commit();
                    $msg['responseCode']="200";
                    $msg["responseMessage"]='Inspection question submitted Successfully ';
                    $msg["inspectionTestScore"] = $testScore;
                    $msg["inspectionStatus"] = 'Approved';
                    $msg["uniqueCode"]= $otp;
            }else{
                $msg['responseCode']="0";
                $msg["responseMessage"]='User not found';
            } 
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg['responseCode']="0";
            $msg["responseMessage"]='Failed,Please Try Again';
            $msg['error']=$e;           
        }
        finally
        {
            return $msg;
        }        
    }
    
    function buyPackage(Request $request)
    {
        
        DB::beginTransaction();
        try
        {
            $userId = $request->userId;         
            $uniqueCode = $request->uniqueCode;
             $todayDate = date("Y-m-d");
             $inspectionDetail = InspectionRequest::select('inspection_requests.id','inspection_requests.package_id','inspection_requests.insurance_id','pm.plan_title','pm.valid_for','pa.service_category_id', 'pa.service_sub_category_id', 'pa.policy_type')
                                ->leftJoin('packages as pa','pa.id','=','inspection_requests.package_id')
                                ->leftJoin('plan_masters as pm','pm.id','=','pa.plan_master_id')
                                ->where('inspection_requests.unique_code',$uniqueCode)
                                ->first();
             $inspectionRequestId = $inspectionDetail->id;
             $uniqueCodeStatus = Policy::where('inspection_request_id',$inspectionRequestId)->first();
             
              if (count($inspectionDetail)>0 && empty($uniqueCodeStatus)) {
             
             $userDetails = User::where('id',$userId)->select('email','mobile','country_code')->first();
             $emailId = $userDetails->email;
             $mobileNumber = $userDetails->country_code.$userDetails->mobile;
             $inspecData = $inspectionDetail->toArray();
             $packagePrice = PackageFeature::getPackagePrice($inspectionDetail->package_id, $userId);
           //  echo'<pre>'; print_r($packagePrice); die;
            if($inspecData){                
                #unique code generation
                $string = 'ABCDEFGHIJKLMNOPQRSTUVWYZ1234567890';
                $string_shuffled = str_shuffle($string);
                $policyNo = substr($string_shuffled, 1, 5);
                $timeToadd = "+$inspectionDetail->valid_for months";
                $effectiveDate = date('Y-m-d', strtotime("$timeToadd", strtotime($todayDate)));
               
                    $data = new Policy; 
                    $data->name = $inspectionDetail->plan_title;
                    $data->policy_number = $policyNo;
                    $data->user_id = $userId;
                    $data->insurance_id = $inspectionDetail->insurance_id;
                    $data->policy_expiry_date = $effectiveDate;
                    $data->service_category_id = $inspectionDetail->service_category_id;
                    $data->service_subcategory_id = $inspectionDetail->service_sub_category_id;
                    $data->inspection_request_id = $inspectionDetail->id;
                    $data->policy_amount = $packagePrice['packagePrice'];
                    $data->policy_type = $inspectionDetail->policy_type;
                    $data->package_id = $inspectionDetail->package_id;
                    $data->save();
                        
                    $from_email = 'admin@wecare.com';
                    $from_name = 'Wecare Team';
                    $base_host = $_SERVER['SERVER_NAME'];                    
                    $uemail = $emailId;
                    $data = array(
                        'msg' => 'You have successfully purchased the package. Your Policy number is'.$policyNo,
                        'image' => ''
                    );

                    $pageuser = 'emails.welcome';
                    $subject = 'Wecare Policy Confirmation';
                    $smsObj = new Smsapi();
                    $smsObj->sendusermail($pageuser,$data,$subject,$from_email,$from_name,$uemail);  
                    $userMsg = 'You have successfully purchased the package. Your Policy number is'.$policyNo;
                    $smsObj->sendsms_api($mobileNumber,$userMsg);
                    
                    DB::commit();
                    $msg['responseCode']="200";
                    $msg["responseMessage"]='Package purchased successfully';
            }else{
                $msg['responseCode']="0";
                $msg["responseMessage"]='Some error occured';
            }
              }else{
                $msg['responseCode']="0";
                $msg["responseMessage"]='Unique Code Invalid';
                if(!empty($uniqueCodeStatus)){
                    $msg["responseMessage"]='Package Already purchased';
                }
                
            }
              
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg['responseCode']="0";
            $msg["responseMessage"]='Failed,Please Try Again';
            $msg['error']=$e;
        }
        finally
        {
            return $msg;
        }        
    }
    
    function packageHistory(Request $request)
    {  
        DB::beginTransaction();
        try
        {
            $userId = $request->userId;
            $inspecData = '';
             $inspectionDetail = Policy::select('policies.package_id','policies.created_at as issueDate','pm.plan_title as packageName','pm.image as packageImg', 'pm.valid_for as packageValidity_inMonth', 'policies.policy_amount as packagePrice','policies.insurance_id')
                                ->leftJoin('packages as pa','pa.id','=','policies.package_id')
                                ->leftJoin('plan_masters as pm','pm.id','=','pa.plan_master_id')
                                ->where('policies.user_id',$userId)
                                ->get();
             
             $inspecData = $inspectionDetail->toArray();
                         
            if($inspecData){   
                    DB::commit();
                    $msg['responseCode']="200";
                    $msg["responseMessage"]='Package history get successfully';
                    $msg["package"]=$inspectionDetail;
            }else{
                $msg['responseCode']="0";
                $msg["responseMessage"]='Failed,Data not found';
            } 
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg['responseCode']="0";
            $msg["responseMessage"]='Failed,Please Try Again';
            $msg['error']=$e;
        }
        finally
        {
            return $msg;
        }        
    }
    
     function dealerAssignmentListing(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $userId = $request->userId;  
            $redemption=PolicyRedemptionRequest::select('policy_redemption_requests.*','p.policy_number','p.service_subcategory_id','p.package_id','ir.brand')
                                ->leftJoin('policies as p','p.id','=','policy_redemption_requests.policy_id')
                                ->leftJoin('insurance_requests as ir','ir.id','=','p.insurance_id')
                                ->where('policy_redemption_requests.dealer_assinged',$userId)
                                ->get();
                                //->toSql();
            //dd($redemption);die;
             $redemData = $redemption->toArray();

                         
            if($redemData){   
                    DB::commit();
                    $msg['responseCode']="200";
                    $msg["responseMessage"]='Dealer Assignment get successfully';
                    $msg["data"]=$redemption;
            }else{
                $msg['responseCode']="0";
                $msg["responseMessage"]='Failed,Data not found';
            } 
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg['responseCode']="0";
            $msg["responseMessage"]='Failed,Please Try Again';
            $msg['error']=$e;
        }
        finally
        {
            return $msg;
        }        
    }
    
    function userFaultList(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $policyRedemId = $request->policyRedemId;
            
            $inspectionDetail = RedemptionPolicyRequest::select('fault.id','fault.fault','fault.price')
                                ->leftJoin('faults as fault','fault.id','=','redemption_policy_requests.fault_id')
                                ->where('policy_redemption_request_id',$policyRedemId)
                                ->get();
                        
             $redemData = $inspectionDetail->toArray();
                         
            if($redemData){   
                    DB::commit();
                    $msg['responseCode']="200";
                    $msg["responseMessage"]='Dealer Assignment get successfully';
                    $msg["faultList"]=$inspectionDetail;
            }else{
                $msg['responseCode']="0";
                $msg["responseMessage"]='Failed,Data not found';
            } 
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg['responseCode']="0";
            $msg["responseMessage"]='Failed,Please Try Again';
            $msg['error']=$e;
        }
        finally
        {
            return $msg;
        }        
    }
    
    function dealerAutomobileFeature(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $serviceSubCategoryId = $request->serviceSubCategoryId;  
            $policyId = $request->policyId;
            $policyNumber = $request->policyNumber;
            $featureData =  Feature::where('service_category_id',$serviceSubCategoryId)->select('feature_id','feature','description','price')->get();
            
             $redemData = $featureData->toArray();             
             
            $inspectionDetail = Policy::select('pm.plan_title as packageName','pm.image as packageImg')
                                ->leftJoin('packages as pa','pa.id','=','policies.package_id')
                                ->leftJoin('plan_masters as pm','pm.id','=','pa.plan_master_id')
                                ->where('policies.id',$policyId)
                                ->first();
                         
            if($redemData){   
                    DB::commit();
                    $msg['responseCode']="200";
                    $msg["responseMessage"]='Features get successfully';
                    $msg["feature"]=$featureData;
                    $msg["packageName"]=$inspectionDetail->packageName;
                    $msg["policyNumber"]=$policyNumber;
            }else{
                $msg['responseCode']="0";
                $msg["responseMessage"]='Failed,Data not found';
            } 
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg['responseCode']="0";
            $msg["responseMessage"]='Failed,Please Try Again';
            $msg['error']=$e;
        }
        finally
        {
            return $msg;
        }        
    }
    
    function automobileFeatureCovered(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $userId = $request->userId;
            $packageId = $request->packageId;
            $policyId = $request->policyId;
            $featureId = explode(',',$request->featureId);
            $coveredFeature = array(); $i=0;
            $uncoveredFeature = array(); $j=0;
            $amtToPay = '';
            
            foreach($featureId as $fId){
                $packageFeature = PackageFeature::select('package_features.id', 'fe.price','fe.feature')
                                ->leftJoin('features as fe','fe.feature_id','=','package_features.feature_id')
                                ->where('package_features.package_id',$packageId)
                                ->where('package_features.feature_id',$fId)
                                ->first();
                
                $data = new AutomobileCoveredFeature;
                $data->user_id = $userId;
                $data->package_id = $packageId;
                $data->policy_id = $policyId;
                $data->feature_id = $fId;
                             
                if(!empty($packageFeature)){
                    $coveredFeature[$i]['featureId'] = $fId;
                    $coveredFeature[$i]['featureName'] = $packageFeature->feature;
                    $coveredFeature[$i]['featurePrice'] = $packageFeature->price;
                    $coveredFeature[$i]['featureCoveredStatus'] = 'yes';
                    $i++;
                    $data->feature_covered = 'yes';
                }else{
                    $otherFetaure=Feature::where('feature_id',$fId)->select('price','feature')->first();
                    if(!empty($otherFetaure)){
                        $coveredFeature[$i]['featureId'] = $fId;
                        $coveredFeature[$i]['featureName'] = $otherFetaure->feature;
                        $coveredFeature[$i]['featurePrice'] = $otherFetaure->price;
                        $coveredFeature[$i]['featureCoveredStatus'] = 'no';
                        $i++;
                        $amtToPay = $amtToPay+$otherFetaure->price;
                        $data->feature_covered = 'no';
                    }
                }
                $data->save();
            }                        
             
            DB::commit();
            $msg['responseCode']="200";
            $msg["responseMessage"]='Package Features get successfully';
            $msg["coveredFeature"]=$coveredFeature;
            //$msg["uncoveredFeature"]=$uncoveredFeature;
            $msg["amtToPay"]=$amtToPay;
           
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg['responseCode']="0";
            $msg["responseMessage"]='Failed,Please Try Again';
            $msg['error']=$e;
        }
        finally
        {
            return $msg;
        }        
    }

function confirmPayment(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $cartId = $request->cartId;
            $remarks = $request->remarks;
            $userId = $request->userId;
            $userDetails = User::where('id',$userId)->select('email')->first();
             $emailId = $userDetails->email;
                                
            $pic=Input::file('image');
                                   
            $extension = $pic->getClientOriginalExtension(); // getting image extension
            $name = time() . rand(111, 999) . '.' . $extension; // renameing image 
            $pic->move(public_path().'/uploads/payment/',$name);  
                       
            $updateData = NewSaleRequest::where('cart_id',$cartId)->update(['image'=>$name, 'remarks'=>$remarks]);
            
            $from_email = 'admin@wecare.com';
            $from_name = 'Wecare Team';
            $base_host = $_SERVER['SERVER_NAME'];                    
            $uemail = $emailId;
            $data = array(
                'msg' => 'Your request has been sucessfully sent to Admin. We will inform you once approve by admin.',
                'image' => ''
            );

            $pageuser = 'emails.welcome';
            $subject = 'Wecare Policy Confirmation';
            $smsObj = new Smsapi();
            $smsObj->sendusermail($pageuser,$data,$subject,$from_email,$from_name,$uemail);            
            
            DB::commit();
            $msg['responseCode']="200";
            $msg["responseMessage"]='Data save successfully';
                      
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg['responseCode']="0";
            $msg["responseMessage"]='Failed,Please Try Again';
            $msg['error']=$e;
        }
        finally
        {
            return $msg;
        }        
    }

    function orderDetail(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $orderId = $request->orderId;
            $totalAmt=''; 
            
            $orderData = OrderDetail::select('order_details.price','order_details.quantity','pm.product_title','pm.price','pm.feature_image')
                                       ->leftJoin('product_masters as pm','pm.id','=','order_details.product_id')
                                       ->where('order_details.order_id',$orderId)
                                       ->get();
            foreach($orderData as $orderDetail){
                $orderPrice = $orderDetail->price*$orderDetail->quantity;
                $totalAmt = $totalAmt+$orderPrice;
            }
            
            DB::commit();
            $msg['responseCode']="200";
            $msg['responseMessage']='Order Details get successfully';
            $msg['orderDetail'] = $orderData;
            $msg['totalAmt'] = $totalAmt;
                      
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg['responseCode']="0";
            $msg["responseMessage"]='Failed,Please Try Again';
            $msg['error']=$e;
        }
        finally
        {
            return $msg;
        }        
    }
    
    
    
    /////////////////////  Start Kishan Code /////////////////////////////////////////

# For Serching Mobile Package

    public function mobileInsuranceRequest()
    {
        $msg = array();
        $customerId = $_POST['userId'];
        $serviceCatId = $_POST['serviceCatId'];
        $serviceSubCatId = $_POST['serviceSubCatId'];
        $model = $_POST['model'];
        $purchaseDate = $_POST['purchaseTime'];
        $price = $_POST['price'];
        $insuranceType = $_POST['insuranceType'];
        $roleId = $_POST['roleId'];
        $brand = $_POST['brand'];

        $datelib = new DateLibrary();
        $month = $datelib->getMonthBetweenDates($purchaseDate,date('Y-m-d'));

        if ($roleId=='1'){
            $policyType = 'waranty';
        }
        else{
            $policyType = $insuranceType;
        }


        if(!empty($customerId)){
            if(!empty($serviceCatId) || !empty($serviceSubCatId) || !empty($model) || !empty($price) || !empty($purchaseDate))
            {
                MobileInsuranceRequest::where('user_id',$customerId)->delete();
                #save data in temprory Table
                $sale = new MobileInsuranceRequest();
                $sale->user_id = $customerId;
                $sale->service_category_id = $serviceCatId;
                $sale->service_sub_category_id = $serviceSubCatId;
                $sale->model = $brand;
                $sale->model = $model;
                $sale->month = $month;
                $sale->price = $price;
                $sale->date_of_purchase = $purchaseDate;
                $sale->status = 0;
                $sale->save();

                $mobileInsuranceId = $sale['id'];

                $packageData ='';
                $modelId = MobileModel::where('model',$model)->select('model_id')->first();

                if(!empty($modelId)){
                    $packageData = Package::where('service_category_id',$serviceCatId)
                        ->where('service_sub_category_id',$serviceSubCatId)
                        ->where('model_id',$modelId->model_id)
                        ->where('policy_type',$policyType)
                        ->where('status', 1)
                        ->select('base_price','plan_master_id','id')
                        ->get();
                    if (count($packageData)>0) {
                        $packageDetail = $packageData->toArray();
                    }
                    else{
                        $packageDetail = '';
                    }
                }
                //echo'<pre>'; print_r($packageDetail); die;
                if(empty($packageData) || empty($packageDetail)){
                    $packageData = Package::where('service_category_id',$serviceCatId)
                        ->where('service_sub_category_id',$serviceSubCatId)
                        ->where('range_lowerlimit', '<=', $price)
                        ->where('range_uperlimit', '>=', $price)
                        ->where('policy_type',$policyType)
                        ->where('status', 1)
                        ->select('base_price','plan_master_id','id')
                        ->get();
                    $packageDetail = $packageData->toArray();
                }
                //echo'<pre>'; print_r($packageData); die;
                if(!empty($packageDetail)){
                    $i=0;
                    foreach($packageData as $package)
                    {
                        $ageCharge = '';
                        if ($policyType == 'waranty') {
                            $ageCharge = TimelyCharge::where('package_id', $package->id)
                                ->where('age_type', 'monthly')
                                ->where('age', $month)
                                ->select('id', 'package_id', 'percentage_amount')
                                ->first();
                        }
                        else{
                            $ageCharge = 'not waranty';
                        }
                        if(!empty($ageCharge))
                        {
                            $plan = PlanMaster::where('id',$package->plan_master_id)->select('plan_title','image','valid_for')->first();
                            $planName = $plan->plan_title;
                            $planBaseAmt = $package->base_price;
                            if ($ageCharge == 'not waranty'){
                                $packagePrice = $package->base_price;
                            }
                            else {
                                $planAgePrice = ($planBaseAmt * $ageCharge->percentage_amount) / 100;
                                $packagePrice = $planBaseAmt + $planAgePrice;
                            }
                            $packageAmount[$i]['package'] = "$planName";
                            $packageAmount[$i]["packagePrice"] = "$packagePrice";
                            $packageAmount[$i]["packageId"] = "$package->id";
                            $packageAmount[$i]["features"] = "Data Not added";
                            $packageAmount[$i]["packageValidity"] = "$plan->valid_for months";
                            $packageAmount[$i]["image"] = "$plan->image";
                            $i++;
                            Session::set($package->id, $packagePrice);
                        }
                    }
                    
                    if(empty($packageAmount)){
                        $packageAmount = 'No Package Found.';
                    }
                    
                    $msg['responseCode'] = "200";
                    $msg['responseMessage'] = "Data saved successfully";
                    $msg['package'] = $packageAmount;
                    $msg['mobileInsuranceId'] = $mobileInsuranceId;
                }else{
                    $msg['responseCode'] = "200";
                    $msg['responseMessage'] = "Data saved successfully";
                    $msg['package'] = 'No Package Found.';
                }
            }else{
                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "Failed! All details are required.";
            }
        }else{
            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "Failed! Please Enter Cudtomer ID.";
        }
        return $msg;
    }


    public function saveUserMobileInformation(Request $request)
    {
        $msg = array();
        $todayDate = date("Y-m-d H:i:s");
        $userId = $request->userId;
        $mobileInsuranceId = $request->mobileInsuranceId;
        $packageId = $request->packageId;
        $brand = $request->brand;
        $imeiNumber = $request->imeiNumber;
        $price = $request->price;
        $imageCount = $request->imageCount;

        if(!empty($userId) || !empty($packageId) || !empty($mobileInsuranceId) || !empty($imeiNumber) || !empty($imageCount))
        {
            $data = new MobilePackageUserInformation();
            $data->user_id = $userId;
            $data->mobile_insurance_request_id = $mobileInsuranceId;
            $data->package_id = $packageId;
            $data->brand = $brand;
            $data->imei_number = $imeiNumber;
            $data->price = $price;
            $data->created_at = $todayDate;
            $data->updated_at = $todayDate;
            $data->save();

            $mobilePackageInformationId = $data->id;

            for($i=1;$i<=$imageCount;$i++){
                $mobileDocument = new MobilePackageUserDocument();
                $pic=Input::file('image'.$i);

                $extension = $pic->getClientOriginalExtension(); // getting image extension
                $name = time() . rand(111, 999) . '.' . $extension; // renameing image
                $mobileDocument->image = $name;
                $pic->move(public_path().'/uploads/mobile_package/',$name);
                $mobileDocument->mobile_package_user_information_id = $mobilePackageInformationId;
                $mobileDocument->created_at = $todayDate;
                $mobileDocument->updated_at = $todayDate;
                $mobileDocument->save();
            }

            $msg['responseCode'] = "200";
            $msg['responseMessage'] = "Successfully save customer mobile details";
            $msg['userId'] = $userId;
            $msg['mobilePackageInformationId'] = $mobilePackageInformationId;
        }else{
            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "Failed! All Fields are required";
        }
        return $msg;
    }


    public function saveCustomerDetailMobile(Request $request){

        $msg = array();
        $todayDate = date("Y-m-d");
        $userId = $request->userId;
        $roleId = $request->roleId;
        $fname = $request->fname;
        $lname = $request->lname;
        $emailId = $request->emailId;
        $mobile = $request->mobile;
        $address = $request->address;
        $mobilePackageInformationId = $request->mobilePackageInformationId;
        $packageAmt = $request->packageAmt;



        if(!empty($userId) || !empty($fname) || !empty($emailId) || !empty($mobile) || !empty($address) || !empty($roleId) || !empty($mobilePackageInformationId))
        {
            try {
                
                $userDetails = User::where('email', $emailId)->first();
                if (count($userDetails)>0) {

                    $userId = $userDetails->id;
                }

                if ($roleId == '2' && count($userDetails)<1) {
                    
                        $users = new User();

                        $users->first_name = $fname;
                        $users->last_name = $lname;
                        $users->email = $emailId;
                        $users->password = bcrypt('123456');
                        $users->mobile = $mobile;
                        $users->country_code = '+91';
                        $users->address = $address;
                        $users->role_master_id = '1';
                        $users->save();

                        $userId = $users->id;

                        $userMsg = 'Your Email is' . $emailId . ' and Your Password is 123456. You can Logged in future to redeem your policy';

                        $smsObj = new Smsapi();
                        $smsObj->sendsms_api('+91' . $mobile, $userMsg);
                    
                } 
                

                $data = array();
                $data['user_id'] = $userId;
                $data['fname'] = $fname;
                $data['lname'] = $lname;
                $data['email'] = $emailId;
                $data['mobile_number'] = $mobile;
                $data['address'] = $address;
                $data['updated_at'] = $todayDate;

                MobilePackageUserInformation::where('id',$mobilePackageInformationId)->update($data);


                $mobileDetail = MobilePackageUserInformation::select('mobile_package_user_informations.id','mobile_package_user_informations.package_id','pm.plan_title','pm.valid_for','pa.service_category_id', 'pa.service_sub_category_id', 'pa.policy_type')
                    ->leftJoin('packages as pa','pa.id','=','mobile_package_user_informations.package_id')
                    ->leftJoin('plan_masters as pm','pm.id','=','pa.plan_master_id')
                    ->where('mobile_package_user_informations.id',$mobilePackageInformationId)
                    ->first();

                if (count($mobileDetail)>0) {
                    $mobileData = $mobileDetail->toArray();
                }
                else{
                    $mobileData = '';
                }


                if ($mobileData) {
                    //$packagePrice = PackageFeature::getPackagePrice($mobileDetail->package_id, $userId);

                    #unique code generation
                    $string = 'ABCDEFGHIJKLMNOPQRSTUVWYZ1234567890';
                    $string_shuffled = str_shuffle($string);
                    $policyNo = substr($string_shuffled, 1, 5);
                    $QrimageName = time() . rand(111, 999);
                    $effectiveDate = date('Y-m-d', strtotime('+'.$mobileDetail->valid_for.' months', strtotime($todayDate)));

                    $data = new Policy();
                    $data->name = $mobileDetail->plan_title;
                    $data->policy_number = $policyNo;
                    $data->user_id = $userId;
                    $data->policy_expiry_date = $effectiveDate;
                    $data->service_category_id = $mobileDetail->service_category_id;
                    $data->service_subcategory_id = $mobileDetail->service_sub_category_id;
                    $data->inspection_request_id = $mobileDetail->id;
                    $data->policy_amount = $packageAmt;
                    $data->policy_type = $mobileDetail->policy_type;
                    $data->package_id = $mobileDetail->package_id;
                    $data->qrcode_image = $QrimageName;
                    $data->save();


                    $userPolicyMsg = 'Your Policy Number is' . $policyNo . ' . You can redeem your package by entering this policy number';

                    $smsObj = new Smsapi();
                    $smsObj->sendsms_api('+91' . $mobile, $userPolicyMsg);


                    ////// Send Mail With Qr Code/////////////////


                    QrCode::size(200)->generate($policyNo, 'public/uploads/qr_code/'.$QrimageName.'.svg');

                    $from_email = 'admin@wecare.com';
                    $from_name = 'Wecare Team';
                    $base_host = $_SERVER['SERVER_NAME'];
                    $image = $base_host.'/we_care_app/public/uploads/qr_code/'.$QrimageName.'.svg';
                    //$link = "http://localhost/lieferzonas/front/resetpassword/".$act_key."";
                    $uemail = $emailId;

                    $policyMsg = 'Your policy number is '.$policyNo;

                    $data = array(
                        'msg' => $policyMsg,
                        'image' => $image
                    );

                    $pageuser = 'emails.welcome';
                    $subject = 'Wecare Policy Confirmation';

                    $smsObj = new Smsapi();
                    $smsObj->sendusermail($pageuser,$data,$subject,$from_email,$from_name,$uemail);


                    DB::commit();
                    $msg['responseCode'] = "200";
                    $msg["responseMessage"] = 'Package purchased successfully';
                }
                else{

                    $msg['responseCode'] = "200";
                    $msg["responseMessage"] = 'Some error occured';
                }
            }

            catch(\Exception $e){

                $msg['responseCode'] = "0";
                $msg['responseMessage'] = "Something went wrong!";
            }
        }else{
            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "Failed! All Fields are required";
        }
        return $msg;
    }

    public function disclaimer(){

        $disclaimer = GeneralSetting::select('disclaimer')->first();
        $msg['responseCode'] = "200";
        $msg['responseMessage'] = "success";
        $msg['disclaimer'] = $disclaimer->disclaimer;
        return $msg;

    }

    public function termCondition(){

        $termCondition = GeneralSetting::select('term_condition')->first();
        $msg['responseCode'] = "200";
        $msg['responseMessage'] = "success";
        $msg['termCondition'] = $termCondition->term_condition;
        return $msg;

    }

    public function privacyPolicy(){

        $termCondition = GeneralSetting::select('privacy_policy')->first();
        $msg['responseCode'] = "200";
        $msg['responseMessage'] = "success";
        $msg['privacyPolicy'] = $termCondition->privacy_policy;
        return $msg;

    }

    public function help(){

        $termCondition = GeneralSetting::select('help')->first();
        $msg['responseCode'] = "200";
        $msg['responseMessage'] = "success";
        $msg['help'] = $termCondition->help;
        return $msg;

    }


    public function searchProductByName(Request $request)
    {
        $msg = array();

        $productTitle = $request->title;

        if(!empty($productTitle)){
            #For sorting

            $product=ProductMaster::select('id','product_title','price','feature_image')->where('product_title', 'like', '%'.$productTitle.'%')->get();

            $data = $product->toArray();
            if(!empty($data)){
                $msg['responseCode'] = "200";
                $msg['responseMessage'] = "Products data fetched successfully";
                $msg['products'] = $data;
            }else{
                $msg['responseCode'] = "200";
                $msg['responseMessage'] = "Failed ! No product found.";
            }

        }else{
            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "Product title could not be null";
        }
        return $msg;
    }

    public function sendMessage(Request $request){

        $msg = array();

        $userId = $request->userId;
        $message = $request->message;
        $eventType = $request->type;

        if(!empty($userId) && !empty($eventType)){

            $userChatDetails = UserChat::select('id')->where('chat_initiate_id', $userId)->first();

            DB::beginTransaction();

            try
            {

                if ($eventType=='set') {
                    if (count($userChatDetails) > 0) {

                        $userChatId = $userChatDetails->id;

                    } else {

                        $userChat = new UserChat();

                        $userChat->chat_initiate_id = $userId;
                        $userChat->chat_handeled_id = 1;

                        $userChat->save();

                        $userChatId = $userChat['id'];

                    }

                    $chatHistory = new UserChatHistory();

                    $chatHistory->user_chat_id = $userChatId;
                    $chatHistory->sender_id = $userId;
                    $chatHistory->reciever_id = 1;
                    $chatHistory->message = $message;

                    $chatHistory->save();
                }

                else{

                    $userChatId = $userChatDetails->id;
                }

                $chatHistories = UserChatHistory::select('user_chat_histories.message','u.first_name','u.last_name','user_chat_histories.created_at')->Join('users as u','u.id','=','user_chat_histories.sender_id')->where('user_chat_histories.user_chat_id',$userChatId)->get();
                if (count($chatHistories)>0){
                    $data = $chatHistories->toArray();
                }
                else{
                    $data = '';
                }

                DB::commit();
                $msg['userId']=$userId;
		        $msg['chatHistories'] = $data;
                $msg['responseCode']='200';
                $msg['responseMessage']='success';
            }
            catch (\Exception $e)
            {
                // print_r($e);
                DB::rollback();
                $msg['responseCode']='0';
                $msg['errorMsg'] = $e->getMessage();
                $msg['responseMessage']='Something went wrong!';
            }


        }else{
            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "Bad Request";
        }
        return $msg;

    }
    
    
    public function redemptionCompleted(Request $request) {        
       $id = $request->id;
        if(!empty($id)){
       $redemption = PolicyRedemptionRequest::where('id', $id)->update(['status' => 'closed']);
        //->toSql();
        //    dd($redemption);die;
        $msg['responseCode'] = '200';
        $msg['responseMessage'] = 'Successfully Completed';        
    }else{
            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "Bad Request";
        }
    return $msg;
}

 public function redemptionRequestList(Request $request) {        
       $uid = $request->user_id;
        if(!empty($uid)){
       $RequestList=PolicyRedemptionRequest::select('policy_redemption_requests.id','policy_redemption_requests.policy_id','policy_redemption_requests.request_date','policy_redemption_requests.dealer_assinged','policy_redemption_requests.status','u.first_name','u.last_name','p.policy_number')
               ->Join('users as u','u.id','=','policy_redemption_requests.dealer_assinged')
               ->Join('policies as p','p.id','=','policy_redemption_requests.policy_id')
               ->where('policy_redemption_requests.user_id', $uid)
               ->get();
            $data = $RequestList->toArray();
            if(!empty($data)){
                $msg['responseCode'] = "200";
                $msg['responseMessage'] = "Redemption Request List";
                $msg['RequestList'] = $data;
            }else{
                $msg['responseCode'] = "200";
                $msg['responseMessage'] = "Failed ! Redemption Request List Not Found.";
            }

        }else{
            $msg['responseCode'] = "0";
            $msg['responseMessage'] = "Bad Request";
        }
        return $msg;
}
/////////////////////  End Kishan Code /////////////////////////////////////////////


    
}
