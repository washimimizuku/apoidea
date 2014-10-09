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
         header ("Location: link_list.php?msg=Tem+de+seleccionar+uma+das+linha!");
         exit;
      } else if ($action == "erase") {
         header ("Location: link_list.php?msg=Tem+de+seleccionar+pelo+menos+uma+linha!");
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
   <form name="form" method="post" action="link_list.php">
      <div id="content">
         <h2>Listas de Links</h2>
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
                  <td>ID <a href="link_list.php?order=id&direction=asc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/up.png" /></a><a href="link_list.php?order=id&direction=desc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/down.png" /></a></td>
                  <td>Título <a href="link_list.php?order=title&direction=asc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/up.png" /></a><a href="link_list.php?order=title&direction=desc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/down.png" /></a></td>
                  <td></td>
                  <td></td>
               </tr>
<?php
      $query = "select id, title from link_list order by $order $direction limit $begin, 11";
      if ($search) {
         $query = "select id, title from link_list where title like '%$search%' order by $order $direction limit $begin, 11";
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
                  <td><?php echo($row[0]); ?></td>
                  <td><?php echo($row[1]); ?></td>
                  <td><a href="link_list.php?action=modify&id%5B%5D=<?php echo($row[0]); ?>"><img class="small_button" src="images/refresh.png" /></a></td>
                  <td><a href="link_list.php?action=erase&id%5B%5D=<?php echo($row[0]); ?>"><img class="small_button" src="images/delete.png" /></a></td>
               </tr>
<?php
      }
?>
               <tr class="title">
                  <td id="paging_row" colspan="5">
                     <div id="paging">
                        <ul>
                           <li class="left">
<?php
      $query = "select count(*) from link_list";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
      $num = $row[0] / 10;
      
      if (($num > 5) && ($page > 0)) {
?>
                              <a href="link_list.php?page=0"><img class="small_button" src="images/double_previous.png" /></a>
<?php
      }
      if ($page > 0) {
?>
                              <a href="link_list.php?page=<? echo($page - 1); ?>"><img class="small_button" src="images/previous.png" /></a>
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
                              <a href="link_list.php?page=<? echo($i); ?>"><? echo($i + 1); ?></a>
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
                              <a href="link_list.php?page=<? echo($page + 1); ?>"><img class="small_button" src="images/next.png" /></a>
<?php
      } else {
?>
                              &nbsp;
<?php
      }
      if (($num > 5) && ($page < $num - 1)) {
?>
                              <a href="link_list.php?page=<? echo(intval($num-0.01)); ?>"><img class="small_button" src="images/double_next.png" /></a>
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
      <h2><a href="link_list.php">Listas de Links</a> > Criar Lista de Links</h2>
<?php
	if ($msg) {
?>
      <p class="message"><?php echo($msg) ?></p>
<?php
	}
?>
      <div class="tabela">
         <form name="form" method="post" action="link_list_submit.php">
            <input type="hidden" name="action" value="create2" />
            <input type="hidden" name="id" value="<?php echo($id) ?>" />
            <table>
               <tr>
                  <td class="title">Título</td>
                  <td class="dark">
                     <input type="text" name="title" />
                     <script type="text/javascript">document.form.title.focus()</script>
                  </td>
               </tr>
               <tr>
                  <td class="title">Links</td>
                  <td class="dark" style="width:33em;">
                     Nome: <input type="text" size="20" maxlength="255" name="name" /><br />
                     URL: <input type="text" size="20" maxlength="255" name="url" /><br />
                     Target: <input type="text" size="20" maxlength="255" name="target" />
                     <input type="image" class="small_button" src="images/more.png" onclick="javascript: form.long_link.value=form.name.value+' | '+form.url.value+' | '+form.target.value; addToList('form', 'long_link', 'link_list'); return (false);" /><br /><br />
                     <div style="float:left; margin-right:.3em">
                        <select name="link_list" size="10" multiple style="width:28.4em;"></select>
                        <input type="hidden" name="long_link" />
                        <input type="hidden" name="all_links_values" />
                        <input type="hidden" name="all_links_texts" />
                     </div>
                     <div style="float:left">
                        <input type="image" class="small_button" src="images/less.png" onclick="javascript: removeFromList('form', 'link_list'); return (false);" /><br /><br /><br /><br /><br /><br />
                        <input type="image" class="small_button" src="images/up.png" onclick="javascript: moveUp('form', 'link_list'); return (false);" /><br />
                        <input type="image" class="small_button" src="images/down.png" onclick="javascript: moveDown('form', 'link_list'); return (false);" />
                     </div>
                  </td>
               </tr>
               <tr><td class="buttons" colspan="2"><input class="image_button" type="image" src="images/criar.png" value="Criar" onclick="javascript: submitListForm('form', 'link_list', 'all_links_values', 'all_links_texts'); return false();" /><input class="image_button" type="image" src="images/cancelar.png" value="Cancelar" onclick="javascript:document.location='link_list.php';return false;" /></td></tr>
            </table>
         </form>
      </div>
   </div>
<?php
   } else if ($action == "modify"){
      $query = "select title from link_list where id='$id[0]'";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());

      $title = $row[0];
?>
   <div id="content">
      <h2><a href="link_list.php">Listas de Links</a> > Alterar Lista de Links</h2>
      <div class="tabela">
         <form name="form" method="post" action="link_list_submit.php">
            <input type="hidden" name="action" value="modify2" />
            <input type="hidden" name="id" value="<?php echo($id[0]) ?>" />
            <table>
               <tr>
                  <td class="title">Título</td>
                  <td class="dark">
                     <input type="text" name="title" value="<?php echo($title); ?>" />
                     <script type="text/javascript">document.form.title.focus()</script>
                  </td>
               </tr>
               <tr>
                  <td class="title">Links</td>
                  <td class="dark" style="width:33em;">
                     Nome: <input type="text" size="20" maxlength="255" name="name" /><br />
                     URL: <input type="text" size="20" maxlength="255" name="url" /><br />
                     Target: <input type="text" size="20" maxlength="255" name="target" />
                     <input type="image" class="small_button" src="images/more.png" onclick="javascript: form.long_link.value=form.name.value+' | '+form.url.value+' | '+form.target.value; addToList('form', 'long_link', 'link_list'); return (false);" /><br /><br />
                     <div style="float:left; margin-right:.3em">
                        <select name="link_list" size="10" multiple style="width:28.4em;">
<?php
      $query = "select id, name, url, target from link where id_link_list=$id[0] order by position, id";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());

      for ($i = 0; $i < mysql_num_rows($result); $i++) {
         $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
?>
                                 <option value="<?php echo($row[0]); ?>"><?php echo($row[1]); ?> | <?php echo($row[2]); ?> | <?php echo($row[3]); ?></option>
<?php
      }
