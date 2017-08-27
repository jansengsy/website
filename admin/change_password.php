<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'/ecommerce/core/init.php';

  if(!is_logged_in()){
    header('Location: login.php');
  }

  include 'includes/head.php';

  $hashed = $user_data['password'];
  $user_id = $user_data['id'];

  $old_password = ((isset($_POST['old_password']))?sanitize($_POST['old_password']):'');
  $old_password = trim($old_password);

  $npassword = ((isset($_POST['new_password']))?sanitize($_POST['new_password']):'');
  $npassword = trim($npassword);
  $confirm = ((isset($_POST['confirm_password']))?sanitize($_POST['confirm_password']):'');
  $confirm = trim($confirm);

  $new_hashed = password_hash($npassword, PASSWORD_DEFAULT);

  $errors = array();
?>

<style>
  body{
    background-image: url('/ecommerce/images/headerlogo/background_m.png');
    background-size: 100vw 100vh;
    background-attachment: fixed;
  }
</style>

<div id="login-form">
  <div>
    <?php
      if($_POST){
        // Form validation
        if(empty($_POST['old_password'])|| empty($_POST['new_password']) || empty($_POST['confirm_password'])){
          $errors[] = 'You must provide fill out all fields.';
        }

        // Password is more than 6 characters
        if(strlen($npassword) < 6){
          $errors[] = 'Passwords must be at least six characters in length.';
        }

        // Check password match
        if($npassword != $confirm){
          $errors[] = 'Passwords do not match.';
        }

        // Verify password
        if(!password_verify($old_password, $hashed)){
          $errors[] = 'Incorrect old password.';
        }

        // Check for errors
        if(!empty($errors)){
          // Display errors
          echo display_errors($errors);
        } else {
          // Change password
          $insertSQL = "UPDATE users SET password = '$new_hashed' WHERE id = '$user_id'";
          $db->query($insertSQL);
          $_SESSION['success_flash'] = 'Password had been changed.';
          header('Location: index.php');
        }
      }
    ?>
  </div>
  <h2 class="text-center">Change Password</h2><hr>
  <form action="change_password.php" method="post">
    <div class="form-group">
      <label for="old_password">Old Password:</label>
      <input type="password" name="old_password" id="old_password" class="form-control" value="<?=$old_password;?>">
    </div>
    <div class="form-group">
      <label for="new_password">New Password:</label>
      <input type="password" name="new_password" id="new_password" class="form-control" value="<?=$npassword;?>">
    </div>
    <div class="form-group">
      <label for="confirm_password">Cofirm Password:</label>
      <input type="password" name="confirm_password" id="confirm_password" class="form-control" value="<?=$confirm;?>">
    </div>
    <div class="form-group">
      <a href="index.php" class="btn btn-default">Cancel</a>
      <input type="submit" name="loginButton" value="Login" class="btn btn-primary">
    </div>
  </form>
  <p class="text-right"><a href="/ecommerce/index.php" alt="home">Visit Site</a></p>
</div>

<?php
  include 'includes/footer.php';
?>
