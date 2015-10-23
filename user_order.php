<?php
include 'init.lib.php';
session_start();
connect_to_mysql();
require("JsHttpRequest.php");
$JsHttpRequest=new JsHttpRequest("utf8");//"windows-1251");

$defaultpass = "123456";

if(!isset($_SESSION[BASE.'userid']) AND $_REQUEST['order']=="opa"){
 
    echo "Только для зарегистрированых пользователей!";
}else{

if($_REQUEST['order']=="send"){

//header ('Refresh: 0; url=' . '/admin/set_status.php?stat=15&nakl=2382');


//echo  $_REQUEST['value'], " ", $_REQUEST['id'];
}
//=================================================================
if($_REQUEST['order']=="del"){
  if($_SESSION[BASE.'userorder']){
      $tQuery = "DELETE FROM `tbl_operation_detail` WHERE 
			    `operation_detail_tovar`='".(int)mysql_real_escape_string($_REQUEST['id'])."' and
			    `operation_detail_operation`='".$_SESSION[BASE.'userorder']."'";
  //echo $tQuery;
      $ver = mysql_query($tQuery);
      set_operation_summ();
  }
echo $setup['menu unit dell'];
}
//echo "value:",$_REQUEST['value']," - id",$_REQUEST['id'], " - order:",$_REQUEST['order'];
if($_REQUEST['order']=="opa"){
	$tQuery = "SELECT `opa_id` FROM `tbl_opa` WHERE
			    `opa_klient`='".$_SESSION[BASE.'userid']."' and
			    `opa_tovar`='".(int)mysql_real_escape_string($_REQUEST['id'])."'
			    ";
	$ver = mysql_query($tQuery);
	  if(mysql_num_rows($ver)>0){
	      echo $setup['menu opa present'];
	  }else{

	      $tQuery = "INSERT INTO `tbl_opa` SET 
			    `opa_klient`='".$_SESSION[BASE.'userid']."',
			    `opa_tovar`='".(int)mysql_real_escape_string($_REQUEST['id'])."',
			    `opa_item`='".(int)mysql_real_escape_string($_REQUEST['value'])."'
			    ";
	      $ver = mysql_query($tQuery);
	      echo $setup['menu opa add'];
	  }
}
//=====================================================
if($_REQUEST['order']=="add"){
    //Если пользователь не зарегистрирован - поищем его или создадим нового
    if(!isset($_SESSION[BASE.'userid'])){
	$curr = mysql_query("SET NAMES utf8");
	$curr = mysql_query("SELECT `currency_name_shot` FROM `tbl_currency` WHERE `currency_id`='1'");

	$result = $folder->query("SELECT * FROM tbl_klienti WHERE klienti_ip = '".$_SERVER['REMOTE_ADDR']."'");
	if($result->num_rows == 0){
	    $tQuery = "INSERT INTO `tbl_klienti`
		(`klienti_id`,
		  `klienti_group`,
		  `klienti_name_1`,
		  `klienti_name_2`,
		  `klienti_name_3`,
		  `klienti_pass`,
		  `klienti_phone_1`,
		  `klienti_email`,
		  `klienti_edit`,
		  `klienti_delivery_id`,
		  `klienti_inet_id`,
		  `klienti_price`,
		  `klienti_discount`,
		  `klienti_ip`
		  
		)VALUES(
		'',
		'3',
		'Гость',
		'',
		'',
		'".md5($defaultpass)."',
		'',
		'',
		'". date("Y-m-d G:i:s")."',
		'6',
		'10',
		'".$setup['price default price']."',
		'0',
		'".$_SERVER['REMOTE_ADDR']."'
		);
		  ";
	    $result = $folder->query($tQuery);
	    $user_id = $folder->insert_id;
	    //Добавим ему в имя ИД
	    $result = $folder->query("UPDATE tbl_klienti SET klienti_name_1 = 'Гость $user_id' WHERE klienti_id = '".$user_id."'");
	    $result = $folder->query("SELECT * FROM tbl_klienti WHERE klienti_id = '".$user_id."'");
	echo "Мы создали для вас временную учетную запись.\n\r Вы можете изменить ее при откравке вашего заказа\n\r или в меню (Редактировать).\n\r";
	}
	
	$user = mysqli_fetch_assoc($result);
	$_SESSION[BASE.'login']	= $user["klienti_email"];
	$_SESSION[BASE.'userlevel']	= $user["klienti_inet_id"];
	$_SESSION[BASE.'username'] 	= $user["klienti_name_1"];
	$_SESSION[BASE.'userid'] 	= $user["klienti_id"];
	$_SESSION[BASE.'usersetup']	= $user["klienti_setup"];
	$_SESSION[BASE.'userdiscount'] = $user["klienti_discount"];
	$_SESSION[BASE.'userprice']	= $user["klienti_price"];
	$_SESSION[BASE.'usercurr']	= mysql_result($curr,0,0);
	
	//Перечитаем меню каталогов если человек залогинился. Для этого обнулим переменную сессии.
	$_SESSION[BASE.'user menu'] = null;
    }

    if($_SESSION[BASE.'userorder']==-1){
	add_new_order();
    }
    if(!isset($_SESSION[BASE.'userorder'])){
	$user_id = $_SESSION[BASE.'userid'];
	$result = $folder->query("SELECT operation_id FROM tbl_operation WHERE operation_klient = '".$user_id."' AND operation_status = '16'");
	if($result->num_rows == 0){
	    add_new_order();
	}else{
	    $operation = $result->fetch_assoc();
	    $_SESSION[BASE.'userorder'] = $operation['operation_id'];
	}
    }

    add_new_item((int)mysql_real_escape_string($_REQUEST['value']),(int)mysql_real_escape_string($_REQUEST['id']));
     echo "Товар добавлен в корзину.";

}
}
function get_zakup($id){
$setup = mysql_query("SET NAMES utf8");
$tQuery = "SELECT  (`currency_ex`*`price_tovar_1`)
	    FROM `tbl_currency`,`tbl_price_tovar`
	    WHERE `price_tovar_id`='$id' and `price_tovar_curr_1`=`currency_id`
	  ";
$setup = mysql_query($tQuery);

}
function add_new_item($value,$id) {
   $ver = mysql_query("SET NAMES utf8");
  $tQuery = "INSERT INTO `tbl_operation_detail` SET 
			    `operation_detail_operation`='".$_SESSION[BASE.'userorder']."',
			    `operation_detail_tovar`='".$id."',
			    `operation_detail_item`='".$value."',
			    `operation_detail_zakup`= (SELECT  (`currency_ex`*`price_tovar_1`)
							FROM `tbl_currency`,`tbl_price_tovar`
							WHERE 
							  `price_tovar_id`='$id' and 
							  `price_tovar_curr_1`=`currency_id`),
			    `operation_detail_price`= 
						(SELECT 
						  (`price_tovar_".$_SESSION[BASE.'userprice']."`*
						      (SELECT `currency_ex` 
							FROM 
							  `tbl_currency`,
							  `tbl_price_tovar`
							WHERE 
							  `price_tovar_curr_".$_SESSION[BASE.'userprice']."`=`currency_id` and
							  `price_tovar_id`='$id'
						      )
						  ) as price 
						FROM `tbl_price_tovar` 
						WHERE `price_tovar_id`='".$id."'),
			    `operation_detail_discount`='".$_SESSION[BASE.'userdiscount']."',
			    `operation_detail_summ`=(".$value." * (SELECT 
						      (`price_tovar_".$_SESSION[BASE.'userprice']."`*
							(SELECT `currency_ex` 
							  FROM 
							    `tbl_currency`,
							    `tbl_price_tovar`
							  WHERE 
							    `price_tovar_curr_".$_SESSION[BASE.'userprice']."`=`currency_id` and
							    `price_tovar_id`='$id'
							)
						      ) as summ FROM `tbl_price_tovar` WHERE `price_tovar_id`='".$id."'))/100*(100-".$_SESSION[BASE.'userdiscount']."),
			    `operation_detail_memo`='',
			    `operation_detail_from`='0',
			    `operation_detail_to`='0',
			    `operation_detail_dell`='0'
			    ";
  $ver = mysql_query($tQuery);
    if (!$ver){
      echo "Query error",$tQuery;
      exit();
    }
set_operation_summ();
//echo $tQuery;
}


