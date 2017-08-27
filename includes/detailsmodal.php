<?php
  require_once '../core/init.php';
  $id = $_POST['id'];
  $id = (int)$id;

  // Base Product
  $sql = "SELECT * FROM products WHERE id = '$id'";
  $result = $db->query($sql);
  $product = mysqli_fetch_assoc($result);

  // Colour
  $colour_id = $product['colour'];
  $sql2 = "SELECT colour FROM colour WHERE id = '$colour_id'";
  $qcolour = $db->query($sql2);
  $colour = mysqli_fetch_assoc($qcolour);

  // Edition
  $expansion_id = $product['expansion'];
  $sql3 = "SELECT expansion FROM expansion WHERE id = '$expansion_id'";
  $qexpansion = $db->query($sql3);
  $expansion = mysqli_fetch_assoc($qexpansion);

  // Rarity
  $rarity_id = $product['rarity'];
  $sql4 = "SELECT rarity FROM rarity WHERE id = '$rarity_id'";
  $qrarity = $db->query($sql4);
  $rarity = mysqli_fetch_assoc($qrarity);

  // State
  $state_id = $product['state'];
  $sql5 = "SELECT state FROM state WHERE id = '$state_id'";
  $qstate = $db->query($sql5);
  $state = mysqli_fetch_assoc($qstate);
?>

<!--Details Modal -->
<?php ob_start(); ?>
<div class="modal fade details-1" id="details-modal" tabindex="-1" role="dialog" aria-labelledby="details-1" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" onclick="closeModal()" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title text-center"><b>MAGIC: THE GATHERING</b> <?= $product['title']; ?></h4>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row">
            <span id="modal_errors" class="bg-danger"></span>
            <div class="col-sm-6">
              <div class="center-block">
                <img src="<?= $product['image']; ?>" alt="Card 1" class="details img-responsive">
              </div>
            </div>
            <div class="col-sm-6">
              <h3><b>£<?= $product['price']; ?></b></h3>
              <p><b>Edition:</b> <?= $expansion['expansion']; ?></p>
              <p><b>Rarity:</b> <?= $rarity['rarity']; ?></p>
              <p><b>Colour:</b> <?= $colour['colour']; ?></p>
              <p><b>Condition:</b> <?= $state['state']; ?></p>
              <hr>
              <p class="stock-txt"><b><?= $product['quantity']; ?> In Stock | Usually dispatched within 24 hours</b></p>
              <hr>
              <form action="add_cart.php" method="post" id="add_product_form">
                <input type="hidden" name="product_id" value="<?=$id;?>">
                <input type="hidden" name="available" id="available" value="<?= $product['quantity']; ?>">
                <!--Quantity-->
                <div class="form-group">
                  <div class="col-xs-3">
                    <label for="quantity">Quantity:</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="0" max="<?= $product['quantity']; ?>">
                  </div>
                </div>
                <!--Card Cndition-->
                <div class="form-group">
                  <div class="col-xs-9">
                    <!--
                    <label for="condition">Condition:</label>
                    <p><b>Condition: <?= $state['state']; ?></b></p>
                    <select class="form-control" id="size" name="size">
                      <option value=""></option>
                      <option value="Mint/Near Mint">Mint/Near Mint</option>
                      <option value="Lightly Played">Lightly Played</option>
                      <option value="Heavily Played">Heavily Played</option>
                    </select>
                  -->
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onclick="closeModal()">Close</button>
        <button type="button" class="btn btn-warning" onclick="add_to_cart();return false;"><span class="glyphicon glyphicon-shopping-cart"></span>Add To Cart</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

  function closeModal(){
    jQuery('#details-modal').modal('hide');
    setTimeout(function(){
      jQuery('#details-modal').remove();
      jQuery('.modal-backdrop').remove();
    }, 500);
  }
</script>

<?php echo ob_get_clean(); ?>
