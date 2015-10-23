<?php

include 'init.lib.php';
include 'nakl.lib.php';

connect_to_mysql();
session_start();


require("JsHttpRequest.php");
$JsHttpRequest=new JsHttpRequest("utf8");//"windows-1251");
echo "******";

$count = 0;
$iKey_add = "";
if(isset($_REQUEST["_add"])) $iKey_add=$_REQUEST["_add"];

$iKey_save = "";
if(isset($_REQUEST["_save"])) $iKey_save=$_REQUEST["_save"];

$iKey_dell = "";
if(isset($_REQUEST["_dell"])) $iKey_dell=$_REQUEST["_dell"];

$iKey_return = "";
if(isset($_REQUEST["_return"])) $iKey_return=$_REQUEST["_return"];

if(!isset($_REQUEST['edit'])){
    $page_to_return = $_REQUEST["_page_to_return"];

    $table_name = $_REQUEST["_table_name"];
    $id_name = $_REQUEST["_id_name"];
    $id_operation = $_REQUEST["_id_value"];
    $result_string="";
}

$s_name="";
$s_value="";
$s_vhere="";
$s_sql_string="";
$s_sql_string_where = "";
$find_id="";


$ver = mysql_query("SET NAMES utf8");
$old_result = mysql_query("SET NAMES utf8");
$new_result = mysql_query("SET NAMES utf8");

if ($iKey_dell == "dell" and $find_id != -1){
$old_result = mysql_query("SELECT `operation_detail_item`, `operation_detail_tovar`,`operation_detail_from`,`operation_detail_to` FROM `tbl_operation_detail` WHERE  `operation_detail_dell`='0' and `operation_detail_operation`='" . $id_operation . "'");
}
if ($iKey_save == "save" and $find_id != -1){
$old_result = mysql_query("SELECT `operation_detail_item`, `operation_detail_tovar`,`operation_detail_from`,`operation_detail_to` FROM `tbl_operation_detail` WHERE  `operation_detail_dell`='0' and `operation_detail_operation`='" . $id_operation . "'");
}


$empty = $post = array();
$find_id=-1;
$count=0;
$tmp=0;
//print_r ($_REQUEST);

foreach ($_POST as $varname => $varvalue){
    if(substr($varname,0,1) != "_"){
    $post[$varname] = $varvalue;

     $tmp++;
    
    list ($field_name,$detail_id) = explode("*",$varname);
if ($find_id ==$detail_id){
 	$s_value .=  "'" . $post[$varname] . "',"; 
	$s_name  .=  "`" .  $field_name . "`,";
}else{      
//=========================CODE START==================================      
//=====================================================================      
$s_name = substr($s_name,0, strlen($s_name)-1);
$s_value = substr($s_value,0, strlen($s_value)-1);
/*
$s_sql_string = "INSERT INTO `" . $table_name . "`(" . $s_name . ") VALUES (" . $s_value . ")";*/
$s_sql_string_where = " WHERE `operation_detail_id`='" . $find_id . "'";
//echo $tmp,"- ",$s_name," --- ",$s_value,"<br>";


if ($iKey_dell == "dell" and $find_id != -1) //============DELETE======
{
echo "dell";
      $ver = mysql_query("UPDATE `" . $table_name . "` SET `operation_detail_dell`='1'" . $s_sql_string_where);
      $result_string = "<br><br>Nom -> " . $id_value . " - DELETED OK";  
}

if ($iKey_save == "save" and $find_id != -1) //===========SAVE=========
{
    $ver = mysql_query("UPDATE `" . $table_name . "` SET `operation_detail_dell`='1'" . $s_sql_string_where);
    if($_POST["operation_detail_item*".$find_id]!='0' and $_POST["operation_detail_item*".$find_id]!=''){
      $s_sql_string = "INSERT INTO `" . $table_name . "`(" . $s_name . ") VALUES (" . $s_value . ")";
      $ver = mysql_query("SET NAMES utf8");
      $ver = mysql_query($s_sql_string);
      $result_string = "Nom -> " . mysql_insert_id() . " - SAVE OK <br>";
    }
}
if ($iKey_add == " + " and $find_id != -1) //=============ADD==========
{
   if($_POST["operation_detail_item*".$find_id]!='0' and $_POST["operation_detail_item*".$find_id]!='' ){
      $s_sql_string = "INSERT INTO `" . $table_name . "`(" . $s_name . ",`operation_detail_zakup`) 
						  VALUES (" . $s_value . ",(SELECT  (`currency_ex`*`price_tovar_1`)
							FROM `tbl_currency`,`tbl_price_tovar`
							WHERE 
							  `price_tovar_id`='$find_id' and 
							  `price_tovar_curr_1`=`currency_id`))";
      $ver = mysql_query("SET NAMES utf8");
      $ver = mysql_query($s_sql_string);
   // echo $s_sql_string,"<br>";
    
      $result_string = "Nom -> ". $tmp. " - " . mysql_insert_id() . " - ADDED OK <br>";
      
      reset_warehouse_on_tovar_from_to($_POST["operation_detail_tovar*".$find_id],$_POST["operation_detail_from*".$find_id],$_POST["operation_detail_to*".$find_id]);//tovar,from,to
      
    }
}
if (isset($_REQUEST['edit'])) //=============ADD==========
{
echo "ok";
   /*if($_POST["operation_detail_item*".$find_id]!='0' and $_POST["operation_detail_item*".$find_id]!='' ){
      $s_sql_string = "INSERT INTO `" . $table_name . "`(" . $s_name . ") VALUES (" . $s_value . ")";
      $ver = mysql_query("SET NAMES utf8");
      $ver = mysql_query($s_sql_string);
    
    
      $result_string = "Nom -> ". $tmp. " - " . mysql_insert_id() . " - ADDED OK <br>";
      
      reset_warehouse_on_tovar_from_to($_POST["operation_detail_tovar*".$find_id],$_POST["operation_detail_from*".$find_id],$_POST["operation_detail_to*".$find_id]);//tovar,from,to
      
    }*/
}
echo "key ",$iKey_add,"<br>";
echo "<title>" . $result_string . "</title>";
echo "<body>" . $result_string . "</body>";
//=====================================================================      
//=============================END CODE================================      
	$s_value =  "'" . $post[$varname] . "',";
	$s_name  =  "`" .  $field_name . "`,";
	$find_id =$detail_id;
}
    
   }
}
$sql_str = "SELECT SUM(`operation_detail_summ`) as op_sum FROM `tbl_operation_detail` WHERE `operation_detail_dell`='0' and `operation_detail_operation`='".$id_operation."'";
$ver = mysql_query($sql_str);

$in_out_str = "SELECT 
		`operation_status_debet` 
	      FROM `tbl_operation_status` 
	      WHERE 
		`operation_status_id`=
		  (SELECT `operation_status` 
		    FROM `tbl_operation` 
		    WHERE `operation_id`='".$id_operation."')";
$in_out = mysql_query($in_out_str);
echo $in_out_str;

if(mysql_result($in_out,0,0)==1) //if debet
{
  $sql_str_upd = "UPDATE `tbl_operation` SET `operation_save`='0', `operation_sotrudnik`='".$_SESSION[BASE.'userid']."',`operation_summ`='-".mysql_result($ver,0,0)."',`operation_data_edit`='".date("Y-m-d G:i:s")."' WHERE `operation_id`='".$id_operation."'";
}else{
  $sql_str_upd = "UPDATE `tbl_operation` SET `operation_save`='0', `operation_sotrudnik`='".$_SESSION[BASE.'userid']."',`operation_summ`='".mysql_result($ver,0,0)."',`operation_data_edit`='".date("Y-m-d G:i:s")."' WHERE `operation_id`='".$id_operation."'";
}
$oper = mysql_query($sql_str_upd);


echo $iKey_save,$find_id;
if ($iKey_save == "save"){ //===========SAVE=========
$new_result = mysql_query("SELECT `operation_detail_item`, `operation_detail_tovar`,`operation_detail_from`,`operation_detail_to` FROM `tbl_operation_detail` WHERE  `operation_detail_dell`='0' and `operation_detail_operation`='" . $id_operation . "'");
  reset_warehouse_on_query_result($old_result);
  reset_warehouse_on_query_result($new_result);
}
if ($iKey_dell == "dell" and $find_id != -1){
  reset_warehouse_on_query_result($old_result);
}

echo "<br>",$page_to_return;
if(!isset($_REQUEST['edit'])) header ('Refresh: 0; url=' . $page_to_return );     
?>
