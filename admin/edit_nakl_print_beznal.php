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
echo "<html><title>Beznal prn ",$operation_id,"</title>
<header><link rel='stylesheet' type='text/css' href='sturm.css'></header>
 <head>
  <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
  
 </head>
  <frameset rows='170,*' cols='*' border='1'>
   <frame src='edit_nakl_print_beznal_header.php?operation_id=",$operation_id,"' name='beznal_print_header_$operation_id' id='beznal_print_header_$operation_id' scrolling='no' noresize>
   <frame src='edit_nakl_print.php?tmp=beznal_rah&operation_id=",$operation_id,"' name='beznal_print_$operation_id' id='beznal_print_$operation_id' scrolling='yes'>
 </frameset>
</html>";
?>
