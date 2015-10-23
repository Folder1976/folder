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
$this_page_name = "edit_warehouse.php";
$this_table_id_name = "warehouse_id";
$this_table_name_name = "warehouse_name";
$this_table_name = "tbl_warehouse";

//==============MODULE===========================================================
$warehouse_id = "";
if(isset($_POST['_id_value'])) $warehouse_id = $_POST['_id_value'];
$warehouse_name = "";
if(isset($_POST['warehouse_name'])) $warehouse_name = $_POST['warehouse_name'];
$warehouse_user = "";
if(isset($_POST['warehouse_user'])) $warehouse_user = $_POST['warehouse_user'];
$warehouse_shot_name = "";
if(isset($_POST['warehouse_shot_name'])) $warehouse_shot_name = $_POST['warehouse_shot_name'];
$warehouse_sort = "";
if(isset($_POST['warehouse_sort'])) $warehouse_sort = $_POST['warehouse_sort'];
$warehouse_memo = "";
if(isset($_POST['warehouse_memo'])) $warehouse_memo = $_POST['warehouse_memo'];
$warehouse_summ = "0";
if(isset($_POST['warehouse_summ'])) $warehouse_summ = "1";

if(isset($_POST['_add'])){
    $s_sql_string = "INSERT INTO " . $this_table_name . "
		  (warehouse_name, warehouse_user, warehouse_memo, warehouse_shot_name, warehouse_sort, warehouse_summ)
		  VALUES
		  ('$warehouse_name', '$warehouse_user', '$warehouse_memo', '$warehouse_shot_name', '$warehouse_sort', '$warehouse_summ');";
    $folder->query($s_sql_string);
    $iKlient_id = $folder->insert_id();
    echo "<h2>Добавлено!</h2>";
}
if(isset($_POST['_save'])){
    $s_sql_string = "UPDATE " . $this_table_name . "
		  SET warehouse_name = '$warehouse_name',
		  warehouse_user = '$warehouse_user',
		  warehouse_memo = '$warehouse_memo',
		  warehouse_shot_name = '$warehouse_shot_name',
		  warehouse_sort = '$warehouse_sort',
		  warehouse_summ = '$warehouse_summ'
		  WHERE
		  warehouse_id = '$warehouse_id';";
    //echo $s_sql_string;
    $result = $folder->query($s_sql_string) or die("Error in the consult.." . mysqli_error($link));
    $iKlient_id = $warehouse_id;
    echo "<h2>Изменено!</h2>";
}
if(isset($_POST['_dell'])){
    $s_sql_string = "DELETE FROM " . $this_table_name . " WHERE warehouse_id = '$warehouse_id';";
    $folder->query($s_sql_string);
    echo "<h2>Удалено!</h2>";
    //Создадим условие чтоб взять первый попавшийся 
    $iKlient_id = '1';
    $this_table_id_name = "1";
}
//=========================================================================


$sort_find = "";
if(isset($_REQUEST["_sort_find"])) $sort_find=$_REQUEST["_sort_find"];

if(!isset($iKlient_id)) $iKlient_id = "";
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
header ('Content-Type: text/html; charset=utf8');
echo "<header><link rel='stylesheet' type='text/css' href='sturm.css'></header>";

echo "<title>Ред Склады</title>";
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

echo "\n<form method='post' action='edit_warehouse.php'>";
echo "\n<input type='submit' name='_add' value='",$m_setup['menu add'],"'/>";
echo "\n<input type='submit' name='_save' value='",$m_setup['menu save'],"'/>";
echo "\n<input type='submit' name='_dell' value='",$m_setup['menu dell'],"'/>";

echo "\n<input type='hidden' name='_id_value' value='"  , $iKlient_id  , "'/>";
echo "\n<input type='hidden' name='_id_name' value='" , $this_table_id_name , "'/>";
echo "\n<input type='hidden' name='_table_name' value='" , $this_table_name , "'/>";

echo "\n<input type='hidden' name='_page_to_return' value='" , $this_page_name , "?" , $this_table_id_name, "='/>";

echo "\n<table border = 0 cellspacing='0' cellpadding='0'>";

echo "\n<tr><td>",$m_setup['menu name1'],":</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='warehouse_name' value='" . mysql_result($ver,0,"warehouse_name") . "'/></td></tr>";

echo "\n<tr><td>",$m_setup['menu name1']," [...]:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:70px'  name='warehouse_shot_name' value='" . mysql_result($ver,0,"warehouse_shot_name") . "'/></td></tr>";

echo "\n<tr><td>",$m_setup['menu memo'],":</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:600px'  name='warehouse_memo' value='" . mysql_result($ver,0,"warehouse_memo") . "'/></td></tr>";

echo "\n<tr><td>",$m_setup['menu sort'],":</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:40px'  name='warehouse_sort' value='" . mysql_result($ver,0,"warehouse_sort") . "'/></td></tr>";

echo "\n<tr><td>",$m_setup['menu warehouse summ'],":</td><td>"; # Group name 1
echo "\n<input type='checkbox'  name='warehouse_summ' ";
      if(mysql_result($ver,0,"warehouse_summ")==true) echo " checked ";
echo "/></td></tr>";

echo "\n</table></form>";

echo "<br><h3>Полный список:</h3>";
$count=0;
while ($count < mysql_num_rows($list))
{
  echo "<a href='$this_page_name?$this_table_id_name=".mysql_result($list,$count,$this_table_id_name)."'>
  " . mysql_result($list,$count,$this_table_name_name) . "</a><br>";
  $count++;
}

echo "\n</body>";

?>
