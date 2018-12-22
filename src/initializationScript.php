<?php

//get Ids from fleetio
require_once "ProcessData.php";

$processData = new ProcessData();

$responseData = $processData->pullDataFromGalooli(true);