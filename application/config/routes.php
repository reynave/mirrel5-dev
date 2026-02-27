<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
| Please see the user guide for complete details:
|	https://codeigniter.com/user_guide/general/routing.html
*/
$route['default_controller'] = 'site';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
 
$route['login/access/']     = "login/access/";   
$route['login/logout/']     = "login/logout/";   
$route['sitemap.xml']       = 'override/sitemap/'; 
$route['rss']               = 'override/rss/'; 
$route['info.json']         = 'override/infojs/'; 

$route['p/(:any)']          = 'site/catalog/$1'; 
$route['p/(:any)/(:any)']   = 'site/product/$1/$2'; 

$route['(:any)'] = 'site/index/$1'; 
//$route['(:any)/(:any)'] = 'website/index/$1/$2'; 


