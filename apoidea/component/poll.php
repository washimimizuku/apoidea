<?php
   include("../database.php");
   
   $id = $_REQUEST['id'];

   $query = "select title, question, multiple_answer, view_stats, begin_date, end_date from poll where id_poll=$id";
   $result = mysql_query($query) or die("Invalid query: " . mysql_error());
   $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());

   $query = "select id_poll_answer, answer from poll_answer where id_poll=$id order by position, id_poll_answer";
   $result = mysql_query($query) or die("Invalid query: " . mysql_error());
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
   <title>poll</title>
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="description" content="Apoidea Content Manager" />
	<meta name="author" content="Nuno Barreto" />
	<style type="text/css" media="screen,projection">
    /* backslash hack hides from IEmac \*/
	   @import "css/apoidea.css";
    /* end hack */
	</style>
	<script type="text/javascript" src="javascript/apoidea.js"></script>
</head>
<body>
<div id="poll">
   <p><?php echo($row[1]) ?></p>
   <form name="form" method="post" action="poll_submit.php">
      <input type="hidden" name="poll" value="<?php echo($id) ?>" />
<?php
   for ($i = 0; $i < mysql_num_rows($result); $i++) {
      $row_answers = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
      if ($row[2]) {
?>
      <input type="checkbox" name="vote[]" value="<?php echo($row_answers[0]) ?>" /> <?php echo($row_answers[1]) ?><br />
<?php
      } else {
?>
      <input type="radio" name="vote" value="<?php echo($row_answers[0]) ?>" /> <?php echo($row_answers[1]) ?><br />
<?php
      }
   }
?>
      <input type="submit" value="Votar" />
   </form>
</div>
</body>
</html>