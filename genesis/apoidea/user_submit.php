<?php
   include("session.php");
	include("database.php");

	$page = $_POST['page'];
   $action = $_POST['action'];
   $id = $_POST['id'];
   
   $id = $_POST['id'];
   $tribeID = $_POST['tribeID'];
   $name = $_POST['name'];
   $username = $_POST['username'];
   $password = $_POST['password'];


   if ($action == "create2"){
      $query = "insert into user (id, tribeID, name, username, password) values ('$id', '$tribeID', '$name', '$username', '$password')";
      mysql_query($query) or die("Invalid query: " . mysql_error());
   }

   if ($action == "modify2") {
      $query = "update user set id='$id', tribeID='$tribeID', name='$name', username='$username', password='$password' where id=$id";
      mysql_query($query) or die("Invalid query: " . mysql_error());
   }
   
   if ($action == "erase2") {
      foreach ($id as $idx) {
         $query = "delete from user where id=$idx";
         mysql_query($query) or die("Invalid query: " . mysql_error());
      }
   }
   
	header ("Location: user.php?page=$page");
   exit;
?>
