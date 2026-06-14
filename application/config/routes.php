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
// $route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['default_controller'] = 'auth/login';  // sebelumnya welcome
$route['login']  = 'auth/login';
$route['logout'] = 'auth/logout';

$route['absensi']         = 'absensi/index';
$route['absensi/masuk']   = 'absensi/masuk';
$route['absensi/pulang']  = 'absensi/pulang';

$route['pengajuan']        = 'pengajuan/index';
$route['pengajuan/create'] = 'pengajuan/create';
$route['pengajuan/store']  = 'pengajuan/store';

$route['pengajuan-admin']        = 'pengajuan_admin/index';
$route['pengajuan-admin/approve/(:num)'] = 'pengajuan_admin/approve/$1';
$route['pengajuan-admin/reject/(:num)']  = 'pengajuan_admin/reject/$1';

$route['laporan'] = 'laporan/index';
$route['laporan/excel'] = 'laporan/excel';
$route['laporan/pdf']   = 'laporan/pdf';

$route['profil/password']        = 'profil/password';
$route['profil/password/update'] = 'profil/password_update';

$route['auth/forgot_password']        = 'auth/forgot_password';
$route['auth/forgot_password/process'] = 'auth/forgot_password_process';
// $route['auth/reset_password']          = 'auth/reset_password';
// $route['auth/reset_password/process']  = 'auth/reset_password_process';

$route['auth/forgot_password/otp'] = 'auth/forgot_password_otp';
$route['auth/forgot_password/verify'] = 'auth/forgot_password_verify';
$route['auth/forgot_password/resend'] = 'auth/forgot_password_resend';
$route['auth/forgot_password/new_password'] = 'auth/forgot_password_new_password';
$route['auth/forgot_password/new_password_process'] = 'auth/forgot_password_new_password_process';

$route['penugasan_lapangan'] = 'penugasan_lapangan/index';
$route['penugasan_lapangan/create'] = 'penugasan_lapangan/create';
$route['penugasan_lapangan/store'] = 'penugasan_lapangan/store';
$route['penugasan_lapangan/detail/(:num)'] = 'penugasan_lapangan/detail/$1';
$route['penugasan_lapangan/edit/(:num)'] = 'penugasan_lapangan/edit/$1';
$route['penugasan_lapangan/update/(:num)'] = 'penugasan_lapangan/update/$1';
$route['penugasan_lapangan/delete/(:num)'] = 'penugasan_lapangan/delete/$1';

$route['penugasan_lapangan/history'] = 'penugasan_lapangan/history';

$route['penugasan_wfh'] = 'penugasan_wfh/index';
$route['penugasan_wfh/create'] = 'penugasan_wfh/create';
$route['penugasan_wfh/store'] = 'penugasan_wfh/store';
$route['penugasan_wfh/edit/(:num)'] = 'penugasan_wfh/edit/$1';
$route['penugasan_wfh/update/(:num)'] = 'penugasan_wfh/update/$1';
$route['penugasan_wfh/delete/(:num)'] = 'penugasan_wfh/delete/$1';

$route['forbidden'] = 'errors/forbidden';
$route['errors/forbidden'] = 'errors/forbidden';








