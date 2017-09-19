<?php

  $sqlExpansion = "SELECT * FROM expansion";
  $expansionQuery = $db->query($sqlExpansion);

  $sqlColour = "SELECT * FROM colour";
  $colourQuery = $db->query($sqlColour);

  $sqlRarity = "SELECT * FROM rarity";
  $rarityQuery = $db->query($sqlRarity);

  $exp = '';

  if(isset($_GET['exp']) && $_GET['exp'] != ''){
    $exp = $_GET['exp'];
  }

 ?>

 <!-- Values are expansion IDs -->
 <div id="">

        <input type="hidden" name="expansion" id="expansion" value="<?=$exp;?>">

         <div class="panel-heading search-panel">
           <h3 class="search-panel-heading-text">Advanced Search:</h3>
         </div>

         <!-- Expansions -->
         <div class="panel-heading search-panel">
           <h4 class="panel-title">
                Edition<span class="glyphicon glyphicon-plus pull-right" data-toggle="collapse" data-target="#expansion-collapse"></span>
           </h4>
         </div>

         <div class="panel-body collapse" id="expansion-collapse">
           <div class="panel-collapse collapse in collapse-body">
             <ul class="list-group">
               <?php while($expansion = mysqli_fetch_assoc($expansionQuery)) : ?>
                 <li class="list-group-item">
                  <div id="filters">
                   <div id="expansion">
                     <input type="checkbox" class="expansion" value="<?=$expansion['id'];?>" id="expansion"> <?=$expansion['expansion'];?>
                   </div>
                  </div>
                 </li>
               <?php endwhile; ?>
           </div>
         </div>

         <!-- Colours -->
         <div class="panel-heading search-panel">
           <h4 class="panel-title">
                Colour<span class="glyphicon glyphicon-plus pull-right" data-toggle="collapse" data-target="#colour-collapse"></span>
           </h4>
         </div>

         <div class="panel-body collapse" id="colour-collapse">
           <div class="panel-collapse collapse in collapse-body">
             <ul class="list-group">
               <?php while($colour = mysqli_fetch_assoc($colourQuery)) : ?>
                 <li class="list-group-item">
                  <div id="filters">
                   <div id="colour">
                     <input type="checkbox" class="colour" value="<?=$colour['id'];?>" id="colour"> <?=$colour['colour'];?>
                   </div>
                  </div>
                 </li>
               <?php endwhile; ?>
           </div>
         </div>

         <!-- Rarity -->
         <div class="panel-heading search-panel">
           <h4 class="panel-title">
                Rarity<span class="glyphicon glyphicon-plus pull-right" data-toggle="collapse" data-target="#rarity-collapse"></span>
           </h4>
         </div>

         <div class="panel-body collapse" id="rarity-collapse">
           <div class="panel-collapse collapse in collapse-body">
             <ul class="list-group">
               <?php while($rarity = mysqli_fetch_assoc($rarityQuery)) : ?>
                 <li class="list-group-item">
                  <div id="filters">
                   <div id="rarity">
                     <input type="checkbox" class="rarity" value="<?=$rarity['id'];?>" id="rarity"> <?=$rarity['rarity'];?>
                   </div>
                  </div>
                 </li>
               <?php endwhile; ?>
           </div>
         </div>
 </div>


<!-- When page is ready, attach events to the filters -->
<script>
    onLoad()

    function onLoad(){
      var filters = {};

      $('#filters div').each(function() {
          var checkedVals = [];
          $("#" + this.id + ' :checked').each(function() {
              checkedVals.push($(this).val());
          });
          filters[this.id] = checkedVals;
      });

      if (document.getElementById("expansion").value != '') {
        filters['expansion'] = document.getElementById("expansion").value;
      }

      $('#filterValues').text(JSON.stringify(filters));

      jQuery.ajax({
          url: '/eCommerce/includes/widgets/getCardsFromFilter.php',
          method: "post",
          data: filters,
          success: function(resp) {
              $("#cards").html(resp);
          },
          error: function() {
              alert("Something went wrong.");
          },
      });
    }

    var filterChanged = function() {

        var filters = {};
        $('#filters div').each(function() {
            var checkedVals = [];
            $("#" + this.id + ' :checked').each(function() {
                checkedVals.push($(this).val());
            });
            filters[this.id] = checkedVals;
        });

        $('#filterValues').text(JSON.stringify(filters));

        jQuery.ajax({
            url: '/eCommerce/includes/widgets/getCardsFromFilter.php',
            method: "post",
            data: filters,
            beforeSend: function() {
               $('#loader').show();
            },
            complete: function(){
               $('#loader').hide();
            },
            success: function(resp) {
                $("#cards").html(resp);
            },
            error: function() {
                alert("Something went wrong.");
            },
        });
    };

    $(document).ready(function() {

      $("#filters input").each(function() {
          //jQuery click events ensure cross-browser support
          $(this).on("click", radioCheck);
      });
    });

    var radioCheck = function() {
      var c = $(this).attr('id');

      var checked = $(this).is(":checked");

      if(!checked){
        $("." + c).prop('checked', false);
      }else{
        $("." + c).prop('checked', false);
        $(this).prop('checked', true);
      }

      filterChanged();
    };

</script>
