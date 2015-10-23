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

if(!isset($_SESSION[BASE.'shop_operation_id'])){
    echo "Not Operation";
    exit();
}

$Fields = "
     `operation_detail_id`, 
     `operation_detail_discount`,
     `operation_detail_item`,
     `operation_detail_memo`,
     `operation_detail_price`,
     `operation_detail_summ`,
     `tovar_id`,
     `tovar_artkl`,
     `tovar_name_1` as tovar_name
    "; 
$ver = mysql_query("SET NAMES utf8");
$tQuery = "SELECT " . $Fields . " 
	  FROM `tbl_operation_detail`,`tbl_tovar`
	  WHERE 
	  `operation_detail_dell`='0' and 
	  `operation_detail_tovar`=`tovar_id` and 
	  `operation_detail_operation`='" . $_SESSION[BASE.'shop_operation_id'] . "' and
	  `operation_detail_memo`like'%***'
	  ORDER BY `operation_detail_id` DESC";
$ver = mysql_query($tQuery);
//echo $tQuery;
$http = "<table class=\"tovar_list\" width=\"100%\">
	 <tr><td colspan=\"6\" align=\"right\"><h1> >> &header грн</h1></td></tr>";
$count=0;
$summ = 0;
while($count<mysql_num_rows($ver)){
	  $http .= "<tr>";
	  $http .= "<td width=\"10%\">".mysql_result($ver,$count,'tovar_artkl')."</td>";
	  $http .= "<td width=\"40%\">".mysql_result($ver,$count,'tovar_name')."</td>";
	  $http .= "<td width=\"5%\">".mysql_result($ver,$count,'operation_detail_price')."</td>";
	  $http .= "<td width=\"100px\">".mysql_result($ver,$count,'operation_detail_item')."</td>";
	  $http .= "<td width=\"80px\"> <input type='text' class='item_rabat' 
			    value='".mysql_result($ver,$count,'operation_detail_discount')."' 
			    id='rabat*".mysql_result($ver,$count,'operation_detail_id')."'
			    onChange='set_rabat(".(int)mysql_result($ver,$count,'operation_detail_id').");'/><b>%</b></td>";
	  $http .= "<td>".mysql_result($ver,$count,'operation_detail_summ')."</td>";
	  if($count==0){
	      $http .= "<td rowspan=\"&rows\" align=\"center\" valign=\"top\" width=\"25%\">&money_key</td>";
	      
	      }
	  $http .= "</tr>";
	  $summ += mysql_result($ver,$count,'operation_detail_summ');


$count++;
}
$http .= "</table>
	  <input type=\"hidden\" id=\"item_summ\" value=\"$summ\"/>";

$money_key="";	  
if($summ != ""){
    $money_key = "<input type=\"button\" class=\"money_key\" 
			value=\"О П Л А Т А\" 
			id=\"money_key\" 
			OnClick=\"money_key();\"/>";
	  }
	  
$http = str_replace("&header",$summ,$http);
$http = str_replace("&money_key",$money_key,$http);
$http = str_replace("&rows",$count,$http);

echo $http;

?>
