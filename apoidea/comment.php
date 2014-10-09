<?php
   // Action
   $action = $_REQUEST['action'];
   $search = $_REQUEST['search'];
   $order  = $_REQUEST['order'];
   if ($order == "") {
      $order = "id_comment";
   }
   $direction  = $_REQUEST['direction'];
   if ($direction == "") {
      $direction = "desc";
   }

   $id = $_REQUEST['id'];
   if (!isset($id)) {
      if ($action == "modify") {
         header ("Location: comment.php?msg=Tem+de+seleccionar+uma+das+linha!");
         exit;
      } else if ($action == "erase") {
         header ("Location: comment.php?msg=Tem+de+seleccionar+pelo+menos+uma+linha!");
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
   <form name="form" name="form" method="post" action="comment.php">
      <div id="content">
         <h2>Comentários</h2>
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
                  <td>Comentário Acima <a href="comment.php?order=id_parent&direction=asc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/up.png" /></a><a href="comment.php?order=id_parent&direction=desc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/down.png" /></a></td>
                  <td>Artigo<a href="comment.php?order=id_article&direction=asc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/up.png" /></a><a href="comment.php?order=id_article&direction=desc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/down.png" /></a></td>
                  <td>Título<a href="comment.php?order=title&direction=asc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/up.png" /></a><a href="comment.php?order=title&direction=desc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/down.png" /></a></td>
                  <td>Nome<a href="comment.php?order=name&direction=asc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/up.png" /></a><a href="comment.php?order=name&direction=desc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/down.png" /></a></td>
                  <td>Email<a href="comment.php?order=email&direction=asc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/up.png" /></a><a href="comment.php?order=email&direction=desc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/down.png" /></a></td>
                  <td>Link<a href="comment.php?order=link&direction=asc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/up.png" /></a><a href="comment.php?order=link&direction=desc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/down.png" /></a></td>
                  <td>Activo<a href="comment.php?order=active&direction=asc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/up.png" /></a><a href="comment.php?order=active&direction=desc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/down.png" /></a></td>
                  <td></td>
                  <td></td>
               </tr>
<?php
      $query = "select id_comment, id_parent, id_article, date, title, text, name, email, link, active from comment order by $order $direction limit $begin, 11";
      if ($search) {
         $query = "select id_comment, id_parent, id_article, date, title, text, name, email, link, active from comment where name like '%$search%' order by $order $direction limit $begin, 11";
      }
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      $num = mysql_num_rows($result);
      if ($num < 10) {
         $end = $num;
      }
      $root = "";
      $article = "";
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
         $root = "[Nenhum]";
      } else {
         $query = "select name from comment where id_comment=$row[1]";
         $result2 = mysql_query($query) or die("Invalid query: " . mysql_error());
         $row2 = mysql_fetch_row($result2) or die("Could not retrieve row: " . mysql_error());
         $root = $row2[0];
      }
      if ($row[2] == 0) {
         $article = "[Nenhum]";
      } else {
         $query = "select title from article where id_article=$row[2]";
         $result2 = mysql_query($query) or die("Invalid query: " . mysql_error());
         $row2 = mysql_fetch_row($result2) or die("Could not retrieve row: " . mysql_error());
         $article = $row2[0];
      }
?>
                  <td class="checkbox"><input type="checkbox" name="id[]" value="<?php echo($row[0]); ?>" /></td>
                  <td><?php echo($root); ?></td>
                  <td><?php echo($article); ?></td>
                  <td><?php echo($row[4]); ?></td>
                  <td><?php echo($row[6]); ?></td>
                  <td><?php echo($row[7]); ?></td>
                  <td><?php echo($row[8]); ?></td>
                  <td><?php if ($row[9] == 1) { echo('Sim'); } else { echo('Não'); } ?></td>
                  <td><a href="comment.php?action=modify&id%5B%5D=<?php echo($row[0]); ?>"><img class="small_button" src="images/refresh.png" /></a></td>
                  <td><a href="comment.php?action=erase&id%5B%5D=<?php echo($row[0]); ?>"><img class="small_button" src="images/delete.png" /></a></td>
               </tr>
<?php
      }
?>
               <tr class="title">
                  <td id="paging_row" colspan="10">
                     <div id="paging">
                        <ul>
                           <li class="left">
<?php
      $query = "select count(*) from comment";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
      $num = $row[0] / 10;
      
      if (($num > 5) && ($page > 0)) {
?>
                              <a href="comment.php?page=0"><img class="small_button" src="images/double_previous.png" /></a>
<?php
      }
      if ($page > 0) {
?>
                              <a href="comment.php?page=<? echo($page - 1); ?>"><img class="small_button" src="images/previous.png" /></a>
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
                              <a href="comment.php?page=<? echo($i); ?>"><? echo($i + 1); ?></a>
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
                              <a href="comment.php?page=<? echo($page + 1); ?>"><img class="small_button" src="images/next.png" /></a>
<?php
      } else {
?>
                              &nbsp;
<?php
      }
      if (($num > 5) && ($page < $num - 1)) {
?>
                              <a href="comment.php?page=<? echo(intval($num-0.01)); ?>"><img class="small_button" src="images/double_next.png" /></a>
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
      <h2><a href="comment.php">Comentários</a> > Criar Comentário</h2>
<?php
	if ($msg) {
?>
      <p class="message"><?php echo($msg) ?></p>
<?php
	}
?>
      <div class="tabela">
         <form name="form" method="post" action="comment_submit.php">
            <input type="hidden" name="action" value="create2" />
            <input type="hidden" name="id" value="<?php echo($id) ?>" />
            <table>
               <tr>
                  <td class="title">Comentário Acima</td>
                  <td class="dark">
                     <select name="parent">
                        <option value="0">Nenhum</option>
<?php
      $query = "select id_comment, title from comment order by id_comment";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      for ($i = 0; $i < mysql_num_rows($result); $i++) {
         $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
?>
                        <option value="<?php echo($row[0]); ?>"><?php echo($row[1]); ?></option>
<?php
      }
?>
                     </select>
                     <script type="text/javascript">document.form.parent.focus()</script>
                  </td>
               </tr>
               <tr>
                  <td class="title">Artigo</td>
                  <td class="dark">
                     <select name="article">
                        <option value="0">Nenhum</option>
<?php
      $query = "select id_article, title from article order by id_article";
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
                  <td class="title">Título</td>
                  <td class="dark">
                     <input type="text" name="title" />
                  </td>
              </tr>
               <tr>
                  <td class="title">Texto</td>
                  <td class="dark">
                     <textarea name="text" rows="5" cols="50"></textarea>
                  </td>
               </tr>
               <tr>
                  <td class="title">Nome</td>
                  <td class="dark">
                     <input type="text" name="name" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Email</td>
                  <td class="dark">
                     <input type="text" name="email" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Link</td>
                  <td class="dark">
                     <input type="text" name="link" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Activo</td>
                  <td class="dark">
                     <input type="radio" name="active" value="1" checked /> Sim <input type="radio" name="active" value="0" /> Não
                  </td>
               </tr>
               <tr><td class="buttons" colspan="2"><input class="image_button" type="image" src="images/criar.png" value="Criar" onclick="javascript: form.submit;" /><input class="image_button" type="image" src="images/cancelar.png" value="Cancelar" onclick="javascript:document.location='comment.php';return false;" /></td></tr>
            </table>
         </form>
      </div>
   </div>
<?php
   } else if ($action == "modify"){
      $query = "select id_parent, id_article, date, title, text, name, email, link, active from comment where id_comment='$id[0]'";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());

      $parent = $row[0];
      $article = $row[1];
      $date = $row[2];
      $title = $row[3];
      $text = $row[4];
      $name = $row[5];
      $email = $row[6];
      $link = $row[7];
      $active = $row[8];
?>
   <div id="content">
      <h2><a href="comment.php">Comentários</a> > Alterar Comentário</h2>
      <div class="tabela">
         <form name="form" method="post" action="comment_submit.php">
            <input type="hidden" name="action" value="modify2" />
            <input type="hidden" name="id" value="<?php echo($id[0]) ?>" />
            <table>
               <tr>
                  <td class="title">Comentário Acima</td>
                  <td class="dark">
                     <select name="parent">
                        <option value="0">Nenhuma</option>
<?php
      $query = "select id_comment, title from comment order by id_comment";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      $selected = "";
      for ($i = 0; $i < mysql_num_rows($result); $i++) {
         $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
         if ($parent == $row[0]) {
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
                     <script type="text/javascript">document.form.parent.focus()</script>
                  </td>
               </tr>
               <tr>
                  <td class="title">Artigo</td>
                  <td class="dark">
                     <select name="article">
                        <option value="0">Nenhum</option>
<?php
      $query = "select id_article, title from article order by id_article";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      $selected = "";
      for ($i = 0; $i < mysql_num_rows($result); $i++) {
         $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
         if ($article == $row[0]) {
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
                  </td>
               </tr>
               <tr>
                  <td class="title">Título</td>
                  <td class="dark">
                     <input type="text" name="title" value="<?php echo($title) ?>" />
                  </td>
              </tr>
               <tr>
                  <td class="title">Texto</td>
                  <td class="dark">
                     <textarea name="text" rows="5" cols="50"><?php echo($text) ?></textarea>
                  </td>
               </tr>
               <tr>
                  <td class="title">Nome</td>
                  <td class="dark">
                     <input type="text" name="name" value="<?php echo($name) ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Email</td>
                  <td class="dark">
                     <input type="text" name="email" value="<?php echo($email) ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Link</td>
                  <td class="dark">
                     <input type="text" name="link" value="<?php echo($link) ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Activo</td>
                  <td class="dark">
                     <input type="radio" name="active" value="1"<?php if($active) {echo(" checked");} ?> /> Sim <input type="radio" name="active" value="0"<?php if(!($active)) {echo(" checked");} ?> /> Não
                  </td>
               </tr>
               <tr><td class="buttons" colspan="2"><input class="image_button" type="image" src="images/alterar.png" value="Alterar" onclick="javascript: form.submit;" /><input class="image_button" type="image" src="images/cancelar.png" value="Cancelar" onclick="javascript:document.location='comment.php';return false;" /></td></tr>
            </table>
         </form>
      </div>
   </div>
<?php
   } else if ($action == "erase") {
?>
   <div id="content">
      <h2><a href="comment.php">Comentários</a> > Apagar Comentário</h2>
      <div class="tabela">
         <form method="post" action="comment_submit.php">
            <input type="hidden" name="action" value="erase2" />
            <input type="hidden" name="id" value="<?php echo($id) ?>" />
            <table>
               <tr class="title">
                  <td>Comentário Acima</td>
                  <td>Artigo</td>
                  <td>Título</td>
                  <td>Nome</td>
                  <td>Email</td>
               </tr>
<?php
      foreach ($id as $idx) {
         $query = "select id_parent, id_article, title, name, email from comment where id_comment='$idx'";
         $result = mysql_query($query) or die("Invalid query: " . mysql_error());
         $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());

         $parent = $row[0];
         $article = $row[1];
         $title = $row[2];
         $name = $row[3];
         $email = $row[4];

         if ($parent > 0) {
            $query = "select title from comment where id_comment=$parent";
            $result = mysql_query($query) or die("Invalid query: " . mysql_error());
            $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
            $parent = $row[0];
         } else {
            $parent = "[Nenhum]";
         }

         if ($article > 0) {
            $query = "select title from article where id_article=$article";
            $result = mysql_query($query) or die("Invalid query: " . mysql_error());
            $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
            $article = $row[0];
         } else {
            $article = "[Nenhum]";
         }
?>
               <tr class="dark">
                  <td><?php echo($parent); ?></td>
                  <td><?php echo($article); ?></td>
                  <td><?php echo($title); ?></td>
                  <td><?php echo($name); ?></td>
                  <td><?php echo($email); ?><input type="hidden" name="id[]" value="<?php echo($idx); ?>" /></td>
               </tr>
<?php
      }
?>
               <tr><td class="buttons" colspan="3"><input class="image_button" type="image" src="images/apagar.png" value="Apagar" onclick="javascript: form.submit;" /><input class="image_button" type="image" src="images/cancelar.png" value="Cancelar" onclick="javascript:document.location='comment.php';return false;" /></td></tr>
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
