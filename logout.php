<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'/ecommerce/config.php';
  session_start();
  session_unset();
  session_destroy();
  $_SESSION['success_flash'] = 'You have successfully logged out.';
  header('Location: user_account.php');
 ?>
