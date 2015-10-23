<?php
include 'init.lib.php';
connect_to_mysql();
session_start();


require("JsHttpRequest.php");
$JsHttpRequest=new JsHttpRequest("utf8");//"windows-1251");


$date_print = date("Y-m-d G:i:s");

if(isset($_REQUEST["parent"])){
    $parent=(int)mysql_real_escape_string($_REQUEST["parent"]);
}else{
    echo "Not parent parametr";
    exit();
}
if(isset($_REQUEST["vidal"])){
    $vidal=(int)mysql_real_escape_string($_REQUEST["vidal"]);
}else{
    echo "Not vidal parametr";
    exit();
}
if(isset($_REQUEST["poluchil"])){
    $poluchil=(int)mysql_real_escape_string($_REQUEST["poluchil"]);
}else{
    echo "Not poluchil parametr";
    exit();
}
if(isset($_REQUEST["ware"])){
    $ware=(int)mysql_real_escape_string($_REQUEST["ware"]);
}else{
    echo "Not ware parametr";
    exit();
}


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

//echo $tQuery;
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
$setup = mysql_query($tQuery);
$count=0;
while ($count<mysql_num_rows($setup)){
 $m_setup[mysql_result($setup,$count,0)]=mysql_result($setup,$count,1);
 $count++;
}
//=======================================================================

$ver = mysql_query("SET NAMES utf8");
      $tQuery = "SELECT `tovar_id`,
			`tovar_artkl`,
			`tovar_name_".$m_setup['print default lang']."` as tovar_name,
			`price_tovar_".$m_setup['price default price']."` as tovar_price,
			`currency_name_shot`,
			`dimension_name`,
			`warehouse_unit_$ware` as warehouse,
			`warehouse_unit_7` as reserv
	  FROM `tbl_tovar`,
		`tbl_price_tovar`,
		
		`tbl_currency`,
		`tbl_tovar_dimension`,
		`tbl_warehouse_unit`
	  WHERE `tovar_parent`='$parent' and
		
		`price_tovar_id`=`tovar_id` and
		`warehouse_unit_tovar_id`=`tovar_id` and		
		`tovar_dimension`=`dimension_id` and
		(`warehouse_unit_$ware` <> 0 or `warehouse_unit_7` <> 0) and
		`currency_id`=`price_tovar_curr_".$m_setup['price default price']."`
	  ORDER BY `tovar_name_".$m_setup['print default lang']."` ASC
	  ";
      $ver = mysql_query($tQuery);

      
  //echo $tQuery;    
 // exit();
if(mysql_num_rows($ver) <1){ 
      echo "null";
      exit();
      }
 $ver_parent = mysql_query("SET NAMES utf8");
      $tQuery = "SELECT `tovar_parent_name` FROM `tbl_parent` WHERE `tovar_parent_id`='$parent'";
 $ver_parent = mysql_query($tQuery);
 
 $ver_poluchil = mysql_query("SET NAMES utf8");
      $tQuery = "SELECT `klienti_name_1` FROM `tbl_klienti` WHERE `klienti_id`='$poluchil'";
 $ver_poluchil = mysql_query($tQuery);
 
 $ver_vidal = mysql_query("SET NAMES utf8");
      $tQuery = "SELECT `firms_name` FROM `tbl_firms` WHERE `firms_id`='$vidal'";
 $ver_vidal = mysql_query($tQuery);
     
 $ver_warname = mysql_query("SET NAMES utf8");
      $tQuery = "SELECT `warehouse_name` FROM `tbl_warehouse` WHERE `warehouse_id`='$ware'";
 $ver_warname = mysql_query($tQuery);
     
      
      
$file_name = "template/excel_parent.xml";
$file_name_save = "tmp/excel_parent.xls";

$excel = file_get_contents($file_name);  
$cells = "";


