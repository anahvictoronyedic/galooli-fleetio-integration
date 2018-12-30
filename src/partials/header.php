<header>
  <nav>
    <div class="container">
      <div class="nav-wrapper">
        <a href="javascript:void(0);" class="brand-logo white-text" style="padding-left:20px;">Galooli - Fleetio Integration Interface</a>
        <ul id="nav-mobile" class="right hide-on-med-and-down">
          <?php

          $uri = $_SERVER['REQUEST_URI'];
          $uri = rtrim($uri, '/');

          if( $uri == path('') || $uri == path('index.php') || $uri . '/' == path('') ){
            ?>
            <li><a href="<?= path('settings.php') ?>" class=" white-text">Settings</a></li>
            <li><a href="<?= path('logout.php') ?>" class=" white-text">Logout</a></li>
            <?php 
          }
          else if( $uri == path('settings.php') ){
            ?>
            <li><a href="<?= path('index.php')?>" class=" white-text">Home</a></li>
            <li><a href="<?= path('logout.php')?>" class=" white-text">Logout</a></li>
            <?php
          }
          ?>
        </ul>
      </div>
    </div>
  </nav>
  </header>