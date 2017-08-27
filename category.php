<?php
  require_once 'core\init.php';
  include 'includes\head.php';
  include 'includes\navigation.php';
  include 'includes\leftbar.php';
  $_GET['caller'] = 'category';
  include 'includes\pagenumberslogic.php';

  if(isset($_GET['cat'])){
    $cat_id = sanitize($_GET['cat']);
  } else {
    $cat_id = '';
  }

  $sql = "SELECT * FROM products WHERE expansion = '$cat_id' AND deleted != 1 ORDER BY title";
  $pquery = $db->query($sql);
  $sql2 = "SELECT * FROM expansion WHERE id = '$cat_id'";
  $equery = $db->query($sql2);
  $title = mysqli_fetch_assoc($equery);
?>

      <!-- Main content -->
      <div class="col-md-8">

        <!-- Page number -->
        <?php
          include 'includes/pagenumbers.php';
        ?>

        <!-- Products -->
        <div class="row">
          <h2 class = ""><?=$title['expansion'];?>:</h2>
          <hr>
          <?php while($product = mysqli_fetch_assoc($pquery)) : ?>
            <div class="col-md-3 text-center">
              <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['title']; ?>" class="img-thumb"/>
              <p class="product-title"><br>MAGIC: THE GATHERING <?php echo $product['title']; ?></p>
              <p class="price"><b>Up To £<?php echo $product['price']; ?></b></p>
              <div class="col-sm-2"></div>
              <button type="button" class="col-sm-4 btn btn-sm btn-success product_button" onclick="detailsmodal(<?= $product['id']; ?>)">Buy</button>
              <button type="button" class="col-sm-4 btn btn-sm btn-outline-secondary product_button" onclick="detailsmodal(<?= $product['id']; ?>)">Details</button>
              <div class="col-sm-2"></div>
              <?php $stock = $product['quantity']; ?>
              <?php if($stock == 0): ?>
                  <p class="col-md-12 text-center stock-alert out-of-stock"><b><?=(($product['quantity'] > 10))?'10+':'Item out of stock';?></b></p>
              <?php else: ?>
                  <p class="col-md-12 text-center stock-alert"><b><?=(($product['quantity'] > 10))?'10+':$product['quantity'];?> in stock</b></p>
              <?php endif; ?>
            </div>
          <?php endwhile; ?>
        </div>

        <?php
          include 'includes\pagenumbers.php';
          include 'includes\widgets\suggest.php';
        ?>
      </div>

      <!-- Page number -->
      <?php
        include 'includes\rightbar.php';

      ?>



<?php
  include 'includes\footer.php';
?>
