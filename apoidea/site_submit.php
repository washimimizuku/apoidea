<?php
   include("session.php");
	include("database.php");

   $action = $_REQUEST['action'];
   
   $id = $_POST['id'];
   $name = $_POST['name'];
   $root = $_POST['root'];
   $num_columns = $_POST['num_columns'];
   $header = $_POST['header'];
   $footer = $_POST['footer'];
   
   $msg = "";
   
   if ($action == "create2"){
      $query = "insert into site (name, root, num_columns, header, footer) values ('$name', '$root', '$num_columns', '$header', '$footer')";
      mysql_query($query) or die("Invalid query: " . mysql_error());
   }
   
   if ($action == "modify2") {
      $query = "update site set name='$name', root='$root', num_columns='$num_columns', header='$header', footer='$footer' where id_site=$id";
      mysql_query($query) or die("Invalid query: " . mysql_error());
   }
   
   if ($action == "erase2") {
      foreach ($id as $idx) {
         $query = "delete from site where id_site=$idx";
         mysql_query($query) or die("Invalid query: " . mysql_error());
      }
   }

	header ("Location: site.php?msg=$msg");
   exit;
?>