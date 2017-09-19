<?php

  require_once 'core\init.php';
  include 'includes\head.php';
  include 'includes\navigation.php';

  if(isset($_GET)){
    $product_title = $_POST['title'];
  }

  $sql = "SELECT * FROM products WHERE title = '$product_title'";
  $pq = $db->query($sql);
  $product = mysqli_fetch_assoc($pq);
?>
      <!-- Main content -->
      <div class="col-md-12 col-sm-12">
        <div class="col-md-1 col-sm-1"> </div>

        <div class="col-md-10 col-sm-10">
          <div class="col-md-6 col-sm-6 solo-product-img">
            <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['title']; ?>" class="img-main"/>
          </div>

          <div class="col-md-6 col-sm-6 solo-product-details">

            <form action="add_cart.php" method="post" id="add_product_form" style="margin-top: 90px; height: 100%;">
              <h2><b>Magic: the Gathering - <?= $product['title']; ?></b></h2>
              <br>
              <h3><b>£<?= $product['price']; ?></b></h3>
              <br>
              <p><b>Edition:</b> <?= $product['expansion']; ?></p>
              <p><b>Rarity:</b> <?= $product['rarity']; ?></p>
              <p><b>Colour:</b> <?= $product['colour']; ?></p>
              <p><b>Condition:</b> <?= $product['state']; ?></p>
              <br>
              <p class="stock-txt"><b><?= $product['quantity']; ?> In Stock | Usually dispatched within 24 hours</b></p>
              <br>
              <br>

              <input type="hidden" name="product_id" value="<?=$product['id'];?>">
              <input type="hidden" name="available" id="available" value="<?= $product['quantity']; ?>">

              <span id="modal_errors" class="bg-danger"></span>

              <div class="form-group">
                <div class="col-xs-3">
                  <label for="quantity">Quantity:</label>
                  <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="0" max="<?= $product['quantity']; ?>">
                </div>
              </div>
              <br><br><br><br><br>
              <button type="button" class="btn btn-warning website-action-button pull-right" style="margin-left: 15px;" onclick="addToCart()"><span class="glyphicon glyphicon-shopping-cart"></span>Add To Cart</button>
              <button type="button" class="btn btn-default website-secondary-button pull-right" onclick="goBack()">Back</button>
            </form>
          </div>

          <?php include 'includes\widgets\recent.php'; ?>
        </div>

        <div class="col-md-1 col-sm-1"> </div>
      </div>

<script type="text/javascript">
  function goBack() {
      window.history.back();
  }

  function addToCart(){
    alert("yo");

    var price = jQuery('#price').val();
    var quantity = jQuery('#quantity').val();
    var available = jQuery('#available').val();
    var colour = jQuery('#colour').val();
    var edition = jQuery('#expansion').val();
    var condition = jQuery('#condition').val();
    var rarity = jQuery('#rarity').val();
    var state = jQuery('#state').val();
    var error = '';
    var data = jQuery('#add_product_form').serialize();

    if(quantity == 0 || quantity == '' || parseInt(quantity, 10) > parseInt(available, 10)){
      alert(quantity);
      error += '<p class="text-danger text-center">You must select a valid quantity"</p>';
      jQuery('#modal_errors').html(error);
      return;
    }else{
      jQuery.ajax({
        url : '/ecommerce/admin/parsers/add_cart.php',
        method: 'post',
        data: data,
        success : function(){
          location.reload();
        },
        error: function(){alert("Something went wrong");}
      });
    }
  }


</script>

<?php
  include 'includes\footer.php';
