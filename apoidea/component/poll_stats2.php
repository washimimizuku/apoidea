<?php
   include("../database.php");

   $poll = $_REQUEST['poll'];
   $msg = $_REQUEST['msg'];
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

   $num_rows = mysql_num_rows($result);
   $length = 421 + ($num_rows * 28);
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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script> window.resizeTo(510,<?php echo($length) ?>) </script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
#tabela { font-family:Verdana, Arial, Helvetica, sans-serif; padding:0px 3px 3px 3px; background-color:#FFFFFF;}
#tabela .texto_entrada { font-family:Verdana, Arial, Helvetica, sans-serif; color:333333; font-size:11px; font-weight:bold; text-align:center; }
#tabela .texto_pergunta { font-family:Verdana, Arial, Helvetica, sans-serif; color:000000; font-size:16px; font-weight:bold; text-align:center; }
#tabela .texto_resposta { font-family:Verdana, Arial, Helvetica, sans-serif; color:000000; font-size:12px; text-align:right; vertical-align:middle; padding:5px 5px 5px 5px; width:150px;}
#tabela .texto_percentagem { font-family:Verdana, Arial, Helvetica, sans-serif; color:000000; font-size:10px; text-align:left; vertical-align:middle; padding:5px 5px 5px 5px; width:250px;}
#tabela .texto_info1 { font-family:Verdana, Arial, Helvetica, sans-serif; color:000000; font-size:11px; text-align:right; vertical-align:middle; padding:5px 0px 0px opx; width:250px; }
#tabela .texto_info2 { font-family:Verdana, Arial, Helvetica, sans-serif; color:676767; font-size:10px; text-align:right; vertical-align:middle; width:250px; }
#tabela .banner { vertical-align:middle; padding:10px 10px 10px 10px;}
-->
</style>
<title>Vota&ccedil;&atilde;o SAPO</title>
</head>
<body topmargin="0" leftmargin="0" bgcolor="#EEEEEE">
<table width="480" border="0" cellspacing="0" cellpadding="0" bgcolor="#A1CEE3">
  <tr>
    <td align="left"><img src="logo.gif" width="112" height="22" hspace="5" vspace="1"></td>
    <td align="right"><a href="#"><img src="botao.gif" width="17" height="17" hspace="10" border="0"></a></td>
  </tr>
  <tr>
    <td colspan="3">
<table width="474" border="0" align="center" vcellpadding="0" cellspacing="0" id="tabela">
  <tr>
    <td height="34" colspan="2" class="texto_entrada"><?php echo($msg) ?></td>
  </tr>
  <tr>
    <td height="3" colspan="2" align="center"><img src="separador.gif" width="430" height="3"></td>
  </tr>
  <tr>
    <td colspan="2" class="texto_pergunta"><?php echo($question) ?></td>
  </tr>
  <tr>
    <td height="100%" colspan="2" valign="top"><table width="400" border="0" cellspacing="0" cellpadding="0" align="center">
<?php
   for ($i = 0; $i < count($answers); $i++) {
      if ($total == 0) {
         $percentage = 0;
      } else {
         $percentage = 100 * $scores[$i] / $total;
      }
?>
  <tr>
     <td class="texto_resposta"><?php echo($answers[$i]) ?></td>
     <td class="texto_percentagem"><img src="http://www.sapo.pt/images/pixel_transp.gif" height="10" width="<?php echo(round($percentage * 2) + 1) ?>" style="background-color: <?php echo($colours[$i % 10]) ?>" /> <?php echo(round($percentage, 2)) ?>% (<?php echo($scores[$i]) ?> votos)</td>
  </tr>
<?php
   }
?>
</table>
</td>
  </tr>
  <tr>
    <td height="3" colspan="2" align="center"><img src="separador.gif" width="430" height="3"></td>
  </tr>
<tr>
    <td height="1" class="texto_info1">Total de votos<br>
      at&eacute; ao momento:<strong><?php echo($total) ?></strong><br><br>
	  Esta pesquisa de opini&atilde;o teve in&iacute;cio em:<br>
	  <strong><?php echo(substr($begin, 0, 4).'-'.substr($begin, 4, 2).'-'.substr($begin, 6, 2).' '.substr($begin, 8, 2).':'.substr($begin, 10, 2).':'.substr($begin, 12, 2)) ?></strong></td>
    <td rowspan="2" class="banner"><img src="banner.gif" width="180" height="150"></td>
</tr>
<tr>
  <td class="texto_info2">Esta pesquisa de opini&atilde;o<br>
    tem como objectivo conhecer<br>
    as prefer&ecirc;ncias dos utilizadores<br>
    do SAPO.</td>
</tr>
</table><table width="400" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><img src="spacer.gif" height="3"></td>
  </tr>
</table>
</td>
  </tr>
</table>
</body>
</html>