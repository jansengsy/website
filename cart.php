<?php

  require_once 'core/init.php';
  include 'includes/head.php';
  include 'includes/navigation.php';

  if($cart_id != ''){
    $cartQ = $db->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
    $result = mysqli_fetch_assoc($cartQ);
    $items = json_decode($result['items'], true);
    $i = 1;
    $sub_total = 0;
    $item_count = 0;
  }
?>

<div class="col-md-12" id="cart-contents">
    <div class="col-md-12">
      <?php include 'includes\rightbar.php'; ?>
      <div class="page-title-banner col-md-10">
        <h2 class="page-title">Shopping Cart:</h2>
      </div>
      <?php include 'includes\rightbar.php'; ?>
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
        <?php include 'includes\rightbar.php'; ?>

        <div class="col-md-10 col-sm-10 cart-container">

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
              $item_count += $item['quantity'];
              $sub_total += ($item['quantity'] * $product['price']);
              }
              ?>
            </tbody>
          </table>
        </div>

        <?php include 'includes\rightbar.php'; ?>
      </div>
      <div class="col-md-12 col-sm-12">
        <?php include 'includes\rightbar.php'; ?>
        <div class="col-md-10 col-sm-10">
          <table class="table table-auto table-condensed text-right table-cart-total pull-right">
            <br>
            <thead class="totals-table-header">
              <th>Total Items</th>
              <th>Sub Total</th>
            </thead>
            <tbody>
              <tr>
                <td><?=$item_count;?></td>
                <td class="bg-success"><?=money($sub_total);?></td>
              </tr>
            </tbody>
          </table>
        </div>
        <?php include 'includes\rightbar.php'; ?>
      </div>
      <div class="col-md-12 col-sm-12">
        <?php include 'includes\rightbar.php'; ?>
        <div class="col-md-10 col-sm-10">
          <button type="button" class="btn btn-primary btn-lg pull-right checkout-button" data-toggle="modal" data-target="#checkoutModal">
            <span class="glyphicon glyphicon-shopping-cart"></span> Checkout
          </button>
        </div>
        <?php include 'includes\rightbar.php'; ?>
      </div>
      <div class="col-md-12 col-sm-12"></div>
    </div>

      <!-- Modal -->
      <div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel">
        <div class="modal-dialog modal-md" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="checkoutModalLabel">Shipping Address</h4>
            </div>
            <div class="modal-body">
              <div class="row">
                <form action="thankyou.php" method="POST" id="payment-form">
                  <input type="hidden" name="sub_total" value="<?=$sub_total;?>">
                  <input type="hidden" name="cart_id" value="<?=$cart_id;?>">
                  <input type="hidden" name="description" value="<?=$item_count . ' item' . (($item_count > 1)?'s':'') . ' from Tiki Trader';?>">

                  <?php
                    if(is_logged_in_customer()){
                      $session_email = $_SESSION['User'];
                      $addressQ = "SELECT * FROM customers WHERE email = '$session_email'";
                      $uQ = $db->query($addressQ);
                      $customerAddress = mysqli_fetch_assoc($uQ);
                      $name = '';
                      for( $i = 0; $i < strlen($customerAddress['full_name']); $i++ ) {
                          $name .= $customerAddress['full_name'][$i];
                      }
                    }
                  ?>
                  <div id="step1" style="display:block;">
                    <div class="form-group col-md-6">
                      <label for="full_name">Full Name:</label>
                      <input class="form-control" id="full_name" type="text" name="full_name" value="<?=((isset($customerAddress['full_name']))?$customerAddress['full_name']:'');?>">
                    </div>
                    <div class="form-group col-md-6">
                      <label for="email">Email:</label>
                      <input class="form-control" id="email" type="email" name="email" value="<?=((isset($customerAddress))?$customerAddress['email']:'');?>">
                    </div>
                    <div class="form-group col-md-6">
                      <label for="street">Street Address 1:</label>
                      <input class="form-control" id="street" type="text" name="street" data-stripe="address_line1" value="<?=((isset($customerAddress))?$customerAddress['street1']:'');?>">
                    </div>
                    <div class="form-group col-md-6">
                      <label for="street2">Street Address 2:</label>
                      <input class="form-control" id="street2" type="text" name="street2" data-stripe="address_line2" value="<?=((isset($customerAddress))?$customerAddress['street2']:'');?>">
                    </div>
                    <div class="form-group col-md-6">
                      <label for="city">City:</label>
                      <input class="form-control" id="city" type="text" name="city" data-stripe="address_state" value="<?=((isset($customerAddress))?$customerAddress['city']:'');?>">
                    </div>
                    <div class="form-group col-md-6">
                      <label for="county">County:</label>
                      <input class="form-control" id="county" type="text" name="county" data-stripe="address_state" value="<?=((isset($customerAddress))?$customerAddress['county']:'');?>">
                    </div>
                    <div class="form-group col-md-6">
                      <label for="post_code">Post Code:</label>
                      <input class="form-control" id="post_code" type="text" name="post_code" data-stripe="address_zip" value="<?=((isset($customerAddress))?$customerAddress['post_code']:'');?>">
                    </div>
                    <div class="form-group col-md-6">
                      <label for="country">Country:</label>
                      <input class="form-control" id="country" type="text" name="country" data-stripe="address_country" value="<?=((isset($customerAddress))?$customerAddress['country']:'');?>">
                    </div>
                  </div>
                  <div id="step2" style="display:none;" class="">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="col-md-3"></div>
                        <div class="form-group col-md-6">
                          <label for="name">Name on Card: *</label>
                          <input type="text" id="name" class="form-control" data-stripe="name">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="col-md-3"></div>
                        <div class="form-group col-md-6 has-feedback">
                          <label for="number">Card Number: *</label>
                          <input type="text" id="number" class="form-control" data-stripe="number">
                          <i style="margin-right: 15px;" class="glyphicon glyphicon-credit-card pull-right form-control-feedback"></i>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12 ">
                        <div class="col-md-3"></div>
                        <div class="form-group col-md-3">
                          <label for="exp-month">Expire Month: *</label>
                          <select id="exp-month" class="form-control" data-stripe="exp_month">
                            <option value=""></option>
                            <?php for($i = 1; $i < 13; $i++): ?>
                              <option value="<?=$i;?>"><?=$i;?></option>
                            <?php endfor; ?>
                          </select>
                        </div>
                        <div class="form-group col-md-3">
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
                    <div class="col-md-3"></div>
                    <div class="form-group col-md-3">
                      <label for="cvc">CVC: *</label>
                      <input type="text" id="cvc" class="form-control" data-stripe="cvc">
                    </div>
                  </div>

              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" onclick="check_address();" id="next_button">Next>></button>

              <button type="button" class="btn btn-primary" onclick="back_address();" id="back_button" style="display:none;"><< Back</button>
              <button type="submit" class="btn btn-primary" id="checkout_button" style="display:none;">Checkout</button>
            </div>
            </form>
          </div>
    <?php endif; ?>
  </div>
</div>

<script>
  function back_address(){
    jQuery('#payment-errors').html('');
    jQuery('#step1').css("display","block");
    jQuery('#step2').css("display","none");
    jQuery('#next_button').css("display","inline-block");
    jQuery('#checkout_button').css("display","none");
    jQuery('#back_button').css("display","none");
    jQuery('#checkoutModalLabel').html("Shipping Address");
  }

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

</script>
<?php

  include 'includes/footer.php';

?>
