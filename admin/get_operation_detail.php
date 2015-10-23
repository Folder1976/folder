<?php
include 'init.lib.php';
/*session_start();
if (!session_verify($_SERVER["PHP_SELF"])){
  exit();
}*/
connect_to_mysql();

require("JsHttpRequest.php");
$JsHttpRequest=new JsHttpRequest("utf8");

//echo "hh";
//$value = $_REQUEST["price"];
$value2 = $_REQUEST["nakl"];


header ('Content-Type: text/html; charset=utf8');


$det_pos = mysql_query("SET NAMES utf8");
$tQuery = "SELECT `operation_detail_id` FROM `tbl_operation_detail` WHERE `operation_detail_dell`='0' and `operation_detail_operation`='".$value2."'";
$det_pos = mysql_query("SET NAMES utf8");
$det_pos = mysql_query($tQuery);//,`tbl_klienti`, `tbl_operation_status` WHERE `operation_klient`=`klienti_id` and `operation_status`=`operation_status_id` " . $tQuery . " ORDER BY `operation_data` DESC, `operation_id` DESC ");
if (!$det_pos)
{
  $http= "Query error Detail Position";
  exit();
}

$count=0;
$http="";
    while ($count < mysql_num_rows($det_pos))
    {
      $http .= mysql_result($det_pos,$count,'operation_detail_id')."*";
      $count++;
    }
//    $http .= $count."*".$http;
echo $count."*".$http;



?>