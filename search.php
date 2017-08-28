<?php

  require_once 'core\init.php';
  include 'includes\head.php';
  include 'includes\navigation.php';

?>

      <!-- Main content -->
      <div class="col-md-12">


      <?php
        include 'includes\rightbar.php';
      ?>

      <div class="page-title-banner col-md-10">
        <h2 class = "page-title">Magic: the Gathering:</h2>
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

        <?php
          include 'includes\rightbar.php';
        ?>

        <!-- Products -->
        <div class="row" id="cards"></div>
      </div>

      <!-- Page number -->
      <?php include 'includes\rightbar.php'; ?>

<?php
  include 'includes\footer.php';
?>
