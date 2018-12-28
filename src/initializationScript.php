<?php

/*
NB: Only run this once, when initializing Data, for proper synchronization
*/
require_once "ProcessData.php";

$processData = new ProcessData();

$responseData = $processData->pullDataFromGalooli(true);