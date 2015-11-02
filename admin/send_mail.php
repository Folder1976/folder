<?php

include 'init.lib.php';
connect_to_mysql();
session_start();
if (!session_verify($_SERVER["PHP_SELF"],"none")){
  exit();
}
include 'money2str.lib.php';
include "libmail.php";

//=========================================================
if ($_SESSION[BASE.'lang'] <1){
  $_SESSION[BASE.'lang']=1;
}
$setup1 = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	  `setup_menu_name`, 
	  `setup_menu_".$_SESSION[BASE.'lang']."`
	  FROM `tbl_setup_menu`";
//echo $tQuery;
$setup1 = mysql_query($tQuery);
//$setup = array();
$count=0;
while ($count<mysql_num_rows($setup1)){
 $setup[mysql_result($setup1,$count,0)]=mysql_result($setup1,$count,1);
 $count++;
}
//==================================SETUP=MENU==========================================
$operation_id = $_REQUEST["operation_id"];
$template_name = "";
if(isset($_REQUEST["tmp"]))$template_name=$_REQUEST["tmp"];

$key = "";
if(isset($_REQUEST["key"]))$key=$_REQUEST["key"];

header ('Content-Type: text/html; charset=utf8');
echo "<header><link rel='stylesheet' type='text/css' href='sturm.css' media='all'></header>
      <title>Send:",$operation_id,"</title>";
      
if(!$template_name){
$ver = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	  `mail_tmp_name`,
	  `mail_tmp_name_ru`
	  FROM `tbl_mail_tmp` 
	  ORDER BY `mail_tmp_sort` ASC
";
//echo ''.$tQuery;
$ver = mysql_query($tQuery);
//echo $tQuery;
echo "
\n<form method='get' action='send_mail.php'>
<table class='menu_top'>
<tr>
  <td colspan=\"5\" align=\"right\">SMS : 
   <input type='text' style='width:90%' name='my_sms' value=''/><br>
  </td>
</tr>
<tr>
  <td colspan=\"5\" align=\"right\">MSG : 
   <input type='text' style='width:90%' name='my_email' value=''/><br>
  </td>
</tr>
<tr>
  <td>
   </td><td>
  <input type='text' style='width:50px' name='operation_id' value='",$operation_id,"'/><br>
  <input type='checkbox' name='mail' value='mail' checked>",$setup['menu email'],"<br>
  <input type='checkbox' name='sms' value='sms' checked>sms<br>
  </td><td>";
  echo "<input type='radio' name='tmp' value='none' checked";
  echo ">none<br>
  <input type='radio' name='tmp' value='print'>",$setup['menu print sale'],"<br>
  <input type='radio' name='tmp' value='warehouse'>",$setup['menu print ware'],"<br>
  <input type='radio' name='tmp' value='bay'>",$setup['menu print bay'],"<br>
  </td><td>
  \n<select style='width:250px' size='5' name='mail_tmp'>";
   $count=0;
    while ($count < mysql_num_rows($ver))
    {
      if(strpos(mysql_result($ver,$count,"mail_tmp_name"),"folder")>0){
	      if($_SESSION[BASE.'userlevel']>900000){
		    echo "\n<option value=" . mysql_result($ver,$count,"mail_tmp_name") . ">" . mysql_result($ver,$count,"mail_tmp_name_ru") . "</option>";
	      }
      }else{
	  echo "\n<option value=" . mysql_result($ver,$count,"mail_tmp_name") . ">" . mysql_result($ver,$count,"mail_tmp_name_ru") . "</option>";
      }
    $count++;
    }
  echo "</select>
  </td>
 </tr>
 <tr>
  <td colspan=\"5\" align=\"center\">
  <input type='submit' name='action' value='",$setup['menu send mail'],"' style='width:100%;height:80px;'/>
</td>
</tr>
</table>
\n</form>
";
exit();
}

//$operation_id = $_GET["operation_id"];
//$template_name = $_GET["tmp"];
//$mail_key = $_REQUEST["mail"];
//$sms_key = $_REQUEST["sms"];
$mail_tmp = "";
$tmp_sms = "";
if(isset($_REQUEST["mail_tmp"]))
  {
  $mail_tmp=$_REQUEST["mail_tmp"];
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
}

