<?php
   include("../database.php");

   $poll = $_REQUEST['poll'];
   $answers[] = array();
   $scores[] = array();
   $total = 0;
   $colours = array('#E90404','#245AC6','#14AD05','#E7D215','#ED9804','#5C589D','#E9A4E8','#6B4809','#000000','#ACA89A'); 
   
   $query = "select question, begin_date from poll where id_poll=$poll";
   $result_poll = mysql_query($query) or die("Invalid query: " . mysql_error());
   $row_poll = mysql_fetch_row($result_poll) or die("Could not retrieve row: " . mysql_error());

   $question = $row_poll[0];
   $begin = $row_poll[1];

   $query = "select id_poll_answer, answer from poll_answer where id_poll=$poll order by position, id_poll_answer";
   $result = mysql_query($query) or die("Invalid query: " . mysql_error());

   for ($i = 0; $i < mysql_num_rows($result); $i++) {
      $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());

      $query = "select count(*) from poll_unique_answer where id_poll=$poll and id_poll_answer=".$row[0];
      $result2 = mysql_query($query) or die("Invalid query: " . mysql_error());
      $row2 = mysql_fetch_row($result2) or die("Could not retrieve row: " . mysql_error());
    
      $answers[$i] = $row[1];
      $scores[$i] = $row2[0];
      $total += $scores[$i];
   }
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
   <table>
      <tr><td colspan="2"><?php echo($question) ?></td></tr>
<?php
   for ($i = 0; $i < count($answers); $i++) {
      $percentage = 100 * $scores[$i] / $total;
?>
      <tr>
         <td><?php echo($answers[$i]) ?></td>
         <td><img src="http://www.sapo.pt/images/pixel_transp.gif" height="10" width="<?php echo(round($percentage * 2) + 1) ?>" style="background-color: <?php echo($colours[$i % 10]) ?>" /> <?php echo(round($percentage, 2)) ?>% (<?php echo($scores[$i]) ?> votos)</td>
      </tr>
<?php
   }
?>
      <tr><td colspan="2">Início: <?php echo($begin) ?></td></tr>
   </table>

</div>
</body>
</html>
