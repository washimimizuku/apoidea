<?php
   // Action
   $action = $_REQUEST['action'];
   $search = $_REQUEST['search'];
   $order  = $_REQUEST['order'];

   $id = $_REQUEST['id'];
   if (!isset($id)) {
      if ($action == "modify") {
         header ("Location: repository.php?msg=Tem+de+seleccionar+uma+das+linha!");
         exit;
      } else if ($action == "erase") {
         header ("Location: repository.php?msg=Tem+de+seleccionar+pelo+menos+uma+linha!");
         exit;
      }
   }

   include("session.php");
      
   $msg = $_REQUEST['msg'];
         
   include("header.php");
   include("menu.php");   
?>
<?php
   if (($action == "") || ($action == "search")) {
   
	if ($order == "") {
      $order = "id";
   }
   $direction  = $_REQUEST['direction'];
   if ($direction == "") {
      $direction = "desc";
   }
   // List Pages
   $page = $_REQUEST['page'];
   if (!$page) {
      $page = 0;
   }
	$pageSize = 20;
   $begin = $page * $pageSize;
   $end = $inicio + $pageSize;

?>
   <form name="form" method="post" action="repository.php">
      <div id="content">
         <h2>Repositório</h2>
<?php
	if ($msg) {
?>
         <p class="message_left"><?php echo($msg) ?></p>
<?php
	}
?>
         <div id="action_menu">
            <div id="search">
               <input type="text" name="search" /><input class="image_button" type="image" src="images/procurar.png" value="Procurar" onclick="javascript: form.action.value='search'; form.submit;" />
               <script type="text/javascript">document.form.search.focus()</script>
            </div>
            <input class="image_button" type="image" src="images/criar.png" value="Criar" onclick="javascript: form.action.value='create'; form.submit;" /><input class="image_button" type="image" src="images/alterar.png" value="Alterar" onclick="javascript: form.action.value='modify'; form.submit;" /><input class="image_button" type="image" src="images/apagar.png" value="Apagar" onclick="javascript: form.action.value='erase'; form.submit;" /><input type="hidden" name="action" value="" />
         </div>
         <div class="tabela">
            <table border="0" cellspacing="0">
               <tr class="title">
                  <td class="checkbox"><input type="checkbox" name="all" onclick="setAllCheckBoxes('form', 'id[]');" /></td>

                  <td></td>
                  <td></td>
               </tr>
<?php
   $query = "select  from repository order by $order $direction limit $begin, ".($pageSize + 1);
   if ($search) {
      $query = "select  from repository where  order by $order $direction limit $begin, ".($pageSize + 1);  
   }
   $result = mysql_query($query) or die("Invalid query: " . mysql_error());
   $num = mysql_num_rows($result);
   if ($num < $pageSize) {
      $end = $num;
   }
   for ($i = 0; $i < $end; $i++) {
      $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
      if ($i % 2) {
?>
               <tr class="dark">
<?php
      } else {
?>
               <tr class="light">
<?php
      }
?>
                  <td class="checkbox"><input type="checkbox" name="id[]" value="<?php echo($row[0]); ?>" /></td>

                  <td><a href="repository.php?action=modify&id%5B%5D=<?php echo($row[0]); ?>&page=<?php echo($page); ?>"><img class="small_button" src="images/refresh.png" /></a></td>
                  <td><a href="repository.php?action=erase&id%5B%5D=<?php echo($row[0]); ?>&page=<?php echo($page); ?>"><img class="small_button" src="images/delete.png" /></a></td>
               </tr>
<?php
   }
?>
               <tr class="title">
                  <td id="paging_row" colspan="3">
                     <div id="paging">
                        <ul>
                           <li class="left">
<?php
      $query = "select count(*) from repository";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
      $num = $row[0] / $pageSize;
      
      if (($num > 5) && ($page > 0)) {
?>
                              <a href="repository.php?page=0"><img class="small_button" src="images/previous2.png" /></a>
<?php
      }
      if ($page > 0) {
?>
                              <a href="repository.php?page=<? echo($page - 1); ?>"><img class="small_button" src="images/previous.png" /></a>
<?php
      } else {
?>
                              &nbsp;
<?php
      }
?>
                           </li>
                           <li class="center">
<?php
      if ($num > 1) {
         $ini = $page - 2;
         if ($ini > intval($num-0.01) - 4) { $ini = intval($num-0.01) - 4;}
         if ($ini < 0) { $ini = 0;}
         
         $j = 0;
         for ($i = $ini; ($i < $num) && ($j < 5); $i++) {
            $j++;
            if ($i == $page) {
?>
                              <? echo($i + 1); ?>
<?php
            } else {
?>
                              <a href="repository.php?page=<? echo($i); ?>"><? echo($i + 1); ?></a>
<?php
            }
         }
      } else {
?>
                              &nbsp;
<?php
      }
?>
                           </li>
                           <li class="right">
<?php
      if ($page < $num - 1) {
?>
                              <a href="repository.php?page=<? echo($page + 1); ?>"><img class="small_button" src="images/next.png" /></a>
<?php
      } else {
?>
                              &nbsp;
<?php
      }
      if (($num > 5) && ($page < $num - 1)) {
?>
                              <a href="repository.php?page=<? echo(intval($num-0.01)); ?>"><img class="small_button" src="images/next2.png" /></a>
<?php
      }
?>
                           </li>
                        </ul>
                     </div>
                  </td>
               </tr>
            </table>
         </div>
      </div>
   </form>
<?php
   }
