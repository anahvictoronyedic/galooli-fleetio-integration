<?php

require_once '../vendor/autoload.php';

session_start();

define('IN_SERVER',false);
define('DEBUG_MODE',true);

// WARNING: Take note of the trailing slash, its needed
define('PARAM_ROOT_PATH','/galooli/galooli-fleetio-integration/');

function path($path){
	return PARAM_ROOT_PATH . $path;
}

?>