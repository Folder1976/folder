<?php
include 'admin/init.lib.php';
session_start();
connect_to_mysql();
require("JsHttpRequest.php");
$JsHttpRequest=new JsHttpRequest("utf8");//"windows-1251");

$operation_id = $_REQUEST["id"];
$file_name = "tmp/excel$operation_id.xls";

header ('Content-Type: text/html; charset=utf8');
   $ver = mysql_query("SET NAMES utf8");
    
if($operation_id > 10){  
$temp = "template/excel.xml";
$excel = file_get_contents($temp);
    $SgetName = "SELECT *
	      FROM `tbl_operation`, `tbl_operation_status`, `tbl_operation_detail`,`tbl_tovar`,`tbl_klienti`
	      WHERE 
	      `operation_status`=`operation_status_id` and
	      `klienti_id`=`operation_klient` and
	      `operation_detail_dell`='0' and
	      `operation_detail_tovar`=`tovar_id` and
	      `operation_id` = `operation_detail_operation` and
	      `operation_dell`='0' and
	      `operation_id` = '$operation_id'
	      ORDER BY `operation_id` DESC";
    $ver = mysql_query($SgetName);

    if($_SESSION[BASE.'userid'] != mysql_result($ver,0,"klienti_id")){
	  echo "NOT USER";
	  exit();
    }
}elseif($operation_id == -1){
$temp = "template/excel_all.xml";
$excel = file_get_contents($temp);
    if(strpos($_SESSION[BASE.'usergroup_setup'],"price_excel",0)>0){
	  $SgetName = "SELECT 	
			`tovar_inet_id_parent`,
			`tovar_artkl`,
			`tovar_name_".$_SESSION[BASE.'lang']."` AS tovar_name,
			`tovar_id`,
			`tovar_inet_id`,
			`price_tovar_".$setup['web default price']."` as price1,
			`price_tovar_".$_SESSION[BASE.'userprice']."` as price2,
			`price_tovar_curr_".$setup['web default price']."` as curr1,
			`price_tovar_curr_".$_SESSION[BASE.'userprice']."` as curr2,
			`description_".$_SESSION[BASE.'lang']."` as tovar_memo,
			`parent_inet_type`
			FROM 
			`tbl_tovar`,
			`tbl_price_tovar`,
			`tbl_description`, 
			`tbl_parent_inet`
			WHERE 
			`tovar_id`=`price_tovar_id` and
			`tovar_inet_id`>0 and
			`parent_inet_id`=`tovar_inet_id_parent` and
			`tovar_id`=`description_tovar_id` 
			ORDER BY `tovar_id` DESC,
				  `tovar_name_1` ASC,
				  `tovar_artkl` ASC
			";	  
	  $ver = mysql_query($SgetName);
	  exit();
    }else{
	echo "NOT ACCESS!";
	exit();
    }
}
$excel = str_replace("&list name","nakl # $operation_id",$excel);

$cells = "<Row ss:Index=\"2\">
		<Cell ss:Index=\"3\"><Data ss:Type=\"String\">".mysql_result($ver,0,"operation_data")."</Data></Cell>
	  </Row>";
$cells .= "<Row ss:Index=\"4\">
		<Cell ss:Index=\"2\" ss:StyleID=\"s21\"><Data ss:Type=\"String\">".$setup['menu nakl']." # $operation_id</Data></Cell>
		<Cell ss:Index=\"3\" ss:StyleID=\"s21\"><Data ss:Type=\"String\">".mysql_result($ver,0,"klienti_name_".$_SESSION[BASE.'lang']).
				    "(".mysql_result($ver,0,"operation_status_name").")</Data></Cell>
		<Cell ss:Index=\"4\" ss:StyleID=\"s21\"><Data ss:Type=\"String\">".$setup['menu summ'].":</Data></Cell>
		<Cell ss:Index=\"5\" ss:StyleID=\"s21\"/>
		<Cell ss:Index=\"6\" ss:StyleID=\"s21\"/>
		<Cell ss:Index=\"7\" ss:StyleID=\"s21\"><Data ss:Type=\"Number\">".mysql_result($ver,0,"operation_summ")."</Data></Cell>
	    </Row>";
$cells .= "<Row ss:Index=\"5\">
		<Cell ss:Index=\"2\"><Data ss:Type=\"String\">".$setup['menu memo']."</Data></Cell>
		<Cell ss:Index=\"3\"><Data ss:Type=\"String\">".mysql_result($ver,0,"operation_memo")."</Data></Cell>
	  </Row>";


$cells .= "<Row ss:Index=\"7\" ss:StyleID=\"s24\">
		<Cell ss:Index=\"1\"><Data ss:Type=\"String\">#</Data></Cell>
		<Cell ss:Index=\"2\"><Data ss:Type=\"String\">".$setup['menu artkl']."</Data></Cell>
		<Cell ss:Index=\"3\"><Data ss:Type=\"String\">".$setup['menu name1']."</Data></Cell>
		<Cell ss:Index=\"4\"><Data ss:Type=\"String\">".$setup['print price']."</Data></Cell>
		<Cell ss:Index=\"5\"><Data ss:Type=\"String\">".$setup['print items']."</Data></Cell>
		<Cell ss:Index=\"6\"><Data ss:Type=\"String\">".$setup['print discount']."</Data></Cell>
		<Cell ss:Index=\"7\"><Data ss:Type=\"String\">".$setup['menu summ']."</Data></Cell>
	  </Row>";

 $count=0;
 while($count < mysql_num_rows($ver)){

 $cells .= "<Row ss:Index=\"".($count+8)."\">
		<Cell ss:Index=\"1\"><Data ss:Type=\"Number\">".($count+1)."</Data></Cell>
		<Cell ss:Index=\"2\"><Data ss:Type=\"String\">".mysql_result($ver,$count,"tovar_artkl")."</Data></Cell>";
		$tovar_name = mysql_result($ver,$count,"tovar_name_".$_SESSION[BASE.'lang']);
		$tovar_name = explode($setup['tovar name sep'], $tovar_name);
 $cells .= " 	<Cell ss:Index=\"3\"><Data ss:Type=\"String\">".$tovar_name[0]."</Data></Cell>
		<Cell ss:Index=\"4\"><Data ss:Type=\"Number\">".mysql_result($ver,$count,"operation_detail_price")."</Data></Cell>
		<Cell ss:Index=\"5\"><Data ss:Type=\"Number\">".mysql_result($ver,$count,"operation_detail_item")."</Data></Cell>
		<Cell ss:Index=\"6\"><Data ss:Type=\"Number\">".mysql_result($ver,$count,"operation_detail_discount")."</Data></Cell>
		<Cell ss:Index=\"7\"><Data ss:Type=\"Number\">".mysql_result($ver,$count,"operation_detail_summ")."</Data></Cell>
		
	  </Row>";
 
 $count++;
 }
 
 $excel = str_replace("&rows",$count+10,$excel);
 $excel = str_replace("&cells",$cells,$excel);
 
 $fp = fopen($file_name,"w");
 fwrite($fp,$excel);
 fclose($fp);
 
 echo $file_name;


?>
