<?php

function send_mail($operation_id,$template_name,$mail_tmp){
include 'admin/money2str.lib.php';
include "admin/libmail.php";
$html = "";
connect_to_mysql();


//$mail_tmp = "";
$tmp_sms = "";

    $tQuery = "SELECT 
	  `mail_tmp_body`,
	  `mail_tmp_sms`
	  FROM `tbl_mail_tmp` 
	  WHERE `mail_tmp_name` = '".$mail_tmp."'
      ";
  $ver = mysql_query("SET NAMES utf8");
  $ver = mysql_query($tQuery);
  $mail_tmp = mysql_result($ver,0,"mail_tmp_body");
  $tmp_sms  = mysql_result($ver,0,"mail_tmp_sms");


if ($template_name=="")$template_name="print";
$temp_header = "admin/template/".$template_name."_header.html";
$temp_fields = "admin/template/".$template_name."_fields.html";
//=================================================================
$ver = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	  `operation_data`,
	  `operation_id`, 
	  `operation_summ`,
	  `operation_memo`,
	  `firms_name`,
	  `klienti_id`,
	  `klienti_name_1`,
	  `klienti_phone_1`,
	  `klienti_email`,
	  `delivery_name`
	  FROM `tbl_operation`,`tbl_klienti`,`tbl_firms`,`tbl_delivery` 
	  WHERE 
	  `operation_klient`=`klienti_id` and 
	  `operation_id`='".$operation_id."' and
	  `operation_prodavec`=`firms_id` and
	  `delivery_id`=`klienti_delivery_id`
";

$ver = mysql_query($tQuery);
//==================================================================
$fields = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	  `tovar_artkl`, 
	  `tovar_name_1`,
	  `operation_detail_price`,
	  `operation_detail_item`,
	  `operation_detail_discount`,
	  `operation_detail_summ`,
	  `operation_detail_memo`,
	  `operation_detail_from`,
	  `operation_detail_to`
	  FROM `tbl_operation_detail`,`tbl_tovar`
	  WHERE 
	  `operation_detail_tovar`=`tovar_id` and 
	  `operation_detail_operation`='".$operation_id."' and
	  `operation_detail_dell` = '0'
";

$fields = mysql_query($tQuery);
//==================================================================
$w_house = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	  `warehouse_id`, 
	  `warehouse_shot_name`
	  FROM `tbl_warehouse`
";
$w_house = mysql_query($tQuery);
//==================================================================
//==================================================================
$saldo = mysql_query("SET NAMES utf8");
$tQuery = "SELECT SUM(operation_summ) as saldo 
	  FROM `tbl_operation`
	  WHERE `operation_klient`='".mysql_result($ver,0,"klienti_id")."'
	  and `operation_dell`='0'
";
//echo $tQuery;
$saldo = mysql_query($tQuery);
//==================================================================
//sms tmp replace
$tmp_sms = str_replace("&operation_data",mysql_result($ver,0,"operation_data"),$tmp_sms);
$tmp_sms = str_replace("&operation_id",mysql_result($ver,0,"operation_id"),$tmp_sms);
$tmp_sms = str_replace("&operation_memo",mysql_result($ver,0,"operation_memo"),$tmp_sms);
$tmp_sms = str_replace("&operation_summ",number_format(mysql_result($ver,0,"operation_summ"),2,".",""),$tmp_sms);
$tmp_sms = str_replace("&date_now",date("Y-m-d G:i:s"),$tmp_sms);
$tmp_sms = str_replace("&klienti_name_1",mysql_result($ver,0,"klienti_name_1"),$tmp_sms);
$tmp_sms = str_replace("&my_sms","",$tmp_sms);


$mail_tmp = str_replace("&operation_data",mysql_result($ver,0,"operation_data"),$mail_tmp);
$mail_tmp = str_replace("&operation_id",mysql_result($ver,0,"operation_id"),$mail_tmp);
$mail_tmp = str_replace("&operation_memo",mysql_result($ver,0,"operation_memo"),$mail_tmp);
$mail_tmp = str_replace("&operation_summ",number_format(mysql_result($ver,0,"operation_summ"),2,".",""),$mail_tmp);
$mail_tmp = str_replace("&date_now",date("Y-m-d G:i:s"),$mail_tmp);
$mail_tmp = str_replace("&klienti_name_1",mysql_result($ver,0,"klienti_name_1"),$mail_tmp);
$mail_tmp = str_replace("&delivery_name",mysql_result($ver,0,"delivery_name"),$mail_tmp);
$mail_tmp = str_replace("&saldo",number_format(mysql_result($saldo,0,"saldo"),2,".",""),$mail_tmp);
$mail_tmp = str_replace("&my_email","",$mail_tmp);

//nakl tmp replase
$tmp_header = file_get_contents($temp_header);
$tmp_header = str_replace("&operation_data",mysql_result($ver,0,"operation_data"),$tmp_header);
$tmp_header = str_replace("&operation_id",mysql_result($ver,0,"operation_id"),$tmp_header);
$tmp_header = str_replace("&operation_memo",mysql_result($ver,0,"operation_memo"),$tmp_header);
$tmp_header = str_replace("&operation_summ",number_format(mysql_result($ver,0,"operation_summ"),2,".",""),$tmp_header);
$summ_str = money2str_ru(mysql_result($ver,0,"operation_summ"));
$tmp_header = str_replace("&operation_str_summ",$summ_str,$tmp_header);
$tmp_header = str_replace("&klienti_name_1",mysql_result($ver,0,"klienti_name_1"),$tmp_header);
$tmp_header = str_replace("&klienti_phone_1",mysql_result($ver,0,"klienti_phone_1"),$tmp_header);
$tmp_header = str_replace("&delivery_name",mysql_result($ver,0,"delivery_name"),$tmp_header);
$tmp_header = str_replace("&firms_name",mysql_result($ver,0,"firms_name"),$tmp_header);
$tmp_header = str_replace("&date_now",date("Y-m-d G:i:s"),$tmp_header);

$tmp_fields = file_get_contents($temp_fields);
$fields_for_out = "<table class='print' width='100%'><thead style='display:table-header-group'>";
$fielss_tmp_str="";
    //==================Fields=Header=====================================
    $fielss_tmp_str_name = $tmp_fields;
    //$style = "<>";
    $fielss_tmp_str_name = str_replace("&count","<b>N",$fielss_tmp_str_name);
    $fielss_tmp_str_name = str_replace("&tovar_artkl","<b>".$setup['menu artkl'],$fielss_tmp_str_name);
    $fielss_tmp_str_name = str_replace("&tovar_name_1","<b>".$setup['menu name1'],$fielss_tmp_str_name);
    $fielss_tmp_str_name = str_replace("&operation_detail_price","<b>".$setup['print price'],$fielss_tmp_str_name);
    $fielss_tmp_str_name = str_replace("&operation_detail_item","<b>".$setup['print items'],$fielss_tmp_str_name);
    $fielss_tmp_str_name = str_replace("&operation_detail_discount","<b>".$setup['print discount'],$fielss_tmp_str_name);
    $fielss_tmp_str_name = str_replace("&operation_detail_summ","<b>".$setup['print summ'],$fielss_tmp_str_name);
    $fielss_tmp_str_name = str_replace("&operation_detail_from","<b>".$setup['print from'],$fielss_tmp_str_name);
    $fielss_tmp_str_name = str_replace("&price_tovar_1","<b>".$setup['print bay'],$fielss_tmp_str_name);
    $fielss_tmp_str_name = str_replace("&summ_upper","<b>".$setup['print upper summ'],$fielss_tmp_str_name);
    $fielss_tmp_str_name = str_replace("&operation_detail_to","<b>".$setup['print to'],$fielss_tmp_str_name);
    $fielss_tmp_str_name = str_replace("&memo","<b>".$setup['menu memo'],$fielss_tmp_str_name);
$fields_for_out .= $fielss_tmp_str_name."</thead>";
    //=================Fields==============================================
$count = 0;
while ($count < mysql_num_rows($fields)){
    $fielss_tmp_str = $tmp_fields;
    $fielss_tmp_str = str_replace("&count",$count+1,$fielss_tmp_str);
    $fielss_tmp_str = str_replace("&tovar_artkl",mysql_result($fields,$count,"tovar_artkl"),$fielss_tmp_str);
    $fielss_tmp_str = str_replace("&tovar_name_1",mysql_result($fields,$count,"tovar_name_1"),$fielss_tmp_str);
    $fielss_tmp_str = str_replace("&operation_detail_price",number_format(mysql_result($fields,$count,"operation_detail_price"),2,".",""),$fielss_tmp_str);
    $fielss_tmp_str = str_replace("&operation_detail_item",mysql_result($fields,$count,"operation_detail_item"),$fielss_tmp_str);
    $fielss_tmp_str = str_replace("&operation_detail_discount",mysql_result($fields,$count,"operation_detail_discount"),$fielss_tmp_str);
    $fielss_tmp_str = str_replace("&operation_detail_summ",number_format(mysql_result($fields,$count,"operation_detail_summ"),2,".",""),$fielss_tmp_str);
	$tmp=0;
	while($tmp<mysql_num_rows($w_house)){
	    if (mysql_result($fields,$count,"operation_detail_to")==mysql_result($w_house,$tmp,"warehouse_id")){
		$fielss_tmp_str = str_replace("&operation_detail_to",mysql_result($w_house,$tmp,"warehouse_shot_name"),$fielss_tmp_str);
	    	$tmp=999999;    
	    }
	$tmp++;
	}
	$tmp=0;
	while($tmp<mysql_num_rows($w_house)){
	    if (mysql_result($fields,$count,"operation_detail_from")==mysql_result($w_house,$tmp,"warehouse_id")){
		$fielss_tmp_str = str_replace("&operation_detail_from",mysql_result($w_house,$tmp,"warehouse_shot_name"),$fielss_tmp_str);
	    	$tmp=999999;    
	    }
	$tmp++;
	}

  $fields_for_out .= $fielss_tmp_str;
$count++;
}
$fields_for_out .= "</table>";
$tmp_header = str_replace("&DELIVE PRINT",$setup['DELIVE PRINT'],$tmp_header);
$tmp_header = str_replace("&ANALITIC PRINT",$setup['ANALITIC PRINT'],$tmp_header);
$tmp_header = str_replace("&ANALITIC WARE PRINT",$setup['ANALITIC WARE PRINT'],$tmp_header);
$tmp_header = str_replace("&PACING PRINT",$setup['PACING PRINT'],$tmp_header);
$tmp_header = str_replace("&print nak no",$setup['print nak no'],$tmp_header);
$tmp_header = str_replace("&print print",$setup['print print'],$tmp_header);
$tmp_header = str_replace("&print vidal",$setup['print vidal'],$tmp_header);
$tmp_header = str_replace("&print supplier",$setup['print supplier'],$tmp_header);
$tmp_header = str_replace("&print otrimal",$setup['print otrimal'],$tmp_header);
$tmp_header = str_replace("&print delivery",$setup['print delivery'],$tmp_header);
$tmp_header = str_replace("&print summ",$setup['print summ'],$tmp_header);
$tmp_header = str_replace("&print pdv",$setup['print pdv'],$tmp_header);
$tmp_header = str_replace("&print string",$setup['print string'],$tmp_header);
$tmp_header = str_replace("&print items",$setup['print items'],$tmp_header);
$tmp_header = str_replace("&print bay",$setup['print bay'],$tmp_header);
$tmp_header = str_replace("&print sale",$setup['print sale'],$tmp_header);
$tmp_header = str_replace("&print memo",$setup['print memo'],$tmp_header);
$tmp_header = str_replace("&print upper summ",$setup['print upper summ'],$tmp_header);

$tmp_header = str_replace("&fields",$fields_for_out,$tmp_header);
$html .= $mail_tmp."<br><br>";
$html .= $tmp_header;

//==================================MAIL===========================================
$setup = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	  `setup_name`, 
	  `setup_param`
	  FROM `tbl_setup`
	  WHERE 
	  `setup_name` like '%email%'

";

$setup = mysql_query($tQuery);
$m = new Mail("UTF-8");
$m_sms = new Mail("UTF-8");
//$this->headers = "Date: ".date("D, j M Y G:i:s")." +0200\r\n";;
$setup = array();
$count=0;
while ($count<mysql_num_rows($setup)){
 $setup[mysql_result($setup,$count,0)]=mysql_result($setup,$count,1);
 $count++;
}

//======================END MAIL IF NEED===================================================================

  $m->From($setup['email name'].";".$setup['email']);
  $m->smtp_on($setup['email smtp'],$setup['email login'],$setup['email pass'],$setup['email port']);//465 587
  $m->Priority(2);
  $m->Body($mail_tmp."<br>".$tmp_header);
  $m->text_html="text/html";
  $m->Subject($setup['email name']." order:".$operation_id);
  $m->To(mysql_result($ver,0,"klienti_email"));
  $html .= "Sending....";
  $error = $m->Send();
  //echo "<br>",$m->Send();
    if($error==1){$html .= "<br>Email sended OK";}
      else{$html .= "<br><b>Email DONT send!!! Error number - ". $error; }

//======================END SMS IF NEED===================================================================

  $m_sms->From($setup['email sms name'].";".$setup['email']);
  $m_sms->smtp_on($setup['email smtp'],$setup['email login'],$setup['email pass'],$setup['email port']);//465 587
  $m_sms->Priority(2);
  $m_sms->Body($tmp_sms);
  $m_sms->text_html="text/html";
  $m_sms->Subject($setup['email sms name'].";".$setup['email sms login'].";".$setup['email sms pass']);
  $m_sms->To(mysql_result($ver,0,"klienti_phone_1").$setup['email sms web']);
  $error = $m_sms->Send();
    if($error==1){$html .= "<br>SMS sended OK";}
      else{$html .= "<br><b>SMS DONT send!!! Error number - ". $error; }
      
      $html .= "<br> SMS <br>";
    
return $html;
    
  
}
?>
