<?php
include 'init.lib.php';
connect_to_mysql();
session_start();

require("JsHttpRequest.php");
$JsHttpRequest=new JsHttpRequest("utf8");//"windows-1251");
session_verify($_SERVER["PHP_SELF"],"none");
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

//echo 
$user_id = $_REQUEST['_user'];
$user_name = "";
$habib_id = $_REQUEST['_set'];
$habib_name = "";

$user = mysql_query("SET NAMES utf8");
$tQuery = "SELECT `klienti_name_1`
	  FROM `tbl_klienti`
	  WHERE `klienti_id`='$user_id'
	  ";
$user = mysql_query($tQuery);
$user_name = mysql_result($user,0,"klienti_name_1");

if($habib_id <0){
      $habib = mysql_query("SET NAMES utf8");
      $tQuery = "SELECT *
	  FROM `tbl_habibulin_parent`
	  ORDER BY `habibulin_parent_id DESC
	  LIMIT 0,1
	  ";
      $habib = mysql_query($tQuery);
}else{
      $habib = mysql_query("SET NAMES utf8");
      $tQuery = "SELECT *
	  FROM `tbl_habibulin_parent`
	  WHERE `habibulin_parent_id`='$habib_id'
	  ";
      $habib = mysql_query($tQuery);
}
$habib_id = mysql_result($habib,0,"habibulin_parent_id");
$habib_name = mysql_result($habib,0,"habibulin_parent_name");



$file_name = "tmp/".$habib_name." - ".$user_id."_".$habib_id.".xls";

      $ver = mysql_query("SET NAMES utf8");
      $tQuery = "SELECT *
	  FROM `tbl_habibulin`,
		`tbl_operation`
	  WHERE `operation_id`=`habibulin_operation` and
		`habibulin_user`='$user_id' and
		`habibulin_parent`='$habib_id'
	  ORDER BY `habibulin_id` ASC 
	  ";
      $ver = mysql_query($tQuery);

      
//  echo "ok";    

$temp = "template/excel.xml";
$excel = file_get_contents($temp);  

$cells = "<Row ss:Index=\"1\">
		<Cell ss:Index=\"1\"><Data ss:Type=\"String\">Sum:</Data></Cell>
		<Cell ss:Index=\"2\"><Data ss:Type=\"String\">&summ</Data></Cell>
		<Cell ss:Index=\"4\"><Data ss:Type=\"String\">".$user_name."</Data></Cell>
	  </Row>";
$cells .= "<Row ss:Index=\"2\">
		<Cell ss:Index=\"1\"><Data ss:Type=\"String\">%:</Data></Cell>
		<Cell ss:Index=\"2\"><Data ss:Type=\"String\">&summ_hab</Data></Cell>
		<Cell ss:Index=\"4\"><Data ss:Type=\"String\">".$habib_name."</Data></Cell>
	  </Row>";

$cells .= "<Row ss:Index=\"4\">
		<Cell ss:StyleID=\"s24\" ss:Index=\"1\"><Data ss:Type=\"String\">N</Data></Cell>
		<Cell ss:StyleID=\"s24\" ss:Index=\"2\"><Data ss:Type=\"String\">data</Data></Cell>
		<Cell ss:StyleID=\"s24\" ss:Index=\"3\"><Data ss:Type=\"String\">nakl</Data></Cell>
		<Cell ss:StyleID=\"s24\" ss:Index=\"4\"><Data ss:Type=\"String\">summ</Data></Cell>
		<Cell ss:StyleID=\"s24\" ss:Index=\"5\"><Data ss:Type=\"String\">%</Data></Cell>
		<Cell ss:StyleID=\"s24\" ss:Index=\"6\"><Data ss:Type=\"String\">Bank</Data></Cell>
		
	  </Row>";


$count = 0;
$Sum = 0;
$Sum_p = 0;
while($count < mysql_num_rows($ver)){
$newDate = date("Y-m-d",strtotime(mysql_result($ver,$count,"operation_data_edit")));
//$delive = explode( ,mysql_result($ver,$count,"operation_memo"));
 $cells .= "<Row ss:Index=\"".($count+5)."\">
		<Cell ss:StyleID=\"s21\" ss:Index=\"1\"><Data ss:Type=\"Number\">".($count+1)."</Data></Cell>
		<Cell ss:StyleID=\"s21\" ss:Index=\"2\"><Data ss:Type=\"String\">".$newDate."</Data></Cell>
		<Cell ss:StyleID=\"s24\" ss:Index=\"3\"><Data ss:Type=\"String\">".mysql_result($ver,$count,"habibulin_operation")."</Data></Cell>
		<Cell ss:StyleID=\"s21\" ss:Index=\"4\"><Data ss:Type=\"Number\">".mysql_result($ver,$count,"habibulin_money")."</Data></Cell>
		<Cell ss:StyleID=\"s21\" ss:Index=\"5\"><Data ss:Type=\"Number\">".mysql_result($ver,$count,"habibulin_user_usd")."</Data></Cell>
		<Cell ss:StyleID=\"s21\" ss:Index=\"6\"><Data ss:Type=\"String\">".mysql_result($ver,$count,"habibulin_operation_description")."</Data></Cell>
		<Cell ss:StyleID=\"s21\" ss:Index=\"7\"><Data ss:Type=\"String\">".mysql_result($ver,$count,"operation_memo")."</Data></Cell>
		<Cell ss:StyleID=\"s21\" ss:Index=\"8\"><Data ss:Type=\"String\">".mysql_result($ver,$count,"habibulin_description")."</Data></Cell>
		
	  </Row>";

$Sum +=mysql_result($ver,$count,"habibulin_money");
$Sum_p += (mysql_result($ver,$count,"habibulin_money")/100*mysql_result($ver,$count,"habibulin_user_usd"));

$count++;
}
  $cells = str_replace("&summ_hab",$Sum_p,$cells);
  $cells = str_replace("&summ",$Sum,$cells);
 
 $excel = str_replace("&rows",$count+10,$excel);
 $excel = str_replace("&cells",$cells,$excel);
 
 $fp = fopen($file_name,"w");
 fwrite($fp,$excel);
 fclose($fp);
 
 echo $file_name;

  /*
//==================================SETUP=MENU==========================================
//==================================MAIL===========================================

    
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
			`price_tovar_".$m_setup['web default price']."` as price1,
			`price_tovar_".$_SESSION[BASE.'userprice']."` as price2,
			`price_tovar_curr_".$m_setup['web default price']."` as curr1,
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
		<Cell ss:Index=\"2\" ss:StyleID=\"s21\"><Data ss:Type=\"String\">".$m_setup['menu nakl']." # $operation_id</Data></Cell>
		<Cell ss:Index=\"3\" ss:StyleID=\"s21\"><Data ss:Type=\"String\">".mysql_result($ver,0,"klienti_name_".$_SESSION[BASE.'lang']).
				    "(".mysql_result($ver,0,"operation_status_name").")</Data></Cell>
		<Cell ss:Index=\"4\" ss:StyleID=\"s21\"><Data ss:Type=\"String\">".$m_setup['menu summ'].":</Data></Cell>
		<Cell ss:Index=\"5\" ss:StyleID=\"s21\"/>
		<Cell ss:Index=\"6\" ss:StyleID=\"s21\"/>
		<Cell ss:Index=\"7\" ss:StyleID=\"s21\"><Data ss:Type=\"Number\">".mysql_result($ver,0,"operation_summ")."</Data></Cell>
	    </Row>";
$cells .= "<Row ss:Index=\"5\">
		<Cell ss:Index=\"2\"><Data ss:Type=\"String\">".$m_setup['menu memo']."</Data></Cell>
		<Cell ss:Index=\"3\"><Data ss:Type=\"String\">".mysql_result($ver,0,"operation_memo")."</Data></Cell>
	  </Row>";


$cells .= "<Row ss:Index=\"7\" ss:StyleID=\"s24\">
		<Cell ss:Index=\"1\"><Data ss:Type=\"String\">#</Data></Cell>
		<Cell ss:Index=\"2\"><Data ss:Type=\"String\">".$m_setup['menu artkl']."</Data></Cell>
		<Cell ss:Index=\"3\"><Data ss:Type=\"String\">".$m_setup['menu name1']."</Data></Cell>
		<Cell ss:Index=\"4\"><Data ss:Type=\"String\">".$m_setup['print price']."</Data></Cell>
		<Cell ss:Index=\"5\"><Data ss:Type=\"String\">".$m_setup['print items']."</Data></Cell>
		<Cell ss:Index=\"6\"><Data ss:Type=\"String\">".$m_setup['print discount']."</Data></Cell>
		<Cell ss:Index=\"7\"><Data ss:Type=\"String\">".$m_setup['menu summ']."</Data></Cell>
	  </Row>";

 $count=0;
 while($count < mysql_num_rows($ver)){

 $cells .= "<Row ss:Index=\"".($count+8)."\">
		<Cell ss:Index=\"1\"><Data ss:Type=\"Number\">".($count+1)."</Data></Cell>
		<Cell ss:Index=\"2\"><Data ss:Type=\"String\">".mysql_result($ver,$count,"tovar_artkl")."</Data></Cell>";
		$tovar_name = mysql_result($ver,$count,"tovar_name_".$_SESSION[BASE.'lang']);
		$tovar_name = explode($m_setup['tovar name sep'], $tovar_name);
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

*/
?>
