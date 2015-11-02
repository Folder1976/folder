<?php
//include 'init.lib.php';
//include 'init.lib.user.php';
//include 'init.lib.user.tovar.php';

//echo $_SERVER['PHP_SELF'],"---",$_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING'];

//session_start();
//connect_to_mysql();

/*if(verify_black_list($_SERVER['REMOTE_ADDR']))
{
  echo "Your IP - ",$_SERVER['REMOTE_ADDR']," blocked!";
  exit();
}*/

//echo $_SERVER['PHP_SELF'],"---",$_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING'];
//$str = $_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING'];
//print_r($GLOBALS);
//echo "-> <br>",$_SERVER['REQUEST_URI'],"<br>",$_SERVER['HTTP_REFERER']," ";
header ('Refresh: 0; url=https://sturm.com.ua/page_not_found_404');
?>
