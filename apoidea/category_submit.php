<?php
   include("session.php");
	include("database.php");

   $action = $_REQUEST['action'];
   
   $id = $_POST['id'];
   $root_category = $_POST['root_category'];
   $name = $_POST['name'];
   $stub = $_POST['stub'];
   $active = $_POST['active'];
   $feed = $_POST['feed'];
   
   $msg = "";
   
   if ($action == "create2"){
      if ($stub == "") {
         $stub = strtolower($name);
         #print $stub;
         $stub = preg_replace('/á|à|ã|â/', 'a', $stub);
         $stub = preg_replace('/é|è|ê/', 'e', $stub);
         $stub = preg_replace('/í|ì|î/', 'i', $stub);
         $stub = preg_replace('/ó|ò|õ|ô/', 'o', $stub);
         $stub = preg_replace('/ú|ù|û/', 'u', $stub);
         $stub = preg_replace('/ç/', 'c', $stub);
         $stub = preg_replace('/\W/' , '_', $stub);
         $stub = preg_replace('/__+/', '_', $stub);
      }
      $query = "insert into category (id_root_category, name, stub, active, feed) values ($root_category, '$name', '$stub', '$active', '$feed')";
      mysql_query($query) or die("Invalid query: " . mysql_error());
   }
   
   if ($action == "modify2") {
      $query = "update category set id_root_category=$root_category, name='$name', stub='$stub', active='$active', feed='$feed' where id_category=$id";
      mysql_query($query) or die("Invalid query: " . mysql_error());
   }
   
   if ($action == "erase2") {
      foreach ($id as $idx) {
         $query = "select * from category where id_root_category='$idx'";
         $result = mysql_query($query) or die("Invalid query: " . mysql_error());
         $num = mysql_num_rows($result);
         
         if ($num > 0) {
            $query = "select name from category where id_category='$idx'";
            $result = mysql_query($query) or die("Invalid query: " . mysql_error());
            $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());

            $msg .= "Não foi possível apagar a categoria $row[0] devido a ter categorias associadas a ela.<br>";
         } else {
            $query = "select * from rel_category_article where id_category='$idx'";
            $result = mysql_query($query) or die("Invalid query: " . mysql_error());
            $num = mysql_num_rows($result);

            if ($num > 0) {
               $query = "select name from category where id_category='$idx'";
               $result = mysql_query($query) or die("Invalid query: " . mysql_error());
               $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());

               $msg .= "Não foi possível apagar a categoria $row[0] devido a ter artigos associadas a ela.<br>";
            } else {
               $query = "delete from category where id_category=$idx";
               mysql_query($query) or die("Invalid query: " . mysql_error());
            }
         }
      }
   }

	header ("Location: category.php?msg=$msg");
   exit;
?>