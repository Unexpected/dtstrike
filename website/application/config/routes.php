<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['default_controller'] = "welcome";
$route['404_override'] = '';


//$route['game/(:num)'] = "game/$1";
//$route['game/u/(:num)'] = "game/$1";

$route['user/(:num)'] = "user/view/$1";
$route['admin/user/(:num)'] = "admin/user_update/$1";

// example: '/en/about' -> use controller 'about'
$route['^fr/(.+)$'] = "$1";
$route['^en/(.+)$'] = "$1";
 
// '/en' and '/fr' -> use default controller
$route['^fr$'] = $route['default_controller'];
$route['^en$'] = $route['default_controller'];


/* End of file routes.php */
/* Location: ./application/config/routes.php */