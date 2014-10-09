<?php
   include("session.php");
	include("database.php");

	$page = $_POST['page'];
   $action = $_POST['action'];
   $id = $_POST['id'];
   
   $id = $_POST['id'];
   $categoryID = $_POST['categoryID'];
   $feedID = $_POST['feedID'];
   $template = $_POST['template'];
   $ttl = $_POST['ttl'];
   $max_entries = $_POST['max_entries'];


   if ($action == "create2"){
      $query = "insert into endPoint (id, categoryID, feedID, template, ttl, max_entries) values ('$id', '$categoryID', '$feedID', '$template', '$ttl', '$max_entries')";
      mysql_query($query) or die("Invalid query: " . mysql_error());
   }

   if ($action == "modify2") {
      $query = "update endPoint set id='$id', categoryID='$categoryID', feedID='$feedID', template='$template', ttl='$ttl', max_entries='$max_entries' where id=$id";
      mysql_query($query) or die("Invalid query: " . mysql_error());
   }
   
   if ($action == "erase2") {
      foreach ($id as $idx) {
         $query = "delete from endPoint where id=$idx";
         mysql_query($query) or die("Invalid query: " . mysql_error());
      }
   }
   
	header ("Location: endPoint.php?page=$page");
   exit;
?>
