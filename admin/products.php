<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'/ecommerce/core/init.php';

  if(!is_logged_in()){
    login_error_redirect();
  }

  include 'includes/head.php';
  include 'includes/navigation.php';

  if (isset($_GET['delete'])){
    $id = sanitize($_GET['delete']);
    $db->query("UPDATE products SET deleted = 1 WHERE id = '$id'");
    header('Location: products.php');
  }

  if(isset($_GET['add']) || isset($_GET['edit'])){

  $colourQuery = $db->query("SELECT * FROM colour ORDER BY colour");
  $expansionQuery = $db->query("SELECT * FROM expansion ORDER BY expansion");
  $stateQuery = $db->query("SELECT * FROM state ORDER BY state");
  $rarityQuery = $db->query("SELECT * FROM rarity ORDER BY rarity");

  $title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):'');
  $price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):'');
  $quantity = ((isset($_POST['quantity']) && $_POST['quantity'] != '')?sanitize($_POST['quantity']):'');
  $colour = ((isset($_POST['colour']) && $_POST['colour'] != '')?sanitize($_POST['colour']):'');
  $expansion = ((isset($_POST['expansion']) && $_POST['expansion'] != '')?sanitize($_POST['expansion']):'');
  $state = ((isset($_POST['state']) && $_POST['state'] != '')?sanitize($_POST['state']):'');
  $rarity = ((isset($_POST['rarity']) && $_POST['rarity'] != '')?sanitize($_POST['rarity']):'');
  $featured = ((isset($_POST['featured']) && $_POST['featured'] != '')?sanitize($_POST['featured']):'');
  $saved_image = '';
  $dbPath = '';
  $tempLoc = '';
  $uploadPath = '';

  if(isset($_GET['edit'])){
    $editID = (int)$_GET['edit'];
    $productResults = $db->query("SELECT * FROM products WHERE id = '$editID'");
    $product = mysqli_fetch_assoc($productResults);

    if(isset($_GET['delete_image'])){
      $image_url = $_SERVER['DOCUMENT_ROOT'].$product['image'];
      unset($image_url);
      $db->query("UPDATE products SET image = '' WHERE id = '$editID'");
      header('Location: products.php?>edit='.$editID);
    }

    $title = $product['title'];
    $price = $product['price'];
    $quantity = $product['quantity'];
    $colour = $product['colour'];
    $expansion = $product['expansion'];
    $state = $product['state'];
    $rarity = $product['rarity'];
    $featured = $product['featured'];
    $saved_image = (($product['image'] != '')?$product['image']:'');
    $dbPath = $saved_image;
  }

  if ($_POST) {

    $title = sanitize($_POST['title']);
    $price = sanitize($_POST['price']);
    $featured = sanitize($_POST['featured']);
    $quantity = $_POST['quantity'];
    $colour = sanitize($_POST['colour']);
    $expansion = $_POST['expansion'];
    $state = $_POST['state'];
    $rarity = $_POST['rarity'];

    $productResults = $db->query("SELECT * FROM products WHERE id = '$editID'");
    $product = mysqli_fetch_assoc($productResults);

    $dbPath = $product['image'];

    $required = array('title', 'price', 'quantity', 'featured');
    $errors = array();
    $i = 0;

    foreach($required as $field){
      if($_POST[$field] == ''){
        $errors[] = $required[$i] . ' is required';
      }
      $i++;
    }

    if(!empty($_FILES)) {
      $photo = $_FILES["image"];
      $image = $photo["type"];
      $name = $photo["name"];
      $nameArray = explode(".",$name);
      $fileName = $nameArray[0];
      $fileExt = $nameArray[1];
      $mime = explode("/",$image);
      $mimeType = $mime[0];
      $mimeExt = $mime[1];
      $tempLoc = $photo['tmp_name'];
      $fileSize = $photo['size'];
      $allowed = array('png', 'jpg', 'jpeg', 'gif');

      $uploadName = $_POST['title'].'.'.$fileExt;
      $uploadPath = BASEURL.'images/products/'.$uploadName;
      $dbPath = "/ecommerce/images/products/".$uploadName;

      if($mimeType != 'image'){
        $errors[] = "The file must be an image";
      }
      if(!in_array($fileExt, $allowed)){
        $errors[] = "The image must be a png, jpg, jpeg, or gif";
      }
      if($fileSize > 15000000){
        $errors[] = "The file must be under 25mb";
      }
      if($fileExt != $mimeExt && ($mimeExt == 'jpeg' && $fileExt != 'jpg')){
        $errors[] = "File extension match failed";
      }
    }

    if(!empty($errors)) {
      echo display_errors($errors);
    }
    else
    {
      move_uploaded_file($tempLoc, $uploadPath);
      $insertSQL = "INSERT INTO products (`title`, `price`, `quantity`, `colour`, `expansion`, `state`, `image`, `rarity`, `featured`, `deleted`)
        VALUES ('$title', '$price', '$quantity', '$colour', '$expansion', '$state', '$dbPath', '$rarity', '$featured', '0')";
        if(isset($_GET['edit'])){
          $insertSQL = "UPDATE products SET title='{$title}' WHERE id = '$editID'";
          $db->query($insertSQL);
          $insertSQL = "UPDATE products SET price='{$price}' WHERE id = '$editID'";
          $db->query($insertSQL);
          $insertSQL = "UPDATE products SET quantity='{$quantity}' WHERE id = '$editID'";
          $db->query($insertSQL);
          $insertSQL = "UPDATE products SET colour='{$colour}' WHERE id = '$editID'";
          $db->query($insertSQL);
          $insertSQL = "UPDATE products SET expansion='{$expansion}' WHERE id = '$editID'";
          $db->query($insertSQL);
          $insertSQL = "UPDATE products SET state='{$state}' WHERE id = '$editID'";
          $db->query($insertSQL);
          $insertSQL = "UPDATE products SET image='{$dbPath}' WHERE id = '$editID'";
          $db->query($insertSQL);
          $insertSQL = "UPDATE products SET featured='{$featured}' WHERE id = '$editID'";
          $db->query($insertSQL);
          $insertSQL = "UPDATE products SET deleted='0' WHERE id = '$editID'";
        }
      $db->query($insertSQL);
    }
  }
?>

  <h2 class="text-center"><?=((isset($_GET['edit']))? 'Edit ':'Add a new ');?>product:</h2>
  <hr>
  <form action="products.php?<?=((isset($_GET['edit']))?'edit='.$editID:'add=1');?>" method="POST" enctype="multipart/form-data">
    <div class="form-group col-md-3">
      <label for="title">Title*:</label>
      <input type="text" name="title" id="title" class="form-control" value="<?=$title;?>"></input>
    </div>
    <div class="form-group col-md-2">
      <label for="price">Price*:</label>
      <input type="text" name="price" id="price" class="form-control" value="<?=$price;?>"></input>
    </div>
    <div class="form-group col-md-2">
      <label for="quantity">Quantity*:</label>
      <input type="number" min="0" name="quantity" id="quantity" class="form-control" value="<?=$quantity;?>"></input>
    </div>
    <div class="form-group col-md-2">
      <label for="colour">Colour*:</label>
      <select name="colour" id="colour" class="form-control">
        <option value=""<?((isset($colour == '')?' selected':'');?>
          <?php while($c = mysqli_fetch_assoc($colourQuery)): ?>
            <option value="<?=$c['id'];?>"<?=(($colour == $c['id'])?' selected':'');?>><?=$c['colour'];?></option>
          <?php endwhile; ?>
        </option>
      </select>
    </div>
    <div class="form-group col-md-3">
      <label for="expansion">Edition*:</label>
      <select name="expansion" id="expansion" class="form-control">
        <option value=""<?((isset($expansion == '')?' selected':'');?>
          <?php while($e = mysqli_fetch_assoc($expansionQuery)): ?>
            <option value="<?=$e['id'];?>"<?=(($expansion == $e['id'])?' selected':'');?>><?=$e['expansion'];?></option>
          <?php endwhile; ?>
        </option>
      </select>
    </div>
    <div class="form-group col-md-2">
      <label for="state">State*:</label>
      <select name="state" id="state" class="form-control">
        <option value=""<?((isset($state == '')?' selected':'');?>
          <?php while($s = mysqli_fetch_assoc($stateQuery)): ?>
            <option value="<?=$s['id'];?>"<?=(($state == $s['id'])?' selected':'');?>><?=$s['state'];?></option>
          <?php endwhile; ?>
        </option>
      </select>
    </div>
    <div class="form-group col-md-2">
      <label for="rarity">Rarity*:</label>
      <select name="rarity" id="rarity" class="form-control">
        <option value=""<?((isset($rarity == '')?' selected':'');?>
          <?php while($r = mysqli_fetch_assoc($rarityQuery)): ?>
            <option value="<?=$r['id'];?>"<?=(($rarity == $r['id'])?' selected':'');?>><?=$r['rarity'];?></option>
          <?php endwhile; ?>
        </option>
      </select>
    </div>
    <div class="form-group col-md-2">
      <label for="featured">Featured*:</label>
      <input type="text" name="featured" id="featured" class="form-control" value="<?=$featured ;?>"></input>
    </div>
    <div class="form-group col-md-6">
      <?php if($dbPath != '') : ?>
        <div class="saved_image">
          <img src="<?=$dbPath; ?>" alt="saved_image"/>
          <a href="products.php?delete_image=1&edit=<?=$editID;?>" class="text-danger btn">Delete Image</a>
        </div>
      <?php else: ?>
        <label for="image">Image*:</label>
        <input type="file" name="image" id="image" class="form-control" value="<?=((isset($dbPath))?$dbPath:'');?>"></input>
      <?php endif; ?>
    </div>
    <div class="form-group col-md-5 pull-right">
      <a href="products.php" class="btn btn-default col-md-2 pull-right">Cancel</a>
      <input type="submit" value="<?=((isset($_GET['edit']))? 'Edit Product':'Add Product');?>" class="btn btn-success pull-right col-md-2 product-add-button"></input>
    </div>
    <div class="clearfix"></div>
  </form>
  <?php } else {
    //Populate Table
    $sql = "SELECT * FROM products WHERE deleted != 1";
    $pResults = $db->query($sql);

    if (isset($_GET['featured'])) {
      $id = (int)$_GET['id'];
      $featured = (int)$_GET['featured'];
      $featuredsql = "UPDATE products SET featured = '$featured' WHERE id = '$id'";
      $db->query($featuredsql);
      header('Location: products.php');
    }
  ?>
  <h2 class="text-center">Products</h2>
  <a href="products.php?add=1" class="btn btn-success pull-right" id="add-product-button">Add Product</a><div class="clearfix"></div>
  <hr>
  <table class="table table-bordered table-condensed table-striped">
    <thead>
      <th></th>
      <th>Product</th><th>Price</th><th>Quantity</th><th>Colour</th>
      <th>Edition</th><th>State</th><th>Rarity</th><th>Featured</th><th>Sold</th>
    </thead>
    <tbody>
      <?php while($product = mysqli_fetch_assoc($pResults)): ?>
        <tr>
          <td>
            <a href="products.php?edit=<?=$product['id']?>" class="btn btn-ex btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
            <a href="products.php?delete=<?=$product['id']?>" class="btn btn-ex btn-default"><span class="glyphicon glyphicon-trash"></span></a>
          </td>
          <td><?=$product['title'];?></td>
          <td><?=money($product['price']);?></td>
          <td><?=$product['quantity'];?></td>
          <td><?=$product['colour'];?></td>
          <td><?=$product['expansion'];?></td>
          <td><?=$product['state'];?></td>
          <td><?=$product['rarity'];?></td>
          <td>
            <a href="products.php?featured=<?=(($product['featured'] == 0)?'1':'0');?>&id=<?=$product['id'];?>" class="btn btn-xs btn-default">
              <span class="glyphicon glyphicon-<?=(($product['featured'] == 1)?'minus':'plus');?>"></span>
            </a>
            &nbsp <?=(($product['featured']==1)?'Featured Product':'');?>
          </td>
          <td><?=$product['deleted'];?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <?php
}
  include 'includes/footer.php';
?>
