<?php

  require_once '..//core/init.php';
  if(!is_logged_in()){
    header('Location login.php');
  }

  include 'includes/head.php';
  include 'includes/navigation.php';

  // Complete order
  if(isset($_GET['complete']) && $_GET['complete'] == 1){
    $cart_id = sanitize((int)$_GET['cart_id']);
    $db->query("UPDATE cart SET shipped = 1 WHERE id = '{$cart_id}'");
    $_SESSION['success_flash'] = "The order has been completed.";
    header('Location: index.php');
  }

  $txn_id = sanitize((int)$_GET['txn_id']);
  $txnQuery = $db->query("SELECT * FROM transactions WHERE id = '{$txn_id}'");
  $txn = mysqli_fetch_assoc($txnQuery);
  $cart_id = $txn['cart_id'];
  $cartQuery = $db->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
  $cart = mysqli_fetch_assoc($cartQuery);
  $items = json_decode($cart['items'], true);

  $idArray = array();
  $products = array();

  foreach ($items as $item) {
    $idArray[] = $item['id'];
  }

  $ids = implode(',', $idArray);

  $productQ = $db->query(
    "SELECT i.id as 'id', i.title as 'title', e.id as 'eid', e.expansion as 'expansion'
    FROM products i
    LEFT JOIN expansion e ON i.expansion = e.id
    WHERE i.id IN ({$ids})");

  while($p = mysqli_fetch_assoc($productQ)){
    foreach ($items as $item) {
      if($item['id'] == $p['id']){
        $x = $item;
        continue;
      }
    }
    $products[] = array_merge($x,$p);
  }

 ?>

 <h2 class="text-center">Items Ordered</h2>

 <table class="table table-condensed table-bordered table-striped">
   <head>
     <th>Quantity</th>
     <th>Title</th>
     <th>Expansion</th>
   </head>
   <tbody>
     <?php foreach($products as $product): ?>
       <tr>
         <td><?=$product['quantity'];?></td>
         <td><?=$product['title'];?></td>
         <td><?=$product['expansion'];?></td>
       </tr>
     <?php endforeach; ?>
   </tbody>
 </table>

 <div class="row">
   <div class="col-md-6">
     <h3 class="text-center">Order Details</h3>
     <table class="table table-condensed table-bordered table-striped">
       <tbody>
         <tr>
           <td>Sub Total</td>
           <td><?=money($txn['sub_total']);?></td>
         </tr>
         <tr>
           <td>Tax</td>
           <td><?=money($txn['tax']);?></td>
         </tr>
         <tr>
           <td>Grand Total</td>
           <td><?=money($txn['grand_total']);?></td>
         </tr>
         <tr>
           <td>Order Date</td>
           <td><?=pretty_date($txn['txn_date']);?></td>
         </tr>
       </tbody>
     </table>
   </div>
   <div class="col-md-6">
     <h3 class="text-center">Shipping Address</h3>
     <table class="table table-condensed table-bordered table-striped">
       <address>
         <?=$txn['full_name'];?><br>
         <?=$txn['street'];?><br>
         <?=(($txn['street2'] != '')?$txn['street2'].'<br>':'');?>
         <?=$txn['city'].', '.$txn['county'].' '.$txn['post_code'];?><br>
         <?=$txn['country'];?><br>
       </address>
     </table>
   </div>
 </div>
 <div class="pull-right">
   <a href="index.php" class="btn btn-lg btn-default">Cancel</a>
   <a href="orders.php?complete=1&cart_id=<?=$cart_id;?>" class="btn btn-lg btn-primary">Complete Order</a>
 </div>
 <?php include 'includes/footer.php'; ?>
