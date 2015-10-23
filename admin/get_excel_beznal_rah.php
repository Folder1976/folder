<?php
include 'init.lib.php';
include 'money2str.lib.php';
connect_to_mysql();
session_start();
//echo "ddddd";

require("JsHttpRequest.php");
$JsHttpRequest=new JsHttpRequest("utf8");//"windows-1251");
$operation_id = $_REQUEST['_operation_id'];
//==================================SETUP===========================================
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

$setup = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	  `setup_menu_name`, 
	  `setup_menu_".$m_setup['print default lang']."`
	  FROM `tbl_setup_menu`

";
//echo $tQuery;
$setup = mysql_query($tQuery);
$count=0;
while ($count<mysql_num_rows($setup)){
 $m_setup[mysql_result($setup,$count,0)]=mysql_result($setup,$count,1);
 $count++;
}
//==================================SETUP===========================================
$ver = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	  `tovar_artkl`, 
	  `tovar_name_".$m_setup['print default lang']."` as tovar_name_1,
	  (`price_tovar_1`*`currency_ex`*`operation_detail_item`) AS price_tovar_1,
	  `operation_detail_price`,
	  `operation_detail_item`,
	  `operation_detail_discount`,
	  `operation_detail_summ`,
	  `operation_detail_memo`,
	  `operation_detail_from`,
	  `operation_detail_to`,
	  `tbl_warehouse_unit`.*
	  FROM `tbl_operation_detail`,`tbl_tovar`,`tbl_price_tovar`,`tbl_currency`,`tbl_warehouse_unit`
	  WHERE 
	  `operation_detail_tovar`=`tovar_id` and 
	  `warehouse_unit_tovar_id`=`tovar_id` and
	  `price_tovar_id`=`tovar_id` and
	  `currency_id`=`price_tovar_curr_1` and
	  `operation_detail_operation`='".$operation_id."' and
	  `operation_detail_dell` = '0'
	  ORDER BY `tovar_name_1` ASC, `tovar_artkl` ASC
";

$fields = mysql_query($tQuery);
	  
$ver = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	  `tbl_operation`.*,
	  `tbl_firms`.*,
	  `klienti_name_1`,
	  `klienti_phone_1`,
	  `delivery_name`
	  FROM `tbl_operation`,`tbl_klienti`,`tbl_firms`,`tbl_delivery` 
	  WHERE 
	  `operation_klient`=`klienti_id` and 
	  `operation_id`='".$operation_id."' and
	  `operation_prodavec`=`firms_id` and
	  `delivery_id`=`klienti_delivery_id`
	  
";	  
$ver = mysql_query($tQuery);


//=============================================================================================
$temp = "template/excel_rah.xml";
$excel = file_get_contents($temp);  

$cells = "<Row ss:Index=\"1\">
		<Cell ss:StyleID=\"s23\" ss:Index=\"1\"><Data ss:Type=\"String\">".$m_setup['print rah no']." : </Data></Cell>
		<Cell ss:Index=\"3\"><Data ss:Type=\"String\">".mysql_result($ver,0,"operation_beznal_rah")." (".$m_setup['print from date']." ".mysql_result($ver,0,"operation_beznal_rah_date").")</Data></Cell>
	  </Row>";
$cells .= "<Row ss:Index=\"2\">
		<Cell ss:StyleID=\"s23\" ss:Index=\"1\"><Data ss:Type=\"String\">".$m_setup['print vidal']." : </Data></Cell>
		<Cell ss:Index=\"3\"><Data ss:Type=\"String\">".mysql_result($ver,0,"firms_name")."</Data></Cell>
	  </Row>";
$cells .= "<Row ss:Index=\"3\">
		<Cell ss:StyleID=\"s23\" ss:Index=\"1\"><Data ss:Type=\"String\">".$m_setup['print otrimal']." : </Data></Cell>
		<Cell ss:Index=\"3\"><Data ss:Type=\"String\">".mysql_result($ver,0,"klienti_name_1")."</Data></Cell>
	  </Row>";
$cells .= "<Row ss:Index=\"4\">
		<Cell ss:StyleID=\"s23\" ss:Index=\"1\"><Data ss:Type=\"String\">".$m_setup['print bank info rah']." : </Data></Cell>
		<Cell ss:Index=\"3\"><Data ss:Type=\"String\">".mysql_result($ver,0,"firms_rah")."</Data></Cell>
	  </Row>";
