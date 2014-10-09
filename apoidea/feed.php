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
         header ("Location: feed.php?msg=Tem+de+seleccionar+uma+das+linha!");
         exit;
      } else if ($action == "erase") {
         header ("Location: feed.php?msg=Tem+de+seleccionar+pelo+menos+uma+linha!");
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
   <form name="form" method="post" action="feed.php">
      <div id="content">
         <h2>Feeds</h2>
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
                  <td>Título <a href="feed.php?order=title&direction=asc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/up.png" /></a><a href="feed.php?order=title&direction=desc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/down.png" /></a></td>
                  <td>Subtítulo <a href="feed.php?order=subtitle&direction=asc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/up.png" /></a><a href="feed.php?order=subtitle&direction=desc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/down.png" /></a></td>
                  <td>url <a href="feed.php?order=url&direction=asc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/up.png" /></a><a href="feed.php?order=url&direction=desc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/down.png" /></a></td>
                  <td>Tipo de Fonte <a href="feed.php?order=source_type&direction=asc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/up.png" /></a><a href="feed.php?order=source_type&direction=desc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/down.png" /></a></td>
                  <td>Localização de Fonte <a href="feed.php?order=source_description&direction=asc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/up.png" /></a><a href="feed.php?order=source_description&direction=desc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/down.png" /></a></td>
                  <td></td>
                  <td></td>
               </tr>
<?php
      $query = "select id, title, url, subtitle, language, pub_date, last_build_date, docs_url, generator, managing_editor, webmaster, copyright, source_type, source_description from feed order by $order $direction limit $begin, 11";
      if ($search) {
         $query =  "select id, title, url, subtitle, language, pub_date, last_build_date, docs_url, generator, managing_editor, webmaster, copyright, source_type, source_description from feed where title like '%$search%' or subtitle like '%$search%' order by $order $direction limit $begin, 11";
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
      if ($row[12] == 0) {
         $source_type = "[Nenhuma]";
      } else {
         $query = "select name from source_type where id=$row[12]";
         $result2 = mysql_query($query) or die("Invalid query: " . mysql_error());
         $row2 = mysql_fetch_row($result2) or die("Could not retrieve row: " . mysql_error());
         $source_type = $row2[0];
      }
?>
                  <td class="checkbox"><input type="checkbox" name="id[]" value="<?php echo($row[0]); ?>" /></td>
                  <td><?php echo($row[1]); ?></td>
                  <td><?php echo($row[3]); ?></td>
                  <td><?php echo($row[2]); ?></td>
                  <td><?php echo($source_type); ?></td>
                  <td><?php echo($row[13]); ?></td>
                  <td><a href="feed.php?action=modify&id%5B%5D=<?php echo($row[0]); ?>"><img class="small_button" src="images/refresh.png" /></a></td>
                  <td><a href="feed.php?action=erase&id%5B%5D=<?php echo($row[0]); ?>"><img class="small_button" src="images/delete.png" /></a></td>
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
      $query = "select count(*) from feed";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
      $num = $row[0] / 10;
      
      if (($num > 5) && ($page > 0)) {
?>
                              <a href="feed.php?page=0"><img class="small_button" src="images/double_previous.png" /></a>
<?php
      }
      if ($page > 0) {
?>
                              <a href="feed.php?page=<? echo($page - 1); ?>"><img class="small_button" src="images/previous.png" /></a>
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
                              <a href="feed.php?page=<? echo($i); ?>"><? echo($i + 1); ?></a>
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
                              <a href="feed.php?page=<? echo($page + 1); ?>"><img class="small_button" src="images/next.png" /></a>
<?php
      } else {
?>
                              &nbsp;
<?php
      }
      if (($num > 5) && ($page < $num - 1)) {
?>
                              <a href="feed.php?page=<? echo(intval($num-0.01)); ?>"><img class="small_button" src="images/double_next.png" /></a>
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
      <h2><a href="feed.php">Feeds</a> > Criar Feed</h2>
<?php
	if ($msg) {
?>
      <p class="message"><?php echo($msg) ?></p>
<?php
	}
?>
      <div class="tabela">
         <form name="form" method="post" action="feed_submit.php">
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
                  <td class="title">url</td>
                  <td class="dark">
                     <input type="text" name="url" />
                  </td>
               </tr>
               <tr>
                  <td class="title">SubTítulo</td>
                  <td class="dark">
                     <input type="text" name="subtitle" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Língua</td>
                  <td class="dark">
                     <input type="text" name="language" />
                  </td>
               </tr>
               <tr>
                  <td class="title">URL Documentos</td>
                  <td class="dark">
                     <input type="text" name="docs_url" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Editor</td>
                  <td class="dark">
                     <input type="text" name="managing_editor" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Webmaster</td>
                  <td class="dark">
                     <input type="text" name="webmaster" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Copyright</td>
                  <td class="dark">
                     <input type="text" name="copyright" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Tipo de Fonte</td>
                  <td class="dark">
                     <select name="source_type">
                        <option value="0">Nenhuma</option>
<?php
      $query = "select id, name from source_type order by id";
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
                  <td class="title">Localização Fonte</td>
                  <td class="dark">
                     <input type="text" name="source_description" />
                  </td>
               </tr>
               <tr>
                  <td class="title">TTL</td>
                  <td class="dark">
                     <input type="text" name="ttl" />
                  </td>
               </tr>
               <tr><td class="buttons" colspan="2"><input class="image_button" type="image" src="images/criar.png" value="Criar" onclick="javascript: form.submit;" /><input class="image_button" type="image" src="images/cancelar.png" value="Cancelar" onclick="javascript:document.location='feed.php';return false;" /></td></tr>
            </table>
         </form>
      </div>
   </div>
<?php
   } else if ($action == "modify"){
      $query = "select title, url, subtitle, language, pub_date, last_build_date, docs_url, generator, managing_editor, webmaster, copyright, source_type, source_description from feed where id='$id[0]'";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());

      $title = $row[0];
      $url = $row[1];
      $subtitle = $row[2];
      $language = $row[3];
      $pub_date = $row[4];
      $last_date = $row[5];
      $docs_url = $row[6];
      $generator = $row[7];
      $managing_editor = $row[8];
      $webmaster = $row[9];
      $copyright = $row[10];
      $source_type = $row[11];
      $source_description = $row[12];

      $pub_year = substr($pub_date,0,4);
      $pub_month = substr($pub_date,5,2);
      $pub_day = substr($pub_date,8,2);

      $last_year = substr($last_date,0,4);
      $last_month = substr($last_date,5,2);
      $last_day = substr($last_date,8,2);
?>
   <div id="content">
      <h2><a href="feed.php">Feeds</a> > Alterar Feed</h2>
      <div class="tabela">
         <form name="form" method="post" action="feed_submit.php">
            <input type="hidden" name="action" value="modify2" />
            <input type="hidden" name="id" value="<?php echo($id[0]) ?>" />
            <table>
               <tr>
                  <td class="title">Título</td>
                  <td class="dark">
                     <input type="text" name="title" value="<?php echo($title) ?>" />
                     <script type="text/javascript">document.form.title.focus()</script>
                  </td>
               </tr>
               <tr>
                  <td class="title">url</td>
                  <td class="dark">
                     <input type="text" name="url" value="<?php echo($url) ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">SubTítulo</td>
                  <td class="dark">
                     <input type="text" name="subtitle" value="<?php echo($subtitle) ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Língua</td>
                  <td class="dark">
                     <input type="text" name="language" value="<?php echo($language) ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">URL Documentos</td>
                  <td class="dark">
                     <input type="text" name="docs_url" value="<?php echo($docs_url) ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Editor</td>
                  <td class="dark">
                     <input type="text" name="managing_editor" value="<?php echo($managing_editor) ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Webmaster</td>
                  <td class="dark">
                     <input type="text" name="webmaster" value="<?php echo($webmaster) ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Copyright</td>
                  <td class="dark">
                     <input type="text" name="copyright" value="<?php echo($copyright) ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Tipo de Fonte</td>
                  <td class="dark">
                     <select name="source_type">
                        <option value="0">Nenhuma</option>
<?php
      $query = "select id, name from source_type order by id";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      for ($i = 0; $i < mysql_num_rows($result); $i++) {
         $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
?>
                        <option value="<?php echo($row[0]); ?>"<?php if ($source_type == $row[0]) {echo(' selected');} ?>><?php echo($row[1]); ?></option>
<?php
      }
?>
                     </select>
                  </td>
               </tr>
               <tr>
                  <td class="title">Localização Fonte</td>
                  <td class="dark">
                     <input type="text" name="source_description" value="<?php echo($source_description) ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">TTL</td>
                  <td class="dark">
                     <input type="text" name="ttl" value="<?php echo($ttl) ?>" />
                  </td>
               </tr>
               <tr><td class="buttons" colspan="2"><input class="image_button" type="image" src="images/alterar.png" value="Alterar" onclick="javascript: form.submit;" /><input class="image_button" type="image" src="images/cancelar.png" value="Cancelar" onclick="javascript:document.location='feed.php';return false;" /></td></tr>
            </table>
         </form>
      </div>
   </div>
<?php
   } else if ($action == "erase") {
?>
   <div id="content">
      <h2><a href="feed.php">Feeds</a> > Apagar Feed</h2>
      <div class="tabela">
         <form method="post" action="feed_submit.php">
            <input type="hidden" name="action" value="erase2" />
            <input type="hidden" name="id" value="<?php echo($id) ?>" />
            <table>
               <tr class="title">
                  <td>ID</td>
                  <td>Título</td>
                  <td>url</td>
                  <td>subtítulo</td>
                  <td>língua</td>
                  <td>pub date</td>
                  <td>last build date</td>
                  <td>docs url</td>
                  <td>generator</td>
                  <td>managing editor</td>
                  <td>webmaster</td>
                  <td>copyright</td>
                  <td>Tipo de Fonte</td>
                  <td>Localização de Fonte</td>
                  <td>TTL</td>
               </tr>
<?php
      foreach ($id as $idx) {
         $query = "select id, title, url, subtitle, language, pub_date, last_build_date, docs_url, generator, managing_editor, webmaster, copyright, source_type, source_description, ttl from feed where id='$idx'";
         $result = mysql_query($query) or die("Invalid query: " . mysql_error());
         $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
?>
               <tr class="dark">
                  <td><?php echo($row[0]); ?></td>
                  <td><?php echo($row[1]); ?></td>
                  <td><?php echo($row[2]); ?></td>
                  <td><?php echo($row[3]); ?></td>
                  <td><?php echo($row[4]); ?></td>
                  <td><?php echo($row[5]); ?></td>
                  <td><?php echo($row[6]); ?></td>
                  <td><?php echo($row[7]); ?></td>
                  <td><?php echo($row[8]); ?></td>
                  <td><?php echo($row[9]); ?></td>
                  <td><?php echo($row[10]); ?></td>
                  <td><?php echo($row[11]); ?></td>
                  <td><?php echo($row[12]); ?></td>
                  <td><?php echo($row[13]); ?></td>
                  <td><?php echo($row[14]); ?><input type="hidden" name="id[]" value="<?php echo($idx); ?>" /></td>
               </tr>
<?php
      }
?>
               <tr><td class="buttons" colspan="14"><input class="image_button" type="image" src="images/apagar.png" value="Apagar" onclick="javascript: form.submit;" /><input class="image_button" type="image" src="images/cancelar.png" value="Cancelar" onclick="javascript:document.location='feed.php';return false;" /></td></tr>
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
