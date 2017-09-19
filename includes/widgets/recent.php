<h2>Top Sellers</h2>
<?php

  $transq = $db->query("SELECT * FROM cart WHERE paid = 1 ORDER BY id DESC LIMIT 8");
  $results = array();
  while($row = mysqli_fetch_assoc($transq)){
    $results[] = $row;
  }

  $row_count = $transq->num_rows;
  $used_ids = array();
  for($i = 0; $i < $row_count; $i++){
    $json_items = $results[$i]['items'];
    $items = json_decode($json_items, true);

    foreach ($items as $item) {
      if(!in_array($item['id'], $used_ids)){
        $used_ids[] = $item['id'];
      }
    }
  }
?>
  <ul class="bxslider">
    <?php foreach($used_ids as $id) :
      $productq = $db->query("SELECT id,title,image FROM products WHERE id = '{$id}'");
      $product = mysqli_fetch_assoc($productq);
      $pquery = $db->query("SELECT * FROM products WHERE id = '{$id}'");
    ?>
      <!-- Products -->
        <?php while($product = mysqli_fetch_assoc($pquery)) : ?>
          <li>
            <div class="col-md-3 text-center product-panel">
              <form class="" action="individual_product.php" method="post">
                <button class="img-wrap" type="submit" name="title" value="<?= $product['title']; ?>"><img src="<?php echo $product['image']; ?>" alt="<?php echo $product['title']; ?>" class="img-thumb-top-sellers"/></button>
              </form>
              <div class="info-container col-sm-12">
                <p class="product-title text-center"><br>MAGIC: THE GATHERING <?php echo $product['title']; ?></p>
              </div>
            </div>
          </li>
        <?php endwhile; ?>
      <?php endforeach; ?>
    </ul>

    <hr>

<script type="text/javascript">
  $('.bxslider').bxSlider({
    minSlides: 5,
    maxSlides: 5,
    slideWidth: 400,
    slideMargin: 1,
    moveSlides: 1,
    auto: true,
    autoControls: true,
    pause: 6000,
    /* This allows a constant movement
    ticker: true,
    speed: 20000*/

  });
</script>
