<?php
   // Action
   $action = $_REQUEST['action'];
   $search = $_REQUEST['search'];
   $order  = $_REQUEST['order'];
   if ($order == "") {
      $order = "id_site";
   }
   $direction  = $_REQUEST['direction'];
   if ($direction == "") {
      $direction = "desc";
   }

   $id = $_REQUEST['id'];
   if (!isset($id)) {
      if ($action == "modify") {
         header ("Location: site.php?msg=Tem+de+seleccionar+uma+das+linha!");
         exit;
      } else if ($action == "erase") {
         header ("Location: site.php?msg=Tem+de+seleccionar+pelo+menos+uma+linha!");
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
   <form name="form" method="post" action="site.php">
      <div id="content">
         <h2>Sites</h2>
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
                  <td>Nome <a href="site.php?order=name&direction=asc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/up.png" /></a><a href="site.php?order=name&direction=desc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/down.png" /></a></td>
                  <td>Root <a href="site.php?order=root&direction=asc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/up.png" /></a><a href="site.php?order=root&direction=desc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/down.png" /></a></td>
                  <td>Colunas</td>
                  <td>Header</td>
                  <td>Footer</td>
                  <td></td>
                  <td></td>
               </tr>
<?php
      $query = "select id_site, name, root, num_columns, header, footer from site order by $order $direction limit $begin, 11";
      if ($search) {
         $query =  "select id_site, name, root, num_columns, header, footer from site where name like '%$search%' order by $order $direction limit $begin, 11";
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
?>
                  <td class="checkbox"><input type="checkbox" name="id[]" value="<?php echo($row[0]); ?>" /></td>
                  <td><?php echo($row[1]); ?></td>
                  <td><?php echo($row[2]); ?></td>
                  <td><?php echo($row[3]); ?></td>
                  <td><?php if ($row[4] == 1) { echo('Sim'); } else { echo('Não'); } ?></td>
                  <td><?php if ($row[5] == 1) { echo('Sim'); } else { echo('Não'); } ?></td>
                  <td><a href="site.php?action=modify&id%5B%5D=<?php echo($row[0]); ?>"><img class="small_button" src="images/refresh.png" /></a></td>
                  <td><a href="site.php?action=erase&id%5B%5D=<?php echo($row[0]); ?>"><img class="small_button" src="images/delete.png" /></a></td>
               </tr>
<?php
      }
?>
               <tr class="title">
                  <td id="paging_row" colspan="8">
                     <div id="paging">
                        <ul>
                           <li class="left">
<?php
      $query = "select count(*) from site";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
      $num = $row[0] / 10;
      
      if (($num > 5) && ($page > 0)) {
?>
                              <a href="site.php?page=0"><img class="small_button" src="images/double_previous.png" /></a>
<?php
      }
      if ($page > 0) {
?>
                              <a href="site.php?page=<? echo($page - 1); ?>"><img class="small_button" src="images/previous.png" /></a>
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
                              <a href="site.php?page=<? echo($i); ?>"><? echo($i + 1); ?></a>
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
                              <a href="site.php?page=<? echo($page + 1); ?>"><img class="small_button" src="images/next.png" /></a>
<?php
      } else {
?>
                              &nbsp;
<?php
      }
      if (($num > 5) && ($page < $num - 1)) {
?>
                              <a href="site.php?page=<? echo(intval($num-0.01)); ?>"><img class="small_button" src="images/double_next.png" /></a>
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
      <h2><a href="site.php">Sites</a> > Criar Site</h2>
<?php
	if ($msg) {
?>
      <p class="message"><?php echo($msg) ?></p>
<?php
	}
?>
      <div class="tabela">
         <form name="form" method="post" action="site_submit.php">
            <input type="hidden" name="action" value="create2" />
            <input type="hidden" name="id" value="<?php echo($id) ?>" />
            <table>
               <tr>
                  <td class="title">Nome</td>
                  <td class="dark">
                     <input type="text" name="name" />
                     <script type="text/javascript">document.form.name.focus()</script>
                  </td>
               </tr>
               <tr>
                  <td class="title">Root</td>
                  <td class="dark">
                     <input type="text" name="root" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Colunas</td>
                  <td class="dark">
                     <select name="num_columns">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3" selected>3</option>
                     </select>
                  </td>
               </tr>
               <tr>
                  <td class="title">Header</td>
                  <td class="dark">
                     <input type="radio" name="header" value="1" checked /> Sim <input type="radio" name="header" value="0" /> Não
                  </td>
               </tr>
               <tr>
                  <td class="title">Footer</td>
                  <td class="dark">
                     <input type="radio" name="footer" value="1" checked /> Sim <input type="radio" name="footer" value="0" /> Não
                  </td>
               </tr>
               <tr><td class="buttons" colspan="2"><input class="image_button" type="image" src="images/criar.png" value="Criar" onclick="javascript: form.submit;" /><input class="image_button" type="image" src="images/cancelar.png" value="Cancelar" onclick="javascript:document.location='site.php';return false;" /></td></tr>
            </table>
         </form>
      </div>
   </div>
<?php
   } else if ($action == "modify"){
      $query = "select name, root, num_columns, header, footer from site where id_site='$id[0]'";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());

      $name = $row[0];
      $root = $row[1];
?>
   <div id="content">
      <h2><a href="site.php">Sites</a> > Alterar Site</h2>
      <div class="tabela">
         <form name="form" method="post" action="site_submit.php">
            <input type="hidden" name="action" value="modify2" />
            <input type="hidden" name="id" value="<?php echo($id[0]) ?>" />
            <table>
               <tr>
                  <td class="title">Nome</td>
                  <td class="dark">
                     <input type="text" name="name" value="<?php echo($name); ?>" />
                     <script type="text/javascript">document.form.name.focus()</script>
                  </td>
               </tr>
               <tr>
                  <td class="title">Root</td>
                  <td class="dark">
                     <input type="text" name="root" value="<?php echo($root); ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Colunas</td>
                  <td class="dark">
                     <select name="num_columns">
                        <option value="1"<?php if ($row[2] == 1) { echo(' selected'); } ?>>1</option>
                        <option value="2"<?php if ($row[2] == 2) { echo(' selected'); } ?>>2</option>
                        <option value="3"<?php if ($row[2] == 3) { echo(' selected'); } ?>>3</option>
                     </select>
                  </td>
               </tr>
               <tr>
                  <td class="title">Header</td>
                  <td class="dark">
                     <input type="radio" name="header" value="1"<?php if ($row[3] == 1) { echo(' checked'); } ?> /> Sim <input type="radio" name="header" value="0"<?php if ($row[3] == 0) { echo(' checked'); } ?> /> Não
                  </td>
               </tr>
               <tr>
                  <td class="title">Footer</td>
                  <td class="dark">
                     <input type="radio" name="footer" value="1"<?php if ($row[4] == 1) { echo(' checked'); } ?> /> Sim <input type="radio" name="footer" value="0"<?php if ($row[4] == 0) { echo(' checked'); } ?> /> Não
                  </td>
               </tr>
               <tr><td class="buttons" colspan="2"><input class="image_button" type="image" src="images/alterar.png" value="Alterar" onclick="javascript: form.submit;" /><input class="image_button" type="image" src="images/cancelar.png" value="Cancelar" onclick="javascript:document.location='site.php';return false;" /></td></tr>
            </table>
         </form>
      </div>
   </div>
<?php
   } else if ($action == "erase") {
?>
   <div id="content">
      <h2><a href="site.php">Sites</a> > Apagar Site</h2>
      <div class="tabela">
         <form method="post" action="site_submit.php">
            <input type="hidden" name="action" value="erase2" />
            <input type="hidden" name="id" value="<?php echo($id) ?>" />
            <table>
               <tr class="title">
                  <td>Nome</td>
                  <td>Root</td>
                  <td>Colunas</td>
                  <td>Header</td>
                  <td>Footer</td>
               </tr>
<?php
      foreach ($id as $idx) {
         $query = "select name, root, num_columns, header, footer from site where id_site='$idx'";
         $result = mysql_query($query) or die("Invalid query: " . mysql_error());
         $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());

         $name = $row[0];
         $root = $row[1];
?>
               <tr class="dark">
                  <td><?php echo($name); ?></td>
                  <td><?php echo($root); ?><input type="hidden" name="id[]" value="<?php echo($idx); ?>" /></td>
                  <td><?php echo($row[2]); ?></td>
                  <td><?php if ($row[3] == 1) { echo('Sim'); } else { echo('Não'); } ?></td>
                  <td><?php if ($row[4] == 1) { echo('Sim'); } else { echo('Não'); } ?></td>
               </tr>
<?php
      }
?>
               <tr><td class="buttons" colspan="5"><input class="image_button" type="image" src="images/apagar.png" value="Apagar" onclick="javascript: form.submit;" /><input class="image_button" type="image" src="images/cancelar.png" value="Cancelar" onclick="javascript:document.location='site.php';return false;" /></td></tr>
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
