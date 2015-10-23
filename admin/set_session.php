<?php
include 'init.lib.php';

connect_to_mysql();
session_start();

require("JsHttpRequest.php");
$JsHttpRequest=new JsHttpRequest("utf8");//"windows-1251");

$value2 = $_REQUEST["nakl"];
$restore = 0;
if(isset($_REQUEST['restore']))$restore = $_REQUEST['restore'];

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
     `operation_detail_dell`,
     `operation_detail_operation`,
     `tovar_id`,
     `tovar_artkl`,
     `tovar_name_1`,
     `tbl_warehouse_unit`.*"; //Tovar
$ver = mysql_query("SET NAMES utf8");
$tQuery = "SELECT " . $Fields . " 
	  FROM `tbl_operation_detail`,`tbl_tovar`,`tbl_warehouse_unit` 
	  WHERE 
	  `warehouse_unit_tovar_id`=`tovar_id` ";
if(!$restore) 
  $tQuery .= " and `operation_detail_dell`='0' ";
$tQuery .= "and `operation_detail_tovar`=`tovar_id` 
	  and `operation_detail_operation`='" . $value2 . "' 
	  GROUP BY `operation_detail_id` ";
if($restore){
    $tQuery .= "ORDER BY `operation_detail_id` DESC";
}else{
    $tQuery .= "ORDER BY `tovar_name_1` ASC";
}	  
//echo $_REQUEST['restore'];
// echo $tQuery;
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
//echo number_format($value2,5,".","");
//echo $tQuery;
$count=0;
$summ = 0;
$http = "";

$http .= "<a href='edit_klient.php?klienti_id=".mysql_result($deliv,0,"klienti_id")."' target=_blank>change </a><b>".mysql_result($deliv,0,"delivery_name")."</b>";

if($restore){
	  $http .= "<br><input type='text' style='width:300px' id='find*".mysql_result($ver,$count,'operation_detail_operation')."'
		    value='find' 
		    onClick='set_field_clear(\"".mysql_result($ver,$count,'operation_detail_operation')."\");'
		    onChange='find(this.value,\"".mysql_result($ver,$count,'operation_detail_operation')."\");'/>";
}

