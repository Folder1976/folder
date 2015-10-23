<?php
include 'init.lib.php';
session_start();
connect_to_mysql();

if(!isset($_GET['city_name'])){
}elseif(empty($_GET['city_name'])) die('пусто');
else{
$find_str=$_GET['city_name'];
  $dell = array("<",
		">",
		"img",
		"src",
		"script",
		"php",
		"\"",
		"'",
		"href"
		);
  $find_str = str_replace($dell,"",$find_str);

$ver = mysql_query("SET NAMES utf8");
$tQuery = "SELECT `find_user`,`find_str` FROM `tbl_find` WHERE upper(find_user)like'%".mb_strtoupper($find_str,'UTF-8')."%'
	   ORDER BY `find_status` DESC LIMIT 0,20";
$ver = mysql_query($tQuery);
    //echo "<option value='11'>",$tQuery,"</option>\n";
//echo $tQuery;
  $count=0;
  while($count<mysql_num_rows($ver))
  {
    $find_str = mysql_result($ver,$count,"find_str");
    $find_user = mysql_result($ver,$count,"find_user");
  
    echo "<option value='",$find_str,"'>",$find_user,"</option>\n";
    $count++;
  }       
   
}

echo "***",$count;

?>