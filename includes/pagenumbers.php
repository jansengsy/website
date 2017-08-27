<!-- Page number -->
<div ng-controller="someController" class="col-sm-12">
  <div class="container col-sm-10">
    <ul class="pagination" total-items="totalItems" items-per-page= "itemsPerPage" ng-model="currentPage">
      <li class="<?=$dissabledL;?>"><a href="<?=$leftLink;?>">&laquo;</a></li>
      <?php for($i = 1; $i <= $p; $i++) : ?>
        <?php
          $sbn = ((isset($productName))?'&searchBarNav=' . $productName:'');
        ?>
        <!-- This looks complex but it isn't - IF CAT IS SET, WE THEN CHECK FOR '', IF EITHER OF THOSE ARE TRUE, WE RETURN A '' OTHERWISE WE RETURN THE VALUE OF CAT-->
        <li><a href="<?=$_GET['caller'];?>.php?page=<?=$i;?>&pageSize=<?=$pageSize;?><?= (isset($_GET['cat'])?(($_GET['cat'] == '')? '' : 'cat=' . $_GET['cat']):''); ?><?=$sbn;?>"><?= $i; ?></a></li>
      <?php endfor; ?>
      <li class="<?=$dissabledR;?>"><a href="<?=$rightLink;?>">&raquo;</a></li>
    </ul>
  </div>

  <!--
  <div class="dropdown-toggle btn btn-default col-sm-1 pageNumber-dropdown">
    <a id="drop1" href="#" role="button" class="dropdown-toggle" data-toggle="dropdown"><?= $pageSize ;?> <b class="caret"></b></a>
    <ul class="dropdown-menu" role="menu">
      <li><a href="<?=$_GET['caller'];?>.php?page=1&pageSize=12&<?= (isset($_GET['cat'])?(($_GET['cat'] == '')? '' : 'cat=' . $_GET['cat']):''); ?><?=$productName?>">12 </a></li>
      <li><a href="<?=$_GET['caller'];?>.php?page=1&pageSize=24&cat=<?=$_GET['cat'];?>&searchBarNav=<?=$productName?>">24 </a></li>
      <li><a href="<?=$_GET['caller'];?>.php?page=1&pageSize=48&cat=<?=$_GET['cat'];?>&searchBarNav=<?=$productName?>">48 </a></li>
      <li><a href="<?=$_GET['caller'];?>.php?page=1&pageSize=72&cat=<?=$_GET['cat'];?>&searchBarNav=<?=$productName?>">72 </a></li>
    </ul>
  </div>
  -->
</div>
