<?php
include 'init.lib.php';
connect_to_mysql();
session_start();
if (!session_verify($_SERVER["PHP_SELF"],"none")){
  exit();
}
if (strpos($_SESSION[BASE.'usersetup'],"analitics")>0){
}else{
  echo "null";
  exit();
}
require("JsHttpRequest.php");
$JsHttpRequest=new JsHttpRequest("utf8");//"windows-1251");

$klienti_sort = "";
$klient_group = 4;
if(isset($_REQUEST["group"])) $klient_group=(int)mysql_real_escape_string($_REQUEST["group"]);
//if($klient_group != 4) $klienti_sort = " and `klienti_group` = '$klient_group' ";
    
$klient_id = 0;
if(isset($_REQUEST["klient"])) $klient_id=(int)mysql_real_escape_string($_REQUEST["klient"]);
if($klient_group > 0 ) $klienti_sort .= " and `klienti_id` = '$klient_id' ";

$klient_start = date("Y-m-d G:i:s");
if(isset($_REQUEST["start"])) $klient_start=mysql_real_escape_string($_REQUEST["start"]);
$klient_end = date("Y-m-d G:i:s");
if(isset($_REQUEST["end"])) $klient_end=mysql_real_escape_string($_REQUEST["end"]);
$procent = 5;
if(isset($_REQUEST["procent"])) $procent=(int)mysql_real_escape_string($_REQUEST["procent"]);
$klient_status = 0;
if(isset($_REQUEST["status"])) $klient_status=(int)mysql_real_escape_string($_REQUEST["status"]);

//echo $_REQUEST["status"];
/*if (!session_verify("get_excel_analitics.php?group=$klient_group&klient=$klient_id&start=$klient_start&end=$klient_end&procent=$procent&status=$klient_status","none")){
  exit();
}*/


//==================================SETUP===========================================
if ($_SESSION[BASE.'lang'] <1){
  $_SESSION[BASE.'lang']=1;
}
$setup = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	  `setup_menu_name`, 
	  `setup_menu_".$_SESSION[BASE.'lang']."`
	  FROM `tbl_setup_menu`
";
//echo $tQuery;
$setup = mysql_query($tQuery);
$m_setup = array();
$count=0;
while ($count<mysql_num_rows($setup)){
 $m_setup[mysql_result($setup,$count,0)]=mysql_result($setup,$count,1);
 $count++;
}

//=======================================================================
$setup = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	  *
	  FROM `tbl_currency`
";
//echo $tQuery;
$setup = mysql_query($tQuery);
$m_curr_ex = array();
$m_curr_name = array();
$curr_list="курси: ";
$count=0;
while ($count<mysql_num_rows($setup)){
 $m_curr_ex[mysql_result($setup,$count,"currency_id")]=mysql_result($setup,$count,"currency_ex");
 $m_curr_name[mysql_result($setup,$count,"currency_id")]=mysql_result($setup,$count,"currency_name_shot");
 $curr_list .= $m_curr_name[mysql_result($setup,$count,"currency_id")]." ".mysql_result($setup,$count,"currency_ex")." , ";
 $count++;
}
//=======================================================================
if($klient_id>0){
  $klienti_sort = " and `klienti_id`='$klient_id'
	  ";
}elseif($klient_group<>4){
  $klienti_sort = " and `klienti_group`='$klient_group'
	  ";
}else{
  exit();
}

$status_sort="";
if($klient_status>0){
  $status_sort = " and `operation_status`='$klient_status' ";
}
//========================================================================
$ver = mysql_query("SET NAMES utf8");
      $tQuery = "SELECT *
	  FROM `tbl_tovar`,
		`tbl_operation_detail`,
		`tbl_klienti`,
		`tbl_price_tovar`,
		`tbl_operation`,
		`tbl_klienti_group`,
		`tbl_operation_status`
	  WHERE `tovar_id`=`operation_detail_tovar` and
		`operation_detail_dell`='0' and
		`klienti_id`=`operation_klient` and
		`operation_detail_operation`=`operation_id` and
		`tovar_id`=`price_tovar_id` and
		`operation_status`=`operation_status_id` and
		`klienti_group`=`klienti_group_id` and
		`operation_data`>'$klient_start' and
		`operation_data`<'$klient_end'
		$klienti_sort $status_sort
	  ORDER BY `operation_id` ASC, `operation_detail_id` ASC
	  ";
      $ver = mysql_query($tQuery);

      
 // echo $tQuery;    
if(mysql_num_rows($ver) === 0){ 
      echo "null";
      exit();
      }
$count_rows = 8; 
$temp = "template/excel_analitics.xml";
$excel = file_get_contents($temp);  
$cells = "";



