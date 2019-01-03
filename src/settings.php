<?php 
require_once 'utils.php';
require_once 'cron.php';

function make_select_options($def = null){
  return array_reduce(array_map(function($v) use($def){
    $_v = htmlspecialchars($v);
    $attr = isset($def) && $def == $v ? 'selected="selected"' : '';
    return "<option $attr value=\"".$v."\">".($_v < 1 ? ' --- STOP ---' : $_v )."</option>";
  },range(0,150,DEBUG_MODE?1:10)), function($s,$v){
    return $s . $v;
  },'');
}

require_login();

if(isset($_POST['action'])){
  $cron1 = new Cron('/usr/bin/php /var/www/project.matrixvtrack.com/app/src/pullcron.php >> /var/log/galooli-fleetio.log 2>&1' 
    , 'PULL_CRON_INTERVAL_MINUTES');
  $cron1->updateCron($_POST['pull-interval']);

  $cron2 = new Cron('/usr/bin/php /var/www/project.matrixvtrack.com/app/src/checkcron.php >> /var/log/galooli-fleetio.log 2>&1'
    , 'CHECK_CHANGE_CRON_INTERVAL_MINUTES');
  $cron2->updateCron($_POST['check-change-interval']);

  $toast_message = 'Settings was updated successfully.';
}

if(isset($_POST['update_condition'])){
    $query = "UPDATE configuration SET value='".$_POST['odometer']."' where name = 'difference_in_odometer'";
    if (Database::updateOrInsert($query)) {
        $toast_message = 'Odometer And Fuel Readings Check Conditions Updated';
    } else {
        $toast_message = "Error updating record: " . mysqli_error($GLOBALS['db_server']);
    }

    $query = "UPDATE configuration SET value='".$_POST['fuel_update']."' where name = 'difference_in_fuel'";
    if (Database::updateOrInsert($query)) {
      $toast_message = 'Odometer And Fuel Readings Check Conditions Updated';
    } else {
        $toast_message = "Error updating record: " . mysqli_error($GLOBALS['db_server']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  
  <title>Galooli | Settings Page</title>

  <?php require_once('partials/head.php'); ?>
</head>
<body>
  <?php require_once('partials/header.php'); ?>
  <div class="container">
    
    <div class="section">
      <form class="col s12" method="POST" action="settings.php">
        <!--<div class="input-field col s6">
          <input id="last_name" type="text" class="validate">
          <label for="last_name">Last Name</label>
        </div>-->
        <div class="row">
          <div class="input-field col s10 m4">
            <select name="pull-interval">
              <?= make_select_options( configuration('PULL_CRON_INTERVAL_MINUTES') ) ?>
            </select>
            <label>Pull From Galooli Every</label>
          </div>
          <div class="col s2 m1 padding-top">
            Minutes
          </div>
          <div class="input-field col s10 m4 offset-m2">
            <select name="check-change-interval">
              <?= make_select_options( configuration('CHECK_CHANGE_CRON_INTERVAL_MINUTES') ) ?>
            </select>
            <label>Push To Fleetio Every</label>
          </div>
          <div class="col s2 m1 padding-top">
            Minutes
          </div>
        </div>
        <div class="row center-align" style="padding-left:40px;">
          <button class="btn btn-large waves-effect waves-light" type="submit" name="action">
            Update Intervals
          </button>
        </div>
      </form>
    </div>

      <div class="section">
          <h5 class="center-align">Set Fleetio Update Condition</h5>
          <?php
              $query = "SELECT value from configuration where name = 'difference_in_odometer'";
              $tableRow = Database::getSingleRow($query);
              $odometerCheck = $tableRow["value"];
              $query = "SELECT value from configuration where name = 'difference_in_fuel'";
              $tableRow = Database::getSingleRow($query);
              $fuelCheck = $tableRow["value"];
          ?>
          <form class="col s12" method="POST" action="settings.php">
              <div class="row">
                  <div class="input-field col s10 m4 offset-m1">
                      <input name="odometer" value="<?=$odometerCheck  ?>">
                      <label class="active">Odometer Check Threshold(Kilometres)</label>
                  </div>
                  <div class="input-field col s10 m4 offset-m2">
                      <input name="fuel_update" value="<?=$fuelCheck  ?>">
                      <label class="active">Fuel Check Threshold(Litres)</label>
                  </div>
              </div>
              <div class="row center-align" style="padding-left:40px;">
                  <button class="btn btn-large waves-effect waves-light orange" type="submit" name="update_condition">
                      Update Conditions
                  </button>
              </div>
          </form>
      </div>
  </div>
  
    <?php require_once('partials/footer.php'); ?>
    
  </body>
</html>
