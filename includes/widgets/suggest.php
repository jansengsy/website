<h2>You may also like:</h2>
<?php

  $pquery = $db->query("SELECT * FROM products ORDER BY quantity LIMIT 25");
  //$results = mysqli_fetch_assoc($transq);

?>
  <ul class="bxslider">
      <!-- Products -->
        <?php while($product = mysqli_fetch_assoc($pquery)) : ?>
          <li>
            <div class="col-md-3 text-center product-panel">
              <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['title']; ?>" class="img-thumb-top-sellers"/>
              <div class="info-container col-sm-12">
                <p class="product-title text-center"><br>MAGIC: THE GATHERING <?php echo $product['title']; ?></p>
              </div>
            </div>
          </li>
        <?php endwhile; ?>
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
    pause: 4000,
    /* This allows a constant movement
    ticker: true,
    speed: 20000*/

  });
</script>
