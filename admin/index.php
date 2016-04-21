<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
//echo phpinfo();
echo "
 <html>
 <head>
 <script type=\"text/javascript\" src=\"js/jquery-2.1.4.min.js\"></script>
  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
  <title>System menu (ver.2.1 26.06.2015)</title>
 </head>

 <frameset rows=\"90,*\" cols=\"*\" border=\"0\">
    <frameset cols=\"*,150\" border=\"0\">
      <frame src=\"top_menu.php\" name=\"top_menu\" id=\"top_menu_id\" scrolling=\"no\" noresize>
      <frame src=\"get_orders_from_inet.php\" name=\"top_info\" id=\"top_info\" scrolling=\"no\" noresize>
    </frameset>
   <frame src=\"operation_list.php\" name=\"operation_list\" id=\"operation_list_id\" scrolling=\"yes\">
 </frameset>

</html>";
?>