?>
<?php
   if ($action == "create")  {
?>
   <div id="content">
      <h2><a href="repository.php">Repositório</a> > Criar Repositório</h2>
<?php
	if ($msg) {
?>
      <p class="message"><?php echo($msg) ?></p>
<?php
	}
?>
      <div class="tabela">
         <form name="form" method="post" action="repository_submit.php">
            <input type="hidden" name="action" value="create2" />
            <input type="hidden" name="id" value="<?php echo($id) ?>" />
            <table>
               <tr>
                  <td class="title">Feed</td>
                  <td class="dark">
                     <select name="feedID">
                        <option value="0">[Nada]</option>
<?php
      $query_feed = "select id, title from feed order by id";
      $result_feed = mysql_query($query_feed) or die("Invalid query: " . mysql_error());
      for ($i = 0; $i < mysql_num_rows($result_feed); $i++) {
         $row_feed = mysql_fetch_row($result_feed) or die("Could not retrieve row: " . mysql_error());
?>
                        <option value="<?php echo($row_feed[0]); ?>"><?php echo($row_feed[1]); ?></option>
<?php
      }
?>
                     </select>
                  </td>
               </tr>
               <tr>
                  <td class="title">Título</td>
                  <td class="dark">
                     <input type="text" size="40" name="title" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Descrição</td>
                  <td class="dark">
                     <input type="text" size="40" name="description" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Data de publicação</td>
                  <td class="dark">
                     <input type="text" size="40" name="publishDate" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Autor</td>
                  <td class="dark">
                     <input type="text" size="40" name="author" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Categoria</td>
                  <td class="dark">
                     <input type="text" size="40" name="category" />
                  </td>
               </tr>
               <tr>
                  <td class="title">URL de Comentários</td>
                  <td class="dark">
                     <input type="text" size="40" name="commentsURL" />
                  </td>
               </tr>
               <tr>
                  <td class="title">GUID</td>
                  <td class="dark">
                     <input type="text" size="40" name="guid" />
                  </td>
               </tr>

               <tr><td class="buttons" colspan="2"><input class="image_button" type="image" src="images/criar.png" value="Criar" onclick="javascript: form.submit;" /><input class="image_button" type="image" src="images/cancelar.png" value="Cancelar" onclick="javascript:document.location='repository.php';return false;" /></td></tr>
            </table>
         </form>
      </div>
   </div>
<?php
   }
