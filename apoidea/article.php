<?php
   // Action
   $action = $_REQUEST['action'];
   $search = $_REQUEST['search'];
   $order  = $_REQUEST['order'];
   if ($order == "") {
      $order = "id_article";
   }
   $direction  = $_REQUEST['direction'];
   if ($direction == "") {
      $direction = "desc";
   }

   $id = $_REQUEST['id'];
   if (!isset($id)) {
      if ($action == "modify") {
         header ("Location: article.php?msg=Tem+de+seleccionar+uma+das+linha!");
         exit;
      } else if ($action == "erase") {
         header ("Location: article.php?msg=Tem+de+seleccionar+pelo+menos+uma+linha!");
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
   $begin = $page * 50;
   $end = $inicio + 50;

   include("header.php");
   include("menu.php");   

   if (($action == "") || ($action == "search")) {
?>
   <form name="form" method="post" action="article.php">
      <div id="content">
         <h2>Artigos</h2>
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
                  <td>ID</td>
                  <td>Categoria</td>
                  <td>Título <a href="article.php?order=title&direction=asc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/up.png" /></a><a href="article.php?order=title&direction=desc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/down.png" /></a></td>
                  <td>Sub Título <a href="article.php?order=subtitle&direction=asc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/up.png" /></a><a href="article.php?order=subtitle&direction=desc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/down.png" /></a></td>
                  <td></td>
                  <td></td>
               </tr>
<?php
   $query = "select id_article, title, subtitle, teaser, text from article order by $order $direction limit $begin, 51";
   if ($search) {
      $query = "select id_article, title, subtitle, teaser, text from article where title like '%$search%' or subtitle like '%$search%' order by $order $direction limit $begin, 51";  
   }
   $result = mysql_query($query) or die("Invalid query: " . mysql_error());
   $num = mysql_num_rows($result);
   if ($num < 50) {
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

      $query = "select id_category from rel_category_article where id_article=$row[0]";
      $result2 = mysql_query($query) or die("Invalid query: " . mysql_error());
      
      $cat="[Nenhuma]";
      if (mysql_num_rows($result2) > 0) {
         $cat = "";
         while ($row2 = mysql_fetch_row($result2)) {
            $query = "select name from category where id_category=$row2[0]";
            $result3 = mysql_query($query) or die("Invalid query: " . mysql_error());
            $row3 = mysql_fetch_row($result3) or die("Could not retrieve row: " . mysql_error());
            $cat .= $row3[0].", ";
         }
         $cat = substr($cat,0,strlen($cat)-2);
      }      
?>
                  <td class="checkbox"><input type="checkbox" name="id[]" value="<?php echo($row[0]); ?>" /></td>
                  <td><?php echo($row[0]); ?></td>
                  <td><?php echo($cat); ?></td>
                  <td><?php echo($row[1]); ?></td>
                  <td><?php echo($row[2]); ?></td>
                  <td><a href="article.php?action=modify&id%5B%5D=<?php echo($row[0]); ?>&page=<?php echo($page); ?>"><img class="small_button" src="images/refresh.png" /></a></td>
                  <td><a href="article.php?action=erase&id%5B%5D=<?php echo($row[0]); ?>&page=<?php echo($page); ?>"><img class="small_button" src="images/delete.png" /></a></td>
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
      $query = "select count(*) from article";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
      $num = $row[0] / 50;
      
      if (($num > 5) && ($page > 0)) {
?>
                              <a href="article.php?page=0"><img class="small_button" src="images/previous2.png" /></a>
<?php
      }
      if ($page > 0) {
?>
                              <a href="article.php?page=<? echo($page - 1); ?>"><img class="small_button" src="images/previous.png" /></a>
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
                              <a href="article.php?page=<? echo($i); ?>"><? echo($i + 1); ?></a>
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
                              <a href="article.php?page=<? echo($page + 1); ?>"><img class="small_button" src="images/next.png" /></a>
<?php
      } else {
?>
                              &nbsp;
<?php
      }
      if (($num > 5) && ($page < $num - 1)) {
?>
                              <a href="article.php?page=<? echo(intval($num-0.01)); ?>"><img class="small_button" src="images/next2.png" /></a>
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
      <h2><a href="article.php">Artigos</a> > Criar Artigos</h2>
<?php
	if ($msg) {
?>
      <p class="message"><?php echo($msg) ?></p>
<?php
	}
?>
      <div class="tabela">
         <form name="form" method="post" action="article_submit.php">
            <input type="hidden" name="action" value="create2" />
            <input type="hidden" name="id" value="<?php echo($id) ?>" />
            <table>
               <tr>
                  <td class="title">Categoria</td>
                  <td class="dark">
                     <select name="category[]" multiple>
<?php
      $query = "select id_category, name from category order by id_category";
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
                  <td class="title">Título</td>
                  <td class="dark">
                     <input type="text" size="40" name="title" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Sub Título</td>
                  <td class="dark">
                     <input type="text" size="40" name="subtitle" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Resumo</td>
                  <td class="dark">
                     <textarea name="teaser" rows="5" cols="40"></textarea>
                  </td>
               </tr>
               <tr>
                  <td class="title">Texto</td>
                  <td class="dark">
                     <textarea name="text" rows="10" cols="70"></textarea>
                  </td>
               </tr>
               <tr>
                  <td class="title">Imagem</td>
                  <td class="dark">
                     <select name="media">
                        <option value="0">Nenhuma</option>
<?php
      $query = "select id_media, name from media order by id_media";
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
                  <td class="title">Link</td>
                  <td class="dark">
                     <input type="text" size="40" name="link" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Tags</td>
                  <td class="dark">
                     <input type="text" size="40" name="tags" />
                  </td>
               </tr>
               <tr><td class="buttons" colspan="2"><input class="image_button" type="image" src="images/criar.png" value="Criar" onclick="javascript: form.submit;" /><input class="image_button" type="image" src="images/cancelar.png" value="Cancelar" onclick="javascript:document.location='article.php';return false;" /></td></tr>
            </table>
         </form>
      </div>
   </div>
<?php
   } else if ($action == "modify"){
      $query = "select id_article, title, subtitle, teaser, text, link, creation_date from article where id_article='$id[0]'";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());

      $query = "select id_media from rel_article_media where id_article=$row[0]";
      $result3 = mysql_query($query) or die("Invalid query: " . mysql_error());
      if (mysql_num_rows($result3) > 0) {
         $row3 = mysql_fetch_row($result3) or die("Could not retrieve row: " . mysql_error());
      }
      
      $title = $row[1];
      $subtitle = $row[2];
      $teaser = $row[3];
      $text = $row[4];
      $link = $row[5];
      $creation_date = $row[6];
      $media = $row3[0];

      $text = preg_replace('/<br \/>/',"\n", $text);
      $text = preg_replace('/<br\/>/',"\n", $text);
      $text = preg_replace('/<br>/',"\n", $text);
?>
   <div id="content">
      <h2><a href="article.php">Artigos</a> > Alterar Artigo</h2>
      <div class="tabela">
         <form name="form" method="post" action="article_submit.php">
            <input type="hidden" name="creation_date" value="<?php echo($creation_date) ?>" />
            <input type="hidden" name="page" value="<?php echo($page) ?>" />
            <input type="hidden" name="action" value="modify2" />
            <input type="hidden" name="id" value="<?php echo($id[0]) ?>" />
            <table>
               <tr>
                  <td class="title">Categoria</td>
                  <td class="dark">
                     <select name="category[]" multiple>
                        <option value="0">Nenhuma</option>
<?php
      $query = "select id_category, name from category order by id_category";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      $num = mysql_num_rows($result);
      $selected = "";
      for ($i = 0; $i < mysql_num_rows($result); $i++) {
         $row4 = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());

         $query = "select * from rel_category_article where id_article=$row[0] and id_category=$row4[0]";
         $result2 = mysql_query($query) or die("Invalid query: " . mysql_error());

         if ($row2 = mysql_fetch_row($result2)) {
            $selected = " selected";
         } else {
            $selected = "";
         }
?>
                        <option value="<?php echo($row4[0]); ?>"<?php echo($selected); ?>><?php echo($row4[1]); ?></option>
<?php
      }
?>
                     </select>
                     <script type="text/javascript">document.form.category.focus()</script>
                  </td>
               </tr>
               <tr>
                  <td class="title">Título</td>
                  <td class="dark">
                     <input type="text" size="40" name="title" value="<?php echo($title); ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Sub Título</td>
                  <td class="dark">
                     <input type="text" size="40" name="subtitle" value="<?php echo($subtitle); ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Resumo</td>
                  <td class="dark">
                     <textarea name="teaser" rows="5" cols="40"><?php echo($teaser); ?></textarea>
                  </td>
               </tr>
               <tr>
                  <td class="title">Texto</td>
                  <td class="dark">
                     <textarea name="text" rows="10" cols="70"><?php echo($text); ?></textarea>
                  </td>
               </tr>
               <tr>
                  <td class="title">Imagem</td>
                  <td class="dark">
                     <select name="media">
                        <option value="0">Nenhuma</option>
<?php
      $query = "select id_media, name from media order by id_media";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      $num = mysql_num_rows($result);
      $selected = "";
      for ($i = 0; $i < mysql_num_rows($result); $i++) {
         $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
         if ($media == $row[0]) {
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
                  <td class="title">Link</td>
                  <td class="dark">
                     <input type="text" size="40" name="link" value="<?php echo($link); ?>" />
                  </td>
               </tr>
<?php
      $tags = "";
            
      $query = "select tag.name from tag, rel_tag_article where rel_tag_article.id_article='$id[0]' and tag.id_tag=rel_tag_article.id_tag";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      while ($row = mysql_fetch_row($result)) {
         if (preg_match('/\s/', $row[0])) {
            $tags .= '"'.$row[0].'" ';
         } else {
            $tags .= $row[0].' ';
         }
      }
      $tags = rtrim($tags);
?>
               <tr>
                  <td class="title">Tags</td>
                  <td class="dark">
                     <input type="text" size="40" name="tags" value='<?php echo($tags); ?>' />
                  </td>
               </tr>
               <tr><td class="buttons" colspan="2"><input class="image_button" type="image" src="images/alterar.png" value="Alterar" onclick="javascript: form.submit;" /><input class="image_button" type="image" src="images/cancelar.png" value="Cancelar" onclick="javascript:document.location='article.php';return false;" /></td></tr>
            </table>
         </form>
      </div>
   </div>
<?php
   } else if ($action == "erase") {
?>
   <div id="content">
      <h2><a href="article.php">Artigos</a> > Apagar Artigo</h2>
      <div class="tabela">
         <form method="post" action="article_submit.php">
            <input type="hidden" name="page" value="<?php echo($page) ?>" />
            <input type="hidden" name="action" value="erase2" />
            <input type="hidden" name="id" value="<?php echo($id) ?>" />
            <table>
               <tr class="title">
                  <td>Categoria</td>
                  <td>Título</td>
                  <td>Sub Título</td>
               </tr>
<?php
      foreach ($id as $idx) {
         $query = "select id_article, title, subtitle, teaser, text, link from article where id_article='$idx'";
         $result = mysql_query($query) or die("Invalid query: " . mysql_error());
         $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());

         $query = "select id_category from rel_category_article where id_article=$row[0]";
         $result2 = mysql_query($query) or die("Invalid query: " . mysql_error());

         $cat = "[Nenhuma]";
         if (mysql_num_rows($result2) > 0) {
            $row2 = mysql_fetch_row($result2) or die("Could not retrieve row: " . mysql_error());
            $query = "select name from category where id_category='$row2[0]'";
            $result = mysql_query($query) or die("Invalid query: " . mysql_error());
            $row3 = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
            $cat = $row3[0];
         }

         $category = $cat;
         $title = $row[1];
         $subtitle = $row[2];
         $teaser = $row[3];
         $text = $row[4];
         $link = $row[5];
?>
               <tr class="dark">
                  <td><?php echo($category); ?></td>
                  <td><?php echo($title); ?></td>
                  <td><?php echo($subtitle); ?><input type="hidden" name="id[]" value="<?php echo($idx); ?>" /></td>
               </tr>
<?php
      }
?>
               <tr><td class="buttons" colspan="3"><input class="image_button" type="image" src="images/apagar.png" value="Apagar" onclick="javascript: form.submit;" /><input class="image_button" type="image" src="images/cancelar.png" value="Cancelar" onclick="javascript:document.location='article.php';return false;" /></td></tr>
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