$cells .= "<Row ss:Index=\"5\">
		<Cell ss:StyleID=\"s23\" ss:Index=\"1\"><Data ss:Type=\"String\">".$m_setup['print bank info zkpo']." : </Data></Cell>
		<Cell ss:Index=\"3\"><Data ss:Type=\"String\">".mysql_result($ver,0,"firms_zkpo")."</Data></Cell>
	  </Row>";
$cells .= "<Row ss:Index=\"6\">
		<Cell ss:StyleID=\"s23\" ss:Index=\"1\"><Data ss:Type=\"String\">".$m_setup['print bank info bank']." : </Data></Cell>
		<Cell ss:Index=\"3\"><Data ss:Type=\"String\">".mysql_result($ver,0,"firms_bank")." (".$m_setup['print bank info mfo']." ".mysql_result($ver,0,"firms_mfo").")</Data></Cell>
	  </Row>";
/*$cells .= "<Row ss:Index=\"7\">
		<Cell ss:Index=\"1\"><Data ss:Type=\"String\">%:</Data></Cell>
		<Cell ss:Index=\"2\"><Data ss:Type=\"String\">&summ_hab</Data></Cell>
		<Cell ss:Index=\"4\"><Data ss:Type=\"String\">habib_name</Data></Cell>
	  </Row>";*/

$cells .= "<Row ss:Index=\"8\">
		<Cell ss:StyleID=\"s24\" ss:Index=\"1\"><Data ss:Type=\"String\">N</Data></Cell>
		<Cell ss:StyleID=\"s24\" ss:Index=\"2\"><Data ss:Type=\"String\">".$m_setup['menu artkl']."</Data></Cell>
		<Cell ss:StyleID=\"s24\" ss:Index=\"3\"><Data ss:Type=\"String\">".$m_setup['menu name1']."</Data></Cell>
		<Cell ss:StyleID=\"s24\" ss:Index=\"4\"><Data ss:Type=\"String\">".$m_setup['print price']."</Data></Cell>
		<Cell ss:StyleID=\"s24\" ss:Index=\"5\"><Data ss:Type=\"String\">".$m_setup['print items']."</Data></Cell>
		<Cell ss:StyleID=\"s24\" ss:Index=\"6\"><Data ss:Type=\"String\">".$m_setup['print discount']."</Data></Cell>
		<Cell ss:StyleID=\"s24\" ss:Index=\"7\"><Data ss:Type=\"String\">".$m_setup['print summ']."</Data></Cell>
		
	  </Row>";


$count = 0;
$Sum = 0;
$Sum_p = 0;
while($count < mysql_num_rows($fields)){
	$tovar_name = mysql_result($fields,$count,"tovar_name_1");
	$tovar_name = explode($m_setup['tovar name sep'], $tovar_name);

 $cells .= "<Row ss:Index=\"".($count+9)."\">
		<Cell ss:StyleID=\"s21\" ss:Index=\"1\"><Data ss:Type=\"Number\">".($count+1)."</Data></Cell>
		<Cell ss:StyleID=\"s21\" ss:Index=\"2\"><Data ss:Type=\"String\">".mysql_result($fields,$count,"tovar_artkl")."</Data></Cell>
		<Cell ss:StyleID=\"s21\" ss:Index=\"3\"><Data ss:Type=\"String\">".$tovar_name[0]."</Data></Cell>
		<Cell ss:StyleID=\"s21\" ss:Index=\"4\"><Data ss:Type=\"Number\">".number_format(mysql_result($fields,$count,"operation_detail_price"),2,".","")."</Data></Cell>
		<Cell ss:StyleID=\"s24\" ss:Index=\"5\"><Data ss:Type=\"Number\">".mysql_result($fields,$count,"operation_detail_item")."</Data></Cell>
		<Cell ss:StyleID=\"s21\" ss:Index=\"6\"><Data ss:Type=\"Number\">".mysql_result($fields,$count,"operation_detail_discount")."</Data></Cell>
		<Cell ss:StyleID=\"s21\" ss:Index=\"7\"><Data ss:Type=\"Number\">".number_format(mysql_result($fields,$count,"operation_detail_summ"),2,".","")."</Data></Cell>
	
	  </Row>";

$Sum += mysql_result($fields,$count,"operation_detail_summ");
//$Sum_p += (mysql_result($ver,$count,"habibulin_money")/100*mysql_result($ver,$count,"habibulin_user_usd"));

$count++;
}

$cells .= "<Row ss:Index=\"".($count+10)."\">
		<Cell ss:StyleID=\"s25\" ss:Index=\"1\"><Data ss:Type=\"String\">".$m_setup['print summ']." : </Data></Cell>
		<Cell ss:StyleID=\"s25\" ss:Index=\"2\"></Cell>
		<Cell ss:StyleID=\"s25\" ss:Index=\"3\"><Data ss:Type=\"String\"></Data></Cell>
		<Cell ss:StyleID=\"s25\" ss:Index=\"4\"><Data ss:Type=\"String\"></Data></Cell>
		<Cell ss:StyleID=\"s25\" ss:Index=\"5\"><Data ss:Type=\"String\"></Data></Cell>
		<Cell ss:StyleID=\"s25\" ss:Index=\"6\"><Data ss:Type=\"String\"></Data></Cell>
		<Cell ss:StyleID=\"s25\" ss:Index=\"7\"><Data ss:Type=\"String\">".number_format($Sum,2,".","")."</Data></Cell>
	  </Row>";
$cells .= "<Row ss:Index=\"".($count+11)."\">
		<Cell ss:StyleID=\"s25\" ss:Index=\"1\"><Data ss:Type=\"String\">".$m_setup['print pdv']." : </Data></Cell>
		<Cell ss:StyleID=\"s25\" ss:Index=\"2\"></Cell>
		<Cell ss:StyleID=\"s25\" ss:Index=\"3\"><Data ss:Type=\"String\"></Data></Cell>
		<Cell ss:StyleID=\"s25\" ss:Index=\"4\"><Data ss:Type=\"String\"></Data></Cell>
		<Cell ss:StyleID=\"s25\" ss:Index=\"5\"><Data ss:Type=\"String\"></Data></Cell>
		<Cell ss:StyleID=\"s25\" ss:Index=\"6\"><Data ss:Type=\"String\"></Data></Cell>
		<Cell ss:StyleID=\"s25\" ss:Index=\"7\"><Data ss:Type=\"String\">0</Data></Cell>
	  </Row>";
$cells .= "<Row ss:Index=\"".($count+12)."\">
		<Cell ss:StyleID=\"s25\" ss:Index=\"1\"><Data ss:Type=\"String\">".$m_setup['print summ']." + ".$m_setup['print pdv']." : </Data></Cell>
		<Cell ss:StyleID=\"s25\" ss:Index=\"2\"></Cell>
		<Cell ss:StyleID=\"s25\" ss:Index=\"3\"></Cell>
		<Cell ss:StyleID=\"s25\" ss:Index=\"4\"><Data ss:Type=\"String\"></Data></Cell>
		<Cell ss:StyleID=\"s25\" ss:Index=\"5\"><Data ss:Type=\"String\"></Data></Cell>
		<Cell ss:StyleID=\"s25\" ss:Index=\"6\"><Data ss:Type=\"String\"></Data></Cell>
		<Cell ss:StyleID=\"s25\" ss:Index=\"7\"><Data ss:Type=\"String\">".number_format($Sum,2,".","")."</Data></Cell>
	  </Row>";
$cells .= "<Row ss:Index=\"".($count+13)."\">
		<Cell ss:StyleID=\"s25\" ss:Index=\"1\"><Data ss:Type=\"String\">".$m_setup['print string']." : </Data></Cell>
		<Cell ss:StyleID=\"s25\" ss:Index=\"2\"></Cell>
		<Cell ss:StyleID=\"s25\" ss:Index=\"3\"><Data ss:Type=\"String\">".money2str_ru($Sum)."</Data></Cell>
		<Cell ss:StyleID=\"s25\" ss:Index=\"4\"></Cell>
		<Cell ss:StyleID=\"s25\" ss:Index=\"5\">></Cell>
		<Cell ss:StyleID=\"s25\" ss:Index=\"6\"></Cell>
		<Cell ss:StyleID=\"s25\" ss:Index=\"7\"></Cell>
	  </Row>";
 // $cells = str_replace("&summ_hab",$Sum_p,$cells);
 // $cells = str_replace("&summ",$Sum,$cells);
 
 $excel = str_replace("&rows",$count+16,$excel);
 $excel = str_replace("&list_name","rah-".$operation_id,$excel);
 $excel = str_replace("&cells",$cells,$excel);
 
 $file_name = "rah-".$operation_id.".xls";
 $fp = fopen($file_name,"w");
 fwrite($fp,$excel);
 fclose($fp);
 
 echo $file_name;


?>
