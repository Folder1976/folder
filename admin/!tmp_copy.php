<?php
include 'init.lib.php';

connect_to_mysql();



$ver = mysql_query("SET NAMES utf8");
$tQuery = " INSERT INTO `tbl_operation_detail_new` 
	      SELECT `tbl_operation_detail`.*
	      FROM `tbl_operation_detail`,`tbl_operation`
	      WHERE 
	      `operation_detail_dell`='0' and
	      `operation_detail_operation`=`operation_id` and
	      `operation_dell`='0'
	      GROUP BY `operation_detail_id`

	  ";
//$ver = mysql_query($tQuery);	  
echo "<br><br>",$tQuery;


$tQuery = " INSERT INTO `tbl_operation_detail_new` 
	      SELECT *
	      FROM `tbl_operation_detail`
	      WHERE 
	      `operation_detail_dell`='0' and
	      `operation_detail_operation`<'10'
	      GROUP BY `operation_detail_id`

	  ";
//$ver = mysql_query($tQuery);	  
echo "<br><br>",$tQuery;

$tQuery = " INSERT INTO `tbl_operation_detail` 
	      SELECT *
	      FROM `tbl_operation_detail_new`
	  ";
//$ver = mysql_query($tQuery);	  
echo "<br><br>",$tQuery;
?>