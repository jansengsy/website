<?php
  $sql = "SELECT * FROM expansion";
  $pquery = $db->query($sql);
  $sql2 = "SELECT * FROM yugiohexpansions";
  $pquery2 = $db->query($sql2);
  $sql3 = "SELECT * FROM pokemonexpansions";
  $pquery3 = $db->query($sql3);

  $sub_total = 0;
  $item_count = 0;

  if($cart_id != ''){
    $cartQ = $db->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
    $result = mysqli_fetch_assoc($cartQ);
    $items = json_decode($result['items'], true);
    $i = 1;

    foreach($items as $item){
      $productQ = $db->query("SELECT * FROM products WHERE id = '{$item['id']}'");
      $product = mysqli_fetch_assoc($productQ);
      $item_count += $item['quantity'];
      $sub_total += ($item['quantity'] * $product['price']);
    }
  }
?>

<!-- Top Nav -->
<nav class="navbar navbar-fixed-top navbar-inverse" role="navigation">

  <div class="col-md-12">
    <div class="navbar-header">

      <a class="navbar-brand" href="index.php">Tiki Trader</a>
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle Navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-cart search-cart" href="cart.php"><span class="glyphicon glyphicon-shopping-cart"></span> (<?= $item_count; ?>) My Cart: <?= money($sub_total);?></a>
      <a class="navbar-cart search-cart" href="user_account.php"><span class="glyphicon glyphicon-user"></span> Account</a>

      <div class="input-group stylish-input-group">
          <input type="text" name="productName" id="searchBarNav" class="form-control"  placeholder="Search" >
          <span class="input-group-addon">
              <button type="submit" onclick="searchForProduct();">
                  <span class="glyphicon glyphicon-search"></span>
              </button>
          </span>
      </div>

    </div>
  </div>

  <div class="col-md-12">
    <div class="navbar-collapse collapse">
      <div class="categories">
        <ul class="nav navbar-nav">
          <li class="dropdown">
            <a href="#" class="dropdown-toggle title-second" data-toggle="dropdown">Magic: the Gathering<span class="caret"></span></a>
            <ul class="dropdown-menu scrollable-menu" role="menu">
              <?php while($mtg = mysqli_fetch_assoc($pquery)) : ?>
                <li><a href="category.php?cat=<?=$mtg['id'];?>"><?php echo $mtg['expansion']; ?></a></li>
              <?php endwhile; ?>
            </ul>
          </li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle title-second" data-toggle="dropdown">Pokemon<span class="caret"></span></a>
            <ul class="dropdown-menu scrollable-menu" role="menu">
              <?php while($pokemon = mysqli_fetch_assoc($pquery3)) : ?>
                <li><a href="coming_soon.php"><?php echo $pokemon['expansion']; ?></a></li>
              <?php endwhile; ?>
            </ul>
          </li>

          <li class="dropdown">
            <a href="#" class="dropdown-toggle title-second" data-toggle="dropdown">Yu-Gi-Oh<span class="caret"></span></a>
            <ul class="dropdown-menu scrollable-menu" role="menu">
              <?php while($yugioh = mysqli_fetch_assoc($pquery2)) : ?>
                <li><a href="coming_soon.php"><?php echo $yugioh['expansion']; ?></a></li>
              <?php endwhile; ?>
            </ul>
          </li>

          <li class="dropdown">
            <a href="coming_soon.php" class="dropdown-toggle title-second">Accessories</a>
          </li>

          <li class="dropdown">
            <a href="coming_soon.php" class="dropdown-toggle title-second">Sale</a>
          </li >
        </ul>
      </div>
    </div>
  </div>
</nav>


<script type="text/javascript">

    function searchForProduct() {

        filters = {};

        var productName = $('#searchBarNav').val();
        filters['productName'] = productName;

        $('#filterValues').text(JSON.stringify(filters));

        jQuery.ajax({
            url: '/eCommerce/includes/widgets/getCardsFromFilter.php',
            method: "post",
            data: filters,
            success: function(resp) {
                $("#cards").html(resp);
            },
            error: function() {
                alert("Something went wrong.");
            },
        });
    };

</script>
