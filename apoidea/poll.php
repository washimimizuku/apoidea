<?php
   // Action
   $action = $_REQUEST['action'];
   $search = $_REQUEST['search'];
   $order  = $_REQUEST['order'];
   if ($order == "") {
      $order = "id_poll";
   }
   $direction  = $_REQUEST['direction'];
   if ($direction == "") {
      $direction = "desc";
   }

   $id = $_REQUEST['id'];
   if (!isset($id)) {
      if ($action == "modify") {
         header ("Location: poll.php?msg=Tem+de+seleccionar+uma+das+linha!");
         exit;
      } else if ($action == "erase") {
         header ("Location: poll.php?msg=Tem+de+seleccionar+pelo+menos+uma+linha!");
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
   <form name="form" method="post" action="poll.php">
      <div id="content">
         <h2>Votações</h2>
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
                  <td>ID <a href="poll.php?order=id_poll&direction=asc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/up.png" /></a><a href="poll.php?order=id_poll&direction=desc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/down.png" /></a></td>
                  <td>Título <a href="poll.php?order=title&direction=asc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/up.png" /></a><a href="poll.php?order=title&direction=desc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/down.png" /></a></td>
                  <td>Pergunta <a href="poll.php?order=question&direction=asc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/up.png" /></a><a href="poll.php?order=question&direction=desc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/down.png" /></a></td>
                  <td>Múltiplas Respostas</td>
                  <td>Ver Estatísticas</td>
                  <td>Início <a href="poll.php?order=begin_date&direction=asc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/up.png" /></a><a href="poll.php?order=begin_date&direction=desc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/down.png" /></a></td>
                  <td>Fim <a href="poll.php?order=end_date&direction=asc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/up.png" /></a><a href="poll.php?order=end_date&direction=desc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/down.png" /></a></td>
                  <td></td>
                  <td></td>
                  <td></td>
               </tr>
<?php
      $query = "select id_poll, title, question, multiple_answer, view_stats, begin_date, end_date from poll order by $order $direction limit $begin, 11";
      if ($search) {
         $query = "select id_poll, title, question, multiple_answer, view_stats, begin_date, end_date from poll where title like '%$search%' or question like '%$search%' order by $order $direction limit $begin, 11";
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
                  <td><?php echo($row[2]); ?></td>
                  <td><?php if ($row[3]) { echo("Sim"); } else { echo("Não"); } ?></td>
                  <td><?php if ($row[4]) { echo("Sim"); } else { echo("Não"); } ?></td>
                  <td><?php echo(substr($row[5],0,4).'-'.substr($row[5],4,2).'-'.substr($row[5],6,2)); ?></td>
                  <td><?php echo(substr($row[6],0,4).'-'.substr($row[6],4,2).'-'.substr($row[6],6,2)); ?></td>
                  <td><a href="component/poll_stats2.php?poll=<?php echo($row[0]); ?>" target="_blank"><img class="small_button" src="images/zoom.png" /></a></td>
                  <td><a href="poll.php?action=modify&id%5B%5D=<?php echo($row[0]); ?>"><img class="small_button" src="images/refresh.png" /></a></td>
                  <td><a href="poll.php?action=erase&id%5B%5D=<?php echo($row[0]); ?>"><img class="small_button" src="images/delete.png" /></a></td>
               </tr>
<?php
      }
?>
               <tr class="title">
                  <td id="paging_row" colspan="11">
                     <div id="paging">
                        <ul>
                           <li class="left">
<?php
      $query = "select count(*) from poll";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
      $num = $row[0] / 10;
      
      if (($num > 5) && ($page > 0)) {
?>
                              <a href="poll.php?page=0"><img class="small_button" src="images/double_previous.png" /></a>
<?php
      }
      if ($page > 0) {
?>
                              <a href="poll.php?page=<? echo($page - 1); ?>"><img class="small_button" src="images/previous.png" /></a>
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
                              <a href="poll.php?page=<? echo($i); ?>"><? echo($i + 1); ?></a>
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
                              <a href="poll.php?page=<? echo($page + 1); ?>"><img class="small_button" src="images/next.png" /></a>
<?php
      } else {
?>
                              &nbsp;
<?php
      }
      if (($num > 5) && ($page < $num - 1)) {
?>
                              <a href="poll.php?page=<? echo(intval($num-0.01)); ?>"><img class="small_button" src="images/double_next.png" /></a>
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
      <h2><a href="poll.php">Votações</a> > Criar Votação</h2>
<?php
	if ($msg) {
?>
      <p class="message"><?php echo($msg) ?></p>
<?php
	}
?>
      <div class="tabela">
         <form name="form" method="post" action="poll_submit.php">
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
                  <td class="title">Pergunta</td>
                  <td class="dark">
                     <input type="text" name="question" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Múltiplas Respostas</td>
                  <td class="dark">
                     <select name="multiple_answer">
                        <option value="1">Sim</option>
                        <option value="0" selected>Não</option>
                     </select>
                  </td>
               </tr>
               <tr>
                  <td class="title">Ver Estatísticas</td>
                  <td class="dark">
                     <select name="view_stats">
                        <option value="1">Sim</option>
                        <option value="0">Não</option>
                     </select>
                  </td>
               </tr>
               <tr>
                  <td class="title">Início</td>
                  <td class="dark">
<?php
      setlocale(LC_ALL, "pt_PT@euro");
      $date = getdate();
      $year = $date['year'];
      $month_text = array("Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
?>
                     <select name="begin_year">
                        <option value="0">Ano</option>
<?php
      for ($i = $year; $i < $year + 5; $i++) { 
?>
                        <option value="<?php echo($i) ?>"<?php if ($i == $year) { echo(' selected'); } ?>><?php echo($i) ?></option>
<?php
      }
?>
                     </select>
                     <select name="begin_month">
                        <option value="0">Mês</option>
<?php
      for ($i = 1; $i < 13; $i++) { 
?>
                        <option value="<?php echo($i) ?>"<?php if ($i == $date['mon']) { echo(' selected'); } ?>><?php echo($month_text[$i-1]) ?></option>
<?php
      }
?>
                     </select>
                     <select name="begin_day">
                        <option value="0">Dia</option>
<?php
      for ($i = 1; $i < 32; $i++) { 
?>
                        <option value="<?php echo($i) ?>"<?php if ($i == $date['mday']) { echo(' selected'); } ?>><?php echo($i) ?></option>
<?php
      }
?>
                     </select>
                  </td>
               </tr>
               <tr>
                  <td class="title">Fim</td>
                  <td class="dark">
                     <select name="end_year">
                        <option value="0">Ano</option>
<?php
      for ($i = $year; $i < $year + 5; $i++) { 
?>
                        <option value="<?php echo($i) ?>"<?php if ($i == $year+1) { echo(' selected'); } ?>><?php echo($i) ?></option>
<?php
      }
?>
                     </select>
                     <select name="end_month">
                        <option value="0">Mês</option>
<?php
      for ($i = 1; $i < 13; $i++) { 
?>
                        <option value="<?php echo($i) ?>"<?php if ($i == $date['mon']) { echo(' selected'); } ?>><?php echo($month_text[$i-1]) ?></option>
<?php
      }
?>
                     </select>
                     <select name="end_day">
                        <option value="0">Dia</option>
<?php
      for ($i = 1; $i < 32; $i++) { 
?>
                        <option value="<?php echo($i) ?>"<?php if ($i == $date['mday']) { echo(' selected'); } ?>><?php echo($i) ?></option>
<?php
      }
?>
                     </select>
                  </td>
               </tr>
               <tr>
                  <td class="title">Respostas</td>
                  <td class="dark" style="width:33em;">
                     <input type="text" size="50" maxlength="255" name="answer" />
                     <input type="image" class="small_button" src="images/more.png" onclick="javascript: addToList('form', 'answer', 'answer_list'); return (false);" /><br /><br />
                     <div style="float:left; margin-right:.3em">
                        <select name="answer_list" size="10" multiple style="width:28.4em;"></select>
                        <input type="hidden" name="all_answers_values" />
                        <input type="hidden" name="all_answers_texts" />
                     </div>
                     <div style="float:left">
                        <input type="image" class="small_button" src="images/less.png" onclick="javascript: removeFromList('form', 'answer_list'); return (false);" /><br /><br /><br /><br /><br /><br />
                        <input type="image" class="small_button" src="images/up.png" onclick="javascript: moveUp('form', 'answer_list'); return (false);" /><br />
                        <input type="image" class="small_button" src="images/down.png" onclick="javascript: moveDown('form', 'answer_list'); return (false);" />
                     </div>
                  </td>
               </tr>
               <tr><td class="buttons" colspan="2"><input class="image_button" type="image" src="images/criar.png" value="Criar" onclick="javascript: submitListForm('form', 'answer_list', 'all_answers_values', 'all_answers_texts'); return false();" /><input class="image_button" type="image" src="images/cancelar.png" value="Cancelar" onclick="javascript:document.location='poll.php';return false;" /></td></tr>
            </table>
         </form>
      </div>
   </div>
<?php
   } else if ($action == "modify"){
      $query = "select title, question, multiple_answer, view_stats, begin_date, end_date from poll where id_poll='$id[0]'";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());
      $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());

      $title = $row[0];
      $question = $row[1];
      $multiple_answer = $row[2];
      $view_stats = $row[3];
      $begin_date = $row[4];
      $end_date = $row[5];

      $begin_year = substr($begin_date,0,4);
      $begin_month = substr($begin_date,4,2);
      $begin_day = substr($begin_date,6,2);

      $end_year = substr($end_date,0,4);
      $end_month = substr($end_date,4,2);
      $end_day = substr($end_date,6,2);
?>
   <div id="content">
      <h2><a href="poll.php">Votações</a> > Alterar Votação</h2>
      <div class="tabela">
         <form name="form" method="post" action="poll_submit.php">
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
                  <td class="title">Pergunta</td>
                  <td class="dark">
                     <input type="text" name="question" value="<?php echo($question); ?>" />
                  </td>
               </tr>
               <tr>
                  <td class="title">Múltiplas Respostas</td>
                  <td class="dark">
                     <select name="multiple_answer">
                        <option value="1">Sim</option>
                        <option value="0"<?php if ($multiple_answer == 0) {echo(" selected");} ?>>Não</option>
                     </select>
                  </td>
               </tr>
               <tr>
                  <td class="title">Ver Estatísticas</td>
                  <td class="dark">
                     <select name="view_stats">
                        <option value="1">Sim</option>
                        <option value="0"<?php if ($view_stats == 0) {echo(" selected");} ?>>Não</option>
                     </select>
                  </td>
               </tr>
               <tr>
                  <td class="title">Início</td>
                  <td class="dark">
<?php
      setlocale(LC_ALL, "pt_PT@euro");
      $date = getdate();
      $year = $date['year'];
      $month_text = array("Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
?>
                     <select name="begin_year">
                        <option value="0">Ano</option>
<?php
      for ($i = $year; $i < $year + 5; $i++) { 
?>
                        <option value="<?php echo($i) ?>"<?php if ($begin_year == $i) {echo(" selected");} ?>><?php echo($i) ?></option>
<?php
      }
?>
                     </select>
                     <select name="begin_month">
                        <option value="0">Mês</option>
<?php
      for ($i = 1; $i < 13; $i++) { 
?>
                        <option value="<?php echo($i) ?>"<?php if ($begin_month == $i) {echo(" selected");} ?>><?php echo($month_text[$i-1]) ?></option>
<?php
      }
?>
                     </select>
                     <select name="begin_day">
                        <option value="0">Dia</option>
<?php
      for ($i = 1; $i < 32; $i++) { 
?>
                        <option value="<?php echo($i) ?>"<?php if ($begin_day == $i) {echo(" selected");} ?>><?php echo($i) ?></option>
<?php
      }
?>
                     </select>
                  </td>
               </tr>
               <tr>
                  <td class="title">Fim</td>
                  <td class="dark">
                     <select name="end_year">
                        <option value="0">Ano</option>
<?php
      for ($i = $year; $i < $year + 5; $i++) { 
?>
                        <option value="<?php echo($i) ?>"<?php if ($end_year == $i) {echo(" selected");} ?>><?php echo($i) ?></option>
<?php
      }
?>
                     </select>
                     <select name="end_month">
                        <option value="0">Mês</option>
<?php
      for ($i = 1; $i < 13; $i++) { 
?>
                        <option value="<?php echo($i) ?>"<?php if ($end_month == $i) {echo(" selected");} ?>><?php echo($month_text[$i-1]) ?></option>
<?php
      }
?>
                     </select>
                     <select name="end_day">
                        <option value="0">Dia</option>
<?php
      for ($i = 1; $i < 32; $i++) { 
?>
                        <option value="<?php echo($i) ?>"<?php if ($end_day == $i) {echo(" selected");} ?>><?php echo($i) ?></option>
<?php
      }
?>
                     </select>
                  </td>
               </tr>
               <tr>
                  <td class="title">Respostas</td>
                  <td class="dark" style="width:33em;">
                     <input type="text" size="50" maxlength="255" name="answer" />
                     <input type="image" class="small_button" src="images/more.png" onclick="javascript: addToList('form', 'answer', 'answer_list'); return (false);" /><br /><br />
                     <div style="float:left; margin-right:.3em">
                        <select name="answer_list" size="10" multiple style="width:28.4em;">
<?php
      $query = "select id_poll_answer, answer from poll_answer where id_poll=$id[0] order by position, id_poll_answer";
      $result = mysql_query($query) or die("Invalid query: " . mysql_error());

      for ($i = 0; $i < mysql_num_rows($result); $i++) {
         $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());
?>
                                 <option value="<?php echo($row[0]); ?>"><?php echo($row[1]); ?></option>
<?php
      }
?>                     
                        </select>
                        <input type="hidden" name="all_answers_values" />
                        <input type="hidden" name="all_answers_texts" />
                        <input type="hidden" name="erased_answers" />
                     </div>
                     <div style="float:left">
                        <input type="image" class="small_button" src="images/less.png" onclick="javascript: removeFromListAndRecordErase('form', 'answer_list', 'erased_answers'); return (false);" /><br /><br /><br /><br /><br /><br />
                        <input type="image" class="small_button" src="images/up.png" onclick="javascript: moveUp('form', 'answer_list'); return (false);" /><br />
                        <input type="image" class="small_button" src="images/down.png" onclick="javascript: moveDown('form', 'answer_list'); return (false);" />
                     </div>
                  </td>
               </tr>
               <tr><td class="buttons" colspan="2"><input class="image_button" type="image" src="images/alterar.png" value="Alterar" onclick="javascript: submitListForm('form', 'answer_list', 'all_answers_values', 'all_answers_texts'); return false();" /><input class="image_button" type="image" src="images/cancelar.png" value="Cancelar" onclick="javascript:document.location='poll.php';return false;" /></td></tr>
            </table>
         </form>
      </div>
   </div>
<?php
   } else if ($action == "erase") {
?>
   <div id="content">
      <h2><a href="poll.php">Votações</a> > Criar Votação</h2>
      <div class="tabela">
         <form method="post" action="poll_submit.php">
            <input type="hidden" name="action" value="erase2" />
            <input type="hidden" name="id" value="<?php echo($id) ?>" />
            <table>
               <tr class="title">
                  <td>Título</td>
                  <td>Pergunta</td>
                  <td>Múltiplas Respostas</td>
                  <td>Ver Estatísticas</td>
                  <td>Início</td>
                  <td>Fim</td>
               </tr>
<?php
      foreach ($id as $idx) {
         $query = "select title, question, multiple_answer, view_stats, begin_date, end_date from poll where id_poll='$idx'";
         $result = mysql_query($query) or die("Invalid query: " . mysql_error());
         $row = mysql_fetch_row($result) or die("Could not retrieve row: " . mysql_error());

         $title = $row[0];
         $question = $row[1];
         $multiple_answer = $row[2];
         $view_stats = $row[3];
         $begin_date = $row[4];
         $end_date = $row[5];
?>
               <tr class="dark">
                  <td><?php echo($title); ?></td>
                  <td><?php echo($question); ?></td>
                  <td><?php if ($multiple_answer) { echo("Sim"); } else { echo("Não"); } ?></td>
                  <td><?php if ($view_stats) { echo("Sim"); } else { echo("Não"); } ?></td>
                  <td><?php echo($begin_date); ?></td>
                  <td><?php echo($end_date); ?><input type="hidden" name="id[]" value="<?php echo($idx); ?>" /></td>
               </tr>
<?php
      }
?>
               <tr><td class="buttons" colspan="6"><input class="image_button" type="image" src="images/apagar.png" value="Apagar" onclick="javascript: form.submit;" /><input class="image_button" type="image" src="images/cancelar.png" value="Cancelar" onclick="javascript:document.location='poll.php';return false;" /></td></tr>
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