?>
<?php
   if ($action == "modify"){
      $query = "select feedID, title, description, publishDate, author, category, commentsURL, guid from repository where id='$id[0]'";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
?>
   <div id="content">
      <h2><a href="repository.php">Repositório</a> > Alterar Repositório</h2>
      <div class="tabela">
         <form name="form" method="post" action="repository_submit.php">
            <input type="hidden" name="creation_date" value="<?php echo($creation_date) ?>" />
            <input type="hidden" name="page" value="<?php echo($page) ?>" />
            <input type="hidden" name="action" value="modify2" />
            <input type="hidden" name="id" value="<?php echo($id[0]) ?>" />
            <table>
               <tr>
                  <td class="title">Feed</td>
                  <td class="dark">
                     <select name="feedID">
                        <option value="0">[Nada]</option>
<?php
      $query_feed = "select id, title from feed order by id";
      $result_feed = mysql_query($query_feed) or die("Invalid query: " . mysql_error());
      $selected = "";
      for ($i = 0; $i < mysql_num_rows($result_feed); $i++) {
         $row_feed = mysql_fetch_row($result_feed) or die("Could not retrieve row: " . mysql_error());
         if ($row_feed[0] == $row[0]) {
            $selected = " selected";
         } else {
            $selected = "";
         }
?>
                        <option value="<?php echo($row_feed[0]); ?>"<?php echo($selected); ?>><?php echo($row_feed[1]); ?></option>
<?php
      }
?>
                     </select>
                  </td>
               </tr>
               <tr>
                  <td class="title">Título</td>
                  <td class="dark">
                     <input type="text" size="40" name="title" value="<?php echo($row[1]); ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Descrição</td>
                  <td class="dark">
                     <input type="text" size="40" name="description" value="<?php echo($row[2]); ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Data de publicação</td>
                  <td class="dark">
                     <input type="text" size="40" name="publishDate" value="<?php echo($row[3]); ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Autor</td>
                  <td class="dark">
                     <input type="text" size="40" name="author" value="<?php echo($row[4]); ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Categoria</td>
                  <td class="dark">
                     <input type="text" size="40" name="category" value="<?php echo($row[5]); ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">URL de Comentários</td>
                  <td class="dark">
                     <input type="text" size="40" name="commentsURL" value="<?php echo($row[6]); ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">GUID</td>
                  <td class="dark">
                     <input type="text" size="40" name="guid" value="<?php echo($row[7]); ?>" />
                  </td>
               </tr>

               <tr><td class="buttons" colspan="2"><input class="image_button" type="image" src="images/alterar.png" value="Alterar" onclick="javascript: form.submit;" /><input class="image_button" type="image" src="images/cancelar.png" value="Cancelar" onclick="javascript:document.location='repository.php';return false;" /></td></tr>
            </table>
         </form>
      </div>
   </div>
<?php
   }
?>
<?php
   if ($action == "erase") {
?>
   <div id="content">
      <h2><a href="repository.php">Repositório</a> > Apagar Repositório</h2>
      <div class="tabela">
         <form method="post" action="repository_submit.php">
            <input type="hidden" name="page" value="<?php echo($page) ?>" />
            <input type="hidden" name="action" value="erase2" />
            <table>
               <tr class="title">
                  <td>Feed</td>
                  <td>Título</td>
                  <td>Descrição</td>
                  <td>Data de publicação</td>
                  <td>Autor</td>
                  <td>Categoria</td>
                  <td>URL de Comentários</td>
                  <td>GUID</td>

               </tr>
<?php
      foreach ($id as $idx) {
         $query = "select feedID, title, description, publishDate, author, category, commentsURL, guid from repository where id='$idx'";
         $result = mysql_query($query) or die("Invalid query: " . mysql_error());
         $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
?>
               <tr class="dark">
<?php
         $query_feed = "select title from feed where id=$row[0]";
         $result_feed = mysql_query($query_feed) or die("Invalid query: " . mysql_error());
         $row_feed = mysql_fetch_row($result_feed) or die("Could not retrieve row: " . mysql_error());
         $feed = $row_feed[0];
?>
                  <td><?php echo($feed); ?></td>
                  <td><?php echo($row[1]); ?></td>
                  <td><?php echo($row[2]); ?></td>
                  <td><?php echo($row[3]); ?></td>
                  <td><?php echo($row[4]); ?></td>
                  <td><?php echo($row[5]); ?></td>
                  <td><?php echo($row[6]); ?></td>
                  <td><?php echo($row[7]); ?></td>

               </tr>
					<input type="hidden" name="id[]" value="<?php echo($idx); ?>" />
<?php
      }
?>
               <tr><td class="buttons" colspan="11"><input class="image_button" type="image" src="images/apagar.png" value="Apagar" onclick="javascript: form.submit;" /><input class="image_button" type="image" src="images/cancelar.png" value="Cancelar" onclick="javascript:document.location='repository.php';return false;" /></td></tr>
            </table>
         </form>
      </div>
   </div>
<?php
   } 
?>
<?php
   include ("footer.php");
?>
