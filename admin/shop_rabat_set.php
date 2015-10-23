<?php

include 'init.lib.php';
//include 'nakl.lib.php';
//include '../init.lib.user.tovar.php';
connect_to_mysql();
session_start();
if (!session_verify($_SERVER["PHP_SELF"],"none")){
  exit();
}

require("JsHttpRequest.php");
$JsHttpRequest=new JsHttpRequest("utf8");//"windows-1251");
//echo "ff";
$get = mysql_query("SET NAMES utf8");
$tQuery = "SELECT `operation_detail_item`,
		  `operation_detail_operation`,
		  `operation_detail_price`
	  FROM `tbl_operation_detail`
	  WHERE 
	  `operation_detail_id`='".(int)$_REQUEST['id']."'
	  ";
$get = mysql_query($tQuery);

//echo $tQuery;

$operation = mysql_result($get,0,"operation_detail_operation");
$summ = mysql_result($get,0,"operation_detail_item") * mysql_result($get,0,"operation_detail_price");
$summ = $summ / 100 * (100 - (int)$_REQUEST['rabat']);
$summ = number_format($summ,2,'.','');

$set = mysql_query("SET NAMES utf8");
$tQuery = "UPDATE `tbl_operation_detail`
	   SET   
		  `operation_detail_discount` = '".(int)$_REQUEST['rabat']."',
		  `operation_detail_summ`='$summ'
	  WHERE 
	  `operation_detail_id`='".(int)$_REQUEST['id']."'
	  ";
$set = mysql_query($tQuery);

$get = mysql_query("SET NAMES utf8");
$tQuery = "SELECT SUM(operation_detail_summ) as summ
	  FROM `tbl_operation_detail`
	  WHERE 
	  `operation_detail_operation`='$operation' and
	  `operation_detail_dell`='0'
	  ";
$get = mysql_query($tQuery);

$set = mysql_query("SET NAMES utf8");
$tQuery = "UPDATE `tbl_operation`
	   SET   
		  `operation_summ`='".mysql_result($get,0,"summ")."'
	  WHERE 
	  `operation_id`='$operation'
	  ";
$set = mysql_query($tQuery);


?>
