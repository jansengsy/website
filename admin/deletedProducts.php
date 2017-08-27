<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'/ecommerce/core/init.php';

  if(!is_logged_in()){
    login_error_redirect();
  }

  include 'includes/head.php';
  include 'includes/navigation.php';

  if(isset($_GET['add'])){
    $id = $_GET['add'];
    $db->query("UPDATE products SET deleted = 0 WHERE id = '$id'");
  }
?>

<?php
  //Populate Table
  $sql = "SELECT * FROM products WHERE deleted != 0";
  $pResults = $db->query($sql);
?>

<h2 class="text-center">Deleted Products</h2>
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
          <a href="deletedProducts.php?add=<?=$product['id']?>" class="btn btn-ex btn-default"><span class="glyphicon glyphicon-plus"></span></a>
        </td>
        <td><?=$product['title'];?></td>
        <td><?=money($product['price']);?></td>
        <td><?=$product['quantity'];?></td>
        <td><?=$product['colour'];?></td>
        <td><?=$product['expansion'];?></td>
        <td><?=$product['state'];?></td>
        <td><?=$product['rarity'];?></td>
        <td>
          <a href="" class="btn btn-xs btn-default">
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
  include 'includes/footer.php';
?>
