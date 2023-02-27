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
$route['menu']								= 'order/index'; // list menu
$route['order']								= 'order/list_order'; // after submit cart
$route['do_order']							= 'order/do_order'; // proses submit order
$route['finish_order']						= 'order/finish_order'; // proses submit order
$route['cart']								= 'order/cart';
$route['do_cart/(:any)']					= 'order/do_cart';
$route['del_cart/(:any)']					= 'order/del_cart';
$route['status']							= 'order/status';

$route['login'] 							= 'conf/index';
$route['dologin'] 							= 'conf/action_login';
$route['logout'] 							= 'conf/logout';

$route['dashboard']							= 'kitchen/dashboard';

$route['payment_paid/(:any)']				= 'kitchen/payment_paid';
$route['payment_unpaid/(:any)']			    = 'kitchen/payment_unpaid';

$route['list_menu']							= 'kitchen/list_menu';
$route['list_menu_off/(:any)']				= 'kitchen/list_menu_off';
$route['list_menu_on/(:any)']				= 'kitchen/list_menu_on';

$route['list_order/(:any)'] 				= 'kitchen/list_order_detail';
$route['update_order/(:num)/(:num)/(:any)'] = 'kitchen/update_order';

$route['default_controller'] 		= 'order/index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
