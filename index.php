<?php

  require_once 'core\init.php';
  include 'includes\head.php';
  include 'includes\navigation.php';

?>
      <!-- Main content -->
      <div class="col-md-12 col-sm-12">
        <div class="col-md-1 col-sm-1">
          <?php include 'includes\rightbar.php'; ?>
        </div>

        <div class="col-md-10 col-sm-10 banner-ad-slider-container">
          <?php include 'includes\widgets\bannerAds.php'; ?>
        </div>

        <div class="col-md-1 col-sm-1">
          <?php include 'includes\rightbar.php'; ?>
        </div>
      </div>

      <div class="col-md-12 col-sm-12">
        <div class="col-md-1 col-sm-1">
          <?php include 'includes\rightbar.php'; ?>
        </div>

        <div class="col-md-10 col-sm-10">
          <?php include 'includes\widgets\recent.php'; ?>
        </div>

        <div class="col-md-1 col-sm-1">
          <?php include 'includes\rightbar.php'; ?>
        </div>


      <!-- Main content -->
      <div class="col-md-12 col-sm-12">
        <div class="col-md-1 col-sm-1">
          <?php include 'includes\rightbar.php'; ?>
        </div>

        <div class="col-md-10 col-sm-10 banner-ad-slider-container text-center">
          <hr>
          <div class="col-md-9 col-sm-9">
            <h2 class="welcome-header text-center">Welcome to Tiki Trader</h2>
            <p class="welcome-message">Tiki Trader are the UK’s up and coming online Magic:
              the Gathering store, stocking a massive range of single cards,
              accessories and sealed products at highly competitive prices.
              Our number one priority is providing a great customer service
              and we aim to: respond to all queries in a timely and professional
              manner, dispatch orders promptly and accurately, and provide
              support for any issues that may arise. We're proud to stand as a
              trusted online retailer of official Pokemon Cards and Yu-Gi-Oh!
              Cards, and our extensive selection of trading cards extends to a
              number of other extremely popular trading card games, including;
              Cardfight!! Vanguard, My Little Pony, Final Fantasy, Star Wars
              Destiny, and more. In addition to offering tens of thousands of
              playing cards, we're also excited to bring to you the most popular
              Tabletop Gaming titles, and the latest Pop! Vinyl Bobbleheads.</p>
          </div>
          <div class="col-md-1 col-sm-1">
            <img src="images/headerlogo/Welcome.png" title="Welcome Image" class="welcome-image"/>
          </div>
          <div class="col-md-12 col-sm-12">
            <a href="search.php" class="btn btn-lg website-action-button shop-now" name="shop-now">Shop Now!</a>
          </div>

        </div>

        <div class="col-md-1 col-sm-1">
          <?php include 'includes\rightbar.php'; ?>
        </div>
      </div>


<?php
  include 'includes\footer.php';
