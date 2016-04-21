<?php

include 'init.lib.php';
include '../init.lib.user.tovar.php';
connect_to_mysql();
session_start();
if (!session_verify($_SERVER["PHP_SELF"],"none")){
  exit();
}

require("JsHttpRequest.php");
$JsHttpRequest=new JsHttpRequest("utf8");//"windows-1251");

//=======================================================
if ($_SESSION[BASE.'lang'] <1){
  $_SESSION[BASE.'lang']=1;
}

global $setup;
 $m_setup = $setup;
//==================================SETUP=MENU==========================================

$count = 0;

$for_link = "";
$find_str = "";

$find_str=$_REQUEST["_find1"];
$operation_id=$_REQUEST["operation_id"];

$iPrice = 1;

$find_str_sql="";
$this_table_name = "tbl_operation_detail";
$long_name = "operation_detail_";
$this_table_id_name = "operation_detail_operation";
//$return_page = "edit_tovar_find.php?operation_id=" . $iKlient_id."&_from=".$tmp_from."&_to=".$tmp_to."&_find1=".$find_str."&_supplier=".$find_supplier."&_parent=".$find_parent;
$warehouse_count=0;
//echo $iKlient_id , " " , $return_page;
$color_null = "transparent";
$color_from = "#87ff8f";
$color_to = "#ffa0a0";
$color_tovar1 = "#ADD8E6";
$color_tovar2 = "#ADD8D0";
$color_tovar_now = $color_tovar1;
$warehouse_row_limit = 15;

$tmp= 1;


$klient_disc=0;
$klient_price=2;



$tQuery = "SELECT `warehouse_id`,`warehouse_name`,`warehouse_shot_name` FROM `tbl_warehouse` ORDER BY `warehouse_sort` ASC";
$warehouse = mysql_query("SET NAMES utf8");
$warehouse = mysql_query($tQuery);
if (!$warehouse)
{
  echo "Query error Warehouse";
  exit();
}

$Fields = "";
$warehouse_count=0;
while ($warehouse_count < mysql_num_rows($warehouse))
{
  
  $Fields .= "`warehouse_unit_" . mysql_result($warehouse,$warehouse_count,"warehouse_id") . "`,";
  $warehouse_count++;
}
//=========================== find string=========================================================

  $find_str_sql = " and (upper(tovar_name_1) like '%" . mb_strtoupper($find_str,'UTF-8') . "%' or upper(tovar_artkl) like '%" . mb_strtoupper($find_str,'UTF-8') . "%')";


//==================================================================================================
$Fields .= "`tovar_id`,`tovar_artkl`,`tovar_name_1`,`tovar_memo`,`tovar_inet_id`"; //Tovar
$ver = mysql_query("SET NAMES utf8");


  $sort = "ORDER BY `tovar_name_1` ASC, `tovar_artkl` ASC";


$tQuery = "SELECT " . $Fields . ",`price_tovar_".$m_setup['price default price']."` as price 
	  FROM `tbl_tovar`,`tbl_warehouse_unit`,`tbl_parent`,`tbl_price_tovar` 
	  WHERE 
	    `warehouse_unit_tovar_id`=`tovar_id` and 
	    `price_tovar_id`=`tovar_id` and
	    `tovar_parent`=`tovar_parent_id` 
	    " . $find_str_sql." ORDER BY `tovar_artkl` ASC LIMIT 0,10";

$ver = mysql_query($tQuery);

$count=0;
$html =  "\n<table width=100% 
		  cellspacing='0' 
		    cellpadding='0' 
		      style='border-left:1px solid;border-right:1px solid;border-top:1px solid' 
			>";


$count=0;
$i = 1;
$html .= "<tr class=\"nak_field_$i\" onClick='close_find_window();'><td colspan='4' align=right>[close]</td>
      </tr>";			

while ($count < mysql_num_rows($ver))
{
$id_tmp=mysql_result($ver,$count,"tovar_id");
      if ($i == 1){
	  $i = 2;
      }else{
	  $i = 1;
      }
  $html .= "<tr class=\"nak_field_$i\" onClick='add_tovar(\"".mysql_result($ver,$count,"tovar_id")."\",\"$operation_id\");'>";
  
  $html .= "<td>". mysql_result($ver,$count,'tovar_artkl'). "&nbsp;</td>";
  $html .= "<td>". mysql_result($ver,$count,'tovar_name_1'). "</b></td>";
  $html .= "<td>(". mysql_result($ver,$count,'price'). ")</b></td>";
  $html .= "<td> -->".  tovar_on_ware(mysql_result($ver,$count,"tovar_id")). "</b></td>";


  $html .= "</tr>";
$count++;
}

$html .= "</table>";

if($count>1){
    echo $html;
}elseif($count==1){
    echo "*".mysql_result($ver,0,"tovar_id")."*";
}

?>