$count = 0;
$count_pp = 1;
$reserv = "";
$tovar_count = 0;
$tovar_summ = 0;
$rows = "";
while($count < mysql_num_rows($ver)){
//=========================================================================   
    $reserv = "";
    if(mysql_result($ver,$count,"reserv") > 0){ //reserv
	  $ver_tmp = mysql_query("SET NAMES utf8");
	    $tQuery = "SELECT SUM(operation_detail_item) as reserv FROM `tbl_operation_detail` 
		WHERE `operation_detail_tovar`='".mysql_result($ver,$count,"tovar_id")."'
		      and `operation_detail_to`='7'
		      and `operation_detail_dell`='0'
		      and `operation_detail_from`='$ware'
		";
	  $ver_tmp = mysql_query($tQuery);
	
	if(mysql_num_rows($ver_tmp)>0){
	  if(mysql_result($ver_tmp,0,"reserv")) $reserv = "(".mysql_result($ver_tmp,0,"reserv").")";
	}else{
	  $reserv = "(error)";
	}
    }
//=========================================================================  
  if($reserv == "" and mysql_result($ver,$count,"warehouse") == 0){//Если резерв не с этого склада и на складе пусто
  }else{
    $rows .= "<Row ss:AutoFitHeight=\"0\" ss:Height=\"20\" >
		  <Cell ss:StyleID=\"s75\"><Data ss:Type=\"Number\">".($count_pp)."</Data></Cell>
		  <Cell ss:StyleID=\"s76\"><Data ss:Type=\"String\">".mysql_result($ver,$count,"tovar_artkl")."</Data></Cell>
		  <Cell ss:StyleID=\"s76\"><Data ss:Type=\"String\">".mysql_result($ver,$count,"tovar_name")."</Data></Cell>
		  <Cell ss:StyleID=\"s75\"><Data ss:Type=\"String\">".mysql_result($ver,$count,"dimension_name")."</Data></Cell>
		  <Cell ss:StyleID=\"s77\"><Data ss:Type=\"Number\">".mysql_result($ver,$count,"warehouse")."</Data></Cell>
		  <Cell ss:StyleID=\"s78\"><Data ss:Type=\"Number\">".mysql_result($ver,$count,"tovar_price")."</Data></Cell>
		  <Cell ss:StyleID=\"s78\"><Data ss:Type=\"Number\">".mysql_result($ver,$count,"tovar_price")*mysql_result($ver,$count,"warehouse")."</Data></Cell>
		  <Cell ss:StyleID=\"s76\"><Data ss:Type=\"String\">$reserv</Data></Cell>
   </Row>";
   $tovar_count = $tovar_count + mysql_result($ver,$count,"warehouse");
   $tovar_summ = $tovar_summ + mysql_result($ver,$count,"tovar_price")*mysql_result($ver,$count,"warehouse");
   $count_pp++;
  }
$count++;   
   }
   
$tmp =mysql_result($ver_parent,0,0)." [".mysql_result($ver_warname,0,0)."]";   
$excel = str_replace("&parent_name",$tmp,$excel);
$excel = str_replace("&data",$date_print,$excel);
$excel = str_replace("&vidal_name",mysql_result($ver_vidal,0,0),$excel);
$excel = str_replace("&poluchil_name",mysql_result($ver_poluchil,0,0),$excel);
$excel = str_replace("&vidal_setup",$m_setup['print vidal'],$excel);
$excel = str_replace("&poluchil_setup",$m_setup['print otrimal'],$excel);

$excel = str_replace("&kod_setup",$m_setup['table tovar_artkl'],$excel);
$excel = str_replace("&name_setup",$m_setup['menu name1'],$excel);
$excel = str_replace("&vimir_setup",$m_setup['menu dimension short'],$excel);
$excel = str_replace("&kol_setup",$m_setup['print items'],$excel);
$excel = str_replace("&price_setup",$m_setup['print price'],$excel);
$excel = str_replace("&summ_setup",$m_setup['print summ'],$excel);
$excel = str_replace("&sale_setup",$m_setup['print memo'],$excel);

$excel = str_replace("&pidsumok_setup",$m_setup['print pidsumok'],$excel);
$excel = str_replace("&pdv_setup",$m_setup['print pdv'],$excel);
$excel = str_replace("&razom_setup",$m_setup['print razom'],$excel);

$excel = str_replace("&Rows",$rows,$excel);
$excel = str_replace("&Row_count",$count+20,$excel);

$excel = str_replace("&tovar_count",$tovar_count,$excel);
$excel = str_replace("&tovar_summ",$tovar_summ,$excel);

//================================================================================================
 $fp = fopen($file_name_save,"w");
 fwrite($fp,$excel);
 fclose($fp);
 echo $file_name_save,"";
 exit();
