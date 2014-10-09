<?php
   // Action
   $action = $_REQUEST['action'];
   $search = $_REQUEST['search'];
   $order  = $_REQUEST['order'];
   if ($order == "") {
      $order = "id_user";
   }
   $direction  = $_REQUEST['direction'];
   if ($direction == "") {
      $direction = "desc";
   }

   $id = $_REQUEST['id'];
   if (!isset($id)) {
      if ($action == "modify") {
         header ("Location: user.php?msg=Tem+de+seleccionar+uma+das+linhas!");
         exit;
      } else if ($action == "erase") {
         header ("Location: user.php?msg=Tem+de+seleccionar+pelo+menos+uma+linha!");
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
   <form name="form" method="post" action="user.php">
      <div id="content">
         <h2>Utilizadores</h2>
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
                  <td>Grupo <a href="user.php?order=id_tribe&direction=asc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/up.png" /></a><a href="user.php?order=id_tribe&direction=desc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/down.png" /></a></td>
                  <td>Username <a href="user.php?order=username&direction=asc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/up.png" /></a><a href="user.php?order=username&direction=desc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/down.png" /></a></td>
                  <td>Nome <a href="user.php?order=name&direction=asc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/up.png" /></a><a href="user.php?order=name&direction=desc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/down.png" /></a></td>
                  <td></td>
                  <td></td>
               </tr>
<?php
   $query = "select id_user, id_tribe, username, name from user order by $order $direction limit $begin, 11";
      if ($search) {
         $query = "select id_user, id_tribe, username, name from user where name like '%$search%' order by $order $direction limit $begin, 11";  
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

      $query = "select name from tribe where id_tribe=$row[1]";
      $result2 = mysql_query($query) or die("Invalid query: " . mysql_error());
      $row2 = mysql_fetch_row($result2) or die("Could not retrieve row: " . mysql_error());
      $cat = $row2[0];
?>
                  <td class="checkbox"><input type="checkbox" name="id[]" value="<?php echo($row[0]); ?>" /></td>
                  <td><?php echo($cat); ?></td>
                  <td><?php echo($row[2]); ?></td>
                  <td><?php echo($row[3]); ?></td>
                  <td><a href="user.php?action=modify&id%5B%5D=<?php echo($row[0]); ?>"><img class="small_button" src="images/refresh.png" /></a></td>
                  <td><a href="user.php?action=erase&id%5B%5D=<?php echo($row[0]); ?>"><img class="small_button" src="images/delete.png" /></a></td>
               </tr>
<?php
   }
?>
               <tr class="title">
                  <td id="paging_row" colspan="6">
                     <div id="paging">
                        <ul>
                           <li class="left">
<?php
      $query = "select count(*) from user";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
      $num = $row[0] / 10;
      
      if (($num > 5) && ($page > 0)) {
?>
                              <a href="user.php?page=0"><img class="small_button" src="images/double_previous.png" /></a>
<?php
      }
      if ($page > 0) {
?>
                              <a href="user.php?page=<? echo($page - 1); ?>"><img class="small_button" src="images/previous.png" /></a>
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
                              <a href="user.php?page=<? echo($i); ?>"><? echo($i + 1); ?></a>
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
                              <a href="user.php?page=<? echo($page + 1); ?>"><img class="small_button" src="images/next.png" /></a>
<?php
      } else {
?>
                              &nbsp;
<?php
      }
      if (($num > 5) && ($page < $num - 1)) {
?>
                              <a href="user.php?page=<? echo(intval($num-0.01)); ?>"><img class="small_button" src="images/double_next.png" /></a>
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
      <h2><a href="user.php">Utilizadores</a> > Criar Utilizadores</h2>
<?php
	if ($msg) {
?>
      <p class="message"><?php echo($msg) ?></p>
<?php
	}
?>
      <div class="tabela">
         <form name="form" method="post" action="user_submit.php">
            <input type="hidden" name="action" value="create2" />
            <input type="hidden" name="id" value="<?php echo($id) ?>" />
            <table>
               <tr>
                  <td class="title">Grupo</td>
                  <td class="dark">
                     <select name="tribe">
                        <option value="0">Nenhuma</option>
<?php
      $query = "select id_tribe, name from tribe order by id_tribe";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      $num = mysql_num_rows($result);
      $selected = "";
      for ($i = 0; $i < mysql_num_rows($result); $i++) {
         $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
?>
                        <option value="<?php echo($row[0]); ?>"><?php echo($row[1]); ?></option>
<?php
      }
?>
                     </select>
                     <script type="text/javascript">document.form.category.focus()</script>
                  </td>
               </tr>
               <tr>
                  <td class="title">Nome</td>
                  <td class="dark">
                     <input type="text" size="40" name="name" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Username</td>
                  <td class="dark">
                     <input type="text" size="40" name="username" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Password</td>
                  <td class="dark">
                     <input type="password" size="40" name="password" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Confirmar Password</td>
                  <td class="dark">
                     <input type="password" size="40" name="password2" />
                  </td>
               </tr>
               <tr><td class="buttons" colspan="2"><input class="image_button" type="image" src="images/criar.png" value="Criar" onclick="javascript: form.submit;" /><input class="image_button" type="image" src="images/cancelar.png" value="Cancelar" onclick="javascript:document.location='user.php';return false;" /></td></tr>
            </table>
         </form>
      </div>
   </div>
<?php
   } else if ($action == "modify"){
      $query = "select id_tribe, name, username from user where id_user='$id[0]'";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());

      $tribe = $row[0];
      $name = $row[1];
      $username = $row[2];
?>
   <div id="content">
      <h2><a href="user.php">Utilizadores</a> > Alterar Utilizador</h2>
      <div class="tabela">
         <form name="form" method="post" action="user_submit.php">
            <input type="hidden" name="action" value="modify2" />
            <input type="hidden" name="id" value="<?php echo($id[0]) ?>" />
            <table>
               <tr>
                  <td class="title">Grupo</td>
                  <td class="dark">
                     <select name="tribe">
                        <option value="0">Nenhuma</option>
<?php
      $query = "select id_tribe, name from tribe order by id_tribe";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      $num = mysql_num_rows($result);
      $selected = "";
      for ($i = 0; $i < mysql_num_rows($result); $i++) {
         $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
         if ($tribe == $row[0]) {
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
                     <script type="text/javascript">document.form.category.focus()</script>
                  </td>
               </tr>
               <tr>
                  <td class="title">Nome</td>
                  <td class="dark">
                     <input type="text" size="40" name="name" value="<?php echo($name); ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Username</td>
                  <td class="dark">
                     <input type="text" size="40" name="username" value="<?php echo($username); ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Password</td>
                  <td class="dark">
                     <input type="password" size="40" name="password" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Confirmar Password</td>
                  <td class="dark">
                     <input type="password" size="40" name="password2" />
                  </td>
               </tr>
               <tr><td class="buttons" colspan="2"><input class="image_button" type="image" src="images/alterar.png" value="Alterar" onclick="javascript: form.submit;" /><input class="image_button" type="image" src="images/cancelar.png" value="Cancelar" onclick="javascript:document.location='user.php';return false;" /></td></tr>
            </table>
         </form>
      </div>
   </div>
<?php
   } else if ($action == "erase") {
?>
   <div id="content">
      <h2><a href="user.php">Utilizadores</a> > Apagar Utilizadores</h2>
      <div class="tabela">
         <form method="post" action="user_submit.php">
            <input type="hidden" name="action" value="erase2" />
            <input type="hidden" name="id" value="<?php echo($id) ?>" />
            <table>
               <tr class="title">
                  <td>Grupo</td>
                  <td>Nome</td>
                  <td>Username</td>
               </tr>
<?php
      foreach ($id as $idx) {
         $query = "select id_tribe, name, username from user where id_user='$idx'";
         $result = mysql_query($query) or die("Invalid query: " . mysql_error());
         $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());

         $query = "select name from tribe where id_tribe='$row[0]'";
         $result = mysql_query($query) or die("Invalid query: " . mysql_error());
         $row2 = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
         $cat = $row2[0];

         $tribe = $row2[0];
         $name = $row[1];
         $username = $row[2];
?>
               <tr class="dark">
                  <td><?php echo($tribe); ?></td>
                  <td><?php echo($name); ?></td>
                  <td><?php echo($username); ?><input type="hidden" name="id[]" value="<?php echo($idx); ?>" /></td>
               </tr>
<?php
      }
?>
               <tr><td class="buttons" colspan="3"><input class="image_button" type="image" src="images/apagar.png" value="Apagar" onclick="javascript: form.submit;" /><input class="image_button" type="image" src="images/cancelar.png" value="Cancelar" onclick="javascript:document.location='user.php';return false;" /></td></tr>
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
