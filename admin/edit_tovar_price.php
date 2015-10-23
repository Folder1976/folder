<?php
include 'init.lib.php';
connect_to_mysql();
session_start();


require("JsHttpRequest.php");
$JsHttpRequest=new JsHttpRequest("utf8");
$operation = $_REQUEST["operation"];
$tovar_id = $_REQUEST["tovar_id"];

header ('Content-Type: text/html; charset=utf8');

$tQuery = "SELECT * FROM `tbl_price`";
$price_id = mysql_query("SET NAMES utf8");
$price_id = mysql_query($tQuery);

$tQuery = "SELECT * FROM `tbl_currency`";
$curr = mysql_query("SET NAMES utf8");
$curr = mysql_query($tQuery);

if($tovar_id=="all"){
    $tQuery = "SELECT * FROM `tbl_price_tovar` ORDER BY `price_tovar_id` ASC";
}else{
    $count=0;
   // echo $tovar_id;
    $tovar_id = explode("*",$tovar_id);
    $find="";
    while(($count+1)<count($tovar_id)){
	$find .="`price_tovar_id`='".$tovar_id[$count]."' or ";
    $count++;
    }
	$find = substr($find,0,-4);
    //echo $find,"<br>";
    $tQuery = "SELECT * FROM `tbl_price_tovar` WHERE $find";
    echo $tQuery;
    //exit();
}
$price_tovar = mysql_query("SET NAMES utf8");
$price_tovar = mysql_query($tQuery);

$ex = array();
$count_tmp=0;
while ($count_tmp<mysql_num_rows($curr)){
 $ex[mysql_result($curr,$count_tmp,"currency_id")]=mysql_result($curr,$count_tmp,"currency_ex");
 $count_tmp++;
}


$new_coef=0;
$update_filds="";



if($operation=="coef"){
$all = 0;
while($all < mysql_num_rows($price_tovar)){
    $tovar_id = mysql_result($price_tovar,$all,"price_tovar_id");
    $tovar_zakup = mysql_result($price_tovar,$all,"price_tovar_1");
    $tovar_zakup_curr = mysql_result($price_tovar,$all,"price_tovar_curr_1");

    
    $count = 0;
    while($count<mysql_num_rows($price_id)){
	$id = mysql_result($price_id,$count,"price_id");
	
	$tovar_price = mysql_result($price_tovar,$all,"price_tovar_$id");
	$tovar_curr = mysql_result($price_tovar,$all,"price_tovar_curr_$id");
	$new_coef = ($tovar_price*$ex[$tovar_curr])/($tovar_zakup*$ex[$tovar_zakup_curr]);
	$new_coef = number_format($new_coef,"3",".","");
	$update_filds .= " `price_tovar_cof_$id`='$new_coef',";
    $count++;
    }

$update_filds = substr($update_filds,0,-1);
$tQuery = "UPDATE `tbl_price_tovar` SET $update_filds WHERE `price_tovar_id`='$tovar_id'";
//echo $tQuery,"<br>";
$update = mysql_query("SET NAMES utf8");
$update = mysql_query($tQuery);
$update_filds="";
$all++;
}
echo "ok";
}
if($operation=="price"){
$all = 0;
while($all < mysql_num_rows($price_tovar)){
    $tovar_id = mysql_result($price_tovar,$all,"price_tovar_id");
    $tovar_zakup = mysql_result($price_tovar,$all,"price_tovar_1");
    $tovar_zakup_curr = mysql_result($price_tovar,$all,"price_tovar_curr_1");
    
    $count = 1;
    while($count<mysql_num_rows($price_id)){
	$id = mysql_result($price_id,$count,"price_id");
	
	$tovar_coef = mysql_result($price_tovar,$all,"price_tovar_cof_$id");
	$tovar_price = mysql_result($price_tovar,$all,"price_tovar_$id");
	$tovar_curr = mysql_result($price_tovar,$all,"price_tovar_curr_$id");
	$new_coef = ($tovar_zakup*$ex[$tovar_zakup_curr])*$tovar_coef/$ex[$tovar_curr];
	$new_coef = number_format($new_coef,"0",".","");
	$update_filds .= " `price_tovar_$id`='$new_coef',";
    $count++;
    }

$update_filds = substr($update_filds,0,-1);
$tQuery = "UPDATE `tbl_price_tovar` SET $update_filds WHERE `price_tovar_id`='$tovar_id'";
echo $tQuery;
$update = mysql_query("SET NAMES utf8");
$update = mysql_query($tQuery);
$update_filds="";
$all++;
}
echo "ok";
}

?>
