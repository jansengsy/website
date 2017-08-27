<?php
  define('BASEURL', $_SERVER['DOCUMENT_ROOT'].'/eCommerce/');
  define('CART_COOKIE', 'SBwi72UCklwiqzzz2');
  define('CART_COOKIE_EXPIRE', time() + (86400 * 30)); //86400 seconds per day

  define('CURRENCY', 'gbp'); // Using pounds - change depending on website
  define('CHECKOUTMODE', 'TEST'); // change test to LIVE when going live...DON'T FORGET!!

  define('STRIPE_PRIVATE', 'sk_test_MO4qVQh3Cyy8FsFCsk7Qpis0');
  define('STRIPE_PUBLIC', 'pk_test_SyF4vwjORJFDPeTAfSVRYKLs');

  if(CHECKOUTMODE == 'LIVE'){
    define('STRIPE_PRIVATE', '');
    define('STRIPE_PUBLIC', '');
  }
 ?>
