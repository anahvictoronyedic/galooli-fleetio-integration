<?php
  require_once 'utils.php';

  if(isset($_POST['action'])){
    if($_POST['username'] == LOGIN_USER && $_POST['password'] == LOGIN_PASSWORD){
      $_SESSION['logged_in'] = true;
      header('Location: '.path('index.php'));
      die();
    }
      $toast_message = 'Invalid auth details provided';
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  
  <title>Galooli | Login Page</title>

  <?php require_once('partials/head.php'); ?>
</head>
<body>
  <?php require_once('partials/header.php'); ?>
  <div class="container">
    
    <div class="section">
      <h5 class="center-align">Login To Continue</h5>

      <form class="col s12" method="POST" action="login.php">
      <div class="row">
        <div class="input-field col s12 m6 offset-m3">
          <input name="username" value="" id="username" type="text" class="validate" autofocus>
          <label for="username">Username</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12 m6 offset-m3">
          <input name="password" id="password" type="password" class="validate">
          <label for="password">Password</label>
        </div>
      </div>
       
      </div>
      <div class="row center-align" >
          <button class="btn btn-large waves-effect waves-light" type="submit" name="action">Login</button>
      </div>
    </form>
    </div>
  </div>
  
    <?php require_once('partials/footer.php'); ?>
   
  </body>
</html>
