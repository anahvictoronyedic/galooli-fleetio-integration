
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
<script>

    $(document).ready(function() {
      M.updateTextFields();
    });

    $(document).ready(function(){
      $('select').formSelect();

      <?php 
if(isset($toast_message)){
  ?>
M.toast({html: "<?= $toast_message ?>"});
  <?php
}
      ?>
    });

  </script>