<?php

include 'init.lib.php';
include 'nakl.lib.php';
//include '../init.lib.user.tovar.php';
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
 $tmp = mysql_query("SET NAMES utf8");
 $tQuery = "SELECT `operation_status_from_as_new`,`operation_status_to_as_new` FROM `tbl_operation_status` WHERE `operation_status_id`='".$m_setup['shop default status']."'";
 $tmp = mysql_query($tQuery);
 $move_from = mysql_result($tmp,0,"operation_status_from_as_new"); 
 $move_to = mysql_result($tmp,0,"operation_status_to_as_new"); 
//==================================SETUP=CURR==========================================
$money_rabat = 0;
if(isset($_REQUEST["rabat"])){
    $money_rabat=(int)$_REQUEST["rabat"];
  }
$money_comm = 0;
if(isset($_REQUEST["comm"])){
    $money_comm=(string)$_REQUEST["comm"];
  }
$money_summ = 0;
if(isset($_REQUEST["summ"])){
    $money_summ=(int)$_REQUEST["summ"];
  }
$money_dano = 0;
if(isset($_REQUEST["dano"])){
    $money_dano=(int)$_REQUEST["dano"];
  }
$money_dano = 0;
if(isset($_REQUEST["dano"])){
    $money_dano=(int)$_REQUEST["dano"];
  }
$money_oper_summ = 0;
if(isset($_REQUEST["oper"])){
    $money_oper_summ=(int)$_REQUEST["oper"];
  }
//==========================================================================================================================  
$operation_id = "";
if(isset($_SESSION[BASE.'shop_operation_id'])){
    $operation_id=$_SESSION[BASE.'shop_operation_id'];
  }else{
    echo "erro*not active operation";
    exit();
  }
  
 $tmp = mysql_query("SET NAMES utf8");
 $tQuery = "SELECT `operation_id` FROM `tbl_operation` WHERE `operation_id`='$operation_id' and `operation_dell`='0'";
 $tmp = mysql_query($tQuery);
  if(mysql_num_rows($tmp)<1){
      echo "error*operation is delleted";
      exit();
  }
//==========================================================================================================================  
  $oper = mysql_query("SET NAMES utf8");
  $tQuery = "INSERT INTO `tbl_operation_detail`
	      (`operation_detail_operation`,
	      `operation_detail_tovar`,
	      `operation_detail_item`,
	      `operation_detail_price`,
	      `operation_detail_zakup`,
	      `operation_detail_discount`,
	      `operation_detail_summ`,
	      `operation_detail_memo`,
	      `operation_detail_from`,
	      `operation_detail_to`,
	      `operation_detail_dell`)
	      VALUES(
	      '$operation_id',
	      '".$m_setup['shop money code']."',
	      '1',
	      '".($money_summ-$money_oper_summ)."',
	      '".($money_summ-$money_oper_summ)."',
	      '0',
	      '".($money_summ-$money_oper_summ)."',
	      '".date("Y-m-d G:i:s")." sum:$money_summ ($money_oper_summ - $money_rabat%, dano:$money_dano) $money_comm',
	      '$move_from',
	      '$move_to',
	      '0'
	      )
	    ";
   $oper = mysql_query($tQuery);

   $tQuery = "UPDATE `tbl_operation`
	    SET `operation_summ` = (SELECT SUM(`operation_detail_summ`) as op_sum
				    FROM `tbl_operation_detail` 
				    WHERE `operation_detail_dell`='0' and 
				    `operation_detail_operation`='".$operation_id."')
	     WHERE `operation_id`='$operation_id' 
	    ";
  $oper = mysql_query($tQuery);

  reset_warehouse_on_tovar_from_to($m_setup['shop money code'],$m_setup['shop default ware from'],$m_setup['shop default ware to']);//tovar,from,to
  
  $oper = mysql_query("SET NAMES utf8");
   $tQuery = "SELECT `operation_detail_id`,`operation_detail_memo`
	      FROM `tbl_operation_detail`
	      WHERE 
	      `operation_detail_dell`='0' and 
	      `operation_detail_operation`='" . $_SESSION[BASE.'shop_operation_id'] . "' and
	      `operation_detail_memo`like'%***'
	    ";
  $oper = mysql_query($tQuery);

  $tmp_id="";
  $tmp_memo="";
  $count=0;
  while($count < mysql_num_rows($oper)){
  
      $tmp_id = mysql_result($oper,$count,"operation_detail_id");
      $tmp_memo = mysql_result($oper,$count,"operation_detail_memo");
      $tmp_memo = substr($tmp_memo,0,-4);
  
      $update = mysql_query("SET NAMES utf8");
      $tQuery = "UPDATE `tbl_operation_detail`
	      SET `operation_detail_memo`='$tmp_memo'
	      WHERE 
	      `operation_detail_id`='$tmp_id'
	    ";
      $update = mysql_query($tQuery);

  
  $count++;
  }
  
     
 
echo "OK";
?>