?>                     
                        </select>
                        <input type="hidden" name="long_link" />
                        <input type="hidden" name="all_links_values" />
                        <input type="hidden" name="all_links_texts" />
                        <input type="hidden" name="erased_links" />
                     </div>
                     <div style="float:left">
                        <input type="image" class="small_button" src="images/less.png" onclick="javascript: removeFromListAndRecordErase('form', 'link_list', 'erased_links'); return (false);" /><br /><br /><br /><br /><br /><br />
                        <input type="image" class="small_button" src="images/up.png" onclick="javascript: moveUp('form', 'link_list'); return (false);" /><br />
                        <input type="image" class="small_button" src="images/down.png" onclick="javascript: moveDown('form', 'link_list'); return (false);" />
                     </div>
                  </td>
               </tr>
               <tr><td class="buttons" colspan="2"><input class="image_button" type="image" src="images/alterar.png" value="Alterar" onclick="javascript: submitListForm('form', 'link_list', 'all_links_values', 'all_links_texts'); return false();" /><input class="image_button" type="image" src="images/cancelar.png" value="Cancelar" onclick="javascript:document.location='link_list.php';return false;" /></td></tr>
            </table>
         </form>
      </div>
   </div>
<?php
   } else if ($action == "erase") {
?>
   <div id="content">
      <h2><a href="link_list.php">Listas de Links</a> > Criar Lista de Links</h2>
      <div class="tabela">
         <form method="post" action="link_list_submit.php">
            <input type="hidden" name="action" value="erase2" />
            <input type="hidden" name="id" value="<?php echo($id) ?>" />
            <table>
               <tr class="title">
                  <td>Título</td>
               </tr>
<?php
      foreach ($id as $idx) {
         $query = "select title from link_list where id='$idx'";
         $result = mysql_query($query) or die("Invalid query: " . mysql_error());
         $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());

         $title = $row[0];
?>
               <tr class="dark">
                  <td><?php echo($title); ?><input type="hidden" name="id[]" value="<?php echo($idx); ?>" /></td>
               </tr>
<?php
      }
?>
               <tr><td class="buttons" colspan="6"><input class="image_button" type="image" src="images/apagar.png" value="Apagar" onclick="javascript: form.submit;" /><input class="image_button" type="image" src="images/cancelar.png" value="Cancelar" onclick="javascript:document.location='link_list.php';return false;" /></td></tr>
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
