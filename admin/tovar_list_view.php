<?php
include '../init.lib.php';
include '../init.lib.user.php';
include '../init.lib.user.tovar.php';
connect_to_mysql();
session_start();

//==================================SETUP===========================================
if ($_SESSION[BASE.'lang'] <1){
  $_SESSION[BASE.'lang']=1;
}
$setup = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	  `setup_menu_name`, 
	  `setup_menu_".$_SESSION[BASE.'lang']."`
	  FROM `tbl_setup_menu`

";
$setup = mysql_query($tQuery);
$m_setup = array();
$count=0;
while ($count<mysql_num_rows($setup)){
 $m_setup[mysql_result($setup,$count,0)]=mysql_result($setup,$count,1);
 $count++;
}
$setup = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
*	  FROM `tbl_setup`
";
$setup = mysql_query($tQuery);
$count=0;
while ($count<mysql_num_rows($setup)){
 $m_setup[mysql_result($setup,$count,0)]=mysql_result($setup,$count,1);
 $count++;
}
//==================================SETUP=MENU==========================================

if(verify_black_list($_SERVER['REMOTE_ADDR']))
{
  echo "Your IP - ",$_SERVER['REMOTE_ADDR']," blocked!";
  exit();
}
save_log($_SERVER['REMOTE_ADDR'],$_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);

echo user_operation_list_view(3320,$m_setup);
?>
