<?php

require_once 'config.php';

error_reporting( DEBUG_MODE ? E_ALL & ~E_DEPRECATED & ~E_NOTICE : 0 );

$db_hostname = "localhost";
$db_username = IN_SERVER ? "ecagon" :'root';
$db_password = IN_SERVER ? "myskynet123sqldb":'';
$db_database = IN_SERVER ? "galoolifleetio" : "galoolifleetio"; 

$db_server=mysqli_connect($db_hostname,$db_username,$db_password); //connection is established here
if(!$db_server)
{
	die('oops database connection problem ! --> '.mysqli_error());
}

$db_select=mysqli_select_db($db_server,$db_database);
if(!$db_select)
{
	die('oops database selection problem ! --> '.mysqli_error());
}

else{

}

?>