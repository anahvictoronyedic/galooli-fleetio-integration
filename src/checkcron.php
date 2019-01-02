
<?php

if(php_sapi_name() === 'cli'){

	echo '<h3>Check Change Cron Running</h3>';

	require_once 'ProcessData.php';

    $processData = new ProcessData();
    $processData->checkforChangeWithinLastHour();

    echo '<h3>Check Change Cron Ran</h3>';
}