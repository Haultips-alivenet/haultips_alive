<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('/', 'Front\UserHomeController@index');
Route::resource('user/dashboard', 'Front\UserHomeController@index');
Route::resource('user/home', 'Front\UserHomeController@userdashboard');
Route::resource('user/faq', 'Front\UserController@faq');
Route::post('user/faq/answer', 'Front\UserController@user_ans_save');
Route::post('partner/faq/question', 'Front\UserController@partner_question_save');
Route::get('getquesAns', 'Front\UserHomeController@getquesAns');
Route::get('about-us', 'Front\UserHomeController@about_us');
Route::get('how-it-works', 'Front\UserHomeController@how_it_work');
Route::post('newsletter/subscribe', 'Front\NewsletterController@index');
Route::get('geo-location/city/{state_id}', 'Front\GeoLocationController@getCityByStateId');
//Route::post('send-message-s', 'ChatController@sendMessageS');
//Route::post('send-message-u', 'ChatController@sendMessageU');
Route::get('chatboxs', 'ChatController@chatBoxS');
Route::get('chatboxu', 'ChatController@chatBoxU');
Route::get('user/support', 'ChatController@user_support');
Route::post('send_message_by_user', 'ChatController@send_message_by_user');
Route::post('send_message_by_support', 'ChatController@send_message_by_support');
// User login panel 
Route::get('user/profile', 'Front\UserController@profile');
Route::post('user/profile/edit', 'Front\UserController@profileEdit');
Route::get('user/changepassword', 'Front\UserController@changepassword');
Route::post('user/changepassword', 'Front\UserController@changepassword');
Route::get('user/my-deliveries/{status}', 'Front\UserController@myDeliveries');
Route::get('user/delivery-detail/{shipping_id}', 'Front\UserController@deliveryDetail');
Route::get('user/my-delivery-delete/{shipping_id}', 'Front\UserController@deliveryDelete');
Route::get('user/all-quotation/{shipping_id}', 'Front\UserController@allQuotation');
Route::get('user/quotation-offer/{quote_id}', 'Front\UserController@quotationDetail');
Route::get('user/quotation-offer/accept/{quote_id}', 'Front\UserController@quotationOfferAccept');
Route::post('user/quotation-offer/accept/cod', 'Front\UserController@quotationOfferAcceptCod');
Route::get('user/quotation-offer/reject/{quote_id}', 'Front\UserController@quotationOfferReject');
Route::get('user/relist-shipment/{shipping_id}', 'Front\UserController@relistShipment');
Route::post('user/relist-shipment/{shipping_id}', 'Front\UserController@relistShipment');
Route::get('user/bank-infomation', 'Front\UserController@bankInformation');
Route::get('user/bank-infomation/delete/{bank_info_id}', 'Front\UserController@bankInformationDelete');
Route::get('user/bank-infomation/add', 'Front\UserController@bankInformationAdd');
Route::post('user/bank-infomation/add', 'Front\UserController@bankInformationAdd');
Route::get('user/transactionhistory', 'Front\UserController@getTransactionHistory');
Route::get('user/new-shipment', 'Front\UserController@shipmentNew');
Route::get('user/find-delivery', 'Front\UserController@shipmentNew');
//partner kyc
Route::get('parner/profile/kyc', 'Front\UserController@partner_profile_kyc');
Route::post('user/kyc/rc', 'Front\UserController@partner_profile_kyc_upload');
Route::post('user/kyc/pan', 'Front\UserController@partner_profile_kyc_upload');
Route::post('user/kyc/business', 'Front\UserController@partner_profile_kyc_upload');

//partner Transporter
Route::get('parner/profile/transporter', 'Front\UserController@partner_profile_transporter');
Route::get('parner/profile/getcapacity', 'Front\UserController@gettruckcapacity');
Route::post('profile/transporter/update', 'Front\UserController@partner_transporter_update');
Route::post('user/payment', 'Front\UserController@payment');
Route::post('user/payment/success', 'Front\UserController@success');
Route::post('user/payment/failure', 'Front\UserController@failure');
Route::post('user/profile-pic/save', 'Front\UserController@profileImageEdit');
// User login panel end

// Partner login panel
Route::get('user/my-offers', 'Front\UserController@myOffer');
Route::get('user/my-offer/{quote_id}', 'Front\UserController@myOfferDetail');
// 


#User

Route::resource('user/login', 'Front\LoginController');
Route::resource('user/signup', 'Front\LoginController@signup');
Route::resource('user/customer', 'Front\LoginController@customer');
Route::resource('user/partner', 'Front\LoginController@partner');
#Customer Registration
Route::resource('user/customer-registration', 'Front\LoginController@customer_registration');
Route::resource('user/partner-registration', 'Front\LoginController@partner_registration');
Route::get('getlength ', 'Front\UserHomeController@gettrucklength');
Route::get('getcapacity ', 'Front\UserHomeController@gettruckcapacity');
Route::get('subCategory/{id}', 'Front\UserHomeController@subCategory');


Route::get('office/{id}', 'Front\ShipmentController@office');
Route::post('office', 'Front\ShipmentController@office');
Route::get('fourwheeler/{id}', 'Front\ShipmentController@twowheeler');
Route::get('twowheeler/{id}', 'Front\ShipmentController@twowheeler');
Route::post('twowheeler', 'Front\ShipmentController@twowheeler');
Route::post('fourwheeler', 'Front\ShipmentController@twowheeler');
Route::get('user/getoffer', 'Front\ShipmentController@getoffer');
Route::get('user/getofferprocess', 'Front\ShipmentController@getofferprocess');
Route::post('partload', 'Front\ShipmentController@partload');
Route::get('householdgoods/{id}', 'Front\ShipmentController@householdgoods');
Route::post('householdgoods', 'Front\ShipmentController@householdgoods');
Route::get('others/{id}', 'Front\ShipmentController@others');
Route::post('others', 'Front\ShipmentController@others');
Route::get('home/{id}', 'Front\ShipmentController@home');
Route::post('home', 'Front\ShipmentController@home');
Route::post('truckbooking', 'Front\ShipmentController@truckbooking');

Route::resource('user/find/deliveries', 'Front\FindDeliveriesController@index');
Route::post('user/find/deliveries', 'Front\FindDeliveriesController@index');
Route::get('getsubcategory ', 'Front\FindDeliveriesController@getsubcategory');
Route::get('user/find/deliveries/details/{id}', 'Front\FindDeliveriesController@delivery_details');
Route::get('user/mobileCheck', 'Front\UserHomeController@mobileCheck');
Route::get('user/verifyotp/{id}', 'Front\LoginController@verifyotp');
Route::get('user/resend/opt/{id}', 'Front\LoginController@resendotp');
Route::post('user/checkotp', 'Front\LoginController@checkotp');
Route::get('bid/offer/{id}', 'Front\FindDeliveriesController@bidoffer');
Route::post('bid/offer/{id}', 'Front\FindDeliveriesController@bidoffer');
Route::post('bid/offer/save/{id}', 'Front\FindDeliveriesController@bidoffersave');
Route::post('partner/question/save', 'Front\FindDeliveriesController@partner_question_save');
//sds

//support
Route::get('support/dashboard', 'SupportDashboardController@index');
Route::get('support/inbox', 'SupportDashboardController@inbox');
Route::get('support/user/chatdetails/{id}', 'SupportDashboardController@chatdetails');

// footer
Route::get('privacy/policy', 'Front\UserHomeController@privacy_policy');

#Admin
Route::get('/admin/login', function () {
    if(Auth::check()){return Redirect::to('admin/dashboard');}
    return view('admin/login');
});


