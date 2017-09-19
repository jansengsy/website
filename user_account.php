<?php

  require_once 'core\init.php';
  include 'includes\head.php';
  include 'includes\navigation.php';

  $errors = array();

  $name = ((isset($_POST['full_name']))?sanitize($_POST['full_name']):'');
  $email_1 = ((isset($_POST['email_2']))?sanitize($_POST['email_2']):'');
  $email_2 = ((isset($_POST['email_3']))?sanitize($_POST['email_3']):'');

  $street1 = ((isset($_POST['street']))?sanitize($_POST['street']):'');
  $street2 = ((isset($_POST['street2']))?sanitize($_POST['street2']):'');
  $city = ((isset($_POST['city']))?sanitize($_POST['city']):'');
  $county = ((isset($_POST['county']))?sanitize($_POST['county']):'');
  $post_code = ((isset($_POST['post_code']))?sanitize($_POST['post_code']):'');
  $country = ((isset($_POST['country']))?sanitize($_POST['country']):'');

  $passwordN = ((isset($_POST['password_set']))?sanitize($_POST['password_set']):'');
  $confirmN = ((isset($_POST['password_confirm']))?sanitize($_POST['password_confirm']):'');

  $email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
  $password = ((isset($_POST['password']))?sanitize($_POST['password']):'');

  if($_POST){

    if(isset($_POST['login'])){

      if(empty($_POST['email']) || empty($_POST['password'])){
        $errors[] = 'You must provide email and password.';
      }

      // Validate email
      if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors[] = 'Email is not valid.';
      }

      // Password is more than 6 characters
      if(strlen($password) < 6){
        $errors[] = 'Passwords must be at least six characters in length.';
      }

      // Does user exists in db
      $query = $db->query("SELECT * FROM customers WHERE email = '$email'");
      $user = mysqli_fetch_assoc($query);
      $userCount = mysqli_num_rows($query);

      // Check for user
      if($userCount < 1){
        $errors[] = 'User email does not exist.';
      }

      // Verify password
      if(!password_verify($password, $user['password'])){
        $errors[] = 'Incorrect password.';
      }

      // Check for errors
      if(!empty($errors)){
        // Display errors
        echo display_errors($errors);
      } else {
        // Login user
        $user_id = $user['id'];
        user_login($email);
      }
    } else {
      $emailQuery = $db->query("SELECT * FROM customers WHERE email = '$email_1'");
      $emailCount = mysqli_num_rows($emailQuery);

      $requirred = array('full_name', 'email_2', 'email_3', 'street', 'city', 'post_code', 'country', 'password_set', 'password_confirm');

      foreach ($requirred as $r) {
        if(empty($_POST[$r])){
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

      if(strlen($passwordN) < 6){
        $errors[] = 'Your password must be at least 6 characters.';
      }

      if($passwordN != $confirmN){
        $errors[] = 'Passwords do not match.';
      }

      if(!filter_var($email_1, FILTER_VALIDATE_EMAIL)){
        $errors[] = 'You must enter a valid email';
      }

      if($email_1 != $email_2){
        $errors[] = 'Emails do not match.';
      }

      if($emailCount > 0){
        $errors[] = 'Email already taken.';
      }

      if(!empty($errors)){
        echo display_errors($errors);
      } else{
        // Add to db
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $insertSQL = "INSERT INTO customers (`full_name`, `email`, `street1`, `street2`,
          `city`, `county`, `post_code`, `country`, `password`)
          VALUES ('$name', '$email_1', '$street1', '$street2', '$city', '$county', '$post_code', '$country', '$hashed')";
        $db->query($insertSQL);
        $_SESSION['success_flash'] = 'Your account has been created.';
        user_login($email_1);
      }
    }
  }
?>

