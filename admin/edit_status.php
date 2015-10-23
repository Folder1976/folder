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
$this_page_name = "edit_status.php";
$this_table_id_name = "operation_status_id";
$this_table_name_name = "operation_status_name";

$this_table_name = "tbl_operation_status";

$sort_find = "";
if(isset($_REQUEST["_sort_find"])) $sort_find=$_REQUEST["_sort_find"];

$iKlient_id = "";
if(isset($_REQUEST[$this_table_id_name])) $iKlient_id=$_REQUEST[$this_table_id_name];

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
$list = mysql_query("SET NAMES utf8");
$str = "SELECT `$this_table_id_name`,`$this_table_name_name` FROM `$this_table_name`
	     WHERE upper(`$this_table_name_name`) LIKE '%".mb_strtoupper(addslashes($sort_find),'UTF-8')."%' or
	    `$this_table_id_name`='$iKlient_id'";
$list = mysql_query($str); 
//echo $str;
		    
if (mysql_num_rows($list)==0)
{
  $list = mysql_query("SET NAMES utf8");
  $list = mysql_query("SELECT " . $this_table_id_name . "," . $this_table_name_name . " FROM ". $this_table_name); 
}
//===========================================================================================================
$ware = mysql_query("SET NAMES utf8");
$ware = mysql_query("SELECT * FROM tbl_warehouse");# WHERE klienti_id = " . $iKlient_id);
if (!$ware)
{
  echo "Query error - tbl_warehouse";
  exit();
}

//===========================================================================================================
header ('Content-Type: text/html; charset=utf8');
echo "<header><link rel='stylesheet' type='text/css' href='sturm.css'></header>";

echo "<title>Operation status edit</title>";
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

echo "\n<tr><td>",$m_setup['menu name1'],":</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='operation_status_name' value='" . mysql_result($ver,0,"operation_status_name") . "'/></td></tr>";

echo "\n<tr><td>",$m_setup['menu memo'],":</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='operation_status_memo' value='" . mysql_result($ver,0,"operation_status_memo") . "'/></td></tr>";

echo "\n<tr><td colspan=2><b>-</b></td></tr>"; # Group name 1
echo "\n<tr><td colspan=2><b>",$m_setup['menu if new order'],":</b></td></tr>"; # Group name 1
//=====================================================================================================================
echo "\n<tr><td>",$m_setup['menu from'],":</td><td>"; # Group klienti
echo "\n<select name='operation_status_from_as_new' style='width:400px'>";# OnChange='submit();'>";
$count=-1;//$m_setup['menu dont ch']
while ($count < mysql_num_rows($ware))
{
  if($count==-1){
      $id=0;
      $name=$m_setup['menu dont change'];
   }else{
      $id=mysql_result($ware,$count,"warehouse_id");
      $name = mysql_result($ware,$count,"warehouse_name");
   }
  echo "\n<option ";
	if (mysql_result($ver,0,"operation_status_from_as_new") == $id) echo "selected ";
  
  echo "value=" . $id. ">" . $name . "</option>";
  $count++;
}
echo "</select></td>";
echo "</tr>";
//======================================================================================================================
//=====================================================================================================================
echo "\n<tr><td>",$m_setup['menu to'],":</td><td>"; # Group klienti
echo "\n<select name='operation_status_to_as_new' style='width:400px'>";# OnChange='submit();'>";
$count=-1;//$m_setup['menu dont ch']
while ($count < mysql_num_rows($ware))
{
  if($count==-1){
      $id=0;
      $name=$m_setup['menu dont change'];
   }else{
      $id=mysql_result($ware,$count,"warehouse_id");
      $name = mysql_result($ware,$count,"warehouse_name");
   }
  echo "\n<option ";
	if (mysql_result($ver,0,"operation_status_to_as_new") == $id) echo "selected ";
  
  echo "value=" . $id. ">" . $name . "</option>";
  $count++;
}
echo "</select></td>";
echo "</tr>";
//======================================================================================================================
//======================================================================================================================
//=====================================================================================================================
echo "\n<tr><td colspan=2><b>-</b></td></tr>"; # Group name 1
echo "\n<tr><td colspan=2><b>",$m_setup['menu if status chang'],":</b></td></tr>"; # Group name 1
//=====================================================================================================================
echo "\n<tr><td>",$m_setup['menu from'],":</td><td>"; # Group klienti
echo "\n<select name='operation_status_from' style='width:400px'>";# OnChange='submit();'>";
$count=-1;//$m_setup['menu dont ch']
while ($count < mysql_num_rows($ware))
{
  if($count==-1){
      $id=0;
      $name=$m_setup['menu dont change'];
   }else{
      $id=mysql_result($ware,$count,"warehouse_id");
      $name = mysql_result($ware,$count,"warehouse_name");
   }
  echo "\n<option ";
	if (mysql_result($ver,0,"operation_status_from") == $id) echo "selected ";
  
  echo "value=" . $id. ">" . $name . "</option>";
  $count++;
}
echo "</select></td>";
echo "</tr>";
//======================================================================================================================
//=====================================================================================================================
echo "\n<tr><td>",$m_setup['menu to'],":</td><td>"; # Group klienti
echo "\n<select name='operation_status_to' style='width:400px'>";# OnChange='submit();'>";
$count=-1;//$m_setup['menu dont ch']
while ($count < mysql_num_rows($ware))
{
  if($count==-1){
      $id=0;
      $name=$m_setup['menu dont change'];
   }else{
      $id=mysql_result($ware,$count,"warehouse_id");
      $name = mysql_result($ware,$count,"warehouse_name");
   }
  echo "\n<option ";
	if (mysql_result($ver,0,"operation_status_to") == $id) echo "selected ";
  
  echo "value=" . $id. ">" . $name . "</option>";
  $count++;
}
echo "</select></td>";
echo "</tr>";
//======================================================================================================================
echo "\n<tr><td>",$m_setup['menu to debet'],":</td><td>"; # Group klienti
echo "\n<input type='checkbox' name='operation_status_debet' value='1' ";
    if (mysql_result($ver,0,"operation_status_debet") == 1) echo "checked ";
    echo "></td></tr>";# OnChange='submit();'>";
//======================================================================================================================
echo "\n<tr><td>",$m_setup['menu to level'],":</td><td>"; # Group klienti
echo "\n<input type='checkbox' name='operation_status_level' value='1' ";
    if (mysql_result($ver,0,"operation_status_level") == 1) echo "checked ";
    echo "></td></tr>";# OnChange='submit();'>";



echo "\n</table></form>"; 
echo "\n</body>";

?>
