<?php
include 'init.lib.php';

connect_to_mysql();
session_start();
if (!session_verify($_SERVER["PHP_SELF"],"+")){
  exit();
}
if(strpos($_SESSION[BASE.'usersetup'],'TOVAR_HIST_VIEW')>0){




require("JsHttpRequest.php");
$JsHttpRequest=new JsHttpRequest("utf8");

$value2 = $_REQUEST["tovar_id"];


header ('Content-Type: text/html; charset=utf8');
echo "<header><link rel='stylesheet' type='text/css' href='sturm.css'></header>";


$Fields = "
    `operation_detail_id`,
    `operation_detail_operation`,
    `operation_detail_tovar`,
     `operation_detail_discount`,
     `operation_detail_from`,
     `operation_detail_to`,
     `operation_detail_item`,
     `operation_detail_memo`,
     `operation_detail_price`,
     `operation_detail_summ`,
     `operation_status_id`,     
     `operation_status_name`,
     `operation_detail_dell`,
     `operation_data`,
     `klienti_name_1`,
     `klienti_id`,
     `klienti_group`,
     `tovar_id`,
     `tovar_artkl`,
     `tovar_name_1`,
     `tbl_warehouse_unit`.*"; //Tovar
$ver = mysql_query("SET NAMES utf8");

$dell = " and `operation_detail_dell`='0' ";
if(isset($_REQUEST["view"])){
 if($_REQUEST["view"]="full") $dell = "";
}

$tQuery = "SELECT " . $Fields . " 
	  FROM `tbl_operation_detail`,`tbl_tovar`,`tbl_warehouse_unit`,`tbl_operation`,`tbl_klienti`,`tbl_operation_status` 
	  WHERE 
	  `warehouse_unit_tovar_id`=`tovar_id` 
	  and `operation_detail_tovar`=`tovar_id` 
	  $dell
	  and `tovar_id`='" . $value2 . "' 
	  and `operation_detail_operation`=`operation_id`
	  and `operation_status`=`operation_status_id`
	  and `klienti_id`=`operation_klient`
	  GROUP BY `operation_detail_id` 
	  ORDER BY `operation_detail_operation` ASC
	  ";
	 // echo $tQuery;
$ver = mysql_query($tQuery);
if (!$ver)
{
  echo "\nQuery error List<br>";
  echo $tQuery;
  exit();
}
$ware = mysql_query("SET NAMES utf8");
$tQuery = "SELECT `warehouse_id`,`warehouse_name` FROM `tbl_warehouse`";
$ware = mysql_query($tQuery);

echo "<title>",mysql_result($ver,0,'tovar_artkl')," ",mysql_result($ver,0,'tovar_name_1'),"</title>";
$count=0;
$http = "
	<a href='edit_tovar_history.php?tovar_id=$value2&view=full'>[Full history]</a><br>
	"; 
$http .="<table><tr><th>data</th><th>nakl</th><th>klient</th><th>status</th><th>art</th><th>name</th><th>price</th><th>item</th><th>disc</th><th>summ</th><th>move</th><th>memo</th></tr>";
    while ($count < mysql_num_rows($ver))
    {
      $http .= "<tr>";
      $http .= "<td>". date('Y-m-d',strtotime(mysql_result($ver,$count,'operation_data')))."</td>";
      $http .= "<td><a href='edit_nakl.php?operation_id=". mysql_result($ver,$count,'operation_detail_operation')."' target='_blank'>".mysql_result($ver,$count,'operation_detail_operation')."</a></td>";
      $http .= "<td>
      <a href='edit_nakl_add_new.php?klienti_id2=". mysql_result($ver,$count,'klienti_id')."'  target='_blank'>[N]</a>&nbsp;
      <a href='edit_klient.php?klienti_id=". mysql_result($ver,$count,'klienti_id')."' target='_blank'>". mysql_result($ver,$count,'klienti_name_1')."</a></td>";
      $http .= "<td><a href='edit_status.php?operation_status_id=". mysql_result($ver,$count,'operation_status_id')."' target='_blank'>". mysql_result($ver,$count,'operation_status_name')."</a></td>";
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
      
            $count1=0;
       while ($count1 < mysql_num_rows($ware) and mysql_result($ver,$count,'operation_detail_to')<>mysql_result($ware,$count1,'warehouse_id')){
       $count1++;
       }
      $http .= "-->".mysql_result($ware,$count1,'warehouse_name');
      $http .= " [".mysql_result($ver,$count,"warehouse_unit_".(string)($count1+1))."]"."</td>";
      //=====================
      //$http .= " = ".mysql_result($ver,$count,'operation_detail_from').mysql_result($ver,$count,'operation_detail_to')."</td>";
      $http .= "<td>".mysql_result($ver,$count,'operation_detail_memo')."</td>";
      $http .= "<td>";
	if(mysql_result($ver,$count,'operation_detail_dell')>0){
	    $http .= "DELLETE";
	}else{
	
	}
      $http .= "</td>";
       $http .= "</tr>";
      $count++;
    }
$http .="</table>";
//    $http .= $count."*".$http;
echo $http;

}

?>
