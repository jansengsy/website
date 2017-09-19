<?php

  require_once 'core/init.php';
  include 'includes/head.php';
  include 'includes/navigation.php';

?>

<div class="col-md-12">
  <!-- Store the step we are on -->
  <input type="hidden" name="step" id="step" value="1">
  <input type="hidden" name="errors" id="errors" value="">
  <input type="hidden" name="postage" id="postage" value="">

  <div class="col-md-1 col-sm-1"></div>

  <div class="col-md-10">
    <!-- Checkout step 1 -->
    <div class="step1" id="step1" style="display:block;">

      <!-- Banner -->
      <div class="col-md-12">
        <div class="page-title-banner col-md-12 col-sm-12">
          <h2 class="page-title">Shopping Cart:</h2>
        </div>
      </div>

      <!-- Top Buttons -->
      <div class="col-md-12 col-sm-12 checkout-top-bar" style="display:block;">
        <button class="btn btn-sm website-action-button" name="back_to_shop">< Continue Shopping</button>
        <button class="btn btn-sm website-secondary-button" name="clear_cart">Empty Cart</button>
      </div>

      <?php if($cart_id == ''): ?>
        <div class="col-md-12">
          <?php include 'includes\rightbar.php'; ?>
          <div class="bg-danger col-md-10">
            <p class="text-center text-danger">
              Your shopping cart is currently empty.
            </p>
          </div>
          <?php include 'includes\rightbar.php'; ?>
        </div>

        <?php include 'includes\rightbar.php'; ?>
        <div class="col-md-10">
          <?php include 'includes\widgets\recent.php'; ?>
        </div>
        <?php include 'includes\rightbar.php'; ?>
      <?php else : ?>
      <div class="col-md-12">
        <div class="col-md-12 col-sm-12 cart-container">
          <table class="table table-auto table-condensed">
            <thead>
              <th></th>
              <th>Item</th>
              <th>Price</th>
              <th>Quantity</th>
              <th>Sub Total</th>
            </thead>
            <tbody>
              <?php
                foreach($items as $item){
                  $product_id = $item['id'];
                  $productQ = $db->query("SELECT * FROM products WHERE id = '{$product_id}'");
                  $product = mysqli_fetch_assoc($productQ);
                  $available = $product['quantity'];
                  $imageTitle = $product['image'];
                  ?>
                  <tr>
                    <td align="center"><img src="<?=$imageTitle;?>" alt="product image" style="height:150px; width:auto;"></td>
                    <td><?=$product['title'];?></td>
                    <td><?=money($product['price']);?></td>
                    <td>
                      <button class="btn btn-xs btn-default" type="button" name="button" onclick="update_cart('removeone', '<?=$product['id'];?>');">-</button>
                      <?=$item['quantity'];?>
                      <?php if($item['quantity'] < $available) : ?>
                        <button class="btn btn-xs btn-default" type="button" name="button" onclick="update_cart('addone', '<?=$product['id'];?>');">+</button>
                      <?php endif; ?>
                    </td>
                    <td><?=money($item['quantity'] * $product['price']);?></td>
                  </tr>
              <?php
              $i++;
              //$item_count += $item['quantity'];
              //$sub_total += ($item['quantity'] * $product['price']);
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-md-12 col-sm-12">
        <div class="col-md-12 col-sm-12">
          <table class="table table-auto table-condensed text-right table-cart-total pull-right">
            <br>
            <thead class="totals-table-header">
              <th>Total Items</th>
              <th>Sub Total</th>
            </thead>
            <tbody>
              <tr>
                <td><?=$item_count;?></td>
                <td class="checkout-subtotal"><?=money($sub_total);?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <?php endif; ?>
    </div>
    <div class="col-md-1 col-sm-1"><?php include 'includes\rightbar.php'; ?></div>

    <!-- Checkout step 2 -->
    <div class="step2" id="step2" style="display:none;">

      <!-- Banner -->
      <div class="col-md-12">
        <div class="page-title-banner col-md-12 col-sm-12">
          <h2 class="page-title">Details:</h2>
        </div>
      </div>

      <!-- Top Buttons -->
      <div class="col-md-12 col-sm-12 checkout-top-bar" style="display:block;">
        <button class="btn btn-sm website-action-button" name="back_to_shop">< Continue Shopping</button>
      </div>

      <!-- Address Containers -->
      <div class="col-md-12 col-sm-12">

        <?php
          if(is_logged_in_customer()){
            $session_email = $_SESSION['User'];
            $addressQ = "SELECT * FROM customers WHERE email = '$session_email'";
            $uQ = $db->query($addressQ);
            $customerAddress = mysqli_fetch_assoc($uQ);
          }
        ?>

        <!-- Shipping Address -->
        <div class="shipping-address col-md-4 col-sm-4">
          <div class="page-title-banner-users users-container col-md-12 col-sm-12" style="margin-bottom: 30px; height: auto;">
            <span class="virtical-center"><h2 class="page-title">Shipping Address:</h2></span>
          </div>

          <form class="" action="#" method="post">
            <div class="name col-md-12" class="row">
              <label for="s_name">Full Name: *</label>
              <input class="form-control" type="text" name="s_name" id="s_name"
                      value="<?=((isset($customerAddress['full_name']))?$customerAddress['full_name']:'');?>">
            </div>

            <div class="name col-md-12" class="row">
              <label class="checkout-address-label" for="s_street1">Street Address 1: *</label>
              <input class="form-control" type="text" name="s_street1" id="s_street1"
                      value="<?=((isset($customerAddress['street1']))?$customerAddress['street1']:'');?>">
            </div>

            <div class="name col-md-12" class="row">
              <label class="checkout-address-label" for="s_street2">Street Address 2: </label>
              <input class="form-control" type="text" name="s_street2" id="s_street2"
                      value="<?=((isset($customerAddress['street2']))?$customerAddress['street2']:'');?>">
            </div>

            <div class="name col-md-12" class="row">
              <label class="checkout-address-label" for="s_city">City: *</label>
              <input class="form-control" type="text" name="s_city" id="s_city"
                      value="<?=((isset($customerAddress['city']))?$customerAddress['city']:'');?>">
            </div>

            <div class="name col-md-12" class="row">
              <label class="checkout-address-label" for="s_county">County: </label>
              <input class="form-control" type="text" name="s_county" id="s_county"
                      value="<?=((isset($customerAddress['county']))?$customerAddress['county']:'');?>">
            </div>

            <div class="name col-md-12" class="row">
              <label class="checkout-address-label" for="s_post_code">Post Code: *</label>
              <input class="form-control" type="text" name="s_post_code" id="s_post_code"
                      value="<?=((isset($customerAddress['post_code']))?$customerAddress['post_code']:'');?>">
            </div>

            <div class="name col-md-12" class="row">
              <label class="checkout-address-label" for="s_country">Country: *</label>
              <input class="form-control" type="text" name="s_country" id="s_country"
                      value="<?=((isset($customerAddress['country']))?$customerAddress['country']:'');?>">
            </div>
          </form>
        </div>

        <!-- Billing Address -->
        <div class="billing-address col-md-4 col-sm-4">
          <div class="page-title-banner-users users-container col-md-12 col-sm-12" style="margin-bottom: 30px; height: auto;">
            <span class="virtical-center"><h2 class="page-title">Billing Address:</h2></span>
          </div>

          <form class="" action="#" method="post" style="margin-top: 20px;">
            <div class="name col-md-12" class="row">
              <label for="b_name">Full Name: *</label>
              <input class="form-control" type="text" name="b_name" id="b_name">
            </div>

            <div class="name col-md-12" class="row">
              <label class="checkout-address-label" for="b_street1">Street Address 1: *</label>
              <input class="form-control" type="text" name="b_street1" id="b_street1">
            </div>

            <div class="name col-md-12" class="row">
              <label class="checkout-address-label" for="b_street2">Street Address 2: </label>
              <input class="form-control" type="text" name="b_street2" id="b_street2">
            </div>

            <div class="name col-md-12" class="row">
              <label class="checkout-address-label" for="b_city">City: *</label>
              <input class="form-control" type="text" name="b_city" id="b_city">
            </div>

            <div class="name col-md-12" class="row">
              <label class="checkout-address-label" for="b_county">County: </label>
              <input class="form-control" type="text" name="b_county" id="b_county">
            </div>

            <div class="name col-md-12" class="row">
              <label class="checkout-address-label" for="b_post_code">Post Code: *</label>
              <input class="form-control" type="text" name="b_post_code" id="b_post_code">
            </div>

            <div class="name col-md-12" class="row">
              <label class="checkout-address-label" for="b_country">Country: *</label>
              <input class="form-control" type="text" name="b_country" id="b_country">
            </div>

            <div class="name col-md-12" class="row" style="margin-top: 15px;">
              <label class="same-billing" for="same-billing">Billing is the same as Shipping *</label>
              <input class="form-check-input" type="checkbox" name="same-billing" id="same-billing">
            </div>
          </form>
        </div>


        <div class="col-md-4 col-sm-4">
          <div class="col-md-12 col-sm-12">
            <table class="table table-auto table-condensed text-right table-cart-total pull-right" style="width:100%; vertical-align: bottom;">
              <br>
              <thead class="totals-table-header">
                <th>Total Items</th>
                <th>Sub Total</th>
              </thead>
              <tbody>
                <tr>
                  <td><?=$item_count;?></td>
                  <td class="checkout-subtotal"><?=money($sub_total);?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-1 col-sm-1"><?php include 'includes\rightbar.php'; ?></div>

    <!-- Checkout step 3 -->
    <div class="step3" id="step3" style="display:none;">

      <!-- Banner -->
      <div class="col-md-12">
        <div class="page-title-banner col-md-12 col-sm-12">
          <h2 class="page-title">Delivery:</h2>
        </div>
      </div>

      <!-- Top Buttons -->
      <div class="col-md-12 col-sm-12 checkout-top-bar" style="display:block;">
        <button class="btn btn-sm website-action-button" name="back_to_shop">< Continue Shopping</button>
      </div>

      <!-- Shipping options table -->
      <div class="col-md-12 col-sm-12">
        <table class="table table-auto table-condensed">
          <thead>
            <th>Delivery Option</th>
            <th>Estimated Delivery</th>
            <th>Cost</th>
            <th></th>
          </thead>
          <tbody>
            <tr>
              <td>Economy Delivery (2-5 Days)</td>
              <td><?= Date('d/m/y', strtotime("+2 days")) . " - " . Date('d/m/y', strtotime("+5 days")); ?></td>
              <td>£0.67</td>
              <td><?= "<input type='checkbox' value='test' id='economy_delivery'></input>"; ?></td>
            </tr>
            <tr>
              <td>Standard Delivery (1-3 Days)</td>
              <td><?= Date('d/m/y', strtotime("+1 days")) . " - " . Date('d/m/y', strtotime("+3 days")); ?></td>
              <td>£0.76</td>
              <td><?= "<input type='checkbox' value='test' id='standard_delivery'></input>"; ?></td>
            </tr>
            <tr>
              <td>First Class Delivery (1 Day)</td>
              <td><?= Date('d/m/y', strtotime("+1 days")); ?></td>
              <td>£1.00</td>
              <td><?= "<input type='checkbox' value='test' id='first_delivery'></input>"; ?></td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="col-md-12 col-sm-12">
        <div class="col-md-12 col-sm-12">
          <table class="table table-auto table-condensed text-right table-cart-total pull-right">
            <br>
            <thead class="totals-table-header">
              <th>Total Items</th>
              <th>Sub Total</th>
            </thead>
            <tbody>
              <tr>
                <td><?=$item_count;?></td>
                <td class="checkout-subtotal" id="subTotal"><?=money($sub_total);?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>
    <div class="col-md-1 col-sm-1"><?php include 'includes\rightbar.php'; ?></div>

    <!-- Checkout step 4 -->
    <div class="step4" id="step4" style="display:none;">

      <!-- Banner -->
      <div class="col-md-12">
        <div class="page-title-banner col-md-12 col-sm-12">
          <h2 class="page-title">Checkout:</h2>
        </div>
      </div>

      <!-- Top Buttons -->
      <div class="col-md-12 col-sm-12 checkout-top-bar" style="display:block;">
        <button class="btn btn-sm website-action-button" name="back_to_shop">< Continue Shopping</button>
      </div>
      <form class="payment-form" action="thankyou.php" id="payment-form" method="POST">
        <div class="col-md-4 col-sm-4">

          <input type="hidden" name="final_full_name" id="final_full_name">
          <input type="hidden" name="final_street1" id="final_street1" data-stripe="address_line1">
          <input type="hidden" name="final_street2" id="final_street2" data-stripe="address_line2">
          <input type="hidden" name="final_city" id="final_city" data-stripe="address_city">
          <input type="hidden" name="final_county" id="final_county" data-stripe="address_state">
          <input type="hidden" name="final_post_code" id="final_post_code" data-stripe="address_zip">
          <input type="hidden" name="final_country" id="final_country" data-stripe="address_country">
          <input type="hidden" name="final_email" id="final_email" value="KeessQQ@gmail.com">

          <input type="hidden" name="sub_total" value="<?=$sub_total;?>">
          <input type="hidden" name="cart_id" value="<?=$cart_id;?>">
          <input type="hidden" name="description" value="<?=$item_count . ' item' . (($item_count > 1)?'s':'') . ' from Tiki Trader';?>">

          <div class="page-title-banner-users users-container col-md-12 col-sm-12" style="margin-bottom: 30px; height: auto;">
            <span class="virtical-center"><h2 class="page-title">Credit Card Details:</h2></span>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="col-md-12"></div>
              <div class="form-group col-md-8">
                <label for="name">Name on Card: *</label>
                <input type="text" id="name" class="form-control" data-stripe="name">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="col-md-12"></div>
              <div class="form-group col-md-8 has-feedback">
                <label for="number">Card Number: *</label>
                <input type="text" id="number" class="form-control" data-stripe="number">
                <i style="margin-right: 15px;" class="glyphicon glyphicon-credit-card pull-right form-control-feedback"></i>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 ">
              <div class="col-md-12"></div>
              <div class="form-group col-md-4">
                <label for="exp-month">Expire Month: *</label>
                <select id="exp-month" class="form-control" data-stripe="exp_month">
                  <option value=""></option>
                  <?php for($i = 1; $i < 13; $i++): ?>
                    <option value="<?=$i;?>"><?=$i;?></option>
                  <?php endfor; ?>
                </select>
              </div>
              <div class="form-group col-md-4">
                <label for="exp-year">Expire year: *</label>
                <select id="exp-year" class="form-control" data-stripe="exp_year">
                  <option value=""></option>
                  <?php $year = date("Y"); ?>
                  <?php for($i = 0; $i < 11; $i++): ?>
                    <option value="<?=$year + $i;?>"><?= $year + $i;?></option>
                  <?php endfor; ?>
                </select>
              </div>
            </div>
          </div>
          <div class="col-md-12"></div>
          <div class="form-group col-md-3">
            <label for="cvc">CVC: *</label>
            <input type="text" id="cvc" class="form-control" data-stripe="cvc">
          </div>
        </div>

      <div class="col-md-8 col-sm-8">

        <div class="page-title-banner-users users-container col-md-12 col-sm-12" style="margin-bottom: 30px; height: auto;">
          <span class="virtical-center"><h2 class="page-title">Order Summary:</h2></span>
        </div>

        <div class="col-md-12 col-sm-12">
          <input type="hidden" id="postCharge" value="">
          <table class="table table-auto table-condensed pull-right">
            <br>
            <thead class="totals-table-header">
              <th>Total Items</th>
              <th>Sub Total</th>
              <th>Postage</th>
              <th>Grand Total</th>
            </thead>
            <tbody>
              <tr>
                <td><?=$item_count;?></td>
                <td><?=$sub_total;?></td>
                <td id="postal"></td>
                <td class="checkout-subtotal" id="grandTotal"></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="col-md-4 col-sm-4">

        <div class="page-title-banner-users users-container col-md-12 col-sm-12" style="margin-bottom: 30px; height: auto;">
          <span class="virtical-center"><h2 class="page-title">Delivery Address:</h2></span>
        </div>

        <div style="margin-left: 20px;">
          <table class="table table-condensed table-bordered table-striped">
            <address>
              <p id="checkout_address_name_s"></p>
              <p id="checkout_address_street1_s"></p>
              <p id="checkout_address_street2_s"></p>
              <p id="checkout_address_city_s"></p>
              <p id="checkout_address_post_code_s"></p>
              <p id="checkout_address_country_s"></p>
            </address>
          </table>
        </div>
      </div>

      <div class="col-md-4 col-sm-4">

        <div class="page-title-banner-users users-container col-md-12 col-sm-12" style="margin-bottom: 30px; height: auto;">
          <span class="virtical-center"><h2 class="page-title">Shipping Address:</h2></span>
        </div>

        <div style="margin-left: 20px;">
          <table class="table table-condensed table-bordered table-striped">
            <address>
              <p id="checkout_address_name_b"></p>
              <p id="checkout_address_street1_b"></p>
              <p id="checkout_address_street2_b"></p>
              <p id="checkout_address_city_b"></p>
              <p id="checkout_address_post_code_b"></p>
              <p id="checkout_address_country_b"></p>
            </address>
          </table>
        </div>
      </div>

    </div>
    <div class="col-md-1 col-sm-1"><?php include 'includes\rightbar.php'; ?></div>

    <div class="checkout-controls col-md-12 col-sm-12">
      <button class="btn btn-lg pull-right website-action-button checkout-button" type="submit" name="checkout_button" id="checkout_button"
              onclick="checkout();">Checkout</button>
      <button class="btn btn-lg pull-right checkout-button website-action-button" type="button" name="next_button" id="next_button"
              onclick="step_change_forward();">Next</button>
      <button class="btn btn-lg pull-right website-secondary-button checkout-button" type="button" name="back_button" id="back_button"
              onclick="step_change_back();">Back</button>
    </div>
    </form>
    <?php include 'includes\rightbar.php'; ?>
  </div>

  <div class="col-md-1 col-sm-1"></div>
</div>

<div class="col-md-12 col-sm-12">
  <?php include 'includes/footer.php'; ?>
</div>

<script type="text/javascript">

  document.addEventListener('DOMContentLoaded', function() {
    jQuery('#back_button').css("display", "none");
    jQuery('#checkout_button').css("display", "none");
  }, false);

  $('#same-billing').change(function(){
    if($('#same-billing').is(":checked")){
      document.getElementById("b_name").value = document.getElementById("s_name").value;
      document.getElementById("b_street1").value = document.getElementById("s_street1").value;
      document.getElementById("b_street2").value = document.getElementById("s_street2").value;
      document.getElementById("b_city").value = document.getElementById("s_city").value;
      document.getElementById("b_county").value = document.getElementById("s_county").value;
      document.getElementById("b_post_code").value = document.getElementById("s_post_code").value;
      document.getElementById("b_country").value = document.getElementById("s_country").value;
    }else{
      document.getElementById("b_name").value = '';
      document.getElementById("b_street1").value = '';
      document.getElementById("b_street2").value = '';
      document.getElementById("b_city").value = '';
      document.getElementById("b_county").value = '';
      document.getElementById("b_post_code").value = '';
      document.getElementById("b_country").value = '';
    }
  });

  $('#first_delivery').change(function(){

    $('#postage').value = 1;

    $("#standard_delivery").prop('checked', false);
    $("#economy_delivery").prop('checked', false);

    if($('#first_delivery').is(":checked")){
      $('#subTotal').html('<?= money($sub_total + 1); ?>');
    } else {
      $('#subTotal').html('<?= money($sub_total); ?>');
    }

    $('#postCharge').html('<?= money(1); ?>');
  });

  $('#standard_delivery').change(function(){

    $('#postage').value = 0.76;

    $("#first_delivery").prop('checked', false);
    $("#economy_delivery").prop('checked', false);

    if($('#standard_delivery').is(":checked")){
      $('#subTotal').html('<?= money($sub_total + 0.76); ?>');
    } else {
      $('#subTotal').html('<?= money($sub_total); ?>');
    }

    $('#postCharge').html('<?= money(0.76); ?>');
  });

  $('#economy_delivery').change(function(){

    $('#postage').value = 0.67;

    $("#standard_delivery").prop('checked', false);
    $("#first_delivery").prop('checked', false);

    if($('#economy_delivery').is(":checked")){
      $('#subTotal').html('<?= money($sub_total + 0.67); ?>');
    } else {
      $('#subTotal').html('<?= money($sub_total); ?>');
    }

    $('#postCharge').html('<?= money(0.67); ?>');
  });


  function validateAddress(){

    var errors = [];
    var required = ["s_name", "s_street1", "s_city", "s_post_code", "s_country"
                    ,"b_name", "b_street1", "b_city", "b_post_code", "b_country"]

    for(var i = 0; i < required.length; i++){
      var id = required[i];
      if(document.getElementById(id) == null || document.getElementById(id).value == ''){
        errors[0] = "Please fill out all required fields.";
      }
    }

    if(errors.length == 0){
      document.getElementById("final_full_name").value = document.getElementById("b_name").value;
      document.getElementById("final_street1").value = document.getElementById("b_street1").value;
      document.getElementById("final_street2").value = document.getElementById("b_street2").value;
      document.getElementById("final_city").value = document.getElementById("b_city").value;
      document.getElementById("final_county").value = document.getElementById("b_county").value;
      document.getElementById("final_post_code").value = document.getElementById("b_post_code").value;
      document.getElementById("final_country").value = document.getElementById("b_country").value;

      $('#checkout_address_name_s').text(document.getElementById("s_name").value);
      $('#checkout_address_street1_s').text(document.getElementById("s_street1").value);
      $('#checkout_address_street2_s').text(document.getElementById("s_street2").value);
      $('#checkout_address_city_s').text(document.getElementById("s_city").value);
      $('#checkout_address_post_code_s').text(document.getElementById("s_post_code").value);
      $('#checkout_address_country_s').text(document.getElementById("s_country").value);

      $('#checkout_address_name_b').text(document.getElementById("b_name").value);
      $('#checkout_address_street1_b').text(document.getElementById("b_street1").value);
      $('#checkout_address_street2_b').text(document.getElementById("b_street2").value);
      $('#checkout_address_city_b').text(document.getElementById("b_city").value);
      $('#checkout_address_post_code_b').text(document.getElementById("b_post_code").value);
      $('#checkout_address_country_b').text(document.getElementById("b_country").value);

      return true;
    } else {
      var error = errors[0];
      alert("Invalid Address: " + error);
      return false;
    }

  }

  function step_change_forward(){

    var step = parseInt(document.getElementById("step").value);

    if(step == 1){
      jQuery('#step1').css("display", "none");
      jQuery('#step3').css("display", "none");
      jQuery('#step4').css("display", "none");

      jQuery('#step2').css("display", "block");
      jQuery('#back_button').css("display", "block");
      jQuery('#next_button').css("display", "block");
      jQuery('#checkout_button').css("display", "none");
    } else if (step == 2) {

      if(validateAddress()){
        jQuery('#step1').css("display", "none");
        jQuery('#step2').css("display", "none");
        jQuery('#step4').css("display", "none");

        jQuery('#step3').css("display", "block");
        jQuery('#back_button').css("display", "block");
        jQuery('#next_button').css("display", "block");
        jQuery('#checkout_button').css("display", "none");
      } else {
        step -= 1;
      }


    } else if (step == 3) {

      if($('#economy_delivery').is(":checked") || $('#first_delivery').is(":checked") || $('#standard_delivery').is(":checked")){
        jQuery('#step1').css("display", "none");
        jQuery('#step2').css("display", "none");
        jQuery('#step3').css("display", "none");

        jQuery('#step4').css("display", "block");
        jQuery('#back_button').css("display", "block");
        jQuery('#next_button').css("display", "none");
        jQuery('#checkout_button').css("display", "block");

        var grand = $('#subTotal').text();
        $('#grandTotal').html(grand);
        var post = $('#postCharge').text();
        $('#postal').html(post);
      } else {
        alert("Please select a delivery option");
        step -= 1;
      }


    } else if (step == 4) {
      alert("Time to checkout");
    }

    if(step < 4){
      document.getElementById("step").value = step += 1;
    }
  }

  function step_change_back(){

    var step = parseInt(document.getElementById("step").value);

    if(step == 1){

    } else if (step == 2) {
      jQuery('#step2').css("display", "none");
      jQuery('#step3').css("display", "none");
      jQuery('#step4').css("display", "none");

      jQuery('#step1').css("display", "block");
      jQuery('#back_button').css("display", "none");
      jQuery('#next_button').css("display", "block");
      jQuery('#checkout_button').css("display", "none");
    } else if (step == 3) {
      jQuery('#step1').css("display", "none");
      jQuery('#step4').css("display", "none");
      jQuery('#step3').css("display", "none");

      jQuery('#step2').css("display", "block");
      jQuery('#back_button').css("display", "block");
      jQuery('#next_button').css("display", "block");
      jQuery('#checkout_button').css("display", "none");
    } else if (step == 4) {
      jQuery('#step1').css("display", "none");
      jQuery('#step2').css("display", "none");
      jQuery('#step4').css("display", "none");

      jQuery('#step3').css("display", "block");
      jQuery('#back_button').css("display", "block");
      jQuery('#next_button').css("display", "block");
      jQuery('#checkout_button').css("display", "none");
    }

    if(step >= 2){
      document.getElementById("step").value = step -= 1;
    }
  }

  $('#number').on('keyup', function(e){
      var val = $(this).val();
      var newval = '';
      val = val.replace(/\s/g, '');
      for(var i=0; i < val.length; i++) {
          if(i%4 == 0 && i > 0) newval = newval.concat(' ');
          newval = newval.concat(val[i]);
      }
      $(this).val(newval);
  })

  // STRIPE CODE
  Stripe.setPublishableKey('<?= STRIPE_PUBLIC; ?>');

  function stripeResponseHandler(status, response){
    var $form = $('#payment-form');

    if (response.error) {
      $form.find('#payment-errors').text(response.error.message);
      $form.find('button').prop('disabled', false);
    } else {
      var token = response.id;

      //jQuery('#stripeToken').val(token);
      $form.append($('<input type="hidden" name="stripeToken" />').val(token));

      $form.get(0).submit();
    }
  }

  jQuery(function($) {

    $('#payment-form').submit(function(event) {
      var $form = $(this);

      $form.find('button').prop('disabled', true);

      Stripe.card.createToken($form, stripeResponseHandler);

      /*
      Stripe.card.createToken({
        number: $('#number').val(),
        cvc: $('#cvc').val(),
        exp_month: $('#exp-month').val(),
        exp_year: $('#exp-year').val()
      }, stripeResponseHandler);
      */

      return false;
    });
  });



</script>
