<?php
   include("session.php");
	include("database.php");

   $action = $_REQUEST['action'];
   
   $id = $_POST['id'];
   $title = $_POST['title'];
   $all_links_values = $_POST['all_links_values'];
   $all_links_texts = $_POST['all_links_texts'];
   $erased_links = $_POST['erased_links'];
   
   $msg = "";
   
   if ($action == "create2"){
      $query = "insert into link_list (title) values ('$title')";
      mysql_query($query) or die("Invalid query: " . mysql_error());

      $query = "select id from link_list where title='$title' order by id desc";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());

      $links = split(";", $all_links_texts);
      foreach ($links as $link) {
         list ($name, $url, $target) = split("\|", $link);
      
         $query = "insert into link (id_link_list, name, url, target) values ('$row[0]', '$name', '$url', '$target')";
         mysql_query($query) or die("Invalid query: " . mysql_error());
      }
   }
   
   if ($action == "modify2") {
      $query = "update link_list set title='$title' where id=$id";
      mysql_query($query) or die("Invalid query: " . mysql_error());

      $links_values = split(";", $all_links_values);
      $links_texts = split(";", $all_links_texts);

      $position = 1;
      foreach ($links_values as $value) {
         if (is_numeric ($value)) {
            list ($name, $url, $target) = split("\|", $links_texts[$position-1]);
            
            $query = "update link set position='$position', name='$name', url='$url', target='$target' where id='$value'";
            mysql_query($query) or die("Invalid query: " . mysql_error());
         } else {
            list ($name, $url, $target) = split("\|", $value);
            
            $query = "insert into link (id_link_list, name, url, target, position) values ('$id', '$name', '$url', '$target', '$position')";
            mysql_query($query) or die("Invalid query: " . mysql_error());
         }
         $position++;
      }

      $erased = split(";", $erased_links);
      foreach ($erased as $link) {
         if (is_numeric ($link)) {
            $query = "delete from link where id=$link";
            mysql_query($query) or die("Invalid query: " . mysql_error());
         }
      }
   }
   
   if ($action == "erase2") {
      foreach ($id as $idx) {
         $query = "delete from link_list where id=$idx";
         mysql_query($query) or die("Invalid query: " . mysql_error());
         $query = "delete from link where id_link_list=$idx";
         mysql_query($query) or die("Invalid query: " . mysql_error());
      }
   }

	header ("Location: link_list.php?msg=$msg");
   exit;
?>