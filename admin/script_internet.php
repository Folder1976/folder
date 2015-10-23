<?php
include 'init.lib.php';
connect_to_mysql();
session_start();
if (!session_verify($_SERVER["PHP_SELF"],"none")){
  exit();
}

$operation_id=0;
if(isset($_REQUEST['operation_id'])) $operation_id=$_REQUEST['operation_id'];
//==================================SETUP=MENU==========================================
header ('Content-Type: text/html; charset=utf8');
echo "<html><title>Internet prn ",$operation_id,"</title>
<header><link rel='stylesheet' type='text/css' href='sturm.css'></header>
 <head>
  <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
  <title>System menu (ver.2.0 07.03.2013)</title>
 </head>
  <frameset rows='40,*' cols='*' border='1'>
   <frame src='script_internet_keys.php?operation_id=",$operation_id,"' name='script_key' id='script_key' scrolling='no' noresize>
   <frame src='edit_nakl_oplata.php?operation_id=",$operation_id,"' name='script_window' id='script_window' scrolling='yes'>
 </frameset>
</html>";
?>