//================================================================================================
  
   
/*   
//number_format($price2,2,'.','');
  $zakup_uah = mysql_result($ver,$count,"price_tovar_1")*$m_curr_ex[mysql_result($ver,$count,"price_tovar_curr_1")];
  if($zakup_uah==0) $zakup_uah=1;
  $coef = mysql_result($ver,$count,"operation_detail_price")/$zakup_uah;
  $item = mysql_result($ver,$count,"operation_detail_item");
  
  $summ_zakup = $summ_zakup + ($item * $zakup_uah);
  $summ_sale = $summ_sale + mysql_result($ver,$count,"operation_detail_summ");
  
  //Если конец накладной или конец списка
    $summ = "";
    if(($count+1) < mysql_num_rows($ver)){
	if($tmp_operation <> mysql_result($ver,($count+1),"operation_id")){
	  $summ = "<Cell ss:Formula=\"=SUM(R[-$count_filds]C[-1]:RC[-1])\" ss:StyleID=\"s24\" ss:Index=\"13\"><Data ss:Type=\"String\"></Data></Cell>";
	}	
    }else{
	  $summ = "<Cell ss:Formula=\"=SUM(R[-$count_filds]C[-1]:RC[-1])\" ss:StyleID=\"s24\" ss:Index=\"13\"><Data ss:Type=\"String\"></Data></Cell>";
    }
    //==========================================================
  
 $cells .= "\n\r<Row ss:Index=\"".($count_rows)."\">
		<Cell ss:StyleID=\"s21\" ss:Index=\"1\"><Data ss:Type=\"Number\">".($count_filds)."</Data></Cell>\n\r
		<Cell ss:StyleID=\"s21\" ss:Index=\"2\"><Data ss:Type=\"String\">".str_replace($find," ",mysql_result($ver,$count,"tovar_artkl"))."</Data></Cell>\n\r
		<Cell ss:StyleID=\"s21\" ss:Index=\"3\"><Data ss:Type=\"String\">".str_replace($find," ",mysql_result($ver,$count,"tovar_name_1"))."</Data></Cell>\n\r
		<Cell ss:StyleID=\"s21\" ss:Index=\"4\"><Data ss:Type=\"Number\">".number_format(mysql_result($ver,$count,"price_tovar_1"),2,'.','')."</Data></Cell>\n\r
		<Cell ss:StyleID=\"s21\" ss:Index=\"5\"><Data ss:Type=\"String\">".$m_curr_name[mysql_result($ver,$count,"price_tovar_curr_1")]."</Data></Cell>\n\r
		<Cell ss:StyleID=\"s24\" ss:Index=\"6\"><Data ss:Type=\"Number\">".$item."</Data></Cell>\n\r
		<Cell ss:StyleID=\"s21\" ss:Index=\"7\"><Data ss:Type=\"Number\">".number_format(mysql_result($ver,$count,"operation_detail_price"),2,'.','')."</Data></Cell>\n\r
		<Cell ss:StyleID=\"s21\" ss:Index=\"8\"><Data ss:Type=\"Number\">".number_format($coef,2,'.','')."</Data></Cell>\n\r
		<Cell ss:StyleID=\"s22\" ss:Index=\"9\"><Data ss:Type=\"Number\">".number_format(mysql_result($ver,$count,"operation_detail_summ"),2,'.','')."</Data></Cell>\n\r
		<Cell ss:StyleID=\"s21\" ss:Index=\"10\"><Data ss:Type=\"Number\">".number_format((mysql_result($ver,$count,"operation_detail_summ") - ($zakup_uah*$item)),2,'.','')."</Data></Cell>\n\r
		<Cell ss:StyleID=\"s22\" ss:Index=\"11\"><Data ss:Type=\"Number\">".$procent."</Data></Cell>\n\r
		<Cell ss:StyleID=\"s21\" ss:Index=\"12\"
		    ss:Formula=\"=RC[-3]/100*RC[-1]\"><Data ss:Type=\"Number\">".number_format($coef,2,'.','')."</Data></Cell>\n\r
		$summ
		
	  </Row>\n\r\n\r\n\r";

$count_filds++;
$count_rows++;
$count++;
}
  
  
 $cells = str_replace("&summ_sale",number_format($summ_sale,0,'.',''),$cells);
 $cells = str_replace("&summ_zakup",number_format($summ_zakup,0,'.',''),$cells);
 $cells = str_replace("&summ_dohid",number_format(($summ_sale-$summ_zakup),0,'.','')."(".number_format(($summ_sale/$summ_zakup),2,'.','').")",$cells);
 $cells = str_replace("&summ_z-ta",number_format(($summ_sale/100*$procent),2,'.',''),$cells);

 $excel = str_replace("&cells",$cells,$excel);
 $excel = str_replace("&rows",$count_rows+10,$excel);


//=======================================================================

if($klient_id>0){
    $file_name = "tmp/an_kl_".mysql_result($ver,0,"klienti_name_1").".xls";
}elseif($klient_group<>4){
    $file_name = "tmp/an_gr_".mysql_result($ver,0,"klienti_group_name").".xls";
}
//=======================================================================
 
 $fp = fopen($file_name,"w");
 fwrite($fp,$excel);
 fclose($fp);

 echo $file_name;
*/



?>
