<?php
include 'init.lib.php';
connect_to_mysql();
session_start();
if (!session_verify($_SERVER["PHP_SELF"],"none")){
  exit();
}


require("JsHttpRequest.php");
$JsHttpRequest=new JsHttpRequest("utf8");//"windows-1251");


$value = $_REQUEST["stat"];
$value2 = $_REQUEST["nakl"];
$value3 = $_REQUEST["edit"];

header ('Content-Type: text/html; charset=utf8');


$update = mysql_query("SET NAMES utf8");
$tQuery = "UPDATE `tbl_operation` SET `".$value3."`='".$value."',`operation_data_edit`='".date("Y-m-d G:i:s")."' WHERE `operation_id`='".$value2."'";
$update = mysql_query($tQuery);

echo "change - ",$value3;


?>
