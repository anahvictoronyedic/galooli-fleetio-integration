<?php

/*
NB: Only run this once, when initializing Data, for proper synchronization
*/
require_once "ProcessData.php";

echo "<a href='/galooli-fleetio-integration/'>Back To Home Page</a>";
$processData = new ProcessData();

$responseData = $processData->pullDataFromGalooli(true);