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
    <ul class="nav navbar-nav pull-right">
      <!-- Search Bar -->
      <li class="dropdown">
        <form class="form-inline" id="search-form">
          <input class="form-control input-md search search-bar" placeholder="Search for a product..." name="productName" id="productName" type="text">
          <button class="btn btn-secondinput-lg ary search" id="searchButton">Search</button>
        </form>
      </li>
      <li class="pull-right">
        <a href="cart.php"><span class="glyphicon glyphicon-shopping-cart"></span> (<?= $item_count; ?>) My Cart: <?= money($sub_total);?></a>
      </li>
    </ul>
  </div>
</nav>

<script type="text/javascript">
  function search(product){

    jQuery.ajax({
        url: '/eCommerce/includes/widgets/getCardsFromFilter.php',
        method: "post",
        data: product,
        success: function(resp) {
            $("#cards").html(resp);
        },
        error: function() {
            alert("Something went wrong.");
        },
    });
  }

  document.getElementById("searchButton").addEventListener("click", search(document.getElementById("searchButton").value), false);
</script>
