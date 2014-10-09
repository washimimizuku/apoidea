<?php
   include("header.php");
   include("menu.php");
   
   $msg = $_REQUEST['msg'];
?>
   <div id="content">
      <h1>Login:</h1>
<?php
	if ($msg) {
?>
      <p class="message"><?php echo($msg) ?></p>
<?php
	}
?>
      <form name="form" method="post" action="login.php">
         <table class="center">
            <tr>
               <td class="title">Utilizador:</td>
               <td class="dark">
                  <input type="text" name="username" />
                  <script type="text/javascript">document.form.username.focus()</script>
               </td>
            </tr>
            <tr>
               <td class="title">Password:</td>
               <td class="dark"><input type="password" name="password" /></td>
            </tr>
            <tr><td class="buttons" align="center" colspan="2"><input class="image_button" type="image" src="images/login.png" value="Login" onclick="javascript: form.submit;" /></td></tr>
         </table>
      </form>
   </div>
<?php
   include("footer.php");
?>