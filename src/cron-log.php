<?php 
require_once 'utils.php';
require_login();

if(DEBUG_MODE){

	$file = '/var/log/galooli-fleetio.log';
clearstatcache();
	if (file_exists($file)) {
		readfile($file);
		if( isset($_GET['reset']) ){
			exec( ' > '.$file );
		}
	}
}
else echo '<h3>Not enabled for now</h3>';