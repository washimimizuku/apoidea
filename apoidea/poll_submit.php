<?php
   include("session.php");
	include("database.php");

   $action = $_REQUEST['action'];
   
   $id = $_POST['id'];
   $title = $_POST['title'];
   $question = $_POST['question'];
   $multiple_answer = $_POST['multiple_answer'];
   $view_stats = $_POST['view_stats'];
   $begin_day = $_POST['begin_day'];
   $begin_month = $_POST['begin_month'];
   $begin_year = $_POST['begin_year'];
   $end_day = $_POST['end_day'];
   $end_month = $_POST['end_month'];
   $end_year = $_POST['end_year'];
   $all_answers_values = $_POST['all_answers_values'];
   $all_answers_texts = $_POST['all_answers_texts'];
   $erased_answers = $_POST['erased_answers'];
      //print "$erased_answers";      
   
   $begin_date = $begin_year."-".$begin_month."-".$begin_day;
   $end_date = $end_year."-".$end_month."-".$end_day." 23:59:59";
   
   $msg = "";
   
   if ($action == "create2"){
      $query = "insert into poll (title, question, multiple_answer, view_stats, begin_date, end_date) "
              ."values ('$title', '$question', '$multiple_answer', '$view_stats', '$begin_date', '$end_date')";
      mysql_query($query) or die("Invalid query: " . mysql_error());

      $query = "select id_poll from poll where title='$title' and question='$question' order by id_poll desc";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());

      $answers = split(";", $all_answers_texts);
      foreach ($answers as $answer) {
         $query = "insert into poll_answer (id_poll, answer) values ('$row[0]', '$answer')";
         mysql_query($query) or die("Invalid query: " . mysql_error());
      }
   }
   
   if ($action == "modify2") {
      $query = "update poll set title='$title', question='$question', multiple_answer='$multiple_answer', view_stats='$view_stats', begin_date='$begin_date', end_date='$end_date' where id_poll=$id";
      mysql_query($query) or die("Invalid query: " . mysql_error());

      $answers_values = split(";", $all_answers_values);
      $answers_texts = split(";", $all_answers_texts);

      $position = 1;
      foreach ($answers_values as $value) {
         if (is_numeric ($value)) {
            $query = "update poll_answer set position='$position', answer='".$answers_texts[$position-1]."' where id_poll_answer='$value'";
            mysql_query($query) or die("Invalid query: " . mysql_error());
         } else {
            $query = "insert into poll_answer (id_poll, answer, position) values ('$id', '$value', '$position')";
            mysql_query($query) or die("Invalid query: " . mysql_error());
         }
         $position++;
      }

      $erased = split(";", $erased_answers);
      foreach ($erased as $answer) {
         if (is_numeric ($answer)) {
            $query = "delete from poll_answer where id_poll_answer=$answer";
            mysql_query($query) or die("Invalid query: " . mysql_error());
         }
      }
   }
   
   if ($action == "erase2") {
      foreach ($id as $idx) {
         $query = "delete from poll where id_poll=$idx";
         mysql_query($query) or die("Invalid query: " . mysql_error());
         $query = "delete from poll_answer where id_poll=$idx";
         mysql_query($query) or die("Invalid query: " . mysql_error());
      }
   }

	header ("Location: poll.php?msg=$msg");
   exit;
?>