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
//==================================SETUP=MENU==========================================
 $tmp = mysql_query("SET NAMES utf8");
 $tQuery = "SELECT `operation_status_from_as_new`,`operation_status_to_as_new` FROM `tbl_operation_status` WHERE `operation_status_id`='".$m_setup['shop default status']."'";
 $tmp = mysql_query($tQuery);
 $move_from = mysql_result($tmp,0,"operation_status_from_as_new"); 
 $move_to = mysql_result($tmp,0,"operation_status_to_as_new"); 
//==================================SETUP=CURR==========================================

//==========================================CURR=============
if ($_SESSION[BASE.'lang'] <1){
  $_SESSION[BASE.'lang']=1;
}
$setup = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	  `currency_id`, 
	  `currency_ex`
	  FROM `tbl_currency`

";
$setup = mysql_query($tQuery);
$m_curr = array();
$count=0;
while ($count<mysql_num_rows($setup)){
 $m_curr[mysql_result($setup,$count,0)]=mysql_result($setup,$count,1);
 $count++;
}
//==================================SETUP=CURR==========================================
$tovar_id = "";
if(isset($_REQUEST["id"])){
    $tovar_id=(int)$_REQUEST["id"];
  }else{
    echo "error*not tovar id";
    exit();
  }
$tovar_item = "";
if(isset($_REQUEST["item"])){
    $tovar_item=$_REQUEST["item"];
  }else{
    echo "error*not item";
    exit();
  }
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
  
 $tmp = mysql_query("SET NAMES utf8");
 $tQuery = "SELECT 
		    `price_tovar_1`,
		    `price_tovar_curr_1`,
		    `price_tovar_".$m_setup['shop default price']."` as price,
		    `price_tovar_curr_".$m_setup['shop default price']."` as price_curr
	  FROM `tbl_price_tovar`
	  WHERE 
	    `price_tovar_id`='$tovar_id'
	    ";
	    //echo $tQuery;
  $tmp = mysql_query($tQuery);

  $tovar_price = mysql_result($tmp,0,"price") * $m_curr[mysql_result($tmp,0,"price_curr")];
  $tovar_zakup = mysql_result($tmp,0,"price_tovar_1") * $m_curr[mysql_result($tmp,0,"price_tovar_curr_1")];
  
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
	      '$tovar_id',
	      '$tovar_item',
	      '$tovar_price',
	      '$tovar_zakup',
	      '0',
	      '".($tovar_item*$tovar_price)."',
	      '".date("Y-m-d G:i:s")." ***',
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

     reset_warehouse_on_tovar_from_to($tovar_id,$m_setup['shop default ware from'],$m_setup['shop default ware to']);//tovar,from,to
 
echo "OK";
?>
