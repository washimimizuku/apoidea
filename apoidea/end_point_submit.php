<?php
	include("database.php");

   $action = $_REQUEST['action'];
   
   $id = $_POST['id'];
   $id_category = $_POST['id_category'];
   $id_feed = $_POST['id_feed'];
   $template = $_POST['template'];
   $ttl = $_POST['ttl'];
   $max_entries = $_POST['max_entries'];
   
   $msg = "";
   
   if ($action == "create2") {
      $query = "insert into end_point (id_category, id_feed, ttl, max_entries) "
              ."values ('$id_category', '$id_feed', '$ttl', '$max_entries')";
      mysql_query($query) or die("Invalid query: " . mysql_error());
   }
   
   if ($action == "modify2") {
      $query = "update end_point set id_category='$id_category', id_feed='$id_feed', ttl='$ttl', max_entries='$max_entries' where id=$id";
      mysql_query($query) or die("Invalid query: " . mysql_error());
   }
   
   if ($action == "erase2") {
      foreach ($id as $idx) {
         $query = "delete from end_point where id=$idx";
         mysql_query($query) or die("Invalid query: " . mysql_error());
      }
   }

	header ("Location: end_point.php?msg=$msg");
   exit;
?>
