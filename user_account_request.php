<?php

  require_once 'core\init.php';
  include 'includes\head.php';
  include 'includes\navigation.php';

  $request = ((isset($_GET['request']))?$_GET['request']:'');

  if($request != '' && $request == "orders"){
    $session_email = $_SESSION['User'];
    $sql = "SELECT * FROM transactions WHERE email = '$session_email' ORDER BY txn_date DESC";
    $oq = $db->query($sql);
    //$orders = mysqli_fetch_assoc($oq);
  }

  $errors = array();

  $name = ((isset($_POST['full_name']))?sanitize($_POST['full_name']):'');
  $street1 = ((isset($_POST['street']))?sanitize($_POST['street']):'');
  $street2 = ((isset($_POST['street2']))?sanitize($_POST['street2']):'');
  $city = ((isset($_POST['city']))?sanitize($_POST['city']):'');
  $county = ((isset($_POST['county']))?sanitize($_POST['county']):'');
  $post_code = ((isset($_POST['post_code']))?sanitize($_POST['post_code']):'');
  $country = ((isset($_POST['country']))?sanitize($_POST['country']):'');

  $email_1 = ((isset($_POST['email_1']))?sanitize($_POST['email_1']):'');
  $email_2 = ((isset($_POST['email_2']))?sanitize($_POST['email_2']):'');

  $pass_n = ((isset($_POST['pass_n']))?sanitize($_POST['pass_n']):'');
  $pass_c = ((isset($_POST['pass_c']))?sanitize($_POST['pass_c']):'');

  $email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
  $password = ((isset($_POST['password']))?sanitize($_POST['password']):'');

  $session_email = $_SESSION['User'];

  if(isset($_POST['update']) && $_POST['update'] != ''){

    $requirred = array('full_name', 'street', 'city', 'post_code', 'country');

    foreach ($requirred as $r) {
      if(empty($_POST[$r]) || $_POST[$r] == ''){
        $errors[] = 'You must fill out all fields.';
        break;
      }
    }

    if(!isset($_POST['terms'])){
      $errors[] = "You must accept terms and conditions";
    }else{
      if($_POST['terms'] != 'on'){
        $errors[] = "You must accept terms and conditions";
      }
    }

    if(!empty($errors)){
      echo display_errors($errors);
    } else{
      // Add to db
      $insertSQL = "UPDATE customers SET full_name='{$name}' WHERE email = '$session_email'";
      $db->query($insertSQL);
      $insertSQL = "UPDATE customers SET street1='{$street1}' WHERE email = '$session_email'";
      $db->query($insertSQL);
      $insertSQL = "UPDATE customers SET street2='{$street2}' WHERE email = '$session_email'";
      $db->query($insertSQL);
      $insertSQL = "UPDATE customers SET city='{$city}' WHERE email = '$session_email'";
      $db->query($insertSQL);
      $insertSQL = "UPDATE customers SET county='{$county}' WHERE email = '$session_email'";
      $db->query($insertSQL);
      $insertSQL = "UPDATE customers SET post_code='{$post_code}' WHERE email = '$session_email'";
      $db->query($insertSQL);
      $insertSQL = "UPDATE customers SET country='{$country}' WHERE email = '$session_email'";
      $db->query($insertSQL);

      $_SESSION['success_flash'] = 'Your address has been updated.';
      redirect('user_account.php');
    }
  } elseif(isset($_POST['email']) && $_POST['email'] != ''){

    $query = $db->query("SELECT * FROM customers WHERE email = '$session_email'");
    $user = mysqli_fetch_assoc($query);

    $emailQuery = $db->query("SELECT * FROM customers WHERE email = '$email_1'");
    $emailCount = mysqli_num_rows($emailQuery);

    $requirred = array('email', 'email_1', 'email_2', 'password');

    foreach ($requirred as $r) {
      if(empty($_POST[$r])){
        $errors[] = 'You must fill out all fields.';
        break;
      }
    }

    if(strlen($password) < 6){
      $errors[] = 'Your password must be at least 6 characters.';
    }

    if(!password_verify($password, $user['password'])){
      $errors[] = 'Incorrect password.';
    }

    if($email != $session_email){
      $errors[] = 'Incorrect account email';
    }

    if(!filter_var($email_1, FILTER_VALIDATE_EMAIL)){
      $errors[] = 'You must enter a valid email';
    }

    if($email_1 != $email_2){
      $errors[] = 'Emails do not match.';
    }

    if($emailCount > 0){
      $errors[] = 'New email already taken.';
    }

    if(!empty($errors)){
      echo display_errors($errors);
    } else{
      // Add to db
      $insertSQL = "UPDATE customers SET email='{$email_1}' WHERE email = '$session_email'";
      $db->query($insertSQL);

      $orderSQL = "SELECT * FROM transactions WHERE email = '$session_email'";
      $orders = $db->query($orderSQL);
      while($order = mysqli_fetch_assoc($orders)){
        echo $email_1 . " " . $session_email;
        $db->query("UPDATE transactions SET email = '$email_1' WHERE email = '$session_email'");
      }
      $_SESSION['success_flash'] = 'Your email has been updated.';
      $_SESSION['User'] = $email_1;
      redirect('user_account.php');
    }
  } elseif(isset($_POST['password']) && $_POST['password'] != ''){

      $query = $db->query("SELECT * FROM customers WHERE email = '$session_email'");
      $user = mysqli_fetch_assoc($query);

      $requirred = array('password', 'pass_n', 'pass_c');

      foreach ($requirred as $r) {
        if(empty($_POST[$r])){
          $errors[] = 'You must fill out all fields.';
          break;
        }
      }

      if(strlen($password) < 6){
        $errors[] = 'Your password must be at least 6 characters.';
      }

      if(strlen($pass_n) < 6){
        $errors[] = 'Your password must be at least 6 characters.';
      }

      if(strlen($pass_c) < 6){
        $errors[] = 'Your password must be at least 6 characters.';
      }

      if($pass_n != $pass_c){
        $errors[] = 'Passwords do not match.';
      }

      if(!password_verify($password, $user['password'])){
        $errors[] = 'Incorrect password.';
      }

      if(!empty($errors)){
        echo display_errors($errors);
      } else{
        // Add to db
        $hashed = password_hash($pass_n, PASSWORD_DEFAULT);
        $insertSQL = "UPDATE customers SET password='$hashed' WHERE email = '$session_email'";
        $db->query($insertSQL);

        $_SESSION['success_flash'] = 'Your password has been updated.';
        redirect('user_account.php');
      }
  }
?>

<div class="col-md-12">

    <?php include 'includes\rightbar.php'; ?>
    <div class="page-title-banner-users users-container col-md-10">
      <span class="virtical-center"><h2 class="page-title">Your <?=$request;?></h2></span>
    </div>
    <?php include 'includes\rightbar.php'; ?>

    <?php if($request == "orders"): ?>
      <div class="col-md-10" style="margin-top: 30px;">
        <table class="table table-bordered table-condensed table-striped">
          <thead>
            <th>Order Number</th>
            <th>Date Ordered</th>
            <th>Items</th>
            <th>Grand Total</th>
            <th>Status</th>
            <th>Dispatched</th>
          </thead>
          <tbody>
            <?php while($order = mysqli_fetch_assoc($oq)): ?>
              <?php
                $cart_id = $order['cart_id'];
                $cartQ = $db->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
                $result = mysqli_fetch_assoc($cartQ);
                $items = json_decode($result['items'], true);
                $shipped = $result['shipped'];
                $paid = $result['paid'];
              ?>
              <tr style="<?=(($shipped == 1)?'background-color: green; color: white;':'');?>">
                <td><?= $order['id']; ?></td>
                <td><?= $order['txn_date']; ?></td>
                <td>
                  <?php $itemString = '' ?>
                  <?php foreach($items as $item){
                    $product_id = $item['id'];
                    $productQ = $db->query("SELECT * FROM products WHERE id = '{$product_id}'");
                    $product = mysqli_fetch_assoc($productQ);
                    $available = $item['quantity'];
                    $itemString .= $product['title'] . " x " . $available . "<br>";
                  } ?>
                  <?= $itemString; ?>
                </td>
                <td><?= $order['grand_total']; ?></td>
                <td><?=(($paid == 0)?'Unpaid':'Paid');?></td>
                <td><?=(($shipped == 0)?'Awaiting Dispatch':'Dispatched');?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
        <a href="user_account.php" class="pull-right btn btn-default btn-lg" style="margin-top : 20px;">Return to Account</a>
      </div>
    <?php elseif($request == "address"): ?>

      <?php
        if(isset($_SESSION['User'])){
          $session_email = $_SESSION['User'];
          $addressQ = "SELECT * FROM customers WHERE email = '$session_email'";
          $uQ = $db->query($addressQ);
          $customerAddress = mysqli_fetch_assoc($uQ);
        }
       ?>
      <div class="col-md-10" style="margin-top: 30px;">
        <form class="user-account-address" action="#" method="post">
          <h3 class="col-md-12" style="margin-bottom: 20px;">Account Address</h3>
          <input type="hidden" name="update" value="update" id="update">
          <div class="form-group col-md-4">
            <label for="full_name">Full Name: *</label>
            <input class="form-control" id="full_name" type="text" name="full_name" value="<?= $customerAddress['full_name']; ?>">
          </div>
          <div class="form-group col-md-4">
          </div>
          <div class="form-group col-md-4">
          </div>
          <h3 class="col-md-12" style="margin-bottom: 20px;">Address Details</h3>
          <div class="form-group col-md-4">
            <label for="street">Street Address 1: *</label>
            <input class="form-control" id="street" type="text" name="street" value="<?= $customerAddress['street1']; ?>">
          </div>
          <div class="form-group col-md-4">
            <label for="street2">Street Address 2:</label>
            <input class="form-control" id="street2" type="text" name="street2"value="<?= $customerAddress['street2']; ?>">
          </div>
          <div class="form-group col-md-4">
            <label for="city">City: *</label>
            <input class="form-control" id="city" type="text" name="city" value="<?= $customerAddress['city']; ?>">
          </div>
          <div class="form-group col-md-4">
            <label for="county">County:</label>
            <input class="form-control" id="county" type="text" name="county" value="<?= $customerAddress['county']; ?>">
          </div>
          <div class="form-group col-md-4">
            <label for="post_code">Post Code: *</label>
            <input class="form-control" id="post_code" type="text" name="post_code" value="<?= $customerAddress['post_code']; ?>">
          </div>
          <div class="form-group col-md-4">
            <label for="country">Country: *</label>
            <input class="form-control" id="country" type="text" name="country" value="<?= $customerAddress['country']; ?>">
          </div>
          <div class="form-group col-md-7">
            <input class="form-check-input" id="terms" name="terms" type="checkbox"> I confirm that I have read the terms and conditions*
          </div>
          <div class="col-md-12">
            <input type="submit" name="" value="Update Address" class="btn btn-success">
          </div>
        </form>
        <a href="user_account.php" class="pull-right btn btn-default btn-lg" style="margin-top : 20px;">Return to Account</a>
      </div>
    <?php elseif($request == "details"): ?>

      <div class="col-md-10" style="margin-top: 30px;">
        <form class="user-account-details" action="#" method="post">
          <h4 class="col-md-12" style="margin-bottom: 20px;">Old Email</h4>
          <input type="hidden" name="email" value="email" id="email">
          <div class="form-group col-md-4">
            <label for="email">Current Email: *</label>
            <input class="form-control" id="email" type="text" name="email" value="">
          </div>
          <div class="form-group col-md-4">
          </div>
          <div class="form-group col-md-4">
          </div>
          <h4 class="col-md-12" style="margin-bottom: 20px;">New Email</h4>
          <div class="form-group col-md-4">
            <label for="email_1">New Email: *</label>
            <input class="form-control" id="email_1" type="text" name="email_1" value="">
          </div>
          <div class="form-group col-md-4">
            <label for="email_2">Confirm Email: *</label>
            <input class="form-control" id="email_2" type="text" name="email_2" value="">
          </div>
          <div class="form-group col-md-4">
          </div>
          <h4 class="col-md-12" style="margin-bottom: 20px;">Confirm Password</h4>
          <div class="form-group col-md-4">
            <label for="password">Account Password: *</label>
            <input class="form-control" id="password" type="password" name="password" value="">
          </div>
          <div class="form-group col-md-4">
          </div>
          <div class="col-md-12">
            <input type="submit" name="" value="Update Email" class="btn btn-success">
          </div>

        </form>
        <a href="user_account.php" class="pull-right btn btn-default btn-lg" style="margin-top : 20px;">Return to Account</a>
      </div>
    <?php elseif($request == "pass"): ?>
      <div class="col-md-10" style="margin-top: 30px;">
        <form class="user-account-password" action="#" method="post">
          <h4 class="col-md-12" style="margin-bottom: 20px;">Old Password</h4>
          <input type="hidden" name="password" value="password" id="password">
          <div class="form-group col-md-4">
            <label for="password">Current Password: *</label>
            <input class="form-control" id="password" type="password" name="password" value="">
          </div>
          <div class="form-group col-md-4">
          </div>
          <div class="form-group col-md-4">
          </div>
          <h4 class="col-md-12" style="margin-bottom: 20px;">New Password</h4>
          <div class="form-group col-md-4">
            <label for="pass_n">New Password: *</label>
            <input class="form-control" id="pass_n" type="password" name="pass_n" value="">
          </div>
          <div class="form-group col-md-4">
            <label for="pass_c">Confirm Password: *</label>
            <input class="form-control" id="pass_c" type="password" name="pass_c" value="">
          </div>
          <div class="form-group col-md-4">
          </div>
          <div class="col-md-12">
            <input type="submit" name="" value="Update Password" class="btn btn-success">
          </div>

        </form>
        <a href="user_account.php" class="pull-right btn btn-default btn-lg" style="margin-top : 20px;">Return to Account</a>
      </div>
    <?php endif; ?>
    <?php include 'includes\rightbar.php'; ?>

</div>

<?php
  include 'includes\footer.php';
?>
