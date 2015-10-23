<?php
include 'admin/init.lib.php';
include 'init.lib.user.tovar.php';
session_start();
connect_to_mysql();
require("JsHttpRequest.php");
$JsHttpRequest=new JsHttpRequest("utf8");//"windows-1251");

header ('Content-Type: text/html; charset=utf8');
   $ver = mysql_query("SET NAMES utf8");
    
$date = date("Y-m-d");
$file_name = "tmp/excel".$date.".xls";
$temp = "template/excel_price.xml";
$excel = file_get_contents($temp);
$excel = str_replace("&list name","price $date",$excel);

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
			ORDER BY  `tovar_name_1` ASC,
				  `tovar_artkl` ASC
				
			";	  
	  $ver = mysql_query($SgetName);
	 // exit();
    }else{
	echo "NOT ACCESS!";
	exit();
    }

$cells = "<Row ss:StyleID=\"s21\">
		<Cell ss:Index=\"1\"><Data ss:Type=\"String\">#</Data></Cell>
		<Cell ss:Index=\"2\"><Data ss:Type=\"String\">".$setup['menu artkl']."</Data></Cell>
		<Cell ss:Index=\"3\"><Data ss:Type=\"String\">".$setup['menu name1']."</Data></Cell>
		<Cell ss:Index=\"4\"><Data ss:Type=\"String\">".$setup['print price']."</Data></Cell>
		<Cell ss:Index=\"5\"><Data ss:Type=\"String\"></Data></Cell>
		<Cell ss:Index=\"6\"><Data ss:Type=\"String\"></Data></Cell>
		<Cell ss:Index=\"7\"><Data ss:Type=\"String\">web</Data></Cell>
	  </Row>";

 $count=0;
 while($count < mysql_num_rows($ver)){
 $war_sum = tovar_on_ware(mysql_result($ver,$count,"tovar_id"));
 
 if($war_sum>7){
    $war_sum = $setup['menu mnogo'];
 }elseif($war_sum<1){
    $war_sum = $setup['menu none'];
 }
 
 $cells .= "
	   <Row>
		<Cell ss:Index=\"1\"><Data ss:Type=\"Number\">".mysql_result($ver,$count,"tovar_id")."</Data></Cell>
		<Cell ss:Index=\"2\"><Data ss:Type=\"String\">".mysql_result($ver,$count,"tovar_artkl")."</Data></Cell>";
		$tovar_name = mysql_result($ver,$count,"tovar_name");
		$tovar_name = explode($setup['tovar name sep'], $tovar_name);
		$tovar_name[0] = str_replace("<","-",$tovar_name[0]);
 		$tovar_name[0] = str_replace(">","-",$tovar_name[0]);
 $cells .= 	"
		<Cell ss:Index=\"3\"><Data ss:Type=\"String\">".$tovar_name[0]."</Data></Cell>
		<Cell ss:Index=\"4\"><Data ss:Type=\"Number\">".mysql_result($ver,$count,"price1")."</Data></Cell>
		<Cell ss:Index=\"5\" ss:StyleID=\"s24\"><Data ss:Type=\"Number\"></Data></Cell>
		<Cell ss:Index=\"6\" ss:StyleID=\"s24\"><Data ss:Type=\"String\">".$war_sum."</Data></Cell>
		<Cell ss:Index=\"7\"><Data ss:Type=\"String\">http://sturm.com.ua/index.php?tovar=".mysql_result($ver,$count,"tovar_id")."</Data></Cell>
	  </Row>";
 
 $count++;
 }
 
 $excel = str_replace("&rows",$count+3,$excel);
 $excel = str_replace("&cells",$cells,$excel);
 
 $fp = fopen($file_name,"w");
 fwrite($fp,$excel);
 fclose($fp);
 
 echo $file_name;

?>