$cells .= "<Row ss:Index=\"1\">
		<Cell ss:Index=\"3\" ss:StyleID=\"s23\"><Data ss:Type=\"String\"></Data></Cell>
		<Cell ss:Index=\"4\" ss:MergeAcross=\"2\" ss:StyleID=\"s23\"><Data ss:Type=\"String\">Вхiднi данi</Data></Cell>
		<Cell ss:Index=\"8\" ss:MergeAcross=\"2\" ss:StyleID=\"s23\"><Data ss:Type=\"String\">Зкореговано</Data></Cell>";
	//$cells .= "<Cell ss:StyleID=\"s23\" ss:Index=\"14\"><Data ss:Type=\"String\">ST-23</Data></Cell>";
	  $cells .= "</Row>";

$cells .= "<Row ss:Index=\"2\">
		<Cell ss:Index=\"3\" ss:StyleID=\"s23\"><Data ss:Type=\"String\">Закуп:</Data></Cell>
		<Cell ss:Index=\"4\" ss:MergeAcross=\"2\" ss:StyleID=\"s23\"><Data ss:Type=\"Number\">&summ_zakup</Data></Cell>
		<Cell ss:Index=\"7\" ss:StyleID=\"s23\"><Data ss:Type=\"String\">грн</Data></Cell>";
	//$cells .= "<Cell ss:StyleID=\"s22\" ss:Index=\"14\"><Data ss:Type=\"String\">ST-22</Data></Cell>";
	  $cells .= "</Row>";

$cells .= "<Row ss:Index=\"3\">
		<Cell ss:Index=\"3\" ss:StyleID=\"s23\"><Data ss:Type=\"String\">Продано:</Data></Cell>
		<Cell ss:Index=\"4\" ss:MergeAcross=\"2\" ss:StyleID=\"s23\"><Data ss:Type=\"Number\">&summ_sale</Data></Cell>
		<Cell ss:Index=\"7\" ss:StyleID=\"s23\"><Data ss:Type=\"String\">грн</Data></Cell>";
	//$cells .= "<Cell ss:StyleID=\"s23\" ss:Index=\"14\"><Data ss:Type=\"String\">ST-23</Data></Cell>";
	 $cells .= "</Row>";
$cells .= "<Row ss:Index=\"4\">
		<Cell ss:Index=\"3\" ss:StyleID=\"s23\"><Data ss:Type=\"String\">Дохiд-Коефiцiент:</Data></Cell>
		<Cell ss:Index=\"4\" ss:MergeAcross=\"2\" ss:StyleID=\"s23\"><Data ss:Type=\"String\">&summ_dohid</Data></Cell>
		<Cell ss:Index=\"7\" ss:StyleID=\"s23\"><Data ss:Type=\"String\"></Data></Cell>";
	// $cells .= "<Cell ss:StyleID=\"s24\" ss:Index=\"14\"><Data ss:Type=\"String\">ST-24</Data></Cell>";
	  $cells .= "</Row>";
$cells .= "<Row ss:Index=\"5\">
		<Cell ss:Index=\"3\" ss:StyleID=\"s23\"><Data ss:Type=\"String\">Процент з-ти:</Data></Cell>
		<Cell ss:Index=\"4\" ss:MergeAcross=\"2\" ss:StyleID=\"s23\"><Data ss:Type=\"Number\">$procent</Data></Cell>
		<Cell ss:Index=\"7\" ss:StyleID=\"s23\"><Data ss:Type=\"String\">%</Data></Cell>";
	//$cells .= "<Cell ss:StyleID=\"s23\" ss:Index=\"14\"><Data ss:Type=\"String\">ST-23</Data></Cell>";
	  $cells .= "</Row>";

$cells .= "<Row ss:Index=\"6\">
		<Cell ss:Index=\"3\" ss:StyleID=\"s23\"><Data ss:Type=\"String\">Нараховано з-ти:</Data></Cell>
		<Cell ss:Index=\"4\" ss:MergeAcross=\"2\" ss:StyleID=\"s23\"><Data ss:Type=\"Number\">&summ_z-ta</Data></Cell>
		<Cell ss:Index=\"7\" ss:StyleID=\"s23\"><Data ss:Type=\"String\">грн</Data></Cell>
		<Cell ss:Index=\"8\" ss:Formula=\"=SUM(R[4]C[4]:R[&rows]C[4])\"  ss:MergeAcross=\"2\" ss:StyleID=\"s23\"><Data ss:Type=\"Number\"></Data></Cell>";
	//$cells .= "<Cell ss:StyleID=\"s23\" ss:Index=\"14\"><Data ss:Type=\"String\">ST-23</Data></Cell>";
	  $cells .= "</Row>";
$cells .= "<Row ss:Index=\"7\">
		<Cell ss:StyleID=\"s23\" ss:Index=\"2\"><Data ss:Type=\"String\">".$curr_list."</Data></Cell>";
	//$cells .= "<Cell ss:StyleID=\"s21\" ss:Index=\"14\"><Data ss:Type=\"String\">ST-21</Data></Cell>";
	  $cells .= "</Row>";


	  $find =array("<",
		      ">"
		      );	
	  //$memo = str_replace($find," ",$memo);
$tmp_operation = -1;	  
$count = 0;
$count_filds = 1;
$summ_zakup = 0;
$summ_sale = 0;
while($count < mysql_num_rows($ver)){
$newDate = date("Y-m-d",strtotime(mysql_result($ver,$count,"operation_data")));
//$delive = explode( ,mysql_result($ver,$count,"operation_memo"));
if($tmp_operation <> mysql_result($ver,$count,"operation_id")){
 $count_rows++;
    $cells .= "\n\r<Row ss:Index=\"".($count_rows)."\">
		<Cell ss:StyleID=\"s23\" ss:Index=\"1\">
				    <Data ss:Type=\"String\">#".mysql_result($ver,$count,"operation_id").
							    " (".$newDate.")".
							    " ".mysql_result($ver,$count,"klienti_name_1").
							    " - ".mysql_result($ver,$count,"operation_status_name").
							"</Data></Cell>\n\r

		</Row>\n\r\n\r\n\r";
$count_rows++;
$cells .= "<Row ss:Index=\"".($count_rows)."\">
		<Cell ss:StyleID=\"s24\" ss:Index=\"1\"><Data ss:Type=\"String\">N</Data></Cell>
		<Cell ss:StyleID=\"s24\" ss:Index=\"2\"><Data ss:Type=\"String\">арт</Data></Cell>
		<Cell ss:StyleID=\"s24\" ss:Index=\"3\"><Data ss:Type=\"String\">назва</Data></Cell>
		<Cell ss:StyleID=\"s24\" ss:Index=\"4\"><Data ss:Type=\"String\">закуп</Data></Cell>
		<Cell ss:StyleID=\"s24\" ss:Index=\"5\"><Data ss:Type=\"String\"></Data></Cell>
		<Cell ss:StyleID=\"s24\" ss:Index=\"6\"><Data ss:Type=\"String\">к-ть</Data></Cell>
		<Cell ss:StyleID=\"s24\" ss:Index=\"7\"><Data ss:Type=\"String\">цiна</Data></Cell>
		<Cell ss:StyleID=\"s24\" ss:Index=\"8\"><Data ss:Type=\"String\">кф</Data></Cell>
		<Cell ss:StyleID=\"s24\" ss:Index=\"9\"><Data ss:Type=\"String\">продж</Data></Cell>
		<Cell ss:StyleID=\"s24\" ss:Index=\"10\"><Data ss:Type=\"String\">дохiд</Data></Cell>
		<Cell ss:StyleID=\"s24\" ss:Index=\"11\"><Data ss:Type=\"String\">%</Data></Cell>
		<Cell ss:StyleID=\"s24\" ss:Index=\"12\"><Data ss:Type=\"String\">з-та</Data></Cell>
		
	  </Row>";
    $tmp_operation = mysql_result($ver,$count,"operation_id");
  $count_filds=1;
  $count_rows++;
}
//number_format($price2,2,'.','');
  $zakup_uah = mysql_result($ver,$count,"operation_detail_zakup");
  $color = "";
  if($zakup_uah == 0){
      $zakup_uah = mysql_result($ver,$count,"price_tovar_1")*$m_curr_ex[mysql_result($ver,$count,"price_tovar_curr_1")];
      if($zakup_uah==0) $zakup_uah=1;
      $color = "1";
  }
  
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
		<Cell ss:StyleID=\"s21\" ss:Index=\"4\"><Data ss:Type=\"Number\">";
			      if($color == "1"){
				  $cells .= number_format(mysql_result($ver,$count,"price_tovar_1"),2,'.','');
			      }else{
				  $cells .= $zakup_uah;
			      }
$cells .= "</Data></Cell>\n\r
		<Cell ss:StyleID=\"s21\" ss:Index=\"5\"><Data ss:Type=\"String\">";
		if($color == "1"){
				  $cells .= $m_curr_name[mysql_result($ver,$count,"price_tovar_curr_1")];
			      }else{
				  $cells .= "*";
			      }
		
$cells .= "</Data></Cell>
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




?>
