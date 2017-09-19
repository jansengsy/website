</div>

<div class="col-md-12 col-sm-12 footer-container">

  <div class="col-md-12 col-sm-12" style="margin-bottom: 15px; margin-top:15px;">
    <footer class="text-center footer" id="footer">&copy; Copyright 2017 Trading Post</footer>
  </div>

  <div class="col-md-3 col-sm-3">
    <?php include 'includes\rightbar.php'; ?>
  </div>

  <div class="col-md-6 col-sm-6">
    <div class="col-md-4 col-sm-4">
      <h3>Tiki Trader</h3>
      <ul>
        <li><a href="#">Shop</a></li>
        <li><a href="#">About</a></li>
        <li><a href="#">Contact</a></li>
      </ul>
    </div>
    <div class="col-md-4 col-sm-4">
      <h3>Help</h3>
      <ul>
        <li><a href="#">Delivery</a></li>
        <li><a href="#">Privacy Policy</a></li>
        <li><a href="#">Terms and Conditions</a></li>
      </ul>
    </div>
    <div class="col-md-4 col-sm-4">
      <h3>Products</h3>
      <ul>
        <li><a href="#">Magic: the Gathering</a></li>
        <li><a href="#">Yu-Gi-Oh!</a></li>
        <li><a href="#">Pokemon</a></li>
      </ul>
    </div>
  </div>

  <div class="col-md-3 col-sm-3">
    <?php include 'includes\rightbar.php'; ?>
  </div>

  <div class="col-md-1 col-sm-1">
    <?php include 'includes\rightbar.php'; ?>
  </div>
</div>

<script type="text/javascript">

  //functon for populating the modals
  function detailsmodal(id){
    var data = {"id" : id};
    jQuery.ajax({
      url : '/ecommerce/includes/detailsmodal.php',
      method : "post",
      data : data,
      success : function(data){
        jQuery('body').append(data);
        jQuery('#details-modal').modal('toggle');
      },
      error : function(){
        alert("Something went wrong");
      }
    });
  }

  // Update cart
  function update_cart(mode, edit_id){
    var data = {"mode" : mode, "edit_id" : edit_id};
    jQuery.ajax({
      url : '/ecommerce/admin/parsers/update_cart.php',
      method : "post",
      data : data,
      success : function(){location.reload();},
      error : function(){alert("Something went wrong.");},
    });
  }

  //check the address
  function check_address(){
    var data = {
      'full_name' : jQuery('#full_name').val(),
      'email' : jQuery('#email').val(),
      'street' : jQuery('#street').val(),
      'street2' : jQuery('#street2').val(),
      'city' : jQuery('#city').val(),
      'county' : jQuery('#county').val(),
      'post_code' : jQuery('#post_code').val(),
      'country' : jQuery('#country').val(),
    };

    jQuery.ajax({
      url : '/ecommerce/admin/parsers/check_address.php',
      method : 'post',
      data : data,
      success : function(data){
        if(data != 'passed'){
          jQuery('#payment-errors').html(data);
        }
        if(data == 'passed'){
          jQuery('#payment-errors').html('');
          jQuery('#step1').css("display","none");
          jQuery('#step2').css("display","block");
          jQuery('#next_button').css("display","none");
          jQuery('#checkout_button').css("display","inline-block");
          jQuery('#back_button').css("display","inline-block");
          jQuery('#checkoutModalLabel').html("Add credit card");
        }
      },
      error : function(){alert("Something went wrong.");},
    });
  }

  // Add to card
  function add_to_cart(){
    jQuery('#modal_errors').html("");
    var price = jQuery('#price').val();
    var quantity = jQuery('#quantity').val();
    var available = jQuery('#available').val();
    var colour = jQuery('#colour').val();
    var edition = jQuery('#expansion').val();
    var condition = jQuery('#condition').val();
    var rarity = jQuery('#rarity').val();
    var state = jQuery('#state').val();
    var error = '';
    var data = jQuery('#add_product_form').serialize();

    if(quantity == 0 || quantity == '' || parseInt(quantity, 10) > parseInt(available, 10)){
      alert(quantity);
      error += '<p class="text-danger text-center">You must select a valid quantity"</p>';
      jQuery('#modal_errors').html(error);
      return;
    }else{
      jQuery.ajax({
        url : '/ecommerce/admin/parsers/add_cart.php',
        method: 'post',
        data: data,
        success : function(){
          location.reload();
        },
        error: function(){alert("Something went wrong");}
      });
    }
  }
</script>
</body>
</html>
