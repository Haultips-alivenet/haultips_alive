<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*

| -------------------------------------------------------------------------

| URI ROUTING

| -------------------------------------------------------------------------

| This file lets you re-map URI requests to specific controller functions.

|

| Typically there is a one-to-one relationship between a URL string

| and its corresponding controller class/method. The segments in a

| URL normally follow this pattern:

|

|	example.com/class/method/id/

|

| In some instances, however, you may want to remap this relationship

| so that a different class/function is called than the one

| corresponding to the URL.

|

| Please see the user guide for complete details:

|

|	http://codeigniter.com/user_guide/general/routing.html

|

| -------------------------------------------------------------------------

| RESERVED ROUTES

| -------------------------------------------------------------------------

|

| There area two reserved routes:

|

|	$route['default_controller'] = 'welcome';

|

| This route indicates which controller class should be loaded if the

| URI contains no data. In the above example, the "welcome" class

| would be loaded.

|

|	$route['404_override'] = 'errors/page_missing';

|

| This route will tell the Router what URI segments to use if those provided

| in the URL cannot be matched to a valid route.

|

*/
Route::get('/', 'Front\UserHomeController@index');
Route::resource('user/dashboard', 'Front\UserHomeController@index');
Route::resource('user/home', 'Front\UserHomeController@userdashboard');
Route::resource('user/faq', 'Front\UserHomeController@faq');
Route::resource('user/notification', 'Front\UserHomeController@notification');
Route::resource('user/changepassword', 'Front\UserHomeController@changepassword');
Route::resource('user/transactionhistory', 'Front\UserHomeController@transactionhistory');
Route::get('getquesAns', 'Front\UserHomeController@getquesAns');
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


Route::get('office/{id}', 'Front\ShipmentController@office');
Route::get('fourwheeler/{id}', 'Front\ShipmentController@twowheeler');
Route::get('twowheeler/{id}', 'Front\ShipmentController@twowheeler');
Route::post('twowheeler', 'Front\ShipmentController@twowheeler');
Route::post('fourwheeler', 'Front\ShipmentController@twowheeler');
Route::get('user/getoffer', 'Front\ShipmentController@getoffer');
Route::get('getoffer', 'Front\ShipmentController@getoffer');

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
Route::get('shipment/BidsReport/{id}', 'ShipmentReportController@bids_report');
Route::resource('shipment/{id}/report', 'ShipmentReportController');
Route::resource('shipment/reportList/{id}', 'ShipmentReportController@index');



$route['default_controller'] = "home";

$route['404_override'] = 'error404';

$route['forgot-password'] = "login/forgot_password";

$route['car-shipping'] = "singlecategory/index";

$route['shipping-profile'] = "shipping/setting/profile";

$route['shipping-edit-profile'] = "shipping/setting/profile_update";

$route['profile_sitesettings'] = "shipping/setting/regional_setting";

$route['profile_communication'] = "shipping/setting/profile_communication";

$route['shipping-change-password'] = "shipping/setting/change_password";

$route['services/(:any).html'] = "services/index/$1";

$route['find-detail/(:any)/(:any)'] = "find/find_details/$1/$2";

$route['admin-find-detail/(:any)/(:any)'] = "admin/find/find_details/$1/$2";

$route['carrier-inbox-detail/(:any)'] = "carrier/inbox/inbox_detail/$1";

$route['carrier-question-detail/(:any)'] = "carrier/question/question_detail/$1";

$route['shipping-inbox-detail/(:any)'] = "shipping/inbox/inbox_detail/$1";

$route['shipping-question-detail/(:any)'] = "shipping/question/question_detail/$1";

$route['helpdesk'] = "helpdesk/home";

$route['shipper-quote-detail/(:any)'] = "carrier/shipperquotelist/shipper_quote_detail/$1";

$route['driver-quote-detail/(:any)'] = "carrier/driver/driver_quote_detail/$1";

//$route['tracking']="tracking/index/$1";



$route['update-shipment/(:any)'] = "shipping/shipper/edit_shipment/$1";

$route['transporter/(:any)'] = "transporter/index/$1";

$route['about-us'] = "aboutus";

$route['driver-login'] = "login/driver_login";

$route['driver-dashboard'] = "carrier/driver/driver_dashboard";

$route['our-team'] = "team";

$route['widgets'] = "tools/widgets";

$route['tracking'] = "tracking/tracker";

$route['shipping-customer-tools'] = "tools/shippingcustomertools";

$route['service-provider-tools'] = "tools/serviceprovidertools";

$route['member-directory'] = "memberdirectory";

$route['delivery-price-estimator'] = "tools/deliverypriceestimator";

$route['resource-center'] = "tools/resourcecenter";

$route['shipping-api'] = "tools/shippingapi";

$route['press'] = "company/press";

$route['highway-to-help'] = "company/highwaytohelp";

$route['jobs'] = "company/jobs";

$route['green-transport'] = "company/greentransport";

$route['get-answers'] = "communitysupport/getanswers";

$route['community'] = "communitysupport/community";

$route['guide'] = "communitysupport/guide";

$route['stories'] = "communitysupport/stories";

$route['safe-transportation'] = "communitysupport/safetransportatio";

$route['transporters'] = "gettingstarted/transporters";

$route['power-providers'] = "gettingstarted/powerproviders";

$route['privacy-policy'] = "privacypolicy";

$route['terms-of-use'] = "termsofuse";

$route['rss-feed'] = "rssfeed";



$route['car-transport'] = "services/car_transport";

$route['courier-services'] = "services/courier_services";

$route['customers-services'] = "services/customers_services";

$route['man-with-a-van'] = "services/man_with_van";

$route['international-shipping'] = "services/international_shipping";
$route['furniture-delivery'] = "services/furniture_del";


$route['carrier-profile'] = "carrier/setting/profile";

$route['carrier-update-profile'] = "carrier/setting/edit_profile";

$route['carrier-change-password'] = "carrier/setting/change_password";

$route['driver-change-password'] = "carrier/driver/change_password";

$route['carrier/payment-history'] = "carrier/payments/payment_history";



$route['apmokek-ir-ivertink'] = "payandvote";



//routing payment-methods to payment_methods

$route['shipping/payment-methods'] = "shipping/payments/payment_methods";

/*$route['shipping/manage-customer-quote/(:any)/(:any)'] = "shipping/managecustomerquote/$1/$2";

$route['shipping/manage-customer-quote/(:any)'] = "shipping/managecustomerquote/$1";

$route['shipping/manage-customer-quote'] = "shipping/managecustomerquote";*/





/* End of file routes.php */

/* Location: ./application/config/routes.php */
$route['android/registration'] = "android/registration";
$route['android/login'] = "android/login";
$route['android/forgotPassword'] = "android/forgotPassword";
$route['android/verifyOtp'] = "android/verifyOtp";
$route['android/sendOtp'] = "android/sendOtp";
$route['android/testSms'] = "android/testSms";