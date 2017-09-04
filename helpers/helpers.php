<?php
  function display_errors($errors){
    $display = '<ul class="bg-danger">';
    foreach($errors as $error){
      $display .= '<li class="text-danger">'.$error.'</li>';
    }
    $display .= '</ul>';
    return $display;
  }

  function sanitize($dirty){
    return htmlentities($dirty, ENT_QUOTES, "UTF-8");
  }

  function money($price){
    return '£' . number_format($price,2);
  }

  function login($user_id){
    $_SESSION['SBUser'] = $user_id;
    global $db;
    $date = date("Y-m-d H:i:s");
    $db->query("UPDATE users SET last_login = '$date' WHERE id = '$user_id'");
    $_SESSION['success_flash'] = 'You are now logged in!';
    header('Location: index.php');
  }

  function user_login($user_id){
    $_SESSION['User'] = $user_id;
    global $db;
    $date = date("Y-m-d H:i:s");
    $db->query("UPDATE customers SET last_login = '$date' WHERE email = '$user_id'");
    $_SESSION['success_flash'] = 'You are now logged in!';
    redirect('user_account.php');
  }

  function is_logged_in(){
    if(isset($_SESSION['SBUser']) && $_SESSION['SBUser'] > 0){
      return true;
    }
    return  false;
  }

  function is_logged_in_customer(){
    if(isset($_SESSION['User']) && $_SESSION['User'] != ''){
      return true;
    }
    return  false;
  }

  function login_error_redirect($url = 'login.php'){
    $_SESSION['error_flash'] = 'You must be logged in to access this page.';
    header('Location: ' . $url);
  }

  function permission_error_redirect($url = 'login.php'){
    $_SESSION['error_flash'] = 'You do not have permission to access this page.';
    header('Location: ' . $url);
  }

  function has_permission($permission = 'admin'){
    global $user_data;
    $permissions = explode(',', $user_data['permissions']);
    if(in_array($permission, $permissions, true)){
      return true;
    }
    return false;
  }

  function pretty_date($date){
    return date("M d, Y h:i A", strtotime($date));
  }

  function redirect($url)
  {
    if (!headers_sent())
    {
        header('Location: '.$url);
        exit;
        }
    else
        {
        echo '<script type="text/javascript">';
        echo 'window.location.href="'.$url.'";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
        echo '</noscript>'; exit;
    }
  }
 ?>
