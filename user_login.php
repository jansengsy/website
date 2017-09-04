<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'/ecommerce/core/init.php';
  include 'includes/head.php';

  $email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
  $email = trim($email);
  $password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
  $password = trim($password);

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
          login($user_id);
        }
      }
    ?>
  </div>
  <h2 class="text-center">Login</h2><hr>
  <form action="login.php" method="post">
    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" name="email" id="email" class="form-control" value="<?=$email;?>">
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" name="password" id="password" class="form-control" value="<?=$password;?>">
    </div>
    <div class="form-group">
      <input type="submit" name="loginButton" value="Login" class="btn btn-primary">
    </div>
  </form>
  <p class="text-right"><a href="/ecommerce/index.php" alt="home">Visit Site</a></p>
</div>

<?php
  include 'includes/footer.php';
?>
