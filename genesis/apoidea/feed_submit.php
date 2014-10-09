<?php
   include("session.php");
	include("database.php");

	$page = $_POST['page'];
   $action = $_POST['action'];
   $id = $_POST['id'];
   
   $id = $_POST['id'];
   $title = $_POST['title'];
   $subtitle = $_POST['subtitle'];
   $url = $_POST['url'];
   $language = $_POST['language'];
   $publishDate = $_POST['publishDate'];
   $lastBuildDate = $_POST['lastBuildDate'];
   $documentationUrl = $_POST['documentationUrl'];
   $generator = $_POST['generator'];
   $managingEditor = $_POST['managingEditor'];
   $webmaster = $_POST['webmaster'];
   $copyright = $_POST['copyright'];
   $guid = $_POST['guid'];
   $sourceTypeID = $_POST['sourceTypeID'];
   $sourceDescription = $_POST['sourceDescription'];
   $ttl = $_POST['ttl'];
   $autoTTL = $_POST['autoTTL'];


   if ($action == "create2"){
      $query = "insert into feed (id, title, subtitle, url, language, publishDate, lastBuildDate, documentationUrl, generator, managingEditor, webmaster, copyright, guid, sourceTypeID, sourceDescription, ttl, autoTTL) values ('$id', '$title', '$subtitle', '$url', '$language', '$publishDate', '$lastBuildDate', '$documentationUrl', '$generator', '$managingEditor', '$webmaster', '$copyright', '$guid', '$sourceTypeID', '$sourceDescription', '$ttl', '$autoTTL')";
      mysql_query($query) or die("Invalid query: " . mysql_error());
   }

   if ($action == "modify2") {
      $query = "update feed set id='$id', title='$title', subtitle='$subtitle', url='$url', language='$language', publishDate='$publishDate', lastBuildDate='$lastBuildDate', documentationUrl='$documentationUrl', generator='$generator', managingEditor='$managingEditor', webmaster='$webmaster', copyright='$copyright', guid='$guid', sourceTypeID='$sourceTypeID', sourceDescription='$sourceDescription', ttl='$ttl', autoTTL='$autoTTL' where id=$id";
      mysql_query($query) or die("Invalid query: " . mysql_error());
   }
   
   if ($action == "erase2") {
      foreach ($id as $idx) {
         $query = "delete from feed where id=$idx";
         mysql_query($query) or die("Invalid query: " . mysql_error());
      }
   }
   
	header ("Location: feed.php?page=$page");
   exit;
?>
