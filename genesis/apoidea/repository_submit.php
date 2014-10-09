<?php
   include("session.php");
	include("database.php");

	$page = $_POST['page'];
   $action = $_POST['action'];
   $id = $_POST['id'];
   
   $id = $_POST['id'];
   $feedID = $_POST['feedID'];
   $title = $_POST['title'];
   $description = $_POST['description'];
   $publishDate = $_POST['publishDate'];
   $author = $_POST['author'];
   $category = $_POST['category'];
   $commentsURL = $_POST['commentsURL'];
   $guid = $_POST['guid'];


   if ($action == "create2"){
      $query = "insert into repository (id, feedID, title, description, publishDate, author, category, commentsURL, guid) values ('$id', '$feedID', '$title', '$description', '$publishDate', '$author', '$category', '$commentsURL', '$guid')";
      mysql_query($query) or die("Invalid query: " . mysql_error());
   }

   if ($action == "modify2") {
      $query = "update repository set id='$id', feedID='$feedID', title='$title', description='$description', publishDate='$publishDate', author='$author', category='$category', commentsURL='$commentsURL', guid='$guid' where id=$id";
      mysql_query($query) or die("Invalid query: " . mysql_error());
   }
   
   if ($action == "erase2") {
      foreach ($id as $idx) {
         $query = "delete from repository where id=$idx";
         mysql_query($query) or die("Invalid query: " . mysql_error());
      }
   }
   
	header ("Location: repository.php?page=$page");
   exit;
?>
