<?php

include 'init.lib.php';
connect_to_mysql();
echo "<b>Nuzno napisat svaz s tablicej tbl_price_tovar
      <br> NELZA UDALAT I DOBAVLAT!!!</b>";

      session_start();
if (!session_verify($_SERVER["PHP_SELF"])){
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
$this_page_name = "edit_price.php";
$this_table_id_name = "price_id";
$this_table_name_name = "price_name";

$this_table_name = "tbl_price";
$sort_find = $_GET["_sort_find"];
$iKlient_id = $_GET[$this_table_id_name];
$iKlient_count = 0;

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
//===========================================================================================================
header ('Content-Type: text/html; charset=utf8');
echo "<title>Price name edit</title>";
echo "<header><link rel='stylesheet' type='text/css' href='sturm.css'></header>";

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

echo "\n<tr><td>Price Name:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='price_name' value='" . mysql_result($ver,0,"price_name") . "'/></td></tr>";

echo "\n</table></form>"; 
echo "\n</body>";

?>
