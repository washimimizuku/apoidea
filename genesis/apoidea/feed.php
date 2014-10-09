<?php
   // Action
   $action = $_REQUEST['action'];
   $search = $_REQUEST['search'];
   $order  = $_REQUEST['order'];

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
   <form name="form" method="post" action="feed.php">
      <div id="content">
         <h2>Feed</h2>
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
                  <td>Título</td>
                  <td>Subtítulo</td>
                  <td>URL</td>
                  <td>Tipo de Fonte</td>
                  <td>Descrição da Fonte</td>

                  <td></td>
                  <td></td>
               </tr>
<?php
   $query = "select title, subtitle, url, sourceTypeID, sourceDescription from feed order by $order $direction limit $begin, ".($pageSize + 1);
   if ($search) {
      $query = "select title, subtitle, url, sourceTypeID, sourceDescription from feed where  order by $order $direction limit $begin, ".($pageSize + 1);  
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
<?php
      $query_sourceType = "select name from sourceType where id=$row[3]";
      $result_sourceType = mysql_query($query_sourceType) or die("Invalid query: " . mysql_error());
      $sourceType="[Nada]";
      if (mysql_num_rows($result_sourceType) > 0) {
         $sourceType = "";
         while ($row_sourceType = mysql_fetch_row($result_sourceType)) {
            $sourceType .= $row_sourceType[0].", ";
         }
         $sourceType = substr($sourceType,0,strlen($sourceType)-2);
      }
?>
                  <td><?php echo($sourceType); ?></td>
                  <td><?php echo($row[4]); ?></td>

                  <td><a href="feed.php?action=modify&id%5B%5D=<?php echo($row[0]); ?>&page=<?php echo($page); ?>"><img class="small_button" src="images/refresh.png" /></a></td>
                  <td><a href="feed.php?action=erase&id%5B%5D=<?php echo($row[0]); ?>&page=<?php echo($page); ?>"><img class="small_button" src="images/delete.png" /></a></td>
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
      $num = $row[0] / $pageSize;
      
      if (($num > 5) && ($page > 0)) {
?>
                              <a href="feed.php?page=0"><img class="small_button" src="images/previous2.png" /></a>
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
                              <a href="feed.php?page=<? echo(intval($num-0.01)); ?>"><img class="small_button" src="images/next2.png" /></a>
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
      <h2><a href="feed.php">Feed</a> > Criar Feed</h2>
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
                     <input type="text" size="40" name="title" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Subtítulo</td>
                  <td class="dark">
                     <input type="text" size="40" name="subtitle" />
                  </td>
               </tr>
               <tr>
                  <td class="title">URL</td>
                  <td class="dark">
                     <input type="text" size="40" name="url" />
                  </td>
               </tr>
               <tr>
                  <td class="title">língua</td>
                  <td class="dark">
                     <input type="text" size="40" name="language" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Data de publicação</td>
                  <td class="dark">
                     <input type="text" size="40" name="publishDate" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Data de actualização</td>
                  <td class="dark">
                     <input type="text" size="40" name="lastBuildDate" />
                  </td>
               </tr>
               <tr>
                  <td class="title">URL da documentação</td>
                  <td class="dark">
                     <input type="text" size="40" name="documentationUrl" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Gerador</td>
                  <td class="dark">
                     <input type="text" size="40" name="generator" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Editor</td>
                  <td class="dark">
                     <input type="text" size="40" name="managingEditor" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Webmaster</td>
                  <td class="dark">
                     <input type="text" size="40" name="webmaster" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Direitos de Autor</td>
                  <td class="dark">
                     <input type="text" size="40" name="copyright" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Identificador único</td>
                  <td class="dark">
                     <input type="text" size="40" name="guid" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Tipo de Fonte</td>
                  <td class="dark">
                     <select name="sourceTypeID">
                        <option value="0">[Nada]</option>
<?php
      $query_sourceType = "select id, name from sourceType order by id";
      $result_sourceType = mysql_query($query_sourceType) or die("Invalid query: " . mysql_error());
      for ($i = 0; $i < mysql_num_rows($result_sourceType); $i++) {
         $row_sourceType = mysql_fetch_row($result_sourceType) or die("Could not retrieve row: " . mysql_error());
?>
                        <option value="<?php echo($row_sourceType[0]); ?>"><?php echo($row_sourceType[1]); ?></option>
<?php
      }
?>
                     </select>
                  </td>
               </tr>
               <tr>
                  <td class="title">Descrição da Fonte</td>
                  <td class="dark">
                     <input type="text" size="40" name="sourceDescription" />
                  </td>
               </tr>
               <tr>
                  <td class="title">TTL</td>
                  <td class="dark">
                     <input type="text" size="40" name="ttl" />
                  </td>
               </tr>
               <tr>
                  <td class="title">TTL automático</td>
                  <td class="dark">
                     <input type="text" size="40" name="autoTTL" />
                  </td>
               </tr>

               <tr><td class="buttons" colspan="2"><input class="image_button" type="image" src="images/criar.png" value="Criar" onclick="javascript: form.submit;" /><input class="image_button" type="image" src="images/cancelar.png" value="Cancelar" onclick="javascript:document.location='feed.php';return false;" /></td></tr>
            </table>
         </form>
      </div>
   </div>
<?php
   }
?>
<?php
   if ($action == "modify"){
      $query = "select title, subtitle, url, language, publishDate, lastBuildDate, documentationUrl, generator, managingEditor, webmaster, copyright, guid, sourceTypeID, sourceDescription, ttl, autoTTL from feed where id='$id[0]'";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
?>
   <div id="content">
      <h2><a href="feed.php">Feed</a> > Alterar Feed</h2>
      <div class="tabela">
         <form name="form" method="post" action="feed_submit.php">
            <input type="hidden" name="creation_date" value="<?php echo($creation_date) ?>" />
            <input type="hidden" name="page" value="<?php echo($page) ?>" />
            <input type="hidden" name="action" value="modify2" />
            <input type="hidden" name="id" value="<?php echo($id[0]) ?>" />
            <table>
               <tr>
                  <td class="title">Título</td>
                  <td class="dark">
                     <input type="text" size="40" name="title" value="<?php echo($row[0]); ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Subtítulo</td>
                  <td class="dark">
                     <input type="text" size="40" name="subtitle" value="<?php echo($row[1]); ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">URL</td>
                  <td class="dark">
                     <input type="text" size="40" name="url" value="<?php echo($row[2]); ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">língua</td>
                  <td class="dark">
                     <input type="text" size="40" name="language" value="<?php echo($row[3]); ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Data de publicação</td>
                  <td class="dark">
                     <input type="text" size="40" name="publishDate" value="<?php echo($row[4]); ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Data de actualização</td>
                  <td class="dark">
                     <input type="text" size="40" name="lastBuildDate" value="<?php echo($row[5]); ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">URL da documentação</td>
                  <td class="dark">
                     <input type="text" size="40" name="documentationUrl" value="<?php echo($row[6]); ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Gerador</td>
                  <td class="dark">
                     <input type="text" size="40" name="generator" value="<?php echo($row[7]); ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Editor</td>
                  <td class="dark">
                     <input type="text" size="40" name="managingEditor" value="<?php echo($row[8]); ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Webmaster</td>
                  <td class="dark">
                     <input type="text" size="40" name="webmaster" value="<?php echo($row[9]); ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Direitos de Autor</td>
                  <td class="dark">
                     <input type="text" size="40" name="copyright" value="<?php echo($row[10]); ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Identificador único</td>
                  <td class="dark">
                     <input type="text" size="40" name="guid" value="<?php echo($row[11]); ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Tipo de Fonte</td>
                  <td class="dark">
                     <select name="sourceTypeID">
                        <option value="0">[Nada]</option>
<?php
      $query_sourceType = "select id, name from sourceType order by id";
      $result_sourceType = mysql_query($query_sourceType) or die("Invalid query: " . mysql_error());
      $selected = "";
      for ($i = 0; $i < mysql_num_rows($result_sourceType); $i++) {
         $row_sourceType = mysql_fetch_row($result_sourceType) or die("Could not retrieve row: " . mysql_error());
         if ($row_sourceType[0] == $row[12]) {
            $selected = " selected";
         } else {
            $selected = "";
         }
?>
                        <option value="<?php echo($row_sourceType[0]); ?>"<?php echo($selected); ?>><?php echo($row_sourceType[1]); ?></option>
<?php
      }
?>
                     </select>
                  </td>
               </tr>
               <tr>
                  <td class="title">Descrição da Fonte</td>
                  <td class="dark">
                     <input type="text" size="40" name="sourceDescription" value="<?php echo($row[13]); ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">TTL</td>
                  <td class="dark">
                     <input type="text" size="40" name="ttl" value="<?php echo($row[14]); ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">TTL automático</td>
                  <td class="dark">
                     <input type="text" size="40" name="autoTTL" value="<?php echo($row[15]); ?>" />
                  </td>
               </tr>

               <tr><td class="buttons" colspan="2"><input class="image_button" type="image" src="images/alterar.png" value="Alterar" onclick="javascript: form.submit;" /><input class="image_button" type="image" src="images/cancelar.png" value="Cancelar" onclick="javascript:document.location='feed.php';return false;" /></td></tr>
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
      <h2><a href="feed.php">Feed</a> > Apagar Feed</h2>
      <div class="tabela">
         <form method="post" action="feed_submit.php">
            <input type="hidden" name="page" value="<?php echo($page) ?>" />
            <input type="hidden" name="action" value="erase2" />
            <table>
               <tr class="title">
                  <td>Título</td>
                  <td>Subtítulo</td>
                  <td>URL</td>
                  <td>língua</td>
                  <td>Data de publicação</td>
                  <td>Data de actualização</td>
                  <td>URL da documentação</td>
                  <td>Gerador</td>
                  <td>Editor</td>
                  <td>Webmaster</td>
                  <td>Direitos de Autor</td>
                  <td>Identificador único</td>
                  <td>Tipo de Fonte</td>
                  <td>Descrição da Fonte</td>
                  <td>TTL</td>
                  <td>TTL automático</td>

               </tr>
<?php
      foreach ($id as $idx) {
         $query = "select title, subtitle, url, language, publishDate, lastBuildDate, documentationUrl, generator, managingEditor, webmaster, copyright, guid, sourceTypeID, sourceDescription, ttl, autoTTL from feed where id='$idx'";
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
<?php
         $query_sourceType = "select name from sourceType where id=$row[12]";
         $result_sourceType = mysql_query($query_sourceType) or die("Invalid query: " . mysql_error());
         $row_sourceType = mysql_fetch_row($result_sourceType) or die("Could not retrieve row: " . mysql_error());
         $sourceType = $row_sourceType[0];
?>
                  <td><?php echo($sourceType); ?></td>
                  <td><?php echo($row[13]); ?></td>
                  <td><?php echo($row[14]); ?></td>
                  <td><?php echo($row[15]); ?></td>

               </tr>
					<input type="hidden" name="id[]" value="<?php echo($idx); ?>" />
<?php
      }
?>
               <tr><td class="buttons" colspan="19"><input class="image_button" type="image" src="images/apagar.png" value="Apagar" onclick="javascript: form.submit;" /><input class="image_button" type="image" src="images/cancelar.png" value="Cancelar" onclick="javascript:document.location='feed.php';return false;" /></td></tr>
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
