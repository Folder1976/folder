<?php
include 'init.lib.php';
connect_to_mysql();
session_start();
if (!session_verify($_SERVER["PHP_SELF"],"none")){
  exit();
}

//$_SESSION[BASE.'lang'];
//==================================SETUP===========================================
if (!isset($_SESSION[BASE.'lang'])){
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
$iKlientGroup = 0;
$sort_find=0;
$operation_sort=0;
if(isset($_REQUEST['operation_sort']))$operation_sort=$_REQUEST['operation_sort'];
if(isset($_REQUEST['iKlientGroup']))$iKlientGroup=$_REQUEST['iKlientGroup'];
if(isset($_REQUEST['_sort_find']))$sort_find = $_REQUEST['_sort_find'];
header ('Content-Type: text/html; charset=utf8');
echo "<header><link rel='stylesheet' type='text/css' href='sturm.css'></header>";



$klienti = mysql_query("SET NAMES utf8");
$sort_find_where = "";
if ($sort_find != null){
$sort_find_where = " WHERE upper(klienti_name_1) like '%" . mb_strtoupper($sort_find,'UTF-8') . "%' or upper(klienti_email) like '%" . mb_strtoupper($sort_find,'UTF-8') . "%' or `klienti_phone_1` like '%" . $sort_find . "%'";
$iKlientGroup=0;
}
//echo "->",$sort_find_where, "--",$sort_find;

$ver = mysql_query("SET NAMES utf8");
$ver = mysql_query("SELECT `operation_status_id`,`operation_status_name` FROM `tbl_operation_status` ORDER BY `operation_status_name` ASC");
//echo "SELECT `operation_status_id`,`operation_status_name` FROM `tbl_operation_status` ORDER BY `operation_status_name` ASC";
if (!$ver)
{
  echo "Query error";
  exit();
}

$sort = mysql_query("SET NAMES utf8");
$sort = mysql_query("SELECT * FROM `tbl_operation_sort` ORDER BY `operation_sort_1` ASC");
//echo "SELECT `operation_status_id`,`operation_status_name` FROM `tbl_operation_status` ORDER BY `operation_status_name` ASC";
if (!$sort)
{
  echo "Query error";
  exit();
}

echo "<table class='menu_top' widht=100%><tr>
	  <td>";
  
  
echo "<table class='key'><tr>";
echo "<tr><td class='key'><a class='key' href='edit_tovar_find.php?operation_id=0&_find=find-str' target='_blank'>",$m_setup['menu tovar list'],"</a></td>";
echo "<td class='key'><a class='key' href='edit_tovar_table.php' target='_blank'>",$m_setup['menu tovar edit'],"</a></td>
	  </tr><tr>";
echo "<td class='key'><a class='key' href='edit_nakl_add_new.php' target='_blank'>",$m_setup['menu new nakl'],"</a></td>";
echo "\n<td class='key'><a class='key' href='get_bank.php' target='_blank'>",$m_setup['menu bank'],"</a></td>
	  </tr><tr>";
echo "\n<td class='key'><a class='key' href='edit_nakl_print.php?tmp=wareanalytics&operation_id=0' target='_blank'>",$m_setup['menu inventar'],"</a></td>";
echo "\n<td class='key'><a class='key' href='setup.php' target='_blank'>",$m_setup['menu setup'],"</a></td>";
echo "</tr><tr></table>
 
</td>";

# Status operation
echo "<td valign=\"top\"><form method='post' action='operation_list.php' target='operation_list'>";

echo "<input type='hidden' name='iGroupSelected' value='" . $iKlientGroup . "'/>";
echo "<table class='menu_top' cellspacing='0' cellpadding='0'><tr><td width = 50>";
echo "<input type='submit' value='",$m_setup['menu reload'],"'/></td><td width = 200>";
echo $m_setup['menu status'],":<select name='iStatus' style='width:200px' OnChange='submit();'>";
$count=0;
while ($count < mysql_num_rows($ver))
{
  echo "<option value=" . mysql_result($ver,$count,"operation_status_id") . ">" . mysql_result($ver,$count,"operation_status_name") . "</option>";
  $count++;
}
echo "</select>";
echo "</td><td width = 200>";

# Klient 
$tQuery="";
if ($iKlientGroup > 0)
{
  $tQuery = $tQuery . "WHERE `klienti_group` = " . $iKlientGroup . " ";
  #echo $tQuery;
}


$ver = mysql_query("SELECT `klienti_id`,`klienti_name_1` FROM `tbl_klienti` " . $tQuery . $sort_find_where . " ORDER BY `klienti_name_1` ASC");
//echo  $tQuery , $sort_find_where;
if (!$ver)
{
  echo "Query error";
  exit();
}
echo $m_setup['menu klient'],":<select name='iKlient' style='width:200px' method='post' OnChange='submit();'>";
echo "<option value=0>** ВСІ ** </option>";
$count=0;
while ($count < mysql_num_rows($ver))
{
  echo "<option value=" . mysql_result($ver,$count,"klienti_id") . ">" . mysql_result($ver,$count,"klienti_name_1") . "</option>";
  $count++;
}
echo "</select>";


echo "</td></tr>
      <tr><td valign=\"top\">
    
      </td><td>".$m_setup['menu nakl']."<br>
	<input type='text' name='operation_id' style='width:70px' method='post' OnEnter='submit();'>
      </td><td>".$m_setup['menu sort']." ".$m_setup['menu nakl']."<br>";
     
	    echo "<select name='operation_sort' style='width:200px' method='post' OnChange='submit();'>";
	    $count=0;
	    while ($count < mysql_num_rows($sort))
	    {
		echo "<option value='" . mysql_result($sort,$count,"operation_sort_where"),"' "; 
		//if($operation_sort==mysql_result($sort,$count,"operation_sort_where")) echo " selected ";
		echo ">" . mysql_result($sort,$count,"operation_sort_".$_SESSION[BASE.'lang'])."</option>";
		$count++;
	    }
echo "</select>";

echo "</td></tr>
</table></form>";

echo "</td><td valign=\"top\">"; #Global table

# Klient Group

$ver = mysql_query("SELECT `klienti_group_id`,`klienti_group_name` FROM `tbl_klienti_group` ORDER BY `klienti_group_name` ASC");

if (!$ver)
{
  echo "Query error";
  exit();
}
echo "<form method='post' action='top_menu.php'>";# target='operation_list'>";
echo "<table class='menu_top'><tr><td valign=\"top\">";
echo $m_setup['menu klient gr'],":</td><td><select name='iKlientGroup' method='post' OnChange='submit();' style='width:200px' >";
$count=0;
while ($count < mysql_num_rows($ver))
{
  echo "<option ";
	if ($iKlientGroup == mysql_result($ver,$count,"klienti_group_id")) echo "selected ";
  echo "value=" . mysql_result($ver,$count,"klienti_group_id") . ">" . mysql_result($ver,$count,"klienti_group_name") . "</option>";
  $count++;
}
echo "</select>";
echo "\n</td></tr><tr><td>",$m_setup['menu sort'],":</td><td>";
echo "<input type='text' style='width:200px'  name='_sort_find' value='' OnChange='submit();'/>";

#echo "hear";
#echo $tQuery;
#echo $iKlientGroup;
#var_dump($post);

echo "</td></tr></table>";
echo "</form>";
echo "</td><td valign=top>";
echo "<table class='key'><tr>";
echo "<tr><td class='key'><a class='key' href='edit_tovar_find.php?operation_id=0&_find=find-str' target='_blank'>",$m_setup['menu tovar list'],"</a></td>";
echo "\n<td class='key'><a class='key' href='edit_tovar_table.php' target='_blank'>",$m_setup['menu tovar edit'],"</a></td>
	  </tr><tr>";
echo "\n<td class='key'><a class='key' href='edit_nakl_add_new.php' target='_blank'>",$m_setup['menu new nakl'],"</a></td>";
echo "\n<td class='key'><a class='key' href='get_bank.php' target='_blank'>",$m_setup['menu bank'],"</a></td>
	  </tr><tr>";
echo "\n<td class='key'><a class='key' href='edit_nakl_print.php?tmp=wareanalytics&operation_id=0' target='_blank'>",$m_setup['menu inventar'],"</a></td>";
echo "\n<td class='key'><a class='key' href='setup.php' target='_blank'>",$m_setup['menu setup'],"</a></td>";
echo "</tr><tr>";
echo "\n<td></td>";
echo "\n<td></td>";
echo "\n<td></td>";
//echo "\n<td></td>";
echo "</tr></table>";
//echo "<td><a href='edit_tovar_find.php?operation_id=0&_find=find-str' target='_blank'>New nakl</a></td>";
echo "</td></tr></table>"; #Global Table


?>
