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
<div class="navbar navbar-fixed-top navbar-inverse  col-md-12" role="navigation">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle Navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>

      <a class="navbar-brand" href="index.php">Tiki Trader</a>


    </div>
    <div class="navbar-collapse collapse">
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle title" data-toggle="dropdown">Magic: the Gathering<span class="caret"></span></a>
          <ul class="dropdown-menu scrollable-menu" role="menu">
            <?php while($mtg = mysqli_fetch_assoc($pquery)) : ?>
              <li><a href="category.php?cat=<?=$mtg['id'];?>"><?php echo $mtg['expansion']; ?></a></li>
            <?php endwhile; ?>
          </ul>
        </li>

        <li class="dropdown">
          <a href="#" class="dropdown-toggle title" data-toggle="dropdown">Pokemon<span class="caret"></span></a>
          <ul class="dropdown-menu scrollable-menu" role="menu">
            <?php while($pokemon = mysqli_fetch_assoc($pquery3)) : ?>
              <li><a href="coming_soon.php"><?php echo $pokemon['expansion']; ?></a></li>
            <?php endwhile; ?>
          </ul>
        </li>

        <li class="dropdown">
          <a href="#" class="dropdown-toggle title" data-toggle="dropdown">Yu-Gi-Oh<span class="caret"></span></a>
          <ul class="dropdown-menu scrollable-menu" role="menu">
            <?php while($yugioh = mysqli_fetch_assoc($pquery2)) : ?>
              <li><a href="coming_soon.php"><?php echo $yugioh['expansion']; ?></a></li>
            <?php endwhile; ?>
          </ul>
        </li>

        <li>
          <a href="coming_soon.php" class="dropdown-toggle title">Accessories</a>
        </li>

        <li>
          <a href="coming_soon.php" class="dropdown-toggle title">Sale</a>
        </li>

        <li>
          <div>
            <a href="#" class="search-element btn btn-md searchbtn" onclick="searchForProduct();">Search</a>
            <input class="search-element form-control input-md" placeholder="Search for a product..." name="productName" id="searchBarNav" type="text">
            <a class="search-cart search-element" href="cart.php"><span class="glyphicon glyphicon-shopping-cart"></span> (<?= $item_count; ?>) <?= money($sub_total);?></a>
          </div>
        </li>
      </ul>
    </div>
</div>

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
