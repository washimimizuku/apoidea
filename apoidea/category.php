<?php
   // Action
   $action = $_REQUEST['action'];
   $search = $_REQUEST['search'];
   $order  = $_REQUEST['order'];
   if ($order == "") {
      $order = "id_category";
   }
   $direction  = $_REQUEST['direction'];
   if ($direction == "") {
      $direction = "desc";
   }

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
         
   // List Pages
   $page = $_REQUEST['page'];
   if (!$page) {
      $page = 0;
   }
   $begin = $page * 10;
   $end = $inicio + 10;
   
   include("header.php");
   include("menu.php");

   if (($action == "") || ($action == "search")) {
?>
   <form name="form" name="form" method="post" action="category.php">
      <div id="content">
         <h2>Categorias</h2>
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
            <table>
               <tr class="title">
                  <td class="checkbox"><input type="checkbox" name="all" onclick="setAllCheckBoxes('form', 'id[]');" /></td>
                  <td>ID</td>
                  <td>Categoria Acima <a href="category.php?order=id_root_category&direction=asc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/up.png" /></a><a href="category.php?order=id_root_category&direction=desc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/down.png" /></a></td>
                  <td>Nome <a href="category.php?order=name&direction=asc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/up.png" /></a><a href="category.php?order=name&direction=desc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/down.png" /></a></td>
                  <td>Stub <a href="category.php?order=stub&direction=asc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/up.png" /></a><a href="category.php?order=stub&direction=desc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/down.png" /></a></td>
                  <td>Activa</td>
                  <td>Feed</td>
                  <td></td>
                  <td></td>
               </tr>
<?php
      $query = "select id_category, id_root_category, name, stub, active, feed from category order by $order $direction limit $begin, 11";
      if ($search) {
         $query = "select id_category, id_root_category, name, stub, active, feed from category where name like '%$search%' order by $order $direction limit $begin, 11";
      }
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      $num = mysql_num_rows($result);
      if ($num < 10) {
         $end = $num;
      }
      $root = "";
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
      if ($row[1] == 0) {
         $root = "[Nenhuma]";
      } else {
         $query = "select name from category where id_category=$row[1]";
         $result2 = mysql_query($query) or die("Invalid query: " . mysql_error());
         $row2 = mysql_fetch_row($result2) or die("Could not retrieve row: " . mysql_error());
         $root = $row2[0];
      }
?>
                  <td class="checkbox"><input type="checkbox" name="id[]" value="<?php echo($row[0]); ?>" /></td>
                  <td><?php echo($row[0]); ?></td>
                  <td><?php echo($root); ?></td>
                  <td><?php echo($row[2]); ?></td>
                  <td><?php echo($row[3]); ?></td>
                  <td><?php if ($row[4] == 1) { echo('Sim'); } else { echo('Não'); } ?></td>
                  <td><?php if ($row[5] == 1) { echo('Sim'); } else { echo('Não'); } ?></td>
                  <td><a href="category.php?action=modify&id%5B%5D=<?php echo($row[0]); ?>"><img class="small_button" src="images/refresh.png" /></a></td>
                  <td><a href="category.php?action=erase&id%5B%5D=<?php echo($row[0]); ?>"><img class="small_button" src="images/delete.png" /></a></td>
               </tr>
<?php
      }
?>
               <tr class="title">
                  <td id="paging_row" colspan="9">
                     <div id="paging">
                        <ul>
                           <li class="left">
<?php
      $query = "select count(*) from category";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
      $num = $row[0] / 10;
      
      if (($num > 5) && ($page > 0)) {
?>
                              <a href="category.php?page=0"><img class="small_button" src="images/double_previous.png" /></a>
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
                              <a href="category.php?page=<? echo(intval($num-0.01)); ?>"><img class="small_button" src="images/double_next.png" /></a>
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
   } if ($action == "create")  {
?>
   <div id="content">
      <h2><a href="category.php">Categorias</a> > Criar Categoria</h2>
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
                     <select name="root_category">
                        <option value="0">Nenhuma</option>
<?php
      $query = "select id_category, name from category order by id_category";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      for ($i = 0; $i < mysql_num_rows($result); $i++) {
         $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
?>
                        <option value="<?php echo($row[0]); ?>"><?php echo($row[1]); ?></option>
<?php
      }
?>
                     </select>
                     <script type="text/javascript">document.form.root_category.focus()</script>
                  </td>
               </tr>
               <tr>
                  <td class="title">Nome</td>
                  <td class="dark">
                     <input type="text" name="name" />
                     <script type="text/javascript">document.form.name.focus()</script>
                  </td>
              </tr>
               <tr>
                  <td class="title">Stub</td>
                  <td class="dark">
                     <input type="text" name="stub" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Activa</td>
                  <td class="dark">
                     <input type="radio" name="active" value="1" checked /> Sim <input type="radio" name="active" value="0" /> Não
                  </td>
               </tr>
               <tr>
                  <td class="title">Feed</td>
                  <td class="dark">
                     <input type="radio" name="feed" value="1" checked /> Sim <input type="radio" name="feed" value="0" /> Não
                  </td>
               </tr>
               <tr><td class="buttons" colspan="2"><input class="image_button" type="image" src="images/criar.png" value="Criar" onclick="javascript: form.submit;" /><input class="image_button" type="image" src="images/cancelar.png" value="Cancelar" onclick="javascript:document.location='category.php';return false;" /></td></tr>
            </table>
         </form>
      </div>
   </div>
<?php
   } else if ($action == "modify"){
      $query = "select id_root_category, name, stub, active, feed from category where id_category='$id[0]'";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());

      $root_category = $row[0];
      $name = $row[1];
      $stub = $row[2];
      $active = $row[3];
      $feed = $row[4];
?>
   <div id="content">
      <h2><a href="category.php">Categorias</a> > Alterar Categoria</h2>
      <div class="tabela">
         <form name="form" method="post" action="category_submit.php">
            <input type="hidden" name="action" value="modify2" />
            <input type="hidden" name="id" value="<?php echo($id[0]) ?>" />
            <table>
               <tr>
                  <td class="title">Categoria Acima</td>
                  <td class="dark">
                     <select name="root_category">
                        <option value="0">Nenhuma</option>
<?php
      $query = "select id_category, name from category order by id_category";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      $selected = "";
      for ($i = 0; $i < mysql_num_rows($result); $i++) {
         $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
         if ($root_category == $row[0]) {
            $selected = " selected";
         } else {
            $selected = "";
         }
?>
                        <option value="<?php echo($row[0]); ?>"<?php echo($selected); ?>><?php echo($row[1]); ?></option>
<?php
      }
?>
                     </select>
                     <script type="text/javascript">document.form.root_category.focus()</script>
                  </td>
               </tr>
               <tr>
                  <td class="title">Nome</td>
                  <td class="dark">
                     <input type="text" name="name" value="<?php echo($name); ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Stub</td>
                  <td class="dark">
                     <input type="text" name="stub" value="<?php echo($stub); ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Activa</td>
                  <td class="dark">
                     <input type="radio" name="active" value="1"<?php if($active) {echo(" checked");} ?> /> Sim <input type="radio" name="active" value="0"<?php if(!($active)) {echo(" checked");} ?> /> Não
                  </td>
               </tr>
               <tr>
                  <td class="title">Feed</td>
                  <td class="dark">
                     <input type="radio" name="feed" value="1"<?php if($feed) {echo(" checked");} ?> /> Sim <input type="radio" name="feed" value="0"<?php if(!($feed)) {echo(" checked");} ?> /> Não
                  </td>
               </tr>
               <tr><td class="buttons" colspan="2"><input class="image_button" type="image" src="images/alterar.png" value="Alterar" onclick="javascript: form.submit;" /><input class="image_button" type="image" src="images/cancelar.png" value="Cancelar" onclick="javascript:document.location='category.php';return false;" /></td></tr>
            </table>
         </form>
      </div>
   </div>
<?php
   } else if ($action == "erase") {
?>
   <div id="content">
      <h2><a href="category.php">Categorias</a> > Apagar Categoria</h2>
      <div class="tabela">
         <form method="post" action="category_submit.php">
            <input type="hidden" name="action" value="erase2" />
            <input type="hidden" name="id" value="<?php echo($id) ?>" />
            <table>
               <tr class="title">
                  <td>Categoria Acima</td>
                  <td>Nome</td>
                  <td>Stub</td>
               </tr>
<?php
      foreach ($id as $idx) {
         $query = "select id_root_category, name, stub from category where id_category='$idx'";
         $result = mysql_query($query) or die("Invalid query: " . mysql_error());
         $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());

         $root_category = $row[0];
         $name = $row[1];
         $stub = $row[2];

         if ($root_category > 0) {
            $query = "select name from category where id_category=$root_category";
            $result = mysql_query($query) or die("Invalid query: " . mysql_error());
            $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
            $root_category = $row[0];
         } else {
            $root_category = "[Nenhuma]";
         }
?>
               <tr class="dark">
                  <td><?php echo($root_category); ?></td>
                  <td><?php echo($name); ?></td>
                  <td><?php echo($stub); ?><input type="hidden" name="id[]" value="<?php echo($idx); ?>" /></td>
               </tr>
<?php
      }
?>
               <tr><td class="buttons" colspan="3"><input class="image_button" type="image" src="images/apagar.png" value="Apagar" onclick="javascript: form.submit;" /><input class="image_button" type="image" src="images/cancelar.png" value="Cancelar" onclick="javascript:document.location='category.php';return false;" /></td></tr>
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
