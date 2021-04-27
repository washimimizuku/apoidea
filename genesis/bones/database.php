<?php
   # Database information
   $server = "localhost";
   $user = "webuser";
   $password = "webuser";
	
   
   $con = mysql_pconnect($server, $user, $password) or die("Could not connect: " . mysql_error());
   mysql_select_db("apoidea");
?>
