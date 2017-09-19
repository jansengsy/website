<?php ?>

<ul class="bxslider">
  <li>
    <form class="" action="search.php" method="post">
      <button class="img-wrap" type="submit" name="title" value="Ixalan"><img src="images/headerlogo/background_m_4.png" title="Ixalan_Banner" /></button>
    </form>
  </li>
  <li>
    <form class="" action="search.php" method="post">
      <a href="search.php?exp=1" class="img-wrap" type="submit" name="title" value="Hour"><img src="images/headerlogo/background_m_5.png" title="Hour_Banner" /></a>
    </form>
  </li>
  <li>
    <form class="" action="search.php" method="post">
      <a href="search.php?exp=5" class="img-wrap" type="submit" name="title" value="Amonkhet"><img src="images/headerlogo/background_m_6.png" title="Amonkhet_Banner" /></a>
    </form>
  </li>
</ul>

<script type="text/javascript">
  $('.bxslider').bxSlider({
    mode: 'horizontal',
    auto: true
  });
</script>
