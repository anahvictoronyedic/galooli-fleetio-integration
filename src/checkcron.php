
<?php

require_once 'ProcessData.php';

if(php_sapi_name() === 'cli'){

    $processData = new ProcessData();
    $processData->checkforChangeWithinLastHour();
}