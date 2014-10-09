<?php
   include("session.php");
	include("database.php");

	$page = $_POST['page'];
   $action = $_POST['action'];
   $id = $_POST['id'];
   
   $id = $_POST['id'];
   $userID = $_POST['userID'];
   $creationDate = $_POST['creationDate'];
   $title = $_POST['title'];
   $subtitle = $_POST['subtitle'];
   $teaser = $_POST['teaser'];
   $text = $_POST['text'];
   $link = $_POST['link'];
   $beginDate = $_POST['beginDate'];
   $endDate = $_POST['endDate'];
   $active = $_POST['active'];


   if ($action == "create2"){
      $query = "insert into article (id, userID, creationDate, title, subtitle, teaser, text, link, beginDate, endDate, active) values ('$id', '$userID', '$creationDate', '$title', '$subtitle', '$teaser', '$text', '$link', '$beginDate', '$endDate', '$active')";
      mysql_query($query) or die("Invalid query: " . mysql_error());
   }

   if ($action == "modify2") {
      $query = "update article set id='$id', userID='$userID', creationDate='$creationDate', title='$title', subtitle='$subtitle', teaser='$teaser', text='$text', link='$link', beginDate='$beginDate', endDate='$endDate', active='$active' where id=$id";
      mysql_query($query) or die("Invalid query: " . mysql_error());
   }
   
   if ($action == "erase2") {
      foreach ($id as $idx) {
         $query = "delete from article where id=$idx";
         mysql_query($query) or die("Invalid query: " . mysql_error());
      }
   }
   
	header ("Location: article.php?page=$page");
   exit;
?>
