<?php
   include("session.php");
	include("database.php");

   $action = $_REQUEST['action'];
   
   $id = $_POST['id'];
   $name = $_POST['name'];
     
   $media_type = "";
   $msg = "";
      
   if ($action == "create2") {
      if ((!$_FILES['file']['error']) && (is_uploaded_file($_FILES['file']['tmp_name']))) {
         $media_type = $_FILES['file']['type'];
         #$_FILES['file']['name']
         #$_FILES['file']['type']
         #$_FILES['file']['size']
         #$_FILES['file']['tmp_name']
         #$_FILES['file']['error']
   
         $uploadDir = '../images/';
         $uploadFile = $uploadDir.$_FILES['file']['name'];
         if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
            $query = "insert into media (media_type, name, location) values ('$media_type', '$name', '".$_FILES['file']['name']."')";
            mysql_query($query) or die("Invalid query: " . mysql_error());
         } else {
            $msg = "Não foi possível copiar o ficheiro."; 
         }
      } else {
         $msg = "Ficheiro demasiado grande."; 
      }
   }   
    
   if ($action == "modify2") {
      $query = "update media set name='$name' where id_media=$id";
      mysql_query($query) or die("Invalid query: " . mysql_error());
   }

   if ($action == "erase2") {
      foreach ($id as $idx) {
         $query = "delete from media where id_media=$idx";
         mysql_query($query) or die("Invalid query: " . mysql_error());
      }
   }

   #print "<pre>";
   #print_r($_FILES);
   #print "<pre>";
      
   header ("Location: media.php?msg=$msg");
   exit;
?>