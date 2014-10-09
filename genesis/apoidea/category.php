<?php
   // Action
   $action = $_REQUEST['action'];
   $search = $_REQUEST['search'];
   $order  = $_REQUEST['order'];

   $id = $_REQUEST['id'];
   if (!isset($id)) {
      if ($action == "modify") {
         header ("Location: category.php?msg=Tem+de+seleccionar+uma+das+linha!");
         exit;
      } else if ($action == "erase") {
         header ("Location: category.php?msg=Tem+de+seleccionar+pelo+menos+uma+linha!");
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
   <form name="form" method="post" action="category.php">
      <div id="content">
         <h2>Categoria</h2>
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
                  <td>Nome</td>
                  <td>Stub</td>
                  <td>Activa</td>
                  <td>Feed</td>

                  <td></td>
                  <td></td>
               </tr>
<?php
   $query = "select name, stub, active, feed from category order by $order $direction limit $begin, ".($pageSize + 1);
   if ($search) {
      $query = "select name, stub, active, feed from category where  order by $order $direction limit $begin, ".($pageSize + 1);  
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
                  <td><?php echo($row[0]); ?></td>
                  <td><?php echo($row[1]); ?></td>
                  <td><?php echo($row[2]); ?></td>
                  <td><?php echo($row[3]); ?></td>

                  <td><a href="category.php?action=modify&id%5B%5D=<?php echo($row[0]); ?>&page=<?php echo($page); ?>"><img class="small_button" src="images/refresh.png" /></a></td>
                  <td><a href="category.php?action=erase&id%5B%5D=<?php echo($row[0]); ?>&page=<?php echo($page); ?>"><img class="small_button" src="images/delete.png" /></a></td>
               </tr>
<?php
   }
?>
               <tr class="title">
                  <td id="paging_row" colspan="7">
                     <div id="paging">
                        <ul>
                           <li class="left">
<?php
      $query = "select count(*) from category";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
      $num = $row[0] / $pageSize;
      
      if (($num > 5) && ($page > 0)) {
?>
                              <a href="category.php?page=0"><img class="small_button" src="images/previous2.png" /></a>
<?php
      }
      if ($page > 0) {
?>
                              <a href="category.php?page=<? echo($page - 1); ?>"><img class="small_button" src="images/previous.png" /></a>
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
                              <a href="category.php?page=<? echo($i); ?>"><? echo($i + 1); ?></a>
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
                              <a href="category.php?page=<? echo($page + 1); ?>"><img class="small_button" src="images/next.png" /></a>
<?php
      } else {
?>
                              &nbsp;
<?php
      }
      if (($num > 5) && ($page < $num - 1)) {
?>
                              <a href="category.php?page=<? echo(intval($num-0.01)); ?>"><img class="small_button" src="images/next2.png" /></a>
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
      <h2><a href="category.php">Categoria</a> > Criar Categoria</h2>
<?php
	if ($msg) {
?>
      <p class="message"><?php echo($msg) ?></p>
<?php
	}
?>
      <div class="tabela">
         <form name="form" method="post" action="category_submit.php">
            <input type="hidden" name="action" value="create2" />
            <input type="hidden" name="id" value="<?php echo($id) ?>" />
            <table>
               <tr>
                  <td class="title">Categoria Acima</td>
                  <td class="dark">
                     <select name="parentCategoryID">
                        <option value="0">[Nada]</option>
<?php
      $query_category = "select id, name from category order by id";
      $result_category = mysql_query($query_category) or die("Invalid query: " . mysql_error());
      for ($i = 0; $i < mysql_num_rows($result_category); $i++) {
         $row_category = mysql_fetch_row($result_category) or die("Could not retrieve row: " . mysql_error());
?>
                        <option value="<?php echo($row_category[0]); ?>"><?php echo($row_category[1]); ?></option>
<?php
      }
?>
                     </select>
                  </td>
               </tr>
               <tr>
                  <td class="title">Nome</td>
                  <td class="dark">
                     <input type="text" size="40" name="name" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Stub</td>
                  <td class="dark">
                     <input type="text" size="40" name="stub" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Activa</td>
                  <td class="dark">
                     <input type="text" size="40" name="active" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Feed</td>
                  <td class="dark">
                     <input type="text" size="40" name="feed" />
                  </td>
               </tr>

               <tr><td class="buttons" colspan="2"><input class="image_button" type="image" src="images/criar.png" value="Criar" onclick="javascript: form.submit;" /><input class="image_button" type="image" src="images/cancelar.png" value="Cancelar" onclick="javascript:document.location='category.php';return false;" /></td></tr>
            </table>
         </form>
      </div>
   </div>
<?php
   }
?>
<?php
   if ($action == "modify"){
      $query = "select parentCategoryID, name, stub, active, feed from category where id='$id[0]'";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
?>
   <div id="content">
      <h2><a href="category.php">Categoria</a> > Alterar Categoria</h2>
      <div class="tabela">
         <form name="form" method="post" action="category_submit.php">
            <input type="hidden" name="creation_date" value="<?php echo($creation_date) ?>" />
            <input type="hidden" name="page" value="<?php echo($page) ?>" />
            <input type="hidden" name="action" value="modify2" />
            <input type="hidden" name="id" value="<?php echo($id[0]) ?>" />
            <table>
               <tr>
                  <td class="title">Categoria Acima</td>
                  <td class="dark">
                     <select name="parentCategoryID">
                        <option value="0">[Nada]</option>
<?php
      $query_category = "select id, name from category order by id";
      $result_category = mysql_query($query_category) or die("Invalid query: " . mysql_error());
      $selected = "";
      for ($i = 0; $i < mysql_num_rows($result_category); $i++) {
         $row_category = mysql_fetch_row($result_category) or die("Could not retrieve row: " . mysql_error());
         if ($row_category[0] == $row[0]) {
            $selected = " selected";
         } else {
            $selected = "";
         }
?>
                        <option value="<?php echo($row_category[0]); ?>"<?php echo($selected); ?>><?php echo($row_category[1]); ?></option>
<?php
      }
?>
                     </select>
                  </td>
               </tr>
               <tr>
                  <td class="title">Nome</td>
                  <td class="dark">
                     <input type="text" size="40" name="name" value="<?php echo($row[1]); ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Stub</td>
                  <td class="dark">
                     <input type="text" size="40" name="stub" value="<?php echo($row[2]); ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Activa</td>
                  <td class="dark">
                     <input type="text" size="40" name="active" value="<?php echo($row[3]); ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Feed</td>
                  <td class="dark">
                     <input type="text" size="40" name="feed" value="<?php echo($row[4]); ?>" />
                  </td>
               </tr>

               <tr><td class="buttons" colspan="2"><input class="image_button" type="image" src="images/alterar.png" value="Alterar" onclick="javascript: form.submit;" /><input class="image_button" type="image" src="images/cancelar.png" value="Cancelar" onclick="javascript:document.location='category.php';return false;" /></td></tr>
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
      <h2><a href="category.php">Categoria</a> > Apagar Categoria</h2>
      <div class="tabela">
         <form method="post" action="category_submit.php">
            <input type="hidden" name="page" value="<?php echo($page) ?>" />
            <input type="hidden" name="action" value="erase2" />
            <table>
               <tr class="title">
                  <td>Categoria Acima</td>
                  <td>Nome</td>
                  <td>Stub</td>
                  <td>Activa</td>
                  <td>Feed</td>

               </tr>
<?php
      foreach ($id as $idx) {
         $query = "select parentCategoryID, name, stub, active, feed from category where id='$idx'";
         $result = mysql_query($query) or die("Invalid query: " . mysql_error());
         $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
?>
               <tr class="dark">
<?php
         $query_category = "select name from category where id=$row[0]";
         $result_category = mysql_query($query_category) or die("Invalid query: " . mysql_error());
         $row_category = mysql_fetch_row($result_category) or die("Could not retrieve row: " . mysql_error());
         $category = $row_category[0];
?>
                  <td><?php echo($category); ?></td>
                  <td><?php echo($row[1]); ?></td>
                  <td><?php echo($row[2]); ?></td>
                  <td><?php echo($row[3]); ?></td>
                  <td><?php echo($row[4]); ?></td>

               </tr>
					<input type="hidden" name="id[]" value="<?php echo($idx); ?>" />
<?php
      }
?>
               <tr><td class="buttons" colspan="8"><input class="image_button" type="image" src="images/apagar.png" value="Apagar" onclick="javascript: form.submit;" /><input class="image_button" type="image" src="images/cancelar.png" value="Cancelar" onclick="javascript:document.location='category.php';return false;" /></td></tr>
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
