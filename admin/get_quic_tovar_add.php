<?php

include 'init.lib.php';
include 'nakl.lib.php';
//session_start();
connect_to_mysql();

require("JsHttpRequest.php");
$JsHttpRequest=new JsHttpRequest("utf8");//"windows-1251");

$operation_id = $_REQUEST['operation_id'];
$tovar_id = $_REQUEST['tovar_id'];
//=================================KURSI VALUT======================
$setup = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	  `currency_id`,`currency_ex`
	  FROM `tbl_currency`
	  ";
$setup = mysql_query($tQuery);
$m_curr = array();
$count=0;
while ($count<mysql_num_rows($setup)){
 $m_curr[mysql_result($setup,$count,0)]=mysql_result($setup,$count,1);
 $count++;
}
//============================================================================

$from_to = mysql_query("SET NAMES utf8");
$tQuery = "SELECT `tbl_price_tovar`.*, `klienti_price`,`klienti_discount`,`operation_status_from_as_new`,
				  `operation_status_to_as_new`
			    FROM `tbl_operation_status`,`tbl_operation`,`tbl_klienti`,`tbl_price_tovar` 
			    WHERE  `operation_status_id`=`operation_status` and 
				    `price_tovar_id`='$tovar_id' and
				    `operation_klient`=`klienti_id` and
				    `operation_id`='" . $operation_id . "'
			    ";
$from_to = mysql_query($tQuery);

			    
echo $tQuery;	

$additem = 1;
$price = mysql_result($from_to,0,"price_tovar_".mysql_result($from_to,0,"klienti_price"));
$discount = mysql_result($from_to,0,"klienti_discount");
$summ = ($price * $additem) / 100 * (100 - $discount);
$summ = number_format($summ,2,".","");
 $zakup = mysql_result($from_to,0,'price_tovar_1') * $m_curr[mysql_result($from_to,0,'price_tovar_curr_1')];
 $zakup = number_format($zakup,0,'.',''); 


$ver = mysql_query("SET NAMES utf8");
$tQuery = "INSERT INTO `tbl_operation_detail` VALUES (
	    '',
	    '$operation_id',
	    '$tovar_id',
	    '$additem',
	    '".$price."',
	    '$zakup',
	    '".$discount."',
	    '".$summ."',
	    '',
	    '".mysql_result($from_to,0,"operation_status_from_as_new")."',
	    '".mysql_result($from_to,0,"operation_status_to_as_new")."',
	    '0')
";
//echo $tQuery;
$ver = mysql_query($tQuery);
$last_index = mysql_insert_id(); // add

$new_result = mysql_query("SET NAMES utf8");
$new_result = mysql_query("SELECT 
			      `operation_detail_item`, 
			      `operation_detail_tovar`,
			      `operation_detail_from`,
			      `operation_detail_to` 
			      FROM `tbl_operation_detail` 
			      WHERE  `operation_detail_id`='$last_index'");
reset_warehouse_on_query_result($new_result);
set_operation_summ($operation_id);
?>
