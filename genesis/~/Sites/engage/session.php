<?php
   session_start();
   header("Cache-control: private");
   $session_username = $_SESSION["username"];
   
   if ($session_username == "") {
      $msg = "Você não tem permissões para aceder a essa opção.";
      header ("Location: index.php?msg=$msg");
      exit;
   }
?>