<?php
	include("database.php");

	$username = $_REQUEST['username'];
	$pwd = $_REQUEST['password'];
	$header = "";


	$query = "select password ".
            "from user ".
            "where username='$username';";
   $result = mysql_query($query) or die("Invalid query: " . mysql_error());

	if (mysql_num_rows($result) > 0) {
		$row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
		if ($row[0] == md5($pwd)) {
			session_start();
         header("Cache-control: private");
			$_SESSION["username"] = $username;
			
         $header = "Location: article.php";
		} else {
			$msg = "A password está errada. Tente novamente.";
			$header = "Location: index.php?msg=$msg";
		}
	} else {
		$msg = "O utilizador $username não existe. Tente novamente.";
		$header = "Location: index.php?msg=$msg";
	}

   #mysql_close($con);

	header ($header);
	exit;
?>
