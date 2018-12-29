<?php 
  require_once 'config.php';
  require_once 'Database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Galooli | Fleetio Integration Interface</title>

  <!-- CSS  -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
  <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
</head>
<body>
  <div class="container">
    <h3 class="center-align">Galooli - Fleetio Integration Interface</h3>
    <div class="section">

      <!--   Icon Section   -->
      <div class="row">
        <div class="col s12 m4">
          <div class="icon-block">
            <p class="text-center red-text">
              Pull Error messages Appear here
            </p>
            <a class="btn btn-large teal darken-4 waves-light waves-effect"
              href="ProcessData.php?call_function=pullGalooli">Pull From Galooli</a>
            <p><strong><i class="material-icons orange-text darken-4">warning</i>Only Use this manual update button if there is a pull error message</strong></p>
          </div>
        </div>

        <div class="col s12 m4">
        <?php
          $query = "SELECT value from configuration where name = 'last_gmt_update_time'";
          $tableRow = Database::getSingleRow($query);
          $lastPullTime = $tableRow["value"];
          $query = "SELECT value from configuration where name = 'last_fleetio_push_time'";
          $tableRow = Database::getSingleRow($query);
          $lastPushTime = $tableRow["value"];
        ?>
          <div class="icon-block">
            <h5 class="center teal-text">Last Data Fetched from Galooli: <strong><?=$lastPullTime  ?></strong></h5>
            <br>
            <h5 class="center teal-text">Last Data Updated to Fleetio: <strong><?=$lastPushTime  ?></strong></h5>
          </div>
        </div>

        <div class="col s12 m4">
          <div class="icon-block">
            <p class="text-center red-text">
              Push Error messages Appear here
            </p>
            <a class="btn btn-large orange darken-4 waves-light waves-effect"
              href="ProcessData.php?call_function=pushfleetio">Push To Fleetio</a>
            <p><strong><i class="material-icons orange-text darken-4">warning</i>Only Use this manual update button if there is a push error message</strong></p>
          </div>
        </div>
      </div>
      <br/>

      <?php
        $query = "SELECT * from pull_report";
        $fleetioTableRows = Database::selectFromTable($query);
      ?>
      <div class="row">
        <h5 class="center-align">Last Data Sent to Fleetio Management App</h5>
        <div class="col s12">
          <table class="highlight responsive-table">
            <thead>
            <tr>
              <th>Unit ID</th>
              <th>Unit Name</th>
              <th>Active Status</th>
              <th>Latitude</th>
              <th>Longitude</th>
              <th>Distance</th>
              <th>Engine Hours</th>
              <th>Fuel Amount</th>
            </tr>
            </thead>

            <tbody>
            <?php
              foreach($fleetioTableRows as $fleetioRow) {
                echo "<tr>
                        <td>".$fleetioRow['unit_id']."</td>
                        <td>".$fleetioRow['unit_name']."</td>
                        <td>".$fleetioRow['active_status']."</td>
                        <td>".$fleetioRow['latitude']."</td>
                        <td>".$fleetioRow['longitude']."</td>
                        <td>".$fleetioRow['distance']."</td>
                        <td>".$fleetioRow['engine_hours']."</td>
                        <td>".$fleetioRow['fuel_report']."</td>
                      </tr>";
              }

            ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>


  <footer class="page-footer teal">
    <div class="footer-copyright">
      <div class="container">
      Made by <a class="brown-text text-lighten-3" href="http://ecagon.com">Ecagon</a>
      </div>
    </div>
  </footer>


  <!--  Scripts-->
  <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script src="js/materialize.js"></script>
  <script src="js/init.js"></script>

  </body>
</html>
