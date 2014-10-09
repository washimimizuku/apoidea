<?php
   include("../database.php");

   $poll = $_POST['poll'];
   $vote = $_POST['vote'];
   $who = $_SERVER["REMOTE_ADDR"].":".$_SERVER['REMOTE_USER'];
   $msg = "";
   
   # verificar se foi seleccionada alguma opção
   if ($vote) {
      # verificar se o utilizador já respondeu
      $query = "select * from poll_unique_answer where id_poll=$poll and who='$who'";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      if (mysql_num_rows($result) == 0) {
         # verificar se a poll está aberta
         $query = "select * from poll where id_poll=$poll and begin_date<='".date('Y-m-d H:i:s')."' and end_date>='".date('Y-m-d H:i:s')."'";
         $result = mysql_query($query) or die("Invalid query: " . mysql_error());
         if (mysql_num_rows($result) > 0) {
            if (is_array($vote)) {
               # checkbox
               foreach ($vote as $v) {
                  $query = "insert into poll_unique_answer values ('$poll', '$v', '$who', NOW())";
                  mysql_query($query) or die("Invalid query: " . mysql_error());
               }
            } else {
               # radiobutton
               $query = "insert into poll_unique_answer values ('$poll', '$vote', '$who', NOW())";
               mysql_query($query) or die("Invalid query: " . mysql_error());
            }
            $msg = "Obrigado pela sua contribui&ccedil;&atilde;o..";
         } else {
            $msg = "J&aacute; n&atilde;o &eacute; poss&iacute;vel votar nesta pesquisa.";
         }
      } else {
         $msg = "Vo&ccedil;&ecirc; j&aacute; respondeu a esta pesquisa.<br>Cada utilizador s&oacute; pode dar a sua opini&atilde;o uma vez.";
      }
   } else {
      $msg = "Tem de escolher uma op&ccedil;&atilde;o.";
   }
	header ("Location: poll_stats2.php?poll=$poll&msg=". urlencode($msg));
   exit;
?>