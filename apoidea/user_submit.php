<?php
   include("session.php");
	include("database.php");

   $action = $_POST['action'];
   
   $id = $_POST['id'];
   $tribe = $_POST['tribe'];
   $name = $_POST['name'];
   $username = $_POST['username'];
   $password = $_POST['password'];
   $password2 = $_POST['password2'];

   if (($password != $password2) && ($action != "erase2")) {
      $msg .= "Os campos de password têm de ser iguais.<br>";
   } else if (($tribe == 0) && ($action != "erase2")) {
      $msg .= "Tem de escolher um grupo para o utilizador.<br>";
   } else {
      if ($action == "create2") {
         $query = "select * from user where username='$username'";
         $result = mysql_query($query) or die("Invalid query: " . mysql_error());
         $num = mysql_num_rows($result);
      
         if ($num > 0) {
            $msg .= "Não foi possível criar o utilizador $username pois já existe.<br>";
         } else {   
            $password = md5($password);
            $query = "insert into user (id_tribe, name, username, password) values ('$tribe', '$name', '$username', '$password')";
            mysql_query($query) or die("Invalid query: " . mysql_error());
         }
      }

      if ($action == "modify2") {
         $query = "";
         if ($password != "") {
            $password = md5($password);
            $query = "update user set id_tribe='$tribe', name='$name', username='$username', password='$password' where id_user=$id";
         } else {
            $query = "update user set id_tribe='$tribe', name='$name', username='$username' where id_user=$id";
         }
         mysql_query($query) or die("Invalid query: " . mysql_error());
      }
   }
   
   if ($action == "erase2") {
      foreach ($id as $idx) {
         $query = "delete from user where id_user=$idx";
         mysql_query($query) or die("Invalid query: " . mysql_error());
      }
   }
   
   header ("Location: user.php?msg=$msg");
   exit;
?>