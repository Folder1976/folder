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
$setup = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	  `setup_name`, 
	  `setup_param`
	  FROM `tbl_setup`

";
$setup = mysql_query($tQuery);
$m_setup = array();
$count=0;
while ($count<mysql_num_rows($setup)){
 $m_setup[mysql_result($setup,$count,0)]=mysql_result($setup,$count,1);
 $count++;
}
//==================================SETUP=MENU==========================================
$count = 0;
$user = 0;

$for_link = "";
$find_str = "";

$find_str=(string)mysql_real_escape_string($_REQUEST["_find1"]);
$operation_id= 0; //(int)mysql_real_escape_string($_REQUEST["operation_id"]);

if(strpos($find_str,$m_setup['klient id pref']) !== false){

      $user = substr($find_str,strlen($m_setup['klient id pref']),strlen($find_str));

$klient = mysql_query("SET NAMES utf8");
$tQuery = "SELECT * FROM `tbl_klienti` 
		  WHERE `klienti_id`='$user'";
$klient = mysql_query($tQuery);			

if(mysql_num_rows($klient)<1){
  echo "*No user finded!*alert";
  exit();
}
      
      
      
      $tmp = mysql_query("SET NAMES utf8");
      $tQuery = "SELECT `operation_id` FROM `tbl_operation` 
		  WHERE `operation_klient`='$user' AND 
			`operation_data`='".date("Y-m-d")."' AND
			`operation_dell`='0'";
			
      $tmp = mysql_query($tQuery);
      if(mysql_num_rows($tmp) > 0){
	 $_SESSION[BASE.'shop_operation_id'] = mysql_result($tmp,0,0);
      }else{
	  $tmp = mysql_query("SET NAMES utf8");
	  $tQuery = "INSERT INTO `tbl_operation`(
			`operation_data`, 
			`operation_klient`,
			`operation_prodavec`, 
			`operation_sotrudnik`, 
			`operation_data_edit`, 
			`operation_status`, 
			`operation_summ`, 
			`operation_memo`, 
			`operation_inet_id`, 
			`operation_on_web`, 
			`operation_dell`, 
			`operation_save`
			) 
			VALUES (
			'".date("Y-m-d")."',
			'$user',
			'1',
			'".$_SESSION[BASE.'userid']."',
			'".date("Y-m-d")."',
			'".$m_setup['shop default status']."',
			'0',
			'auto sale',
			'0',
			'0',
			'0',
			'0'
			)";
	  //echo $tQuery;
	  $tmp = mysql_query($tQuery);
	  $_SESSION[BASE.'shop_operation_id'] = mysql_insert_id();
      }
$_SESSION[BASE.'shop_user_id']=$user;
      
$klient = mysql_query("SET NAMES utf8");
$tQuery = "SELECT * FROM `tbl_klienti` 
		  WHERE `klienti_id`='$user'";
$klient = mysql_query($tQuery);			
    
echo "*reload*<a href=\"#\" onclick=\"javascript:menu_open();\">",
      mysql_result($klient,0,"klienti_name_1"),
      " (",
      mysql_result($klient,0,"klienti_phone_1"),
      ") -> ",$_SESSION[BASE.'shop_operation_id'],
      " *** MENU ***</a>"
      ;
    if(mysql_result($klient,0,"klienti_index")<>""){
	echo "*",mysql_result($klient,0,"klienti_index"),"";
    }else{
	echo "*none";
    }
     
      
exit();
      //echo $find_str," ($user) ",$m_setup['klient id pref']," ",strpos($find_str,$m_setup['klient id pref']);

}



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

  $find_str_sql = " and (upper(tovar_name_1) like '%" . mb_strtoupper($find_str,'UTF-8') . "%' 
		  or upper(tovar_artkl) like '%" . mb_strtoupper($find_str,'UTF-8') . "%' 
		  or upper(tovar_name_2) like '%" . mb_strtoupper($find_str,'UTF-8') . "%' 
		  or upper(tovar_name_3) like '%" . mb_strtoupper($find_str,'UTF-8') . "%' 
		  or upper(tovar_barcode) like '%" . mb_strtoupper($find_str,'UTF-8') . "%') ";


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
	    " . $find_str_sql." ORDER BY `tovar_artkl` ASC LIMIT 0,1000";
//echo $tQuery;
$ver = mysql_query($tQuery);

$count=0;
$html =  "\n<table width=100% 
	      cellspacing='0' 
		cellpadding='0' 
		  class='find-result'
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
  $html .= "<tr class=\"nak_field_$i\" onClick='add_tovar(\"".mysql_result($ver,$count,"tovar_id")."\");'>";
  
  $html .= "<td width=15%>". mysql_result($ver,$count,'tovar_artkl'). "&nbsp;</td>";
  $html .= "<td>". mysql_result($ver,$count,'tovar_name_1'). "</b></td>";
  $html .= "<td width=10%><b>(". mysql_result($ver,$count,'price'). ")</b></td>";
  $html .= "<td width=5%> -->".  tovar_on_ware(mysql_result($ver,$count,"tovar_id")). "</b></td>";


  $html .= "</tr>";
$count++;
}

$html .= "</table>";

//if($count>1){
    echo $html;
//}elseif($count==1){
//    echo "*".mysql_result($ver,0,"tovar_id")."*";
//}

?>