function set_operation_summ(){
  $sum = mysql_query("SET NAMES utf8");
  $tQuery = "SELECT SUM(`operation_detail_summ`) AS sum FROM `tbl_operation_detail` 
	    WHERE `operation_detail_operation`='".$_SESSION[BASE.'userorder']."' and
	    `operation_detail_dell`='0'";
  $sum = mysql_query($tQuery);
 //echo $tQuery;
   $sumup = mysql_query("SET NAMES utf8");
  $tQuery = "UPDATE `tbl_operation` SET `operation_summ`='".number_format(mysql_result($sum,0,0),2,".","")."'
	    WHERE `operation_id`='".$_SESSION[BASE.'userorder']."' and
	    `operation_dell`='0'";
    $sumup = mysql_query($tQuery);

 $_SESSION[BASE.'userordersumm'] = number_format(mysql_result($sum,0,0),2,".","");
}

function add_new_order() {
$date = date("Y-m-d G:i:s");

  $ver = mysql_query("SET NAMES utf8");
  $tQuery = "INSERT INTO `tbl_operation` SET ";
  $tQuery .= "`operation_data`='".$date."',";
  $tQuery .= "`operation_klient`='".$_SESSION[BASE.'userid']."',";
  $tQuery .= "`operation_prodavec`='1',";
  $tQuery .= "`operation_sotrudnik`='-1',";
  $tQuery .= "`operation_data_edit`='".$date."',";
  $tQuery .= "`operation_status`='16',";
  $tQuery .= "`operation_summ`='0',";
  $tQuery .= "`operation_memo`='-',";
  $tQuery .= "`operation_inet_id`='0',";
  $tQuery .= "`operation_dell`='0'";
  $ver = mysql_query($tQuery);
    if (!$ver){
      echo "Query error";
      exit();
    }
 $_SESSION[BASE.'userorder'] = mysql_insert_id();    
 $_SESSION[BASE.'userordersumm'] = 0;    
 
}



?>
