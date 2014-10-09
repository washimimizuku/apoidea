<?php
   include("session.php");
	include("database.php");

   $action = $_REQUEST['action'];
   
   $id = $_POST['id'];
   $parent = $_POST['parent'];
   $article = $_POST['article'];
   $title = $_POST['title'];
   $text = $_POST['text'];
   $name = $_POST['name'];
   $email = $_POST['email'];
   $link = $_POST['link'];
   $active = $_POST['active'];
   
   $msg = "";
   
   if ($action == "create2"){
      $query = "insert into comment (id_parent, id_article, date, title, text, name, email, link, active) values ($parent, '$article', NOW(), '$title', '$text', '$name', '$email', '$link', '$active')";
      mysql_query($query) or die("Invalid query: " . mysql_error());
   }
   
   if ($action == "modify2") {
      $query = "update comment set id_parent=$parent, id_article='$article', title='$title', text='$text', name='$name', email='$email', link='$link', active='$active' where id_comment=$id";
      mysql_query($query) or die("Invalid query: " . mysql_error());
   }
   
   if ($action == "erase2") {
      foreach ($id as $idx) {
         $query = "select * from comment where id_parent='$idx'";
         $result = mysql_query($query) or die("Invalid query: " . mysql_error());
         $num = mysql_num_rows($result);
         
         if ($num > 0) {
            $query = "select title from comment where id_comment='$idx'";
            $result = mysql_query($query) or die("Invalid query: " . mysql_error());
            $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());

            $msg .= "Não foi possível apagar o comentário $row[0] devido a ter comentários associados a ele.<br>";
         } else {
            $query = "delete from comment where id_comment=$idx";
            $result = mysql_query($query) or die("Invalid query: " . mysql_error());
         }
      }
   }

	header ("Location: comment.php?msg=$msg");
   exit;
?>