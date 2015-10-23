<?php
include 'init.lib.php';
include 'nakl.lib.php';
include 'init.lib.user.tovar.php';

connect_to_mysql();
session_start();



require("JsHttpRequest.php");
$JsHttpRequest=new JsHttpRequest("utf8");//"windows-1251");

$user_order=0;
$http="";
//echo $_REQUEST["stat"];
if(isset($_REQUEST["stat"])){
    if (!session_verify($_SERVER["PHP_SELF"]." status:".$_REQUEST["stat"]." nakl:".$_REQUEST["nakl"],"+")){
	exit();
    }
$value = $_REQUEST["stat"];
$value2 = $_REQUEST["nakl"];
}else{
  if($_REQUEST["key"]==1){
      $value=2;
  }else {
      $value=15;
  }

  $value2=$_SESSION[BASE.'userorder'];
  $user_order=$_SESSION[BASE.'userorder'];
  $_SESSION[BASE.'userorder']=null;
  $_SESSION[BASE.'userordersumm']=null;
}


//echo $value,"=",$value2;


header ('Content-Type: text/html; charset=utf8');


if ($value>=0){ //if not dell
$Fields ="";
$ver = mysql_query("SET NAMES utf8");
$tQuery = "SELECT `operation_status_from`,`operation_status_to`,`operation_status_debet` FROM `tbl_operation_status` WHERE `operation_status_id`='". $value . "'";
$ver = mysql_query($tQuery);//,`tbl_klienti`, `tbl_operation_status` WHERE `operation_klient`=`klienti_id` and `operation_status`=`operation_status_id` " . $tQuery . " ORDER BY `operation_data` DESC, `operation_id` DESC ");

if (!$ver)
{
  echo "\nQuery error Status ".$tQuery;
  exit();
}


if (mysql_result($ver,0,'operation_status_from')!=0){
$Fields .= "`operation_detail_from`='".mysql_result($ver,0,'operation_status_from')."',";
}
if (mysql_result($ver,0,'operation_status_to')!=0){
$Fields .= "`operation_detail_to`='".mysql_result($ver,0,'operation_status_to')."',";
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


$http .= "operation reset! ";
echo "set new status.";

/*if($user_order>0) {
echo "send mail";
verify_order_and_send_email($user_order);}*/

}else{//if is delloperation ======================================================================
$update = mysql_query("SET NAMES utf8");
$update = mysql_query("UPDATE `tbl_operation_detail` SET `operation_detail_dell`='2' WHERE `operation_detail_operation`='".$value2."'");
 reset_warehouse_on_operation($value2,2);
$update = mysql_query("UPDATE `tbl_operation_detail` SET `operation_detail_dell`='1' WHERE `operation_detail_operation`='".$value2."'");



$update = mysql_query("SET NAMES utf8");
$tQuery = "UPDATE `tbl_operation` SET `operation_dell`='1' WHERE `operation_id`='".$value2."'";
$update = mysql_query($tQuery);

echo "delleted";//.$http;
$value=0;
//$tmp2 = file_get_contents("http://sturm.com.ua/set_status_inet.php?pass=KLJGbsfgv8y9JKbhlis&orders_id=".$orders_tmp."&status=15");

}//end if dell or update

//Set new status to web
//$ver = mysql_query("SET NAMES utf8");
//$tQuery = "SELECT `operation_inet_id` FROM `tbl_operation` WHERE `operation_id`='". $value2 . "'";
//$ver = mysql_query($tQuery);
//if (mysql_result($ver,0,0)>0){
//  $tmp2 = file_get_contents("http://sturm.com.ua/set_status_inet.php?pass=KLJGbsfgv8y9JKbhlis&orders_id=".mysql_result($ver,0,0)."&status=".$value);
//echo "<br>Set new status to Web.";
//}


?>
