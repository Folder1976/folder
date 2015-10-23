<?php
include 'init.lib.php';
/*session_start();
    $_SESSION[BASE.'lang']
connect_to_mysql();*/

require("admin/JsHttpRequest.php");
$JsHttpRequest=new JsHttpRequest("utf8");//"windows-1251");

echo "1|hfgsdf||g|dfgsdfgh||";
//$value = $_REQUEST["price"];
//$value2 = $_REQUEST["nakl"];

/*
header ('Content-Type: text/html; charset=utf8');

$Fields = "
    `operation_detail_id`,
    `operation_detail_tovar`,
     `operation_detail_discount`,
     `operation_detail_from`,
     `operation_detail_to`,
     `operation_detail_item`,
     `operation_detail_memo`,
     `operation_detail_price`,
     `operation_detail_summ`,
     `tovar_id`,
     `tovar_artkl`,
     `tovar_name_1`,
     `tbl_warehouse_unit`.*"; //Tovar
$ver = mysql_query("SET NAMES utf8");
$tQuery = "SELECT " . $Fields . " 
	  FROM `tbl_operation_detail`,`tbl_tovar`,`tbl_warehouse_unit` 
	  WHERE 
	  `warehouse_unit_tovar_id`=`tovar_id` 
	  and `operation_detail_dell`='0' 
	  and `operation_detail_tovar`=`tovar_id` 
	  and `operation_detail_operation`='" . $value2 . "' 
	  GROUP BY `operation_detail_id` 
	  ORDER BY `tovar_name_1` ASC";
$ver = mysql_query($tQuery);
if (!$ver)
{
  echo "\nQuery error List";
  exit();
}
$ware = mysql_query("SET NAMES utf8");
$tQuery = "SELECT `warehouse_id`,`warehouse_name` FROM `tbl_warehouse`";
$ware = mysql_query($tQuery);

$deliv = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	  `delivery_id`,
	  `delivery_name`,
	  `klienti_id`
	  FROM `tbl_delivery`,`tbl_klienti`,`tbl_operation`
	    WHERE `delivery_id`=`klienti_delivery_id`
	    and `klienti_id`=`operation_klient`
	    and `operation_id`='".$value2."'";
$deliv = mysql_query($tQuery);

$count=0;
$http = "<a href='edit_klient.php?klienti_id=".mysql_result($deliv,0,"klienti_id")."' target=_blank>change </a><b>".mysql_result($deliv,0,"delivery_name")."</b>";
$http .="<table><tr><th>art</th><th>name</th><th>price</th><th>item</th><th>disc</th><th>summ</th><th>move</th><th>memo</th></tr>";
    while ($count < mysql_num_rows($ver))
    {
      $http .= "<tr>";
      $http .= "<td><a href='edit_tovar.php?tovar_id=".mysql_result($ver,$count,'operation_detail_tovar')." ' target='_blank'>";
      $http .= mysql_result($ver,$count,'tovar_artkl')."</a></td>";
      $http .= "<td>".mysql_result($ver,$count,'tovar_name_1')."</td>";
      $http .= "<td>".mysql_result($ver,$count,'operation_detail_price')."</td>";
      $http .= "<td>".mysql_result($ver,$count,'operation_detail_item')."</td>";
      $http .= "<td>".mysql_result($ver,$count,'operation_detail_discount')."</td>";
      $http .= "<td>".mysql_result($ver,$count,'operation_detail_summ')."</td>";
      
      //Warehouse Name Set
      $count1=0;
       while ($count1 < mysql_num_rows($ware) and mysql_result($ver,$count,'operation_detail_from')<>mysql_result($ware,$count1,'warehouse_id')){
       $count1++;
       }
      $http .= "<td>".mysql_result($ware,$count1,'warehouse_name')."";
      $http .= " [".mysql_result($ver,$count,"warehouse_unit_".(string)($count1+1))."]";
     // $http .= " ["."warehouse_unit_".(string)$count1."]";
      
            $count1=0;
       while ($count1 < mysql_num_rows($ware) and mysql_result($ver,$count,'operation_detail_to')<>mysql_result($ware,$count1,'warehouse_id')){
       $count1++;
       }
      $http .= "-->".mysql_result($ware,$count1,'warehouse_name');
      $http .= " [".mysql_result($ver,$count,"warehouse_unit_".(string)($count1+1))."]"."</td>";
      //=====================
      //$http .= " = ".mysql_result($ver,$count,'operation_detail_from').mysql_result($ver,$count,'operation_detail_to')."</td>";
      $http .= "<td>".mysql_result($ver,$count,'operation_detail_memo')."</td>";
       $http .= "</tr>";
      $count++;
    }
$http .="</table>";
//    $http .= $count."*".$http;
echo $http;



?>
