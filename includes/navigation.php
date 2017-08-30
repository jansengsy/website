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
<nav class="navbar navbar-inverse navbar-fixed-top bg-inverse col-md-12">
  <div class="col-md-5">
    <div class="container container-nav">
      <a href="index.php" class="navbar-brand">Tiki Trader</a>
      <ul class="nav navbar-nav">
        <!-- MTG Expansions -->
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">MAGIC: THE GATHERING<span class="caret"></span></a>
          <ul class="dropdown-menu scrollable-menu" role="menu">
            <?php while($mtg = mysqli_fetch_assoc($pquery)) : ?>
              <li><a href="category.php?cat=<?=$mtg['id'];?>"><?php echo $mtg['expansion']; ?></a></li>
            <?php endwhile; ?>
          </ul>
        </li>

        <!-- YU-GI-OH Expansions -->
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">YU-GI-OH!<span class="caret"></span></a>
          <ul class="dropdown-menu scrollable-menu" role="menu">
            <?php while($yugioh = mysqli_fetch_assoc($pquery2)) : ?>
              <li><a href="#"><?php echo $yugioh['expansion']; ?></a></li>
            <?php endwhile; ?>
          </ul>
        </li>

        <!-- Pokemon Expansions -->
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">POKEMON<span class="caret"></span></a>
          <ul class="dropdown-menu scrollable-menu" role="menu">
            <?php while($pokemon = mysqli_fetch_assoc($pquery3)) : ?>
              <li><a href="#"><?php echo $pokemon['expansion']; ?></a></li>
            <?php endwhile; ?>
          </ul>
        </li>
      </ul>
    </div>
  </div>
  <div class="col-md-7">
    <a class="pull-right col-md-1 search-cart search-element" href="cart.php"><span class="glyphicon glyphicon-shopping-cart"></span> (<?= $item_count; ?>) <?= money($sub_total);?></a>
    <a href="#" class="search-element search-button pull-right col-md-1 btn btn-md" onclick="searchForProduct();">Search</a>
    <input class="search-element pull-right col-md-9 form-control input-md" placeholder="Search for a product..." name="productName" id="searchBarNav" type="text">
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
