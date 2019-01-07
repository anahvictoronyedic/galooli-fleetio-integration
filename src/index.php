<?php
  require_once 'utils.php';
  require_once 'Database.php';
  require_login();

  function formatDate($dateString) {
    return date('g:ia j M, Y',strtotime($dateString));
  } 

  $query = "SELECT value from configuration where name = 'PULL_CRON_INTERVAL_MINUTES'";
  $tableRow = Database::getSingleRow($query);
  $refreshTime = $tableRow["value"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Galooli | Fleetio Integration Interface</title>
  <?php require_once('partials/head.php'); ?>
  <meta http-equiv="refresh" content="<?=$refreshTime * 60 ?>" > 
</head>
<body>
  <?php require_once('partials/header.php'); ?>
  <div class="container">

    <div class="section">

      <!--   Icon Section   -->
      <div class="row">
        <div class="col s12 m4">
            <?php
            $query = "SELECT value from configuration where name = 'pull_error_time'";
            $tableRow = Database::getSingleRow($query);
            $pullError = $tableRow["value"];
            ?>
            <div class="icon-block">
                <p class="text-center orange-text">
                    <?php
                    if ($pullError != NULL && $pullError > 3) {
                        echo "<i class=\"material-icons red-text darken-4\">warning</i>
                        Network or Server Error Occurred When Fetching Data from Galooli Servers, 
                        Wait for re-trial or use the manual override button
                        <a class=\"btn btn-large teal darken-4 waves-light waves-effect\" target=\"_blank\"
                          href=\"ProcessData.php?call_function=pullGalooli\">Pull From Galooli</a>";
                    } else {
                        echo "No Fetch Errors";
                        echo "<h6 class=\"teal-text\">All Galooli Fetch Data up to date</h6>";
                    }
                    ?>
                </p>
            <p>
              <strong>
                <i class="material-icons orange-text darken-4">warning</i>
                  The Manual Fetch button will appear here if there is a Galooli Data Fetch error message
              </strong>
            </p>
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
            <h5 class="center teal-text">Last Data Fetched from Galooli: <strong><?=formatDate($lastPullTime)  ?></strong></h5>
            <br>
            <h5 class="center teal-text">Last Data Updated to Fleetio: <strong><?=formatDate($lastPushTime)  ?></strong></h5>
          </div>
        </div>

        <div class="col s12 m4">
          <?php
              $query = "SELECT value from configuration where name = 'push_error_time'";
              $tableRow = Database::getSingleRow($query);
              $pushError = $tableRow["value"];
          ?>
          <div class="icon-block">
            <p class="text-center orange-text">
                <?php
                    if ($pushError != NULL && $pushError > 3) {
                        echo "<i class=\"material-icons red-text darken-4\">warning</i>
                            Network or Server Error Occurred When Pushing Data to Fleetio Servers, 
                            Wait for re-trial or use the manual override button
                            <a class=\"btn btn-large orange darken-4 waves-light waves-effect\" target=\"_blank\"
                            href=\"ProcessData.php?call_function=pushfleetio\">Push To Fleetio</a>
                            Only use this manual update button if there is an error message";
                    } else {
                        echo "No Update Errors";
                        echo "<h6 class=\"teal-text\">All Fleetio Update Data up to date</h6>";

                    }
                ?>

            </p>
            <p><strong>
              <i class="material-icons orange-text darken-4">warning</i>
              The Manual update button will appear here if there is a Fleetio Update error message</strong>
            </p>
          </div>
        </div>
      </div>
      <br/>

      <?php
        $query = "SELECT * from push_report";
        $fleetioTableRows = Database::selectFromTable($query);

      ?>
      <div class="row">
        <h5 class="center-align">Latest Data Sent to Fleetio Management App</h5>
        <div class="col s12">
            <?php
            if ($fleetioTableRows) {
            ?>
          <table class="highlight responsive-table">
            <thead>
            <tr>
              <th>Unit ID</th>
              <th>Unit Name</th>
              <th>Active Status</th>
              <th>Latitude</th>
              <th>Longitude</th>
              <th>Distance</th>
              <th>Fuel Amount</th>
              <th>Last Updated</th>
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
                    <td>".$fleetioRow['fuel_report']."</td>
                    <td>".formatDate($fleetioRow['modified_at'])."</td>
                  </tr>";
                }
            ?>
            </tbody>
          </table>
            <?php
                }

                else {
                    echo "<br/><h3 class='orange-text center-align'>NO RECORDS UPDATED TO FLEETIO</h3>";
                }
            ?>
        </div>
      </div>

    </div>
  </div>

    <?php require_once('partials/footer.php'); ?>
  </body>
</html>
