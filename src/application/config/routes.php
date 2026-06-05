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
|	https://codeigniter.com/userguide3/general/routing.html
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
$route['default_controller']        = 'Home';
$route['404_override']              = '';
$route['translate_uri_dashes']      = FALSE;

// 前台
$route['about']                       = 'Front/About/index';
$route['products']                    = 'Front/Products/index';
$route['products/category/(:any)']    = 'Front/Products/category/$1';
$route['products/(:num)']             = 'Front/Products/detail/$1';
$route['news']                        = 'Front/News/index';
$route['news/(:num)']                 = 'Front/News/detail/$1';
$route['contact']                     = 'Front/Contact/index';
$route['contact/send']                = 'Front/Contact/send';

// 購物車
$route['cart']                        = 'Front/Cart/index';
$route['cart/add']                    = 'Front/Cart/add';
$route['cart/update']                 = 'Front/Cart/update';
$route['cart/remove/(:num)']          = 'Front/Cart/remove/$1';

// 會員
$route['member/register']             = 'Front/Member/register';
$route['member/login']                = 'Front/Member/login';
$route['member/logout']               = 'Front/Member/logout';
$route['member/dashboard']            = 'Front/Member/dashboard';
$route['member/orders/(:any)']        = 'Front/Member/order_detail/$1';

// 結帳
$route['checkout']                    = 'Front/Checkout/index';
$route['checkout/confirm']            = 'Front/Checkout/confirm';
$route['checkout/success/(:any)']     = 'Front/Checkout/success/$1';

// 後台
$route['admin']                       = 'Admin/Auth/login';
$route['admin/login']                 = 'Admin/Auth/login';
$route['admin/logout']                = 'Admin/Auth/logout';
$route['admin/dashboard']             = 'Admin/Dashboard/index';
$route['admin/products']              = 'Admin/Products/index';
$route['admin/products/create']       = 'Admin/Products/create';
$route['admin/products/store']        = 'Admin/Products/store';
$route['admin/products/edit/(:num)']  = 'Admin/Products/edit/$1';
$route['admin/products/update/(:num)']= 'Admin/Products/update/$1';
$route['admin/products/delete/(:num)']= 'Admin/Products/delete/$1';
$route['admin/products/toggle/(:num)']= 'Admin/Products/toggle/$1';
$route['admin/orders']                = 'Admin/Orders/index';
$route['admin/orders/(:num)']         = 'Admin/Orders/detail/$1';
$route['admin/orders/status/(:num)']  = 'Admin/Orders/update_status/$1';
$route['admin/news']                  = 'Admin/News/index';
$route['admin/news/create']           = 'Admin/News/create';
$route['admin/news/store']            = 'Admin/News/store';
$route['admin/news/edit/(:num)']      = 'Admin/News/edit/$1';
$route['admin/news/update/(:num)']    = 'Admin/News/update/$1';
$route['admin/news/delete/(:num)']    = 'Admin/News/delete/$1';
$route['admin/news/toggle/(:num)']    = 'Admin/News/toggle/$1';
$route['admin/contacts']              = 'Admin/Contacts/index';
$route['admin/contacts/(:num)']       = 'Admin/Contacts/detail/$1';
$route['admin/contacts/delete/(:num)']= 'Admin/Contacts/delete/$1';
