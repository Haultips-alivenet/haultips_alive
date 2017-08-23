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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/login', function () {
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

