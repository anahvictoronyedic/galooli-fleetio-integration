<?php 
require_once 'utils.php';
require_once 'cron.php';

function make_select_options($def = null){
  return array_reduce(array_map(function($v) use($def){
    $_v = htmlspecialchars($v);
    $attr = isset($def) && $def == $v ? 'selected="selected"' : '';
    return "<option $attr value=\"".$v."\">".($_v < 1 ? ' --- STOP ---' : $_v )."</option>";
  },range(0,150,10)), function($s,$v){
    return $s . $v;
  },'');
}

require_login();

if(isset($_POST['action'])){
  $cron1 = new Cron('/usr/bin/php /var/www/html/galooli-fleetio-integration/src/pullcron.php > /dev/null 2>&1' 
    , 'PULL_CRON_INTERVAL_MINUTES');
  $cron2 = new Cron('/usr/bin/php /var/www/html/galooli-fleetio-integration/src/checkcron.php > /dev/null 2>&1' 
    , 'CHECK_CHANGE_CRON_INTERVAL_MINUTES');
  $cron1->updateCron($_POST['pull-interval']);
  $cron2->updateCron($_POST['check-change-interval']);

  $toast_message = 'Settings was updated successfully.';
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
        <div class="input-field col s6">
        <select name="pull-interval">
          <?= make_select_options( configuration('PULL_CRON_INTERVAL_MINUTES') ) ?>
        </select>
        <label>Pull Interval In Minutes</label>
      </div><div class="input-field col s6">
        <select name="check-change-interval">
          <?= make_select_options( configuration('CHECK_CHANGE_CRON_INTERVAL_MINUTES') ) ?>
        </select>
        <label>Check Change Interval In Minutes</label>
      </div>
       
      </div>
 <div class="row"style="padding-left:40px;">
    <button class="btn waves-effect waves-light" type="submit" name="action">Update
    </button>
</div>
    </form>
    </div>
  </div>
  
    <?php require_once('partials/footer.php'); ?>
    
  </body>
</html>
