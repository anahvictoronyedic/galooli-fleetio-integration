
<?php

if(php_sapi_name() === 'cli'){

	echo '<h3>Pull Cron Running</h3>';

    require_once 'ProcessData.php';

    $query = "UPDATE configuration SET value = value + 1  where name = 'no_of_pull_cron'";

    if (Database::updateOrInsert($query)) {
        echo "No of pull cron Record updated successfully<br/><br/>";
    } else {
        echo "Error updating record: " . mysqli_error($GLOBALS['db_server'])."<br/>";
    }

    $processData = new ProcessData();
    $processData->pullDataFromGalooli(false);

    echo '<h3>Pull Cron Ran</h3>';
	
}