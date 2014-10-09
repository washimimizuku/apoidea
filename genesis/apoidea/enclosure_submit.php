<?php
   include("session.php");
	include("database.php");

	$page = $_POST['page'];
   $action = $_POST['action'];
   $id = $_POST['id'];
   
   $id = $_POST['id'];
   $repositoryID = $_POST['repositoryID'];
   $mimeType = $_POST['mimeType'];
   $length = $_POST['length'];
   $url = $_POST['url'];


   if ($action == "create2"){
      $query = "insert into enclosure (id, repositoryID, mimeType, length, url) values ('$id', '$repositoryID', '$mimeType', '$length', '$url')";
      mysql_query($query) or die("Invalid query: " . mysql_error());
   }

   if ($action == "modify2") {
      $query = "update enclosure set id='$id', repositoryID='$repositoryID', mimeType='$mimeType', length='$length', url='$url' where id=$id";
      mysql_query($query) or die("Invalid query: " . mysql_error());
   }
   
   if ($action == "erase2") {
      foreach ($id as $idx) {
         $query = "delete from enclosure where id=$idx";
         mysql_query($query) or die("Invalid query: " . mysql_error());
      }
   }
   
	header ("Location: enclosure.php?page=$page");
   exit;
?>
