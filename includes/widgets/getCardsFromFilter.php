<?php

  $db = mysqli_connect('127.0.0.1', 'root', '', 'ecommerce');
  if(mysqli_connect_errno()) {
    echo 'Database connection failed with error(s): ' . mysqli_connect_error();
    die();
  }

  $dissabledL = '';
  $dissabledR = '';
  $p = 0;

  $sql = "SELECT * FROM products WHERE deleted = 0";

  $expansion_id = ((isset($_POST['expansion']) && $_POST['expansion'] != '')?$_POST['expansion']:'');
  $colour_id = ((isset($_POST['colour']) && $_POST['colour'] != '')?$_POST['colour']:'');
  $rarity_id = ((isset($_POST['rarity']) && $_POST['rarity'] != '')?$_POST['rarity']:'');
  $product_name = ((isset($_POST['productName']) && $_POST['productName'] != '')?$_POST['productName']:'');
  $pageNumber = ((isset($_POST['page']) && $_POST['page'] != '1')?$_POST['page']:'1');
  $pageSize = ((isset($_POST['pageSize']) && $_POST['pageSize'] != '1')?$_POST['pageSize']:'12');
  $sort_by = ((isset($_POST['sortBy']) && $_POST['sortBy'] != '')?$_POST['sortBy']:'');
  if($expansion_id != '' && $expansion_id[0] != 'all'){

    $sql .= " AND expansion = " . $expansion_id[0];
    $aa = $expansion_id[0];

    for($i = 1; $i < count($expansion_id); $i++){
      $sql .= " OR expansion = " . $expansion_id[$i];
    }
  }

  if($colour_id != '' && $colour_id[0] != 'all'){
    $sql .= " AND colour = " . $colour_id[0];
    $bb = $colour_id[0];

    for($i = 1; $i < count($colour_id); $i++){
      $sql .= " OR colour = " . $colour_id[$i];
    }
  }

  if($rarity_id != '' && $colour_id[0] != 'all'){
    $sql .= " AND rarity = " . $rarity_id[0];
    $cc = $rarity_id[0];

    for($i = 1; $i < count($rarity_id); $i++){
      $sql .= " OR rarity = " . $rarity_id[$i];
    }
  }

  if($product_name != ''){
    $sql .= " AND title LIKE '%$product_name%'";
  }

  if($sort_by != ''){
    if($sort_by == 1){
      $sql .= " ORDER BY price";
    }
    elseif($sort_by == 2){
      $sql .= " ORDER BY price DESC";
    }
    elseif($sort_by == 3){
      $sql .= " ORDER BY title";
    }
    elseif($sort_by == 4){
      $sql .= " ORDER BY title DESC";
    }
    elseif($sort_by == 5){
      $sql .= "";
    }
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

  $data = array();
  $data['si'] = ($pageNumber * $p) - $p;
  $data['pages'] = $p;
  $data['cur_page'] = $pageNumber;


?>

    <div ng-controller="pageController" class="col-sm-12">
      <div class="container col-sm-9">
        <ul class="pagination" total-items="totalItems" items-per-page="itemsPerPage" ng-model="currentPage">
          <li><a alt="1" href="<?=$leftLink;?>">&laquo;&laquo;</a></li>
          <?php $limit = (($p < 5)?$p:5);?>
          <?php for($i = 1; $i <= $limit; $i++) : ?>
              <li><a alt="<?=$i;?>" href="#"><?= $i; ?></a></li>
          <?php endfor; ?>
          <li><a alt="<?= $p; ?>" href="<?=$rightLink;?>">&raquo;&raquo;</a></li>
        </ul>
      </div>

      <div class="pull-right container col-sm-3">
        <div class="dropdown-toggle btn btn-default pageNumber-dropdown">
          <a id="drop1" href="#" role="button" class="dropdown-toggle" data-toggle="dropdown"><?= $pageSize ;?> <b class="caret"></b></a>
          <ul class="dropdown-menu page_size" role="menu">
            <li><a href="#" alt="12">12 </a></li>
            <li><a href="#" alt="24">24 </a></li>
            <li><a href="#" alt="48">48 </a></li>
            <li><a href="#" alt="72">72 </a></li>
          </ul>
        </div>

        <div class="dropdown-toggle btn btn-default pageNumber-dropdown">
          <a id="drop1" href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">Sort By <b class="caret"></b></a>
          <ul class="dropdown-menu sort_by" role="menu">
            <li><a href="#" alt="1">Price: Low-High </a></li>
            <li><a href="#" alt="2">Price: High-Low </a></li>
            <li><a href="#" alt="3">Title: A-Z </a></li>
            <li><a href="#" alt="4">Title: Z-A</a></li>
            <li><a href="#" alt="5">Relevance</a></li>
          </ul>
        </div>
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
            <li><a alt="<?=$i;?>" href="#"><?= $i; ?></a></li>
          <?php endfor; ?>
          <li class="<?=$dissabledR;?>"><a href="<?=$rightLink;?>">&raquo;</a></li>
        </ul>
      </div>
    </div>

<script type="text/javascript">

// page number
$('ul.pagination li a').on('click',function(e){

  e.preventDefault();
  filters = {};

  var expansion = <?= ((isset($expansion_id[0]))?$expansion_id[0]:0);?>;
  var colour = <?= ((isset($colour_id[0]))?$colour_id[0]:0);?>;
  var rarity = <?= ((isset($rarity_id[0]))?$rarity_id[0]:0);?>;
  var pageSize = <?= ((isset($pageSize))?$pageSize:0);?>;
  var productName = <?= ((isset($product_name[0]))?$product_name[0]:0);?>;
  var sortBy = <?= ((isset($sort_by[0]))?$sort_by[0]:0);?>;

  var current_element = $(this);
  var cur_elem_content = current_element.attr("alt");
  filters['page'] = cur_elem_content;

  if(expansion != 0){
    filters['expansion'] = expansion;
  }
  if(colour != 0){
    filters['colour'] = colour;
  }
  if(rarity != 0){
    filters['rarity'] = rarity;
  }
  if(pageSize != 0){
    filters['pageSize'] = pageSize;
  }
  if(typeof productName !== 'undefined' && productName != 0){
    filters['productName'] = productName;
  }
  if(sortBy != 0){
    filters['sortBy'] = sortBy;
  }


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

// page size
$('ul.page_size li a').on('click',function(e){

  e.preventDefault();
  filters = {};

  var expansion = <?= ((isset($expansion_id[0]))?$expansion_id[0]:0);?>;
  var colour = <?= ((isset($colour_id[0]))?$colour_id[0]:0);?>;
  var rarity = <?= ((isset($rarity_id[0]))?$rarity_id[0]:0);?>;
  var page = <?= ((isset($pageNumber[0]))?$pageNumber[0]:0);?>;
  var productName = <?= ((isset($product_name[0]))?$product_name[0]:0);?>;
  var sortBy = <?= ((isset($sort_by[0]))?$sort_by[0]:0);?>;

  var current_element = $(this);
  var cur_elem_content = current_element.attr("alt");
  filters['pageSize'] = cur_elem_content;

  if(expansion != 0){
    filters['expansion'] = expansion;
  }
  if(colour != 0){
    filters['colour'] = colour;
  }
  if(rarity != 0){
    filters['rarity'] = rarity;
  }
  if(sortBy != 0){
    filters['sortBy'] = sortBy;
  }
  if(page != 0){
    filters['page'] = page;
  }
  if(productName != 0){
    filters['productName'] = productName;
  }

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

// sort by
$('ul.sort_by li a').on('click',function(e){

  e.preventDefault();
  filters = {};

  var expansion = <?= ((isset($expansion_id[0]))?$expansion_id[0]:0);?>;
  var colour = <?= ((isset($colour_id[0]))?$colour_id[0]:0);?>;
  var rarity = <?= ((isset($rarity_id[0]))?$rarity_id[0]:0);?>;
  var page = <?= ((isset($pageNumber[0]))?$pageNumber[0]:0);?>;
  var productName = <?= ((isset($product_name[0]))?$product_name[0]:0);?>;
  var pageSize = <?= ((isset($pageSize))?$pageSize:0);?>;

  var current_element = $(this);
  var cur_elem_content = current_element.attr("alt");
  filters['sortBy'] = cur_elem_content;

  if(expansion != 0){
    filters['expansion'] = expansion;
  }
  if(colour != 0){
    filters['colour'] = colour;
  }
  if(rarity != 0){
    filters['rarity'] = rarity;
  }
  if(page != 0){
    filters['page'] = page;
  }
  if(typeof productName !== 'undefined' && productName != 0){
    filters['productName'] = productName;
  }
  if(pageSize != 0){
    filters['pageSize'] = pageSize;
  }

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
