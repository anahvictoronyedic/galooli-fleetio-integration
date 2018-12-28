<?php

require_once 'db.php';

function configuration($name,$value=false){
	global $db_server;
	$name=mysqli_real_escape_string($db_server,$name);
	if($value !== false){
		$value=mysqli_real_escape_string($db_server,$value);
		$query = "REPLACE INTO configuration(name,value) VALUES ('$name',".(is_null($value) ? "NULL" : "'$value'" ).")";
		return mysqli_query($db_server,$query)!==false;
	}
	$query = "select * from configuration where name = '$name'";
	$result = mysqli_query($db_server,$query);
	if($result){
		if( $row = mysqli_fetch_array($result) ){
			return $row['value'];
		}
	}
	return false;
}

function require_login(){
	if(!isset($_SESSION['logged_in'])){
		header('Location: '.path('login.php'));
		die();
	}
}