<?php

  require_once 'core/init.php';

  // Set secret key
  \Stripe\Stripe::setApiKey(STRIPE_PRIVATE);

  // Get the credit card details submitted by the form
  $token = $_POST['stripeToken'];

  // Get the rest of the post data
  $full_name = sanitize($_POST['full_name']);
  $email = sanitize($_POST['email']);
  $street = sanitize($_POST['street']);
  $street2 = sanitize($_POST['street2']);
  $city = sanitize($_POST['city']);
  $county = sanitize($_POST['county']);
  $post_code = sanitize($_POST['post_code']);
  $country = sanitize($_POST['country']);

  $sub_total = sanitize($_POST['sub_total']);
  $cart_id = sanitize($_POST['cart_id']);
  $description = sanitize($_POST['description']);
  $charge_amount = number_format($sub_total, 2) * 100;

  $metadata = array(
    "cart_id"     => $cart_id,
    "sub_total"   => $sub_total,
  );

  // Create the charge on stripe server
  try{
    $charge = \Stripe\Charge::create(array(
      "amount" => $charge_amount, // Amount in pennies
      "currency" => CURRENCY,
      "source" => $token,
      "description" => $description,
      "receipt_email" => $email,
      "metadata" => $metadata)
    );

    // Adjust inventory
    $itemq = $db->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
    $iresults = mysqli_fetch_assoc($itemq);
    $items = json_decode($iresults['items'], true);

    foreach ($items as $item) {
      $item_id = $item['id'];
      $productq = $db->query("SELECT quantity FROM products WHERE id = '{$item_id}'");
      $product_quantity = mysqli_fetch_assoc($productq);
      $new_quantity = $product_quantity['quantity'] - $item['quantity'];

      $quantityq = $db->query("UPDATE products SET quantity = '{$new_quantity}' WHERE id = '{$item_id}'");
    }

    // Update cart
    $db->query("UPDATE cart SET paid = 1 WHERE id = '{$cart_id}'");
    $db->query("INSERT INTO transactions
      (charge_id, cart_id, full_name, email, street, street2, city, county, post_code, country, sub_total, tax, grand_total, description, txn_type) VALUES
      ('{$charge->id}', '{$cart_id}', '{$full_name}', '{$email}', '{$street}', '{$street2}', '{$city}', '{$county}', '{$post_code}', '{$country}', '{$sub_total}',
        '{0}', '{$sub_total}', '{$description}', '{$charge->object}')");

    $domain = ($_SERVER['HTTP_HOST'] != 'localhost')? '.'.$_SERVER['HTTP_HOST']: false;

    setcookie(CART_COOKIE, '', 1, "/", $domain, false);

    include 'includes/head.php';
    include 'includes/navigation.php';
    include 'includes/headerpartial.php';
    ?>

      <h1 class="text-center text-success">Thank you!</h1>
      <p>Your card has been successfully charged <?=money($sub_total);?>. You have been emailed a receipt. Please
          check your spam folder if it is not in your inbox. Additionally you can print this page as a receipt.</p>

      <p>Your receipt number is: <strong><?=$cart_id?></strong></p>
      <p>Your order will be shipped to the address below:</p>

      <address class="">
        <?=$full_name;?><br>
        <?=$street;?><br>
        <?=(($street2 != '')?$street2.'<br>':'');?>
        <?=$city . ', ' . $county . ' ' . $post_code;?><br>
        <?=$country;?><br>
      </address>

    <?php
    include 'includes/footer.php';

  } catch(\Stripe\Error\Card $e){
    // The card has been declined
    echo $e;
  }

 ?>
