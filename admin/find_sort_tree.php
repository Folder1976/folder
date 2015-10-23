<?php
include 'init.lib.php';
include 'nakl.lib.php';
connect_to_mysql();
session_start();
//if (!session_verify($_SERVER["PHP_SELF"])){
//  exit();
//}


require("JsHttpRequest.php");
$JsHttpRequest=new JsHttpRequest("utf8");//"windows-1251");
//echo "hh";

$tbl = $_REQUEST["table"];
$id = $_REQUEST["table_id"];
$name = $_REQUEST["table_name"];
$find = $_REQUEST["find_str"];


header ('Content-Type: text/html; charset=utf8');
//echo "ffff";

$ver = mysql_query("SET NAMES utf8");
//echo strpos($find,"`");
//if(strpos($find,"`")>0){
$tQuery = "SELECT `".$id."`,`".$name."` FROM `".$tbl."` WHERE ".$find."
	   ORDER BY `".$name."` ASC";
/*}else{
$tQuery = "SELECT `".$id."`,`".$name."` FROM `".$tbl."` WHERE upper(".$name.")like'%".mb_strtoupper($find,'UTF-8')."%'
	   ORDER BY `".$name."` ASC";
}*/	   
	   
$ver = mysql_query($tQuery);

if (!$ver)
{
  echo "\nQuery error Status ".$tQuery;
  exit();
}
$http="";
$count=0;
while($count<mysql_num_rows($ver)){

$http .= mysql_result($ver,$count,0)."|".mysql_result($ver,$count,1)."||";

$count++;
}

echo $http;

?>
