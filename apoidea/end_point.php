<?php
   // Action
   $action = $_REQUEST['action'];
   $search = $_REQUEST['search'];
   $order  = $_REQUEST['order'];
   if ($order == "") {
      $order = "id";
   }
   $direction  = $_REQUEST['direction'];
   if ($direction == "") {
      $direction = "desc";
   }

   $id = $_REQUEST['id'];
   if (!isset($id)) {
      if ($action == "modify") {
         header ("Location: end_point.php?msg=Tem+de+seleccionar+uma+das+linha!");
         exit;
      } else if ($action == "erase") {
         header ("Location: end_point.php?msg=Tem+de+seleccionar+pelo+menos+uma+linha!");
         exit;
      }
   }

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
   <form name="form" method="post" action="end_point.php">
      <div id="content">
         <h2>End Points</h2>
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
                  <td>Categoria <a href="end_point.php?order=id_category&direction=asc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/up.png" /></a><a href="end_point.php?order=id_category&direction=desc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/down.png" /></a></td>
                  <td>Feed <a href="end_point.php?order=id_feed&direction=asc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/up.png" /></a><a href="end_point.php?order=id_feed&direction=desc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/down.png" /></a></td>
                  <td>TTL</td>
                  <td>Máximo Artigos</td>
                  <td></td>
                  <td></td>
               </tr>
<?php
      $query = "select id, id_category, id_feed, template, ttl, max_entries from end_point order by $order $direction limit $begin, 11";
      if ($search) {
         $query =  "select end_point.id, end_point.id_category, end_point.id_feed, end_point.template, end_point.ttl, end_point.max_entries from end_point, category, feed where end_point.id_category=category.id_category and end_point.id_feed=feed.id and (category.name like '%$search%' or feed.title like '%$search%') order by end_point.$order $direction limit $begin, 11";
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
         $category = "[Nenhuma]";
      } else {
         $query = "select name from category where id_category=$row[1]";
         $result2 = mysql_query($query) or die("Invalid query: " . mysql_error());
         $row2 = mysql_fetch_row($result2) or die("Could not retrieve row: " . mysql_error());
         $category = $row2[0];
      }
      if ($row[2] == 0) {
         $feed = "[Nenhum]";
      } else {
         $query = "select title from feed where id=$row[2]";
         $result2 = mysql_query($query) or die("Invalid query: " . mysql_error());
         $row2 = mysql_fetch_row($result2) or die("Could not retrieve row: " . mysql_error());
         $feed = $row2[0];
      }
?>                
                  <td class="checkbox"><input type="checkbox" name="id[]" value="<?php echo($row[0]); ?>" /></td>
                  <td><?php echo($category); ?></td>
                  <td><?php echo($feed); ?></td>
                  <td><?php echo($row[4]); ?></td>
                  <td><?php echo($row[5]); ?></td>
                  <td><a href="end_point.php?action=modify&id%5B%5D=<?php echo($row[0]); ?>"><img class="small_button" src="images/refresh.png" /></a></td>
                  <td><a href="end_point.php?action=erase&id%5B%5D=<?php echo($row[0]); ?>"><img class="small_button" src="images/delete.png" /></a></td>
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
      $query = "select count(*) from end_point";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
      $num = $row[0] / 10;
      
      if (($num > 5) && ($page > 0)) {
?>
                              <a href="end_point.php?page=0"><img class="small_button" src="images/double_previous.png" /></a>
<?php
      }
      if ($page > 0) {
?>
                              <a href="end_point.php?page=<? echo($page - 1); ?>"><img class="small_button" src="images/previous.png" /></a>
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
                              <a href="end_point.php?page=<? echo($i); ?>"><? echo($i + 1); ?></a>
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
                              <a href="end_point.php?page=<? echo($page + 1); ?>"><img class="small_button" src="images/next.png" /></a>
<?php
      } else {
?>
                              &nbsp;
<?php
      }
      if (($num > 5) && ($page < $num - 1)) {
?>
                              <a href="end_point.php?page=<? echo(intval($num-0.01)); ?>"><img class="small_button" src="images/double_next.png" /></a>
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
      <h2><a href="end_point.php">End Points</a> > Criar End Point</h2>
<?php
	if ($msg) {
?>
      <p class="message"><?php echo($msg) ?></p>
<?php
	}
?>
      <div class="tabela">
         <form name="form" method="post" action="end_point_submit.php">
            <input type="hidden" name="action" value="create2" />
            <input type="hidden" name="id" value="<?php echo($id) ?>" />
            <table>
               <tr>
                  <td class="title">Categoria</td>
                  <td class="dark">
                     <select name="id_category">
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
                  </td>
               </tr>
               <tr>
                  <td class="title">Feed</td>
                  <td class="dark">
                     <select name="id_feed">
                        <option value="0">Nenhuma</option>
<?php
      $query = "select id, title from feed order by id";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      for ($i = 0; $i < mysql_num_rows($result); $i++) {
         $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
?>
                        <option value="<?php echo($row[0]); ?>"><?php echo($row[1]); ?></option>
<?php
      }
?>
                     </select>
                     <script type="text/javascript">document.form.title.focus()</script>
                  </td>
               </tr>
               <tr>
                  <td class="title">TTL</td>
                  <td class="dark">
                     <input type="text" name="ttl" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Máximo artigos</td>
                  <td class="dark">
                     <input type="text" name="max_entries" />
                  </td>
               </tr>
               <tr><td class="buttons" colspan="2"><input class="image_button" type="image" src="images/criar.png" value="Criar" onclick="javascript: form.submit;" /><input class="image_button" type="image" src="images/cancelar.png" value="Cancelar" onclick="javascript:document.location='end_point.php';return false;" /></td></tr>
            </table>
         </form>
      </div>
   </div>
<?php
   } else if ($action == "modify"){
      $query = "select id_category, id_feed, template, ttl, max_entries from end_point where id='$id[0]'";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());

      $id_category = $row[0];
      $id_feed = $row[1];
      $template = $row[2];
      $ttl = $row[3];
      $max_entries = $row[4];
?>
   <div id="content">
      <h2><a href="end_point.php">End Points</a> > Alterar End Point</h2>
      <div class="tabela">
         <form name="form" method="post" action="end_point_submit.php">
            <input type="hidden" name="action" value="modify2" />
            <input type="hidden" name="id" value="<?php echo($id[0]) ?>" />
            <table>
               <tr>
                  <td class="title">Categoria</td>
                  <td class="dark">
                     <select name="id_category">
                        <option value="0">Nenhuma</option>
<?php
      $query = "select id_category, name from category order by id_category";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      for ($i = 0; $i < mysql_num_rows($result); $i++) {
         $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
?>
                        <option value="<?php echo($row[0]); ?>"<?php if ($id_category == $row[0]) {echo(' selected');} ?>><?php echo($row[1]); ?></option>
<?php
      }
?>
                     </select>
                  </td>
               </tr>
               <tr>
                  <td class="title">Feed</td>
                  <td class="dark">
                     <select name="id_feed">
                        <option value="0">Nenhuma</option>
<?php
      $query = "select id, title from feed order by id";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      for ($i = 0; $i < mysql_num_rows($result); $i++) {
         $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
?>
                        <option value="<?php echo($row[0]); ?>"<?php if ($id_feed == $row[0]) {echo(' selected');} ?>><?php echo($row[1]); ?></option>
<?php
      }
?>
                     </select>
                  </td>
               </tr>
               <tr>
                  <td class="title">TTL</td>
                  <td class="dark">
                     <input type="text" name="ttl" value="<?php echo($ttl); ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Máximo artigos</td>
                  <td class="dark">
                     <input type="text" name="max_entries" value="<?php echo($max_entries); ?>" />
                  </td>
               </tr>
               <tr><td class="buttons" colspan="2"><input class="image_button" type="image" src="images/alterar.png" value="Alterar" onclick="javascript: form.submit;" /><input class="image_button" type="image" src="images/cancelar.png" value="Cancelar" onclick="javascript:document.location='end_point.php';return false;" /></td></tr>
            </table>
         </form>
      </div>
   </div>
<?php
   } else if ($action == "erase") {
?>
   <div id="content">
      <h2><a href="end_point.php">End Points</a> > Apagar End Point</h2>
      <div class="tabela">
         <form method="post" action="end_point_submit.php">
            <input type="hidden" name="action" value="erase2" />
            <input type="hidden" name="id" value="<?php echo($id) ?>" />
            <table>
               <tr class="title">
                  <td>Categoria</td>
                  <td>Feed</td>
                  <td>TTL</td>
                  <td>Máximo Artigos</td>
               </tr>
<?php
      foreach ($id as $idx) {
         $query = "select id_category, id_feed, ttl, max_entries from end_point where id='$idx'";
         $result = mysql_query($query) or die("Invalid query: " . mysql_error());
         $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());

         if ($row[0] == 0) {
            $category = "[Nenhuma]";
         } else {
            $query = "select name from category where id_category=$row[0]";
            $result2 = mysql_query($query) or die("Invalid query: " . mysql_error());
            $row2 = mysql_fetch_row($result2) or die("Could not retrieve row: " . mysql_error());
            $category = $row2[0];
         }
         if ($row[1] == 0) {
            $feed = "[Nenhum]";
         } else {
            $query = "select title from feed where id=$row[1]";
            $result2 = mysql_query($query) or die("Invalid query: " . mysql_error());
            $row2 = mysql_fetch_row($result2) or die("Could not retrieve row: " . mysql_error());
            $feed = $row2[0];
         }
?>                
               <tr class="dark">
                  <td><?php echo($category); ?></td>
                  <td><?php echo($feed); ?></td>
                  <td><?php echo($row[2]); ?></td>
                  <td><?php echo($row[3]); ?><input type="hidden" name="id[]" value="<?php echo($idx); ?>" /></td>
               </tr>
<?php
      }
?>
               <tr><td class="buttons" colspan="4"><input class="image_button" type="image" src="images/apagar.png" value="Apagar" onclick="javascript: form.submit;" /><input class="image_button" type="image" src="images/cancelar.png" value="Cancelar" onclick="javascript:document.location='end_point.php';return false;" /></td></tr>
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
