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