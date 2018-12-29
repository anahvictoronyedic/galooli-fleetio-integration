<?php 
require_once 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>

  <title>Galooli | Fleetio Integration Interface</title>

  <?php require_once('partials/head.php'); ?>
</head>
<body>
  <?php require_once('partials/header.php'); ?>
  <div class="container">

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

    <?php require_once('partials/footer.php'); ?>
  </body>
</html>
