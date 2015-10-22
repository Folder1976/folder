<?php
include 'init.lib.php';
connect_to_mysql();

require("JsHttpRequest.php");
$JsHttpRequest=new JsHttpRequest("utf8");//"windows-1251");
//$_REQUEST["parent"];

//echo "ffffffffffffffffffffffff";
//exit();

session_start();
if ($_SESSION[BASE.'lang'] <1){
  $_SESSION[BASE.'lang']=1;
}

$ver = mysql_query("SET NAMES utf8");
$ver = mysql_query("SELECT * FROM `tbl_parent_inet` WHERE `parent_inet_type`='1' and `parent_inet_parent`='".$_REQUEST["id"]."' ORDER BY `parent_inet_sort` ASC");
if (!$ver)
{
  echo "Query error - LANG";
  exit();
}

echo "<table";
if($_REQUEST["id"] == 0) echo "class='menu_top'";
echo ">";

$count=0;
while ($count < mysql_num_rows($ver))
{
  echo "<tr><td><a href='#none' onclick='catalog_view(",mysql_result($ver,$count,"parent_inet_id"),");'>
  <div style='float:left;' id='parent_open*",mysql_result($ver,$count,"parent_inet_id"),"'>[+]&nbsp</div></a>
  <a href='index.php?parent=",mysql_result($ver,$count,"parent_inet_id"),"'>", mysql_result($ver,$count,"parent_inet_".$_SESSION[BASE.'lang']), "</a>
  ";
  
  echo "
  
    <div id='parent*",mysql_result($ver,$count,"parent_inet_id"),"'></div>
  </td></tr>";
  $count++;
}
echo "</table>";
//echo $http;



?>
