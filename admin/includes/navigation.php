<!-- Top Nav -->
<nav class="navbar navbar-default navbar-fixed-top navbar-padded">
  <div class="container">
    <a href="/eCommerce/admin/index.php" class="navbar-brand">Admin Area</a>
    <ul class="nav navbar-nav">
      <li><a href="products.php">Products</a></li>
      <li><a href="deletedProducts.php">Deleted Products</a></li>
      <li><a href="editions.php">Editions</a></li>
      <li><a href="colour.php">Colour</a></li>
      <li><a href="rarity.php">Rarity</a></li>
      <li><a href="state.php">States</a></li>
      <?php if(has_permission('admin')): ?>
        <li><a href="users.php">Users</a></li>
      <?php endif; ?>
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Hello <?=$user_data['first'];?>
          <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" role="menu">
          <li><a href="change_password.php">Change Password</a></li>
          <li><a href="/ecommerce/index.php">Front End</a></li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </li>
    </ul>
  </div>
</nav>
