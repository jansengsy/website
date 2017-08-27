<?php

$currentPageNumber = 1;
$pageNumberNoChanges = 1;
$pageSize = 12;
$dissabledL = '';
$dissabledR = '';

if(isset($_GET['pageSize'])){
  $pageSize = (int)$_GET['pageSize'];
}

if(isset($_GET['page'])){

  $currentPageNumber = (int)$_GET['page'];
  $pageNumberNoChanges = (int)$_GET['page'];

  if($_GET['page'] == 1){
    $pageNumber = 0;
    $pageNumber *= $pageSize;
  } else {
    $pageNumber = $_GET['page'] - 1;
    $pageNumber *= $pageSize;
  }
}
else{
  $pageNumber = 0;
  $pageNumber *= $pageSize;
}

if(isset($_POST['searchBarNav']) || isset($_GET['searchBarNav'])){
  trim($productName);
  $sql1 = "SELECT count(*) FROM products WHERE deleted != 1 AND title LIKE '%{$productName}%'";
  $pquery1 = $db->query($sql1);
  $r1 = mysqli_fetch_assoc($pquery1);
} elseif (isset($_GET['cat']) && $_GET['cat'] != ''){
  $category = $_GET['cat'];
  $sql1 = "SELECT count(*) FROM products WHERE deleted != 1 AND expansion = '$category'";
  $pquery1 = $db->query($sql1);
  $r1 = mysqli_fetch_assoc($pquery1);
} else {
  $sql1 = "SELECT count(*) FROM products WHERE deleted != 1 AND featured = 1";
  $pquery1 = $db->query($sql1);
  $r1 = mysqli_fetch_assoc($pquery1);
}


$sql2 = "SELECT * FROM products WHERE deleted != 1 AND featured = 1 ORDER BY id LIMIT $pageNumber,$pageSize";
$pquery2 = $db->query($sql2);

// Counting number of pages
$count = $r1['count(*)'];
$p = 0;
$p = ceil($count/$pageSize);

if(isset($_GET['page']) && $_GET['page'] >= $p){
  $dissabledR = 'disabled';
}else{
  $dissabledR = '';
  $currentPageNumber++;
}

if($pageNumber <= 0){
  $dissabledL = 'disabled';
}else {
  $dissabledL = '';
  $currentPageNumber--;
}

if(isset($_POST['searchBarNav'])){
  $searchLink = '&searchBarNav=' . $_POST['searchBarNav'];
}

$catLink = ((isset($_GET['caller']) && $_GET['caller'] == 'category'))?'&cat=' . $_GET['cat']:'';

//$newLinkL = $_GET['caller'] . '.php?page=' . $currentPageNumber . '&pageSize=' . $pageSize . '&caller=' . $_GET['caller'] . $catLink;
//$newLinkR = $_GET['caller'] . '.php?page=' . $currentPageNumber . '&pageSize=' . $pageSize . '&caller=' . $_GET['caller'] . $catLink;
//$leftLink = (($dissabledL == 'disabled'))?'':$newLinkL;
//$rightLink = (($dissabledR == 'disabled'))?'':$newLinkR;
 ?>
