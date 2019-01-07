<?php
if(php_sapi_name() != 'cli'){
	session_start();
}

require_once dirname(__FILE__) . '/../vendor/autoload.php';

define('IN_SERVER',true);
define('DEBUG_MODE',true);

// WARNING: Take note of the trailing slash, its needed
define('PARAM_ROOT_PATH','/galooli-fleetio-integration/');

define( 'LOGIN_USER' , IN_SERVER ? 'project' : 'galooli' );
define( 'LOGIN_PASSWORD' , IN_SERVER ? 'skynet123' : 'galooli' );

function path($path){
	return PARAM_ROOT_PATH . $path;
}

?>