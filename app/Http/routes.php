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
#User
Route::resource('user/find/deliveries', 'Front\FindDeliveriesController');
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
Route::get('admin/userList', 'RegistrationController@index');

Route::get('admin/users/{id}/delete', 'RegistrationController@destroy');
Route::resource('admin/users', 'RegistrationController');

# Admin Category
Route::resource('admin/category', 'CategoryController');
Route::post('admin/Category-Save', 'CategoryController@store');
Route::get('admin/category/{id}/update', 'CategoryController@create');
Route::get('admin/category/{id}/delete', 'CategoryController@destroy');

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
Route::resource('shipment/{id}/report', 'ShipmentReportController');
Route::resource('shipment/reportList/{id}', 'ShipmentReportController@index');








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
Route::post('webservices/test', 'AndroidController@test');