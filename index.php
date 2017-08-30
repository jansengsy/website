<?php

  require_once 'core\init.php';
  include 'includes\head.php';
  include 'includes\headerfull.php';
  include 'includes\navigation.php';

?>

      <!-- Main content -->
      <div class="col-md-12">


      <?php
        include 'includes\rightbar.php';
      ?>

      <div class="page-title-banner col-md-10">
        <h2 class="page-title">Featured Products:</h2>
      </div>

      <?php
        include 'includes\rightbar.php';
      ?>
      </div>
      <?php
        include 'includes\rightbar.php';
        include 'includes\leftbar.php';
      ?>

      <div class="col-md-8">

        <!-- Products -->
        <div class="row" id="cards"></div>

        <?php
          include 'includes\rightbar.php';
          include 'includes\widgets\recent.php';
        ?>
      </div>

      <!-- Page number -->
      <?php include 'includes\rightbar.php'; ?>
<?php
  include 'includes\footer.php';
?>
