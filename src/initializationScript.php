<?php

/*
NB: Only run this once, when initializing Data, for proper synchronization
*/
require_once 'config.php';
require_once "ProcessData.php";

echo "<a href='".path('index.php')."'>Back To Home Page</a>";
$processData = new ProcessData();

$responseData = $processData->pullDataFromGalooli(true);