<?php
	include("database.php");

   $action = $_REQUEST['action'];
   
   $id = $_POST['id'];
   $title = $_POST['title'];
   $url = $_POST['url'];
   $subtitle = $_POST['subtitle'];
   $language = $_POST['language'];
   $pub_day = $_POST['pub_day'];
   $pub_month = $_POST['pub_month'];
   $pub_year = $_POST['pub_year'];
   $last_day = $_POST['last_day'];
   $last_month = $_POST['last_month'];
   $last_year = $_POST['last_year'];
   $docs_url = $_POST['docs_url'];
   $generator = $_POST['generator'];
   $managing_editor = $_POST['managing_editor'];
   $webmaster = $_POST['webmaster'];
   $copyright = $_POST['copyright'];
   $source_type = $_POST['source_type'];
   $source_description = $_POST['source_description'];
   $ttl = $_POST['ttl'];
   
   $pub_date = $pub_year."-".$pub_month."-".$pub_day;
   $last_build_date = $last_year."-".$last_month."-".$last_day;
   
   $msg = "";
   
   if ($action == "create2"){
      $query = "insert into feed (title, url, subtitle, language, pub_date, last_build_date, docs_url, generator, managing_editor, webmaster, copyright, source_type, source_description, $ttl) "
              ."values ('$title', '$url', '$subtitle', '$language', '$pub_date', '$last_build_date', '$docs_url', '$generator',
'$managing_editor', '$webmaster', '$copyright', '$source_type', '$source_description', '$ttl')";
      mysql_query($query) or die("Invalid query: " . mysql_error());
   }
   
   if ($action == "modify2") {
      $query = "update feed set title='$title', url='$url', subtitle='$subtitle', language='$language', pub_date='$pub_date',
last_build_date='$last_build_date', docs_url='$docs_url', generator='$generator', managing_editor='$managing_editor',
webmaster='$webmaster', copyright='$copyright', source_type='$source_type', source_description='$source_description', ttl='$ttl' where id=$id";
      mysql_query($query) or die("Invalid query: " . mysql_error());
   }
   
   if ($action == "erase2") {
      foreach ($id as $idx) {
         $query = "delete from feed where id=$idx";
         mysql_query($query) or die("Invalid query: " . mysql_error());
      }
   }

	header ("Location: feed.php?msg=$msg");
   exit;
?>
