
<?php

if(php_sapi_name() === 'cli'){

	echo '<h3>Pull Cron Running</h3>';

	require_once 'ProcessData.php';

    $processData = new ProcessData();
    $processData->pullDataFromGalooli(false);

    echo '<h3>Pull Cron Ran</h3>';
	
}