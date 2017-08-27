<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'/ecommerce/config.php';
  session_start();
  session_unset();
  session_destroy();
  header('Location: login.php');
 ?>
