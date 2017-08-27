<?php

  $db = mysqli_connect('127.0.0.1', 'root', '', 'ecommerce');
  if(mysqli_connect_errno()) {
    echo 'Database connection failed with error(s): ' . mysqli_connect_error();
    die();
  }

  $pageSize = 12;
  $dissabledL = '';
  $dissabledR = '';
  $p = 0;
  $pageNumber = 1;

  $sql = "SELECT * FROM products WHERE deleted = 0";

  $expansion_id = ((isset($_POST['expansion']) && $_POST['expansion'] != '')?$_POST['expansion']:'');
  $colour_id = ((isset($_POST['colour']) && $_POST['colour'] != '')?$_POST['colour']:'');
  $rarity_id = ((isset($_POST['rarity']) && $_POST['rarity'] != '')?$_POST['rarity']:'');
  $product_name = ((isset($_POST['productName']) && $_POST['productName'] != '')?$_POST['productName']:'');

  if($expansion_id != ''){

    $sql .= " AND expansion = " . $expansion_id[0];

    for($i = 1; $i < count($expansion_id); $i++){
      $sql .= " OR expansion = " . $expansion_id[$i];
    }
  }

  if($colour_id != ''){
    $sql .= " AND colour = " . $colour_id[0];

    for($i = 1; $i < count($colour_id); $i++){
      $sql .= " OR colour = " . $colour_id[$i];
    }
  }

  if($rarity_id != ''){
    $sql .= " AND rarity = " . $rarity_id[0];

    for($i = 1; $i < count($rarity_id); $i++){
      $sql .= " OR rarity = " . $rarity_id[$i];
    }
  }

  if($product_name != ''){
    $sql .= " AND title = " . $_POST['productName'];
  }

  if(isset($_GET['page']) && $_GET['page'] != ''){
    $pageLimit = $_GET['page'] - 1;
    $pageLimit *= $pageSize;
  } else {
    $pageLimit = 0;
  }

  $split = explode("*", $sql);
  $sqlCount = "SELECT COUNT(*) " . $split[1];
  $countQ = $db->query($sqlCount);
  $c = mysqli_fetch_assoc($countQ);
  $count = $c['COUNT(*)'];
  $p = ceil($count/$pageSize);

  $sql .= " LIMIT $pageLimit,$pageSize";
  $pquery = $db->query($sql);

?>

    <div ng-controller="pageController" class="col-sm-12">
      <div class="container col-sm-10">
        <ul class="pagination" total-items="totalItems" items-per-page= "itemsPerPage" ng-model="currentPage">
          <li class="<?=$dissabledL;?>"><a href="<?=$leftLink;?>">&laquo;</a></li>
          <?php for($i = 1; $i <= $p; $i++) : ?>
              <li><a href="#"><?= $i; ?></a></li>
          <?php endfor; ?>
          <li class="<?=$dissabledR;?>"><a href="<?=$rightLink;?>">&raquo;</a></li>
        </ul>
      </div>
    </div>

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

    <div ng-controller="pageController" class="col-sm-12">
      <div class="container col-sm-10">
        <ul class="pagination" total-items="totalItems" items-per-page= "itemsPerPage" ng-model="currentPage">
          <li class="<?=$dissabledL;?>"><a href="<?=$leftLink;?>">&laquo;</a></li>
          <?php for($i = 1; $i <= $p; $i++) : ?>
            <?php
              $sbn = ((isset($productName))?'&searchBarNav=' . $productName:'');
            ?>
            <!-- This looks complex but it isn't - IF CAT IS SET, WE THEN CHECK FOR '', IF EITHER OF THOSE ARE TRUE, WE RETURN A '' OTHERWISE WE RETURN THE VALUE OF CAT-->
            <li><a href=""><?= $i; ?></a></li>
          <?php endfor; ?>
          <li class="<?=$dissabledR;?>"><a href="<?=$rightLink;?>">&raquo;</a></li>
        </ul>
      </div>
    </div>
