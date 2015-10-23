<?php
include 'init.lib.php';

connect_to_mysql();



$ver = mysql_query("SET NAMES utf8");
$tQuery = "SELECT * 
	  FROM `tbl_operation_detail`
	  ";
$ver = mysql_query($tQuery);	  

$count=0;
    while ($count < mysql_num_rows($ver))
    {

$ver = mysql_query("SET NAMES utf8");
$tQuery = "SELECT `tbl_operation_detail`.*,
		  `operation_id` 
	  FROM `tbl_operation_detail`,`tbl_operation`
	  WHERE 
	      `operation_detail_dell`='0' and
	      `operation_detail_operation`=`operation_id` and
	      `operation_dell`='0'
	  GROUP BY `operation_detail_id`
	  ";
$ver = mysql_query($tQuery);	  


    $ins = mysql_query("SET NAMES utf8");
    $tQuery = "
	  INSERT INTO `tbl_operation_detail_arhiv`
	  (`operation_detail_id`,
	   `operation_detail_operation`,
	   `operation_detail_tovar`,
	   `operation_detail_item`,
	   `operation_detail_price`,
	   `operation_detail_discount`,
	   `operation_detail_summ`,
	   `operation_detail_memo`,
	   `operation_detail_from`,
	   `operation_detail_to`,
	   `operation_detail_dell`)
	   VALUES
	   ('',
	  '".mysql_result($ver,$count,"operation_detail_operation")."',
	  '".mysql_result($ver,$count,"operation_detail_tovar")."',
	  '".mysql_result($ver,$count,"operation_detail_item")."',
	  '".mysql_result($ver,$count,"operation_detail_price")."',
	  '".mysql_result($ver,$count,"operation_detail_discount")."',
	  '".mysql_result($ver,$count,"operation_detail_summ")."',
	  '".mysql_result($ver,$count,"operation_detail_memo")."',
	  '".mysql_result($ver,$count,"operation_detail_from")."',
	  '".mysql_result($ver,$count,"operation_detail_to")."',
    	  '".mysql_result($ver,$count,"operation_detail_dell")."')
	  ";
    $ins = mysql_query($tQuery);	  
    //if(mysql_result($ver,$count,"operation_detail_operation") == "4342") $count = mysql_num_rows($ver)+1;
    
    $count++;
}

?>