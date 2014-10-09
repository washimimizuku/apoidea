<?php
   # Database information
   $server = "localhost";
   $user = "webuser";
   $password = "webuser";
	#$user = "irmaosme";
	#$password = "oe1988";
	#$user = "simplice";
	#$password = "theman";
	
   
   $con = mysql_pconnect($server, $user, $password) or die("Could not connect: " . mysql_error());
   #mysql_select_db("irmaosme_apoidea2");
   mysql_select_db("apoidea");
   #mysql_select_db("simplice_apoidea");
?>
