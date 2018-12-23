<?php


require_once 'config.php';
require_once "ProcessData.php";

$processData = new ProcessData();

$responseData = $processData->pullDataFromGalooli(false);


/*
TODO: push data to fleetio server to fleetio servers

*/

/*
TODO: indicate/ throw exception when a push or pull of data returns with an error message thrice

*/

?>