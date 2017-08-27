<?php
  require_once "../core/init.php";

  if(!is_logged_in()){
    login_error_redirect();
  }

  include 'includes/head.php';
  include 'includes/navigation.php';

  // Get Editions From Database
  $sql = "SELECT * FROM rarity ORDER BY rarity";
  $results = $db->query($sql);

  $errors = array();

  // Delete Edition
  if(isset($_GET['delete']) && !empty($_GET['delete'])){
    $delete_id = (int)$_GET['delete'];
    $delete_id = sanitize($delete_id);
    $sql = "DELETE FROM rarity WHERE id = '$delete_id'";
    $db->query($sql);
    header('Location: rarity.php');
  }

  // Edit Edition
  if(isset($_GET['edit']) && !empty($_GET['edit'])){
    $edit_id = (int)$_GET['edit'];
    $edit_id = sanitize($edit_id);
    $sql = "SELECT * FROM rarity WHERE id = '$edit_id'";
    $edit_result = $db->query($sql);
    $eEdition = mysqli_fetch_assoc($edit_result);
  }

  // If add form is submitted
  if (isset($_POST['add_submit'])){

    $newEdition = sanitize($_POST['rarity']);
    // check if edition is blank
    if($_POST['rarity'] == ''){
      $errors[] .= 'No rarity entered.';
    }

    // Check if edition exists in db
    $sql = "SELECT * FROM rarity WHERE rarity = '$newEdition'";
    if(isset($_GET['edit'])){
      $sql = "SELECT * FROM rarity WHERE rarity = '$newEdition' AND id != '$edit_id'";
    }
    $eresult = $db->query($sql);
    $c = mysqli_num_rows($eresult);

    if($c > 0){
      $errors[] .= $newEdition.' already exists, please choose another rarity name.';
    }
    else {
      // Edition is safe to add to db
      $sql = "INSERT INTO rarity (rarity) VALUES('$newEdition')";
      if(isset($_GET['edit'])){
        $sql = "UPDATE rarity SET rarity = '$newEdition' WHERE id = '$edit_id'";
      }
      $db->query($sql);
      header('Location: rarity.php');
    }

    // Display errors
    if(!empty($errors)){
      echo display_errors($errors);
    }
  }
 ?>

<h2 class="text-center">Rarity</h2>

<!-- Edition form -->
<div class="text-center">
  <hr>
  <form class="form-inline" action="rarity.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:'');?>" method="post">
      <div class="form-group">
        <label for="rarity"><?=((isset($_GET['edit']))?'Edit':'Add a'); ?> rarity</label>
        <?php
          $edition_value = '';
          if(isset($_GET['edit'])) {
            $edition_value = $eEdition['rarity'];
          }
          else {
            if(isset($_POST['rarity'])){
              $edition_value = sanitize($_POST['rarity']);
            }
          }
        ?>
        <input class ="form-control" type="text" name="rarity" id="rarity" value="<?=$edition_value;?>">
        <?php if(isset($_GET['edit'])): ?>
          <a href="rarity.php" class="btn btn-default">Cancel</a>
        <?php endif; ?>
        <input class ="btn btn-success" type="submit" name="add_submit" value="Save">

      </div>
  </form>
</div><hr>

<table class="table table-bordered table-striped table-auto table-condensed">
  <thead>
    <th>Edit</th>
    <th>Edition</th>
    <th>Delete</th>
  <tbody>
    <?php while($expansion = mysqli_fetch_assoc($results)) : ?>
      <tr>
        <td><a href="rarity.php?edit=<?=$expansion['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a></td>
        <td><?= $expansion['rarity']; ?></td>
        <td><a href="rarity.php?delete=<?=$expansion['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-trash"></span></a></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php
  include 'includes/footer.php';
 ?>

 <?php while($mtg = mysqli_fetch_assoc($results)) : ?>
   <li><a href="#"><?php echo $mtg['rarity']; ?></a></li>
 <?php endwhile; ?>