if ($template_name=="")$template_name="print";
$temp_header = "template/".$template_name."_header.html";
$temp_fields = "template/".$template_name."_fields.html";
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
$tmp_sms = str_replace("&my_sms",$_REQUEST['my_sms'],$tmp_sms);
$tmp_sms = str_replace("&operation_summ",number_format(mysql_result($ver,0,"operation_summ"),2,".",""),$tmp_sms);
$tmp_sms = str_replace("&date_now",date("Y-m-d G:i:s"),$tmp_sms);
$tmp_sms = str_replace("&klienti_name_1",mysql_result($ver,0,"klienti_name_1"),$tmp_sms);


$mail_tmp = str_replace("&operation_data",mysql_result($ver,0,"operation_data"),$mail_tmp);
$mail_tmp = str_replace("&operation_id",mysql_result($ver,0,"operation_id"),$mail_tmp);
$mail_tmp = str_replace("&my_email",$_REQUEST['my_email'],$mail_tmp);
$mail_tmp = str_replace("&operation_memo",mysql_result($ver,0,"operation_memo"),$mail_tmp);
$mail_tmp = str_replace("&operation_summ",number_format(mysql_result($ver,0,"operation_summ"),2,".",""),$mail_tmp);
$mail_tmp = str_replace("&date_now",date("Y-m-d G:i:s"),$mail_tmp);
$mail_tmp = str_replace("&klienti_name_1",mysql_result($ver,0,"klienti_name_1"),$mail_tmp);
$mail_tmp = str_replace("&delivery_name",mysql_result($ver,0,"delivery_name"),$mail_tmp);
$mail_tmp = str_replace("&saldo",number_format(mysql_result($saldo,0,"saldo"),2,".",""),$mail_tmp);

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
    
    $tovar_name = mysql_result($fields,$count,"tovar_name_1");
    //echo $tovar_name;
    if($template_name!="bay" and $template_name!="warehouse"){
	$tovar_name = explode($setup['tovar name sep'], $tovar_name);
	$fielss_tmp_str = str_replace("&tovar_name_1",$tovar_name[0],$fielss_tmp_str);
    }else{
		if(strlen($tovar_name)>50){
		    $tovar_name = "<div class=\"barcode\" style=\"width:390px;height:14px;\" align=\"left\">
				  ".$tovar_name."
				  </div>";
		}
    
	$fielss_tmp_str = str_replace("&tovar_name_1",$tovar_name,$fielss_tmp_str);
    }
    
    
    $fielss_tmp_str = str_replace("&count",$count+1,$fielss_tmp_str);
    $fielss_tmp_str = str_replace("&tovar_artkl",mysql_result($fields,$count,"tovar_artkl"),$fielss_tmp_str);
   // $fielss_tmp_str = str_replace("&tovar_name_1",$fielss_tmp_str,$fielss_tmp_str);
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
echo $mail_tmp,"<br><br>";
echo $tmp_header;

$m = new Mail("UTF-8");
$m_sms = new Mail("UTF-8");


//======================END MAIL IF NEED===================================================================
//echo '<pre>'; print_r(var_dump($setup)); die();
if(isset($_REQUEST["mail"])){
  $m->From($setup['email name'].";".$setup['email']);
  $m->smtp_on($setup['email smtp'],$setup['email login'],$setup['email pass'],$setup['email port']);//465 587
  $m->Priority(2);
  $m->Body($mail_tmp."<br>".$tmp_header);
  $m->text_html="text/html";
  $m->Subject($setup['email name']." order:".$operation_id);
  $m->To(mysql_result($ver,0,"klienti_email"));
  echo "Sending....";
  $error = $m->Send();
  //echo "<br>",$m->Send();
    if($error==1){echo "<br>Email sended OK";}
      else{echo "<br><b>Email DONT send!!! Error number - ", $error; }
}
//======================END SMS IF NEED===================================================================
if(isset($_REQUEST["sms"])){
  $m_sms->From($setup['email sms name'].";".$setup['email']);
  $m_sms->smtp_on($setup['email smtp'],$setup['email login'],$setup['email pass'],$setup['email port']);//465 587
  $m_sms->Priority(2);
  $m_sms->Body($tmp_sms);
  $m_sms->text_html="text/html";
  $m_sms->Subject($setup['email sms name'].";".$setup['email sms login'].";".$setup['email sms pass']);
  $m_sms->To(mysql_result($ver,0,"klienti_phone_1").$setup['email sms web']);
  $error = $m_sms->Send();
    if($error==1){echo "<br>SMS sended OK";}
      else{echo "<br><b>SMS DONT send!!! Error number - ", $error; }
      
      echo "<br> SMS <br>";
    //  echo "<br>", $m_sms->Get();
}

?>
