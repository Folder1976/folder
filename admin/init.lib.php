<?php
include_once "config/config.php";
function connect_to_mysql(){
//echo "<pre>".print_r(var_dump(get_defined_vars()));
$dbcnx = mysql_connect(DB_HOST, DB_USER, DB_PASS);
  if (!$dbcnx)
  {
  echo "Not connect to MySQL";
  exit();
  }

  if (!mysql_select_db(BASE,$dbcnx))
  {
  echo "No base present";
  exit();
  }
  
//echo "HA HA";
}
function Get_IP() {
$ip = getenv(HTTP_X_FORWARDED_FOR);
if(!$ip){
  $ip = getenv(REMOTE_ADDR);
  }else{
  $tmp = ",";
    if(strlen(strstr($ip,$tmp))!=0){
    $ips = explode($tmp,$ip);
    $ip = $ips[count($ips)-1];
    }
}
return trim($ip);
}

function session_verify($web,$key) {

//if(!defined("BASE")) define("BASE","STURM",true); //Тут пишем имя каталога к которому коннектимся

if(isset($_SESSION[BASE.'base'])){
if(empty($_SERVER['QUERY_STRING'])){
    save_log($_SERVER['REMOTE_ADDR'],$_SERVER["PHP_SELF"]."?".$web);
}else{
    save_log($_SERVER['REMOTE_ADDR'],$_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
}

  if(verify_black_list($_SERVER['REMOTE_ADDR']))
  {
    echo "Your IP - ",$_SERVER['REMOTE_ADDR']," blocked!";
    exit();
  }
}


header ('Content-Type: text/html; charset=utf8');
if ($key <> "none"){
  echo "<p align='right' style='color:#610b0b;font-size:xx-small'>User: <b>",$_SESSION[BASE.'username'],"</b>
  <a href='login.php'> (exit)</a>";
  }
  
  if (!isset($_SESSION[BASE.'username']) or !isset($_SESSION[BASE.'base'])){
    if ($key <> "none"){
	echo "No User found/";
	header ('Refresh: 1; url=login.php?web='.$web);
	exit();
    }
  }else{
    if(strpos($_SESSION[BASE.'usersetup'],$_SESSION[BASE.'base'])>0){
      return true;
    }else{
      if(strpos($_SESSION[BASE.'usersetup'],$web)>0){
	return true;      
      }else{
	echo "<font color='red'> (",$web," is closed for this user) </font>
	<a href='login.php'> reconnect</a>
	";
	return false;
      }
}
  
  }

}
function user_menu_lang(){
//session_start();

echo "<script>
      function reload_windows(){
	parent.frames.top_menu.document.location.reload();
	parent.frames.operation_list.document.location.reload();
      }
</script>";


$ver = mysql_query("SET NAMES utf8");
$ver = mysql_query("SELECT * FROM `tbl_web_lang` ORDER BY `web_lang_id` ASC");
if (!$ver)
{
  echo "Query error - LANG";
  exit();
}

if ($_SESSION[BASE.'lang'] <1){
  $_SESSION[BASE.'lang']=1;
}

echo "\n<form method='post' action='",$_SERVER["PHP_SELF"],"' onChange=submit();>";
echo "\n<select name='lang' id='lang' style='width:80px' OnChange='reload_windows();'>";

$count=0;
while ($count < mysql_num_rows($ver))
{
  echo "\n<option ";
	if ($_SESSION[BASE.'lang'] == mysql_result($ver,$count,"web_lang_id")) echo "selected ";
  
  echo "value=" . mysql_result($ver,$count,"web_lang_id") . ">" . mysql_result($ver,$count,"web_lang_lang") . "</option>";
  $count++;
}
echo "</select>";
echo "</form>";
}
function verify_black_list($ip){
$ver = mysql_query("SELECT * FROM `tbl_black_ip_list` WHERE `black_ip_ip`='$ip'");
//echo "SELECT * FROM `tbl_black_ip_list` WHERE `black_ip_ip`='$ip'";
if (!$ver)
{
  echo "Query error - Black List";
  exit();
}
if(mysql_num_rows($ver) > 0)
{
  return 1;
}else{
  return 0;
}


}
function save_log($ip,$web){
//session_start();

$date = date("Y-m-d G:i:s"); 
$user = 0;  
if(isset($_SESSION[BASE.'userid']))$user = $_SESSION[BASE.'userid'];
  
$tQuery = "INSERT INTO `tbl_log`
		    (`log_date`,
		      `log_ip`,
		      `log_web`,
		      `log_user_id`)
		      VALUES
		      (
		      '$date',
		      '$ip',
		      '$web',
		      '$user'
		      )
		      ";
 // echo $tQuery;
  $ver = mysql_query($tQuery);
}
?>
