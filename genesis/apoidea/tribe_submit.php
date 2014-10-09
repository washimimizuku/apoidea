<?php
   include("session.php");
	include("database.php");

	$page = $_POST['page'];
   $action = $_POST['action'];
   $id = $_POST['id'];
   
   $id = $_POST['id'];
   $name = $_POST['name'];


   if ($action == "create2"){
      $query = "insert into tribe (id, name) values ('$id', '$name')";
      mysql_query($query) or die("Invalid query: " . mysql_error());
   }

   if ($action == "modify2") {
      $query = "update tribe set id='$id', name='$name' where id=$id";
      mysql_query($query) or die("Invalid query: " . mysql_error());
   }
   
   if ($action == "erase2") {
      foreach ($id as $idx) {
         $query = "delete from tribe where id=$idx";
         mysql_query($query) or die("Invalid query: " . mysql_error());
      }
   }
   
	header ("Location: tribe.php?page=$page");
   exit;
?>
