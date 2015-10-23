<?php
//include 'admin/init.lib.php';
include 'admin/nakl.lib.php';
//include 'init.lib.user.tovar.php';

//session_start();

//connect_to_mysql();

//require("JsHttpRequest.php");
//$JsHttpRequest=new JsHttpRequest("utf8");//"windows-1251");

function set_status($id,$key){
$user_order=$id;
$http="";
$value2 = $id;

  if($key==1){
      $value=2;
  }else {
      $value=15;
  }


if ($value>=0){ //if not dell
$Fields ="";
$ver = mysql_query("SET NAMES utf8");
$tQuery = "SELECT `operation_status_from_as_new`,`operation_status_to_as_new`,`operation_status_debet` FROM `tbl_operation_status` WHERE `operation_status_id`='". $value . "'";
$ver = mysql_query($tQuery);//,`tbl_klienti`, `tbl_operation_status` WHERE `operation_klient`=`klienti_id` and `operation_status`=`operation_status_id` " . $tQuery . " ORDER BY `operation_data` DESC, `operation_id` DESC ");


if (mysql_result($ver,0,'operation_status_from_as_new')!=0){
$Fields .= "`operation_detail_from`='".mysql_result($ver,0,'operation_status_from_as_new')."',";
}
if (mysql_result($ver,0,'operation_status_to_as_new')!=0){
$Fields .= "`operation_detail_to`='".mysql_result($ver,0,'operation_status_to_as_new')."',";
}
$Fields = substr($Fields, 0, strlen($Fields)-1);


$update_old = mysql_query("SET NAMES utf8");
$tQuery = "SELECT `operation_detail_tovar`,`operation_detail_from`,`operation_detail_to` FROM `tbl_operation_detail` WHERE `operation_detail_operation`='".$value2."' and `operation_detail_dell`='0'";
$update_old = mysql_query($tQuery);

$update = mysql_query("SET NAMES utf8");
$tQuery = "UPDATE `tbl_operation_detail` SET ".$Fields." WHERE `operation_detail_operation`='".$value2."' and `operation_detail_dell`='0'";
$update = mysql_query($tQuery);
$http .= "warehouse reset! ";
reset_warehouse_on_operation($value2,0);
//echo mysql_result($update_old,0,'operation_detail_tovar');
reset_warehouse_on_query_result($update_old);


$select = mysql_query("SET NAMES utf8");
$tQuery = "SELECT SUM(`operation_detail_summ`) AS summ_all FROM `tbl_operation_detail` WHERE `operation_detail_operation`='".$value2."' and `operation_detail_dell`='0'";
$select = mysql_query($tQuery);

$summ_all = mysql_result($select,0,'summ_all');

if (mysql_result($ver,0,'operation_status_debet') == '1') $summ_all=$summ_all-$summ_all*2;
if ($summ_all != 0){
$update = mysql_query("SET NAMES utf8");
$tQuery = "UPDATE `tbl_operation` SET `operation_status`='".$value."', `operation_data_edit`='".date("Y-m-d G:i:s")."', `operation_summ`='".$summ_all."' WHERE `operation_id`='".$value2."'";
$update = mysql_query($tQuery);
}

}else{//if is delloperation ======================================================================
/*$update = mysql_query("SET NAMES utf8");
$update = mysql_query("UPDATE `tbl_operation_detail` SET `operation_detail_dell`='2' WHERE `operation_detail_operation`='".$value2."'");
 reset_warehouse_on_operation($value2,2);
$update = mysql_query("UPDATE `tbl_operation_detail` SET `operation_detail_dell`='1' WHERE `operation_detail_operation`='".$value2."'");



$update = mysql_query("SET NAMES utf8");
$tQuery = "UPDATE `tbl_operation` SET `operation_dell`='1' WHERE `operation_id`='".$value2."'";
$update = mysql_query($tQuery);
$value=0;
*/
}//end if dell or update
}
?>