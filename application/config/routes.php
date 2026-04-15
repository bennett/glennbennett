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
$route['default_controller'] = 'site';
$route['404_override'] = 'my404';
$route['translate_uri_dashes'] = FALSE;
$route['cortez'] = 'cortez';
$route['booking'] = 'booking';
$route['facebook'] = 'facebook';
$route['cortez_edit'] = 'cortez_edit';
$route['newsletter'] = 'site/mlist';
$route['calendar'] = 'site/cal';
$route['contact'] = 'contact/index';
$route['contact/send'] = 'contact/send';
$route['cal-image'] = 'site/cal_image';
$route['cal-image/(:any)'] = 'site/cal_image/$1';

// Admin routes
$route['admin'] = 'admin/index';
$route['admin/login'] = 'admin_login/index';
$route['admin/login/check'] = 'admin_login/check';
$route['admin/logout'] = 'admin_login/logout';
$route['admin/login/google'] = 'admin_login/google';
$route['admin/login/google_callback'] = 'admin_login/google_callback';
$route['admin/dup_events'] = 'admin_dup/index';
$route['admin/dup_events/day'] = 'admin_dup/day';
$route['admin/dup_events/generate_csv'] = 'admin_dup/generate_csv';
$route['admin/promo'] = 'admin_promo/index';
$route['admin/promo/(:any)'] = 'admin_promo/$1';
$route['admin/(:any)'] = 'admin/$1';

// Tools
$route['migrate'] = 'migrate/index';
$route['migrate/run'] = 'migrate/run';
$route['migrate/rollback'] = 'migrate/rollback';

$route['(:any)'] = 'site/$1';


/*
$route['follow'] = '/site/mlist';
$route['cal'] = '/site/cal';
$route['calendar'] = '/site/cal';
$route['about'] = '/site/about';
$route['tip'] = '/site/tip';
*/
