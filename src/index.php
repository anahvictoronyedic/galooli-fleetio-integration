<?php 
require_once 'utils.php';

require_login();
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
            <button class="btn btn-large teal darken-4 waves-light waves-effect">Pull From Galooli</button>
            <p><strong><i class="material-icons orange-text darken-4">warning</i>Only Use this manual update button if there is a pull error message</strong></p>
          </div>
        </div>

        <div class="col s12 m4">
          
          <div class="icon-block">
            <h5 class="center teal-text">Data Updated from Galooli: <strong> 22, Nov 2018, 09:52:03</strong></h5>
            <br>
            <h5 class="center teal-text">Data Updated to Fleetio: <strong> 22, Nov 2018, 09:52:03</strong></h5>
          </div>
        </div>

        <div class="col s12 m4">
          <div class="icon-block">
            <p class="text-center red-text">
              Push Error messages Appear here
            </p>
            <button class="btn btn-large orange darken-4 waves-light waves-effect">Push To Fleetio</button>
            <p><strong><i class="material-icons orange-text darken-4">warning</i>Only Use this manual update button if there is a push error message</strong></p>
          </div>
        </div>
      </div>
      <br/>
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
              <th>longitude</th>
              <th>Distance</th>
              <th>Engine Hours</th>
            </tr>
            </thead>

            <tbody>
            <tr>
              <td>Alvin</td>
              <td>Eclair</td>
              <td>$0.87</td>
              <td>Alvin</td>
              <td>Eclair</td>
              <td>$0.87</td>
              <td>$0.87</td>
            </tr>
            <tr>
              <td>Alan</td>
              <td>Jellybean</td>
              <td>$3.76</td>
              <td>Alan</td>
              <td>Jellybean</td>
              <td>$3.76</td>
              <td>$3.76</td>
            </tr>
            <tr>
              <td>Jonathan</td>
              <td>Lollipop</td>
              <td>$7.00</td>
              <td>Jonathan</td>
              <td>Lollipop</td>
              <td>$7.00</td>
              <td>$0.87</td>
            </tr>
            <tr>
              <td>Alvin</td>
              <td>Eclair</td>
              <td>$0.87</td>
              <td>Alvin</td>
              <td>Eclair</td>
              <td>$0.87</td>
              <td>$0.87</td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>

    <?php require_once('partials/footer.php'); ?>
  </body>
</html>
