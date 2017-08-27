<?php

  require_once $_SERVER['DOCUMENT_ROOT'].'/ecommerce/core/init.php';
  $name = sanitize($_POST['full_name']);
  $email = sanitize($_POST['email']);
  $street = sanitize($_POST['street']);
  $street2 = sanitize($_POST['street2']);
  $city = sanitize($_POST['city']);
  $county = sanitize($_POST['county']);
  $post_code = sanitize($_POST['post_code']);
  $country = sanitize($_POST['country']);

  $errors = array();
  $required = array(
    'full_name'   => 'Full Name',
    'email'       => 'Email',
    'street'      => 'Street Address 1',
    'city'        => 'City',
    'post_code'   => 'Post Code',
    'country'     => 'Country',
  );

  // Check if all required fields are occupied
  foreach ($required as $f => $d) {
    if(empty($_POST[$f]) || $_POST[$f] == '' ){
      $errors[] = $d . ' is required.';
    }
  }

  // Is email valid
  if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $errors[] = 'Invalid email address.';
  }

  if(!empty($errors)){
    echo display_errors($errors);
  } else {
    echo 'passed';
  }
 ?>
