<?php
   include("session.php");
	include("database.php");

   $page = $_POST['page'];
   $action = $_POST['action'];
   $id = $_POST['id'];
   
   $category = $_POST['category'];
   $title = $_POST['title'];
   $subtitle = $_POST['subtitle'];
   $teaser = $_POST['teaser'];
   $text = $_POST['text'];
   $creation_date = $_POST['creation_date'];
   $media = $_POST['media'];
   $link = $_POST['link'];
   $tags = $_POST['tags'];

   $allTags = array();
   if ($tags) {
      $allTags = split(" ", $tags);
      $processedTags = array();
      $temp = "";
      foreach ($allTags as $tag) {
         if (substr($tag,0,2) == '\"') {
            $temp .= substr($tag,2);
            
            if (substr($tag,-2) == '\"') {
               array_push($processedTags, substr($temp,0,strlen($temp)-2));
               $temp = "";
            }
         } else if ($temp != "") {
            if (substr($tag,-2) == '\"') {
               $temp .= ' '.substr($tag,0,strlen($tag)-2);
               array_push($processedTags, $temp);
               $temp = "";
            } else {
               $temp .= ' '.$tag;
            }
         } else if ($temp == "") {
            array_push($processedTags, $tag);
         }
      }
      array_push($processedTags, $temp);
      $allTags = $processedTags;
   }

   $text = preg_replace('/\n/',"<br />", $text);
   if ($action == "create2"){
      $query = "insert into article(title, subtitle, teaser, text, link, creation_date, active) values ('$title', '$subtitle', '$teaser', '$text', '$link', DATE_ADD( now( ) , INTERVAL 6 HOUR ), 1)";
      mysql_query($query) or die("Invalid query: " . mysql_error());
      
      $query = "select id_article from article where title='$title' and subtitle='$subtitle' order by id_article desc";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
      $id = $row[0];
      
      foreach ($category as $cat) {
         if ($cat != 0) {
            $query = "insert into rel_category_article(id_article, id_category) values ('$id', '$cat')";
            mysql_query($query) or die("Invalid query: " . mysql_error());
         }
      }
      
      $query = "insert into rel_article_media(id_article, id_media) values ('$id', '$media')";
      mysql_query($query) or die("Invalid query: " . mysql_error());
      
      # Tags
      if ($tags) {
         foreach ($allTags as $tag) {
            $tag = rtrim($tag);
            if ($tag != '') {
               $query = "select * from tag where name='$tag'";
               $result = mysql_query($query) or die("Invalid query: " . mysql_error());
               if (mysql_num_rows($result)) {
                  $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
               } else {
                  $query = "insert into tag values ('', '$tag');";
                  mysql_query($query) or die("Invalid query: " . mysql_error());
            
                  $query = "select * from tag where name='$tag'";
                  $result = mysql_query($query) or die("Invalid query: " . mysql_error());
                  $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
               }
               
               $query = "insert into rel_tag_article values ($row[0], $id);";
               mysql_query($query) or die("Invalid query: " . mysql_error());               
            }
         }
      }
   }

   if ($action == "modify2") {
      $query = "update article set title='$title', subtitle='$subtitle', teaser='$teaser', text='$text', link='$link', creation_date='$creation_date' where id_article=$id";
      mysql_query($query) or die("Invalid query: " . mysql_error());
      $query = "delete from rel_category_article where id_article=$id";
      mysql_query($query) or die("Invalid query: " . mysql_error());

      foreach ($category as $cat) {
         if ($cat != 0) {
            $query = "insert into rel_category_article(id_article, id_category) values ('$id', '$cat')";
            mysql_query($query) or die("Invalid query: " . mysql_error());
         }
      }
      
      $query = "delete from rel_article_media where id_article=$id";
      mysql_query($query) or die("Invalid query: " . mysql_error());
      $query = "insert into rel_article_media(id_article, id_media) values ('$id', '$media')";
      mysql_query($query) or die("Invalid query: " . mysql_error());

      # Tags
      $query = "delete from rel_tag_article where id_article=$id";
      mysql_query($query) or die("Invalid query: " . mysql_error());
      if ($tags) {
         foreach ($allTags as $tag) {
            $tag = rtrim($tag);
            if ($tag != '') {
               $query = "select * from tag where name='$tag'";
               $result = mysql_query($query) or die("Invalid query: " . mysql_error());
               if (mysql_num_rows($result)) {
                  $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
               } else {
                  $query = "insert into tag values ('', '$tag');";
                  mysql_query($query) or die("Invalid query: " . mysql_error());
            
                  $query = "select * from tag where name='$tag'";
                  $result = mysql_query($query) or die("Invalid query: " . mysql_error());
                  $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
               }
               
               $query = "insert into rel_tag_article values ($row[0], $id);";
               mysql_query($query) or die("Invalid query: " . mysql_error());               
            }
         }
      }
   }
   
   if ($action == "erase2") {
      foreach ($id as $idx) {
         $query = "delete from article where id_article=$idx";
         mysql_query($query) or die("Invalid query: " . mysql_error());
         $query = "delete from rel_category_article where id_article=$idx";
         mysql_query($query) or die("Invalid query: " . mysql_error());
         $query = "delete from rel_article_media where id_article=$idx";
         mysql_query($query) or die("Invalid query: " . mysql_error());
         $query = "delete from rel_tag_article where id_article=$idx";
         mysql_query($query) or die("Invalid query: " . mysql_error());
      }
   }
   
	header ("Location: article.php?page=$page");
   exit;
?>