$http .="<table><tr class=\"nak_header_find\"><th></th><th>art</th><th>name</th><th>price</th><th>item</th><th>disc</th><th>summ</th><th>move</th><th>memo</th>";
if($restore){
    $http .= "<th><a href='javascript:reload_nakl($value2,\"reload\");'>*** SAVE ***</a></th>";
}
$http .= "</tr>";
    while ($count < mysql_num_rows($ver))
    {
      $http .= "<tr class=\"nak_field_1\">";
      $http .= "<td>";
      $http .= "<a href='edit_tovar_history.php?tovar_id=".mysql_result($ver,$count,'operation_detail_tovar')." ' target='_blank'>".($count+1)."+</a>";
      $http .= "<td><a href='edit_tovar.php?tovar_id=".mysql_result($ver,$count,'operation_detail_tovar')." ' target='_blank'>";
      $http .= mysql_result($ver,$count,'tovar_artkl')."</a></td>";
      $http .= "<td>".mysql_result($ver,$count,'tovar_name_1')."</td>";
      
      if($restore){
	  $http .= "<td><input type='text' style='width:40px' id='operation_detail_price*".mysql_result($ver,$count,'operation_detail_id')."' 
		    value='".mysql_result($ver,$count,'operation_detail_price')."' 
		    onChange='update_detail_fil(".mysql_result($ver,$count,'operation_detail_id').",this.value,\"operation_detail_price\",$value2);'></td>";

	  $http .= "<td><input type='text' style='width:30px' id='operation_detail_item*".mysql_result($ver,$count,'operation_detail_id')."'
		    value='".mysql_result($ver,$count,'operation_detail_item')."' 
		    onChange='update_detail_fil(".mysql_result($ver,$count,'operation_detail_id').",this.value,\"operation_detail_item\",$value2);'></td>";

	  $http .= "<td><input type='text' style='width:25px' id='operation_detail_discount*".mysql_result($ver,$count,'operation_detail_id')."'
		    value='".mysql_result($ver,$count,'operation_detail_discount')."' 
		    onChange='update_detail_fil(".mysql_result($ver,$count,'operation_detail_id').",this.value,\"operation_detail_discount\",$value2);'></td>";

	  $http .= "<td><input type='text' style='width:60px' id='operation_detail_summ*".mysql_result($ver,$count,'operation_detail_id')."'
		    value='".mysql_result($ver,$count,'operation_detail_summ')."' 
		    onChange='update_detail_fil(".mysql_result($ver,$count,'operation_detail_id').",this.value,\"operation_detail_summ\",$value2);'></td>";
		      
      }else{
	  $http .= "<td>".mysql_result($ver,$count,'operation_detail_price')."</td>";
	  $http .= "<td>".mysql_result($ver,$count,'operation_detail_item')."</td>";
	  $http .= "<td>".mysql_result($ver,$count,'operation_detail_discount')."</td>";
	  $http .= "<td>".mysql_result($ver,$count,'operation_detail_summ')."</td>";

      }
       $summ += number_format(mysql_result($ver,$count,'operation_detail_summ'),2,".","");
      
//Warehouse Name Set
$http .= "<td>";

if($restore){
	  $count1=0;
	  $http .= "<select style='width:100px' 
		      onChange='update_detail_fil(".mysql_result($ver,$count,'operation_detail_id').",this.value,\"operation_detail_from\",$value2);'>";
	  while ($count1 < mysql_num_rows($ware)){
	      $http .= "<option ";
	      if(mysql_result($ver,$count,'operation_detail_from')==mysql_result($ware,$count1,'warehouse_id')) $http .= " selected ";
	      $http .= "value='".mysql_result($ware,$count1,'warehouse_id')."'>[".mysql_result($ver,$count,"warehouse_unit_".(string)($count1+1))."]"
			.mysql_result($ware,$count1,'warehouse_name').
		      "</option>";
	      $count1++;
	  }
	  $http .= "</select>-->";
	  
	  $count1=0;
	  $http .= "<select style='width:100px' 
		      onChange='update_detail_fil(".mysql_result($ver,$count,'operation_detail_id').",this.value,\"operation_detail_to\",$value2);'>";
	  while ($count1 < mysql_num_rows($ware)){
	      $http .= "<option ";
	      if(mysql_result($ver,$count,'operation_detail_to')==mysql_result($ware,$count1,'warehouse_id')) $http .= " selected ";
	      $http .= "value='".mysql_result($ware,$count1,'warehouse_id')."'>[".mysql_result($ver,$count,"warehouse_unit_".(string)($count1+1))."]"
			.mysql_result($ware,$count1,'warehouse_name').
		      "</option>";
	      $count1++;
	  }
	  $http .= "</select>";

}else{
      if(mysql_result($ver,$count,'operation_detail_from')>0){
	  $count1=0;
	  while ($count1 < mysql_num_rows($ware) and mysql_result($ver,$count,'operation_detail_from')<>mysql_result($ware,$count1,'warehouse_id')){
	    $count1++;
	  }
	  $http .= mysql_result($ware,$count1,'warehouse_name');
	  $http .= " [".mysql_result($ver,$count,"warehouse_unit_".(string)($count1+1))."]";
      }    
      if(mysql_result($ver,$count,'operation_detail_to')>0){
	  $count1=0;
	  while ($count1 < mysql_num_rows($ware) and mysql_result($ver,$count,'operation_detail_to')<>mysql_result($ware,$count1,'warehouse_id')){
	  $count1++;
	  }
	  $http .= "-->".mysql_result($ware,$count1,'warehouse_name');
	  $http .= " [".mysql_result($ver,$count,"warehouse_unit_".(string)($count1+1))."]";
      }
}  
$http .= "</td>";
  //=====================
      $http .= "<td>".mysql_result($ver,$count,'operation_detail_memo')."</td>";

if($restore){
    if(mysql_result($ver,$count,'operation_detail_dell') == '1'){
	  $http .= "<td><a href='#none' onClick='update_detail(".mysql_result($ver,$count,'operation_detail_id').",0,$value2);'>
	  restore - $summ </a>  </td>";
    }else{
	  $http .= "<td><a href='#none' onClick='update_detail(".mysql_result($ver,$count,'operation_detail_id').",1,$value2);'>
	  dell - $summ </a>  </td>";
    }
} 
 
$http .= "</tr>";      
       $count++;
}
    
$http .="</table>";
$http .= "SUMM = ".$summ;
//    $http .= $count."*".$http;
echo $http;



?>
