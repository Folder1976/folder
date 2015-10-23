<?php
include 'init.lib.php';
connect_to_mysql();
session_start();
if (!session_verify($_SERVER["PHP_SELF"],"+")){
  exit();
}

//==================================SETUP===========================================
if ($_SESSION[BASE.'lang'] <1){
  $_SESSION[BASE.'lang']=1;
}
$setup = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	  `setup_menu_name`, 
	  `setup_menu_".$_SESSION[BASE.'lang']."`
	  FROM `tbl_setup_menu`
	  WHERE 
	  `setup_menu_name` like '%menu%'

";
//echo $tQuery;
$setup = mysql_query($tQuery);
$m_setup = array();
$count=0;
while ($count<mysql_num_rows($setup)){
 $m_setup[mysql_result($setup,$count,0)]=mysql_result($setup,$count,1);
 $count++;
}
//==================================SETUP=MENU==========================================
$count = 0;
$this_page_name = "edit_delivery.php";
$this_table_id_name = "delivery_id";
$this_table_name_name = "delivery_name";

$this_table_name = "tbl_delivery";
$sort_find_where = "";
$iKlient_id = $_GET[$this_table_id_name];
$sort_find = "";
if(isset($_REQUEST["_sort_find"])) $sort_find = $_REQUEST["_sort_find"];
$iKlient_count = 0;
if(!$iKlient_id) $iKlient_id=1;
$ver = mysql_query("SET NAMES utf8");
$ver = mysql_query("SELECT * FROM " . $this_table_name . " WHERE " . $this_table_id_name . " = " . $iKlient_id);
if (!$ver)
{
  echo "Query error - ", $this_table_name;
  exit();
}

//==========================================================================================================
if ($sort_find != null){
$sort_find_where = " WHERE upper(" . $this_table_name_name . ") like '%" . mb_strtoupper($sort_find,'UTF-8') . "%'";
}
//echo "SELECT " . $this_table_id_name . "," . $this_table_name_name . " FROM ". $this_table_name  . $sort_find_where;

$list = mysql_query("SET NAMES utf8");
$list = mysql_query("SELECT " . $this_table_id_name . "," . $this_table_name_name . " FROM ". $this_table_name  . $sort_find_where); 
if (mysql_num_rows($list)==0)
{
  $list = mysql_query("SET NAMES utf8");
  $list = mysql_query("SELECT " . $this_table_id_name . "," . $this_table_name_name . " FROM ". $this_table_name); 
}

if (!$list)
{
  echo "Query error - header select";
  exit();
}

header ('Content-Type: text/html; charset=utf8');
echo "<header><link rel='stylesheet' type='text/css' href='sturm.css'></header>";

echo "<title>Delivery edit</title>";
echo "\n<body>\n";

//========================================================================================================
echo "\n<form method='get' action='" , $this_page_name , "'>";
echo "\n<table border = 0 cellspacing='0' cellpadding='0'>";
echo "\n<tr><td>Sort / Find:</td><td>"; # Group name 1
echo "\n<input type='text' style='width:400px'  name='_sort_find' value='" . $sort_find . "'  method='get' OnChange='submit();'/></td></tr>";

echo "\n<tr><td>" , $this_table_name , ":</td><td>"; # List
echo "\n<select name='" . $this_table_id_name . "' style='width:400px' method='get' OnChange='submit();'>";# OnChange='submit();'>";

$count=0;
while ($count < mysql_num_rows($list))
{
  echo "\n<option ";
	#echo mysql_result($ver,0,"klienti_group") , " " , mysql_result($kli_grp,$count,"klienti_group_id");
	if (mysql_result($ver,0,$this_table_id_name) == mysql_result($list,$count,$this_table_id_name)) echo "selected ";
  
  echo "value=" . mysql_result($list,$count,$this_table_id_name) . ">" . mysql_result($list,$count,$this_table_name_name) . "</option>";
  $count++;
}
echo "</select></td></tr>";
echo "</table><br><br>";
echo "\n</form>";
//========================================================================================================

echo "\n<form method='post' action='edit_table.php'>";
echo "\n<input type='submit' name='_add' value='",$m_setup['menu add'],"'/>";
echo "\n<input type='submit' name='_save' value='",$m_setup['menu save'],"'/>";
echo "\n<input type='submit' name='_dell' value='",$m_setup['menu dell'],"'/>";

echo "\n<input type='hidden' name='_id_value' value='"  , $iKlient_id  , "'/>";
echo "\n<input type='hidden' name='_id_name' value='" , $this_table_id_name , "'/>";
echo "\n<input type='hidden' name='_table_name' value='" , $this_table_name , "'/>";

echo "\n<input type='hidden' name='_page_to_return' value='" , $this_page_name , "?" , $this_table_id_name, "='/>";

echo "\n<table border = 0 cellspacing='0' cellpadding='0'>";

echo "\n<tr><td>Delivery Name:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='delivery_name' value='" . mysql_result($ver,0,"delivery_name") . "'/></td></tr>";

echo "\n<tr><td>Delivery Phone:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='delivery_tel' value='" . mysql_result($ver,0,"delivery_tel") . "'/></td></tr>";

echo "\n<tr><td>Delivery suf:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='delivery_suf' value='" . mysql_result($ver,0,"delivery_suf") . "'/></td></tr>";

echo "\n<tr><td>Delivery Memo:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='delivery_memo' value='" . mysql_result($ver,0,"delivery_memo") . "'/></td></tr>";

echo "\n</table></form>"; 
echo "\n</body>";

function str_rus_upper($string){
$string = strtr($string,"qwertyuiopasdfghjklzxcvbnm","44");
return $string;
}
?>
