<!DOCTYPE html>
<html lang="en">
<head>
  <title>Galooli | Fleetio Integration Interface</title>
  <?php require_once('partials/head.php'); ?>
  <meta http-equiv="refresh" content="120" > 
</head>
<body>

<?php

require_once 'config.php';
require_once "ProcessData.php";

$processData = new ProcessData();

echo '<div style="margin:auto; width: 800px">';
echo "<a href='".path('index.php')."'>Back To Home Page</a>";
$responseData = $processData->pullDataFromGalooli(false);
echo "</div>";

?>

</body>
</html>