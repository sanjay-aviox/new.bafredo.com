<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/


$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['admin'] = 'admin/admin';
$route['product/all'] = 'product/all';
$route['product/sales'] = 'product/sales';
$route['product/new'] = 'product/newArrival';
$route['admin/settings'] = 'admin/dashboard/settings';
$route['admin/subscribe'] = 'admin/dashboard/subscribe';
$route['admin/contact-us'] = 'admin/dashboard/contact_us';
$route['admin/orders'] = 'admin/dashboard/orders';
$route['admin/product/([0-9]+)/gallery'] = 'admin/product/gallery/$1';

$route['page/(.*)/(.*)'] = 'page/$1/$2';
$route['page(.*)'] = 'page/index$1';
$route['account/remove_history'] = 'account/remove_history';
$route['account/order_history(.*)'] = 'account/order_history$1';
$route['account'] = 'account/index';
$route['product/(.*)/(.*)'] = 'product/$1/$2';
$route['product(.*)'] = 'product/index$1';
$route['category(.*)'] = 'category/index$1';
$route['verifyEmail'] = 'account/verifyemail';
$route['match-otp'] = 'account/confirmOTP';
$route['feature-product'] = 'home/feature';
$route['feature-product/(.*)'] = 'home/feature';
$route['search-product'] = 'home/searchresult';
$route['filter-product'] = 'home/filters';
$route['refresh-captcha'] = 'registration/refreshCaptcha';
$route['forgot-password'] = 'login/forgotpassword';
$route['add-review'] = 'product/addReviews';

$route['reset-password'] ='account/resetPassword';


