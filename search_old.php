<?php
  require_once 'core\init.php';
  include 'includes\head.php';
  include 'includes\navigation.php';

  $_GET['caller'] = 'search';
  $_GET['searching'] = 'true';

  $productName = '';

  if(isset($_POST['searchBarNav'])){
    $productName = $_POST['searchBarNav'];
  } elseif(isset($_GET['searchBarNav'])){
    $productName = $_GET['searchBarNav'];
  }

  include 'includes\pagenumberslogic.php';

  if(isset($_GET['cat'])){
    $cat_id = sanitize($_GET['cat']);
  } else {
    $cat_id = '';
  }

  if($_GET['caller'] == 'search' && $_GET['searching'] == 'true'){
    trim($productName);
    $sql = "SELECT * FROM products WHERE title LIKE '%{$productName}%'";
    $pquery = $db->query($sql);
  } else {
    $sql = "SELECT * FROM products WHERE expansion = '$cat_id' AND deleted != 1 ORDER BY title";
    $pquery = $db->query($sql);
    $sql2 = "SELECT * FROM expansion WHERE id = '$cat_id'";
    $equery = $db->query($sql2);
    $title = mysqli_fetch_assoc($equery);
  }

?>

      <!-- Main content -->
      <div class="col-md-12">


      <?php
        include 'includes\rightbar.php';
      ?>

      <div class="page-title-banner col-md-10">
        <h2 class = "page-title">Search results for "<?=ucfirst($productName);?>":</h2>
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

        <!-- Page number -->
        <?php
          include 'includes/pagenumbers.php';
        ?>

        <!-- Products -->
        <div class="row">

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

        <?php include 'includes\pagenumbers.php'; ?>
      </div>

      <!-- Page number -->
      <?php include 'includes\rightbar.php'; ?>

<?php
  include 'includes\footer.php';
?>
