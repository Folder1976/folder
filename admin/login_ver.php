<?php
include 'init.lib.php';
echo "connecting...";
connect_to_mysql();
if($_REQUEST["pass"]==null){
echo "User not found or login+pass not corect!";
header ('Refresh: 1; url=login.php?web='.$_REQUEST["web"]);
exit();
}
session_start();
$_SESSION[BASE.'base']=$_REQUEST["base"];


$ver = mysql_query("SET NAMES utf8");
$ver = mysql_query("SELECT 
		    `klienti_name_1`,
		    `klienti_id`,
		    `klienti_inet_id`,
		    `klienti_email`,
		    `klienti_setup` 
		    FROM `tbl_klienti` 
		    WHERE `klienti_email`='".$_REQUEST["login"]."' 
		    and `klienti_pass`='".$_REQUEST["pass"]."'");
		    
if (!$ver)
{
  echo "Query error ";
  exit();
}
/*echo $_SESSION[BASE.'base'],"SELECT 
		    `klienti_name_1`,
		    `klienti_id`,
		    `klienti_setup` 
		    FROM `tbl_klienti` 
		    WHERE `klienti_email`='".$_REQUEST["login"]."' 
		    and `klienti_pass`='".$_REQUEST["pass"]."'";*/
		    
		    
if(mysql_num_rows($ver)==0){
echo "User not found or login+pass not corect!";
header ('Refresh: 5; url=login.php?web='.$_REQUEST["web"]);
exit();
}
echo "User (",mysql_result($ver,0,"klienti_name_1"),") connected to ",$_REQUEST["base"];

$_SESSION[BASE.'base']=$_REQUEST["base"];
//echo mysql_result($ver,0,"klienti_inet_id");
$_SESSION[BASE.'userlevel']=mysql_result($ver,0,"klienti_inet_id");
$_SESSION[BASE.'login']=mysql_result($ver,0,"klienti_email");
$_SESSION[BASE.'username']=mysql_result($ver,0,"klienti_name_1");
$_SESSION[BASE.'usersetup']=mysql_result($ver,0,"klienti_setup");
$_SESSION[BASE.'userid']=mysql_result($ver,0,"klienti_id");

$web_t=$_REQUEST["web"];

if($web_t=="/login_ver.php"){
  $web_t="/index.php";
}
//echo $_REQUEST["web"];
echo "<script>parent.frames.top_menu.document.location.reload();</script>";
echo "<script>parent.frames.top_info.document.location.reload();</script>";
//echo "<script>parent.frames.operation_list.document.location.reload();</script>";
header ('Refresh: 1; url='.$_REQUEST["web"]);


echo "\n</body>";
?>
