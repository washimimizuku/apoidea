<?php
include("header.php");
include("menu.php");
?>
<link rel="stylesheet" href="css/lists.css" type="text/css">
<script language="JavaScript" type="text/javascript" src="javascript/coordina.js"></script>
<script language="JavaScript" type="text/javascript" src="javascript/drag.js"></script>
<script language="JavaScript" type="text/javascript" src="javascript/dragdrop.js"></script>
<script language="JavaScript" type="text/javascript" src="javascript/layout.js"></script>



   <form action="teste.php" method="get">
      <div id="content">
         <h2><a href="layout.php">Layout</a> > Criar Layout</h2>
         <div class="tabela">
         
            <div id="layout">
               <ul id="depot_box" class="sortable boxy">
                  <li id="1">Menu</li>
                  <li id="2">Artigo</li>
                  <li id="3">Poll</li>
                  <li id="4">rss</li>
               </ul>
               <ul id="header_box" class="sortable boxy">
               </ul>
               <ul id="left_box" class="sortable boxy">
               </ul>
               <ul id="center_box" class="sortable boxy">   
               </ul>
               <ul id="right_box" class="sortable boxy">
               </ul>
               <ul id="footer_box" class="sortable boxy">
               </ul>


               <input type="hidden" name="order" id="order" value="" />
               <input class="image_button" type="image" src="images/criar.png" value="Criar" onclick="javascript: form.submit;" /><input class="image_button" type="image" src="images/cancelar.png" value="Cancelar" onclick="javascript:document.location='layout.php';" />
            </div>
         </div>
      </div>
   </form>
<?php
include("footer.php");
?>
