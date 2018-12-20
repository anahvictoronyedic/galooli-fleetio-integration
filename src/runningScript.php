<?php

require_once "ProcessData.php";

echo "Running Script ... ";
$processData = new ProcessData();

$responseData = $processData->pullDataFromGalooli();

echo "Response Data: ".$responseData;

/*
TODO: initialize call to galooli api and get data
*/

/*
TODO: process data gotten to check if eligible for a push to fleetio servers
*/

/*
TODO: push data to fleetio server to fleetio servers

*/

/*
TODO: indicate/ throw exception when a push or pull of data returns with an error message thrice

*/

?>