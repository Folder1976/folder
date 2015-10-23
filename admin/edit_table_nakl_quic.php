<?php

include 'init.lib.php';
include 'nakl.lib.php';
connect_to_mysql();
session_start();


require("JsHttpRequest.php");
$JsHttpRequest=new JsHttpRequest("utf8");//"windows-1251");

$count = 0;
$iKey = "";
if(isset($_REQUEST["key"])) $iKey=$_REQUEST["key"];
$operation_id = $_REQUEST["operation_id"];

if($iKey=="reload"){//======================================= RELOAD
    $tovari = mysql_query("SELECT 
				  `operation_detail_tovar`
			  FROM `tbl_operation_detail` 
			  WHERE `operation_detail_operation`='" . $operation_id . "'
			  GROUP BY `operation_detail_tovar`");

    $summ = mysql_query("SELECT SUM(`operation_detail_summ`) as oper_summ 
		      FROM `tbl_operation_detail` WHERE 
		      `operation_detail_dell`='0' and 
		      `operation_detail_operation`='" . $operation_id . "'");

    $update = mysql_query("SET NAMES utf8");
    $update = mysql_query("UPDATE `tbl_operation` 
			  SET 
			    `operation_summ`='".mysql_result($summ,0,0)."',
			    `operation_save`='0',
			    `operation_sotrudnik`='".$_SESSION[BASE.'userid']."',
			    `operation_data_edit`='".date("Y-m-d G:i:s")."' 
			  WHERE `operation_id`='$operation_id'"); 

    $count = 0;
    while($count<mysql_num_rows($tovari)){
	  reset_warehouse_on_tovar_id(mysql_result($tovari,$count,"operation_detail_tovar"));
      $count++;
    }
}//======================================= RELOAD
 // $sql_str_upd = "UPDATE `tbl_operation` SET `operation_save`='0', `operation_sotrudnik`='".$_SESSION[BASE.'userid']."',`operation_summ`='".mysql_result($ver,0,0)."',`operation_data_edit`='".date("Y-m-d G:i:s")."' WHERE `operation_id`='".$id_operation."'";

echo "SAVED and RELOAD!";
?>
