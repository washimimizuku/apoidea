<?php
   include("session.php");
	include("database.php");

	$page = $_POST['page'];
   $action = $_POST['action'];
   $id = $_POST['id'];
   
   $id = $_POST['id'];
   $name = $_POST['name'];
   $driver = $_POST['driver'];


   if ($action == "create2"){
      $query = "insert into sourceType (id, name, driver) values ('$id', '$name', '$driver')";
      mysql_query($query) or die("Invalid query: " . mysql_error());
   }

   if ($action == "modify2") {
      $query = "update sourceType set id='$id', name='$name', driver='$driver' where id=$id";
      mysql_query($query) or die("Invalid query: " . mysql_error());
   }
   
   if ($action == "erase2") {
      foreach ($id as $idx) {
         $query = "delete from sourceType where id=$idx";
         mysql_query($query) or die("Invalid query: " . mysql_error());
      }
   }
   
	header ("Location: sourceType.php?page=$page");
   exit;
?>
