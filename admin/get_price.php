<?php
include 'init.lib.php';

connect_to_mysql();
session_start();
/*if (!session_verify($_SERVER["PHP_SELF"],"none")){
  exit();
}*/


require("JsHttpRequest.php");
$JsHttpRequest=new JsHttpRequest("utf8");
//echo "222*3333*4";

//echo "hh";
$value = $_REQUEST["price"];
$value2 = $_REQUEST["tovar"];
//echo $value," ",$value2;


header ('Content-Type: text/html; charset=utf8');


$tQuery = "SELECT `price_tovar_".$value."`,`price_tovar_curr_".$value."`,`price_tovar_1`,`price_tovar_curr_1` FROM `tbl_price_tovar` WHERE `price_tovar_id`='".$value2."'";
$price = mysql_query("SET NAMES utf8");
$price = mysql_query($tQuery);//,`tbl_klienti`, `tbl_operation_status` WHERE `operation_klient`=`klienti_id` and `operation_status`=`operation_status_id` " . $tQuery . " ORDER BY `operation_data` DESC, `operation_id` DESC ");
if (!$price)
{
  $http= "Query error PriceName";
  exit();
  
}
//$curr = mysql_query("SET NAMES utf8");
$tQuery = "SELECT `currency_name_shot`,`currency_ex` FROM `tbl_currency` WHERE `currency_id`='".mysql_result($price,0,'price_tovar_curr_'.$value)."'";
$curr = mysql_query("SET NAMES utf8");
$curr = mysql_query($tQuery);//,`tbl_klienti`, `tbl_operation_status` WHERE `operation_klient`=`klienti_id` and `operation_status`=`operation_status_id` " . $tQuery . " ORDER BY `operation_data` DESC, `operation_id` DESC ");
if (!$curr)
{
  $http= "Query error Currency";
  exit();
 } 

$tQuery = "SELECT `currency_ex` FROM `tbl_currency` WHERE `currency_id`='".mysql_result($price,0,'price_tovar_curr_1')."'";
$curr_in = mysql_query("SET NAMES utf8");
$curr_in = mysql_query($tQuery);//,`tbl_klienti`, `tbl_operation_status` WHERE `operation_klient`=`klienti_id` and `operation_status`=`operation_status_id` " . $tQuery . " ORDER BY `operation_data` DESC, `operation_id` DESC ");
if (!$curr_in)
{
  $http= "Query error Currency";
  exit();
 } 
 
$http = mysql_result($price,0,'price_tovar_'.$value)."*".mysql_result($curr,0,'currency_name_shot')."*".mysql_result($curr,0,'currency_ex')."*".mysql_result($price,0,'price_tovar_1') * mysql_result($curr_in,0,'currency_ex');
echo $http;

//echo "4444";

?>
