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

  $sql = "SELECT * FROM products WHERE deleted = 0";

  $expansion_id = ((isset($_POST['expansion']) && $_POST['expansion'] != '')?$_POST['expansion']:'');
  $colour_id = ((isset($_POST['colour']) && $_POST['colour'] != '')?$_POST['colour']:'');
  $rarity_id = ((isset($_POST['rarity']) && $_POST['rarity'] != '')?$_POST['rarity']:'');
  $product_name = ((isset($_POST['productName']) && $_POST['productName'] != '')?$_POST['productName']:'');
  $pageNumber = ((isset($_POST['page']) && $_POST['page'] != '1')?$_POST['page']:'1');

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

  if($pageNumber > 0){
    $pageLimit = $pageNumber - 1;
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
              <?php
                $eLink = '';
                if(isset($expansion_id) && $expansion_id != ''){
                  $eLink = $expansion_id[0];
                }
                $cLink = '';
                if(isset($colour_id) && $colour_id != ''){
                  $cLink = $colour_id[0];
                }
                $rLink = '';
                if(isset($rarity_id) && $rarity_id != ''){
                  $rLink = $rarity_id[0];
                }
              ?>
              <li class="gogo" value="yo"><a href="#" name="hello"><?= $i; ?></a></li>
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
            <li><a href="#"><?= $i; ?></a></li>
          <?php endfor; ?>
          <li class="<?=$dissabledR;?>"><a href="<?=$rightLink;?>">&raquo;</a></li>
        </ul>
      </div>
    </div>

<script type="text/javascript">


  $('ul.pagination li a').on('click',function(e){

    var filters = {};
    
    var expansion = "<?=$expansion_id;?>";
    var colour = "<?=$colour_id;?>";
    var rarity = "<?=$rarity_id;?>";
    var page = 2;

    alert(page);

    filters['page'] = page;
    filters['expansion'] = expansion;
    filters['colour'] = colour;
    filters['rarity'] = rarity;

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
  });

</script>