<div class="col-md-12">

  <?php if(is_logged_in_customer()): ?>
    <?php include 'includes\rightbar.php'; ?>
    <?php
      if(isset($_SESSION['User'])){
        $session_email = $_SESSION['User'];
        $customerName = "SELECT * FROM customers WHERE email = '$session_email'";
        $uQ = $db->query($customerName);
        $customer = mysqli_fetch_assoc($uQ);
      }
    ?>
    <div class="page-title-banner-users users-container col-md-10">
      <span class="virtical-center"><h2 class="page-title">Welcome back to Tiki Trader, <?=$customer['full_name'];?></h2></span>
    </div>
    <?php include 'includes\rightbar.php'; ?>
    <!--<a class="btn btn-default btn-md" href="logout.php">Logout</a>-->
    <?php include 'includes\rightbar.php'; ?>
    <div class="col-md-10">
      <form class="col-md-10" action="index.html" style="margin-top: 50px;" method="post">
        <div class="col-md-6" style="margin-top:20px;">
          <h2>My Orders <a href="user_account_request.php?request=orders"><span class="glyphicon glyphicon-triangle-right"></span></a></h2>
          <p>View and track any orders placed on Tiki Trader using this account</p>
        </div>
        <div class="col-md-6" style="margin-top:20px;">
          <h2>My Address <a href="user_account_request.php?request=address"><span class="glyphicon glyphicon-triangle-right"></span></a></h2>
          <p>View and manage your delivery and billing address</p>
        </div>
        <hr class="col-md-12">
        <div class="col-md-6" style="margin-top:20px;">
          <h2>My Details <a href="user_account_request.php?request=details" class="user-account-arrow"><span class="glyphicon glyphicon-triangle-right"></span></a></h2>
          <p>Update your account details such as email address</p>
        </div>
        <div class="col-md-6" style="margin-top:20px;">
          <h2>Change Password <a href="user_account_request.php?request=pass"><span class="glyphicon glyphicon-triangle-right"></span></a></h2>
          <p>Securely change your account password</p>
        </div>
        <hr class="col-md-12">
        <a href="logout.php" class="pull-right btn website-secondary-button btn-lg" style="margin-top : 20px; margin-right: -30px;">Logout</a>
      </form>
    </div>
    <?php include 'includes\rightbar.php'; ?>










  <?php else: ?>
    <div class="account-page-nolog-titles col-md-12">

      <?php include 'includes\rightbar.php'; ?>
      <div class="page-title-banner-users users-container col-md-5">
        <span class="virtical-center"><h2 class="page-title">Create An Account:</h2></span>
      </div>

      <div class="page-title-banner-users users-container col-md-5">
        <h2 class="page-title">Sign In:</h2>
      </div>

      <?php include 'includes\rightbar.php'; ?>

    </div>

    <div class="account-page-nolog-titles users-container col-md-12">

      <?php include 'includes\rightbar.php'; ?>

      <div class="account-creation col-md-5">
        <p style="margin-top:20px; margin-bottom: 20px;">Creating an account is simple and easy, and will allow you to access your details when you return to the site.</p>
        <form class="user-account-details" action="#" method="post">
          <h3 class="col-md-12" style="margin-bottom: 20px;">Account Details</h3>
          <input type="hidden" name="create" value="create" id="create">
          <div class="form-group col-md-7">
            <label for="full_name">Full Name: *</label>
            <input class="form-control" id="full_name" type="text" name="full_name">
          </div>
          <div class="form-group col-md-7">
            <label for="email_2">Email: *</label>
            <input class="form-control" id="email_2" type="email" name="email_2">
          </div>
          <div class="form-group col-md-7">
            <label for="email_3">Confirm Email: *</label>
            <input class="form-control" id="email_3" type="email" name="email_3">
          </div>
          <h3 class="col-md-12" style="margin-bottom: 20px;">Address Details</h3>
          <div class="form-group col-md-6">
            <label for="street">Street Address 1: *</label>
            <input class="form-control" id="street" type="text" name="street">
          </div>
          <div class="form-group col-md-6">
            <label for="street2">Street Address 2:</label>
            <input class="form-control" id="street2" type="text" name="street2">
          </div>
          <div class="form-group col-md-6">
            <label for="city">City: *</label>
            <input class="form-control" id="city" type="text" name="city">
          </div>
          <div class="form-group col-md-6">
            <label for="county">County:</label>
            <input class="form-control" id="county" type="text" name="county">
          </div>
          <div class="form-group col-md-6">
            <label for="post_code">Post Code: *</label>
            <input class="form-control" id="post_code" type="text" name="post_code">
          </div>
          <div class="form-group col-md-6">
            <label for="country">Country: *</label>
            <input class="form-control" id="country" type="text" name="country">
          </div>
          <h3 class="col-md-12" style="margin-bottom: 20px;">Account Security</h3>
          <div class="form-group col-md-7">
            <label for="password_set">Password: *</label>
            <input class="form-control" id="password_set" type="password" name="password_set">
          </div>
          <div class="form-group col-md-7">
            <label for="password_confirm">Confirm Password: *</label>
            <input class="form-control" id="password_confirm" type="password" name="password_confirm">
          </div>
          <div class="form-group col-md-7">
            <input  class="form-check-input" id="terms" name="terms" type="checkbox"> I confirm that I have read the terms and conditions*
          </div>
          <div class="col-md-12">
            <input type="submit" name="" value="Create Account" class="btn website-action-button">
            <a href="user_account.php" class="btn website-secondary-button">Clear</a>
          </div>
        </form>
      </div>

      <div class="returning-login users-container col-md-5">
        <p style="margin-top:20px; margin-bottom: 20px;">Login to your user account.</p>
        <form class="returning-user" action="#" method="post">
          <h3 class="col-md-12" style="margin-bottom: 20px;">Login:</h3>
          <input type="hidden" name="login" value="login" id="login">
          <div class="form-group col-md-7">
            <label for="email_1">Email: *</label>
            <input class="form-control" id="email" type="email" name="email">
          </div>
          <div class="form-group col-md-7">
            <label for="password">Password: *</label>
            <input class="form-control" id="password" type="password" name="password">
          </div>
          <div class="col-md-12">
            <button class="btn btn-lg website-action-button" type="submit" name="button">Login</button>
          </div>
        </form>
      </div>


      <?php include 'includes\rightbar.php'; ?>

    </div>
  <?php endif; ?>
</div>

<?php
  include 'includes\footer.php';