// Authentication routes...
Route::get('/auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

Route::get('admin/dashboard', 'DashboardController@index');

# Admin Panel Routes
Route::post('admin/store', 'RegistrationController@store');
Route::get('admin/mobileCheck', 'RegistrationController@mobileCheck');
Route::get('admin/userList', 'RegistrationController@index');
Route::get('admin/users/{id}/delete', 'RegistrationController@destroy');
Route::get('admin/user/status/{id}', 'RegistrationController@user_active_Inactive');
Route::resource('admin/users', 'RegistrationController');

//support
Route::post('admin/support/store', 'SupportController@store');
Route::get('admin/supportList', 'SupportController@index');
Route::get('admin/support/{id}/delete', 'SupportController@destroy');
Route::get('admin/support/status/{id}', 'SupportController@user_active_Inactive');
Route::resource('admin/support', 'SupportController');

# Admin Category
Route::resource('admin/category', 'CategoryController');
Route::post('admin/Category-Save', 'CategoryController@store');
Route::get('admin/category/{id}/update', 'CategoryController@create');
Route::get('admin/category/{id}/delete', 'CategoryController@destroy');

# Admin Materials
Route::resource('admin/materials', 'MaterialsController');
Route::post('admin/materials-Save', 'MaterialsController@store');
Route::get('admin/materials/{id}/update', 'MaterialsController@create');
Route::get('admin/materials/{id}/delete', 'MaterialsController@destroy');
Route::get('admin/partner/changesttus/{id}', 'PartnerRegistrationController@changesttus');
Route::get('admin/materials/status/{id}', 'MaterialsController@changesttus');

# Admin Sub Category
Route::resource('admin/subcategory', 'SubCategoryController');
Route::post('admin/subCategory-Save', 'SubCategoryController@store');
Route::get('admin/subcategory/{id}/update', 'SubCategoryController@create');
Route::get('admin/subcategory/{id}/delete', 'SubCategoryController@destroy');

# Admin partner
Route::resource('admin/partner', 'PartnerRegistrationController');
Route::post('admin/partner/store', 'PartnerRegistrationController@store');
Route::get('admin/partnerList', 'PartnerRegistrationController@index');
Route::get('gettransporterData', 'PartnerRegistrationController@gettransporterData');
Route::get('admin/partner/{id}/delete', 'PartnerRegistrationController@destroy');
Route::get('admin/partner/{id}/approve', 'PartnerRegistrationController@approve');
Route::post('admin/partnerDocumentsUpload', 'PartnerRegistrationController@DocumentsUpload');
Route::get('admin/partner/status/{id}', 'PartnerRegistrationController@partner_active_Inactive');
Route::get('user/emailverify/{id}', 'Front\LoginController@emailverify');
# Truck Length
Route::resource('admin/trucklength', 'TruckLengthController');
Route::post('admin/trucklength-Save', 'TruckLengthController@store');
Route::get('admin/trucklength/{id}/update', 'TruckLengthController@create');
Route::get('admin/trucklength/{id}/delete', 'TruckLengthController@destroy');
Route::get('checktrucklength ', 'TruckLengthController@checktrucklength');

# Truck Capacity
Route::resource('admin/truckcapacity', 'TruckCapacityController');
Route::get('gettrucklength ', 'TruckCapacityController@gettrucklength');
Route::post('admin/truckcapacity-Save', 'TruckCapacityController@store');
Route::resource('admin/truckcapacity-search', 'TruckCapacityController@create');
Route::get('admin/truckcapacity/{id}/update', 'TruckCapacityController@create');
Route::get('admin/truckcapacity/{id}/delete', 'TruckCapacityController@destroy');
Route::get('gettruckcapacity ', 'TruckCapacityController@gettruckcapacity');

# Cost Estimation
Route::resource('admin/cost', 'CostController');
Route::post('admin/cost-Save', 'CostController@store');
Route::get('admin/cost/{id}/update', 'CostController@create');
Route::get('admin/cost/{id}/delete', 'CostController@destroy');

# adminshipment admin
Route::resource('admin/adminshipment/shipList', 'AdminShipmentController@shipList');
Route::resource('admin/adminshipment', 'AdminShipmentController');
Route::post('admin/adminshipment-Save', 'AdminShipmentController@store');
Route::post('admin/adminshipment-update', 'AdminShipmentController@update');
Route::get('admin/adminshipment/{id}/update', 'AdminShipmentController@edit');
Route::get('admin/adminshipment/{id}/delete', 'AdminShipmentController@destroy');

#Transaction-History
Route::resource('admin/transaction/history', 'TransactionHistoryController');


#shipment/report
Route::get('shipment/detailsReport/{id}', 'ShipmentReportController@details_report');
Route::get('shipment/BidsReport/{id}', 'ShipmentReportController@bids_report');
Route::get('shipment/codPayments/{id}', 'ShipmentReportController@cod_payments');
Route::get('shipment/cod/payment/{id}', 'ShipmentReportController@cod_payments_save');
Route::resource('shipment/{id}/report', 'ShipmentReportController');
Route::post('shipment/reportList/{id}', 'ShipmentReportController@index');








#Webservices
Route::post('webservicesToken/token', 'AndroidController@token');
Route::post('webservices/login', 'AndroidController@login');
Route::post('webservices/sendOtp', 'AndroidController@sendOtp');
Route::post('webservices/otpVerification', 'AndroidController@otpVerification');
Route::post('webservices/userRegistration', 'AndroidController@userRegistration');
Route::post('webservices/partnerRegistration', 'AndroidController@partnerRegistration');
Route::post('webservices/getCategories', 'AndroidController@getCategories');
Route::post('webservices/getSubCategories', 'AndroidController@getSubCategories');
Route::post('webservices/getDiningData', 'AndroidController@getDiningData');
Route::post('webservices/getLivingRoomData', 'AndroidController@getLivingRoomData');
Route::post('webservices/getKitchenData', 'AndroidController@getKitchenData');
Route::post('webservices/getHomeOfficeData', 'AndroidController@getHomeOfficeData');
Route::post('webservices/getGarageData', 'AndroidController@getGarageData');
Route::post('webservices/getOutdoorData', 'AndroidController@getOutdoorData');
Route::post('webservices/getMiscellaneousData', 'AndroidController@getMiscellaneousData');
Route::post('webservices/homeCategory', 'AndroidController@homeCategory');
Route::post('webservices/getBoxesData', 'AndroidController@getBoxesData');
Route::post('webservices/getEquipmentData', 'AndroidController@getEquipmentData');
Route::post('webservices/getGeneralShipmentData', 'AndroidController@getGeneralShipmentData');
Route::post('webservices/getMaterial', 'AndroidController@getMaterial');
Route::post('webservices/officeCategory', 'AndroidController@officeCategory');
Route::post('webservices/otherCategory', 'AndroidController@otherCategory');
Route::post('webservices/houseHoldGoodsCategory', 'AndroidController@houseHoldGoodsCategory');
Route::post('webservices/vehicleShifting', 'AndroidController@vehicleShifting');
Route::post('webservices/partLoad', 'AndroidController@partLoad');
Route::post('webservices/getTruckLength', 'AndroidController@getTruckLength');
Route::post('webservices/getTruckCapacity', 'AndroidController@getTruckCapacity');
Route::post('webservices/truckBooking', 'AndroidController@truckBooking');
Route::post('webservices/confirmAddress', 'AndroidController@confirmAddress');
Route::post('webservices/shipmentPost', 'AndroidController@shipmentPost');
Route::post('webservices/myDeliveries', 'AndroidController@myDeliveries');
Route::post('webservices/deliveryDetail', 'AndroidController@deliveryDetail');
Route::post('webservices/quotationOffer', 'AndroidController@quotationOffer');
Route::post('webservices/notification', 'AndroidController@notification');
Route::post('webservices/addNewAccount', 'AndroidController@addNewAccount');
Route::post('webservices/bankInfo', 'AndroidController@bankInfo');
Route::post('webservices/transactionDetail', 'AndroidController@transactionDetail');
Route::post('webservices/changePassword', 'AndroidController@changePassword');
Route::post('webservices/editProfile', 'AndroidController@editProfile');
Route::post('webservices/viewProfile', 'AndroidController@viewProfile');
Route::post('webservices/userQuestions', 'AndroidController@userQuestions');
Route::post('webservices/findDelivery', 'AndroidController@findDelivery');
Route::post('webservices/findDetail', 'AndroidController@findDetail');
Route::post('webservices/categoryFilter', 'AndroidController@categoryFilter');
Route::post('webservices/minimumBid', 'AndroidController@minimumBid');
Route::post('webservices/submitOffer', 'AndroidController@submitOffer');
Route::post('webservices/myOffers', 'AndroidController@myOffers');
Route::post('webservices/myOfferDetail', 'AndroidController@myOfferDetail');
Route::post('webservices/partnerNotification', 'AndroidController@partnerNotification');
Route::post('webservices/partnerTransactionDetail', 'AndroidController@partnerTransactionDetail');
Route::post('webservices/quotationOfferDetail', 'AndroidController@quotationOfferDetail');
Route::post('webservices/acceptOffer', 'AndroidController@acceptOffer');
Route::post('webservices/rejectOffer', 'AndroidController@rejectOffer');
Route::post('webservices/payment', 'AndroidController@payment');
Route::post('webservices/askQuestion', 'AndroidController@askQuestion');
Route::post('webservices/quesAnswer', 'AndroidController@quesAnswer');
Route::post('webservices/getAnswer', 'AndroidController@getAnswer');
Route::post('webservices/deleteShipment', 'AndroidController@deleteShipment');
Route::post('webservices/partnerProfileEdit', 'AndroidController@partnerProfileEdit');
Route::post('webservices/kycUpdate', 'AndroidController@kycUpdate');
Route::post('webservices/viewKYC', 'AndroidController@viewKYC');
Route::post('webservices/partnerProfileView', 'AndroidController@partnerProfileView');
Route::post('webservices/deleteBankInfo', 'AndroidController@deleteBankInfo');
Route::post('webservices/paymentSucess', 'AndroidController@paymentSucess');
Route::post('webservices/paymentFailure', 'AndroidController@paymentFailure');
Route::post('webservices/paymentByCod', 'AndroidController@paymentByCod');
Route::post('webservices/createHashForPay', 'AndroidController@createHashForPay');
Route::post('webservices/demoPaymentSucess', 'AndroidController@demoPaymentSucess');
Route::post('webservices/customerPaymentDetail', 'AndroidController@customerPaymentDetail');
Route::post('webservices/partnerPaymentDetail', 'AndroidController@partnerPaymentDetail');
Route::post('webservices/conjob', 'AndroidController@conjob');
Route::post('webservices/sendMsgByUser', 'AndroidController@sendMsgByUser');
Route::post('webservices/ReceiveMsgByUser', 'AndroidController@ReceiveMsgByUser');
Route::get('webservices/privacyPolicy', 'AndroidController@privacyPolicy');
Route::post('webservices/generateHashCode', 'AndroidController@generate_hash_code');