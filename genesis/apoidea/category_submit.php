<?php
   include("session.php");
	include("database.php");

	$page = $_POST['page'];
   $action = $_POST['action'];
   $id = $_POST['id'];
   
   $id = $_POST['id'];
   $parentCategoryID = $_POST['parentCategoryID'];
   $name = $_POST['name'];
   $stub = $_POST['stub'];
   $active = $_POST['active'];
   $feed = $_POST['feed'];


   if ($action == "create2"){
      $query = "insert into category (id, parentCategoryID, name, stub, active, feed) values ('$id', '$parentCategoryID', '$name', '$stub', '$active', '$feed')";
      mysql_query($query) or die("Invalid query: " . mysql_error());
   }

   if ($action == "modify2") {
      $query = "update category set id='$id', parentCategoryID='$parentCategoryID', name='$name', stub='$stub', active='$active', feed='$feed' where id=$id";
      mysql_query($query) or die("Invalid query: " . mysql_error());
   }
   
   if ($action == "erase2") {
      foreach ($id as $idx) {
         $query = "delete from category where id=$idx";
         mysql_query($query) or die("Invalid query: " . mysql_error());
      }
   }
   
	header ("Location: category.php?page=$page");
   exit;
?>
