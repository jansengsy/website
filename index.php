<?php

  if(isset($_POST['expansionFilter'])) {
    echo $_POST['expansionFilter'];
    exit;
  }

  require_once 'core\init.php';
  include 'includes\head.php';
  include 'includes\headerfull.php';
  include 'includes\navigation.php';
  include 'includes\pagenumberslogic.php';

  $pLink = '';
  $eLink = '';
  $cLink = '';
  $rLink = '';

  if(isset($_GET['page'])){
    $pLink = $_GET['page'];
  }

  if(isset($_GET['expansion'])){
    $eLink = $_GET['expansion'];
  }

  if(isset($_GET['colour'])){
    $cLink = $_GET['colour'];
  }

  if(isset($_GET['rarity'])){
    $rLink = $_GET['rarity'];
  }
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

        <?php include 'includes\widgets\recent.php'; ?>
      </div>

      <!-- Page number -->
      <?php include 'includes\rightbar.php'; ?>
<?php
  include 'includes\footer.php';
?>
