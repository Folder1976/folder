<?php

include 'init.lib.php';
connect_to_mysql();
session_start();
if (!session_verify($_SERVER["PHP_SELF"],"none")){
  exit();
}
include 'money2str.lib.php';
include "libmail.php";



 //==================================SETUP===========================================
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
//=========================================================
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
//$m_setup = array();
$count=0;
while ($count<mysql_num_rows($setup)){
 $m_setup[mysql_result($setup,$count,0)]=mysql_result($setup,$count,1);
 $count++;
}
//==================================SETUP=MENU==========================================
//$klienti_id = 0;
$klient_last=0;
if(isset($_REQUEST["klient_last"])){
  $klient_last = $_REQUEST["klient_last"];
  }
  
$template_name = "";
if(isset($_REQUEST["tmp"]))$template_name=$_REQUEST["tmp"];

$key = "";
if(isset($_REQUEST["key"]))$key=$_REQUEST["key"];

header ('Content-Type: text/html; charset=utf8');
echo "<header><link rel='stylesheet' type='text/css' href='sturm.css' media='all'></header>
      <title>Send:",$klienti_id,"</title>";
      
if(!isset($_REQUEST["action"])){
$ver = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	  `mail_tmp_name`,
	  `mail_tmp_name_ru`
	  FROM `tbl_mail_tmp` 
	  ORDER BY `mail_tmp_sort` ASC
";

$ver = mysql_query($tQuery);
//echo $tQuery;
echo "
\n<form method='get' action='send_mail_all.php'>
<table class='menu_top' width='400px'>
<tr>
  <td colspan=\"4\" align=\"right\">SMS : 
   <input type='text' style='width:90%' name='my_sms' value=''/><br>
  </td>
</tr>
<tr>
  <td colspan=\"4\" align=\"right\">MSG : 
   <input type='text' style='width:90%' name='my_email' value=''/><br>
  </td>
</tr>
<tr>
  <td>
   </td><td>
  <input type='text' style='width:100px' name='klienti_id' value='$klient_last'/><br>
  <input type='checkbox' name='mail' value='mail' checked>",$m_setup['menu email'],"<br>
  <input type='checkbox' name='sms' value='sms' >sms<br>
  </td><td>
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
  <input type='submit' name='action' value='",$m_setup['menu send mail'],"' style='width:100%;height:80px;'/>
</td>
</tr>
</table>
\n</form>
";
exit();
}


$mail_tmp = "";
$tmp_sms = "";
if(isset($_REQUEST["mail_tmp"]))
  {
  $mail_tmp=$_REQUEST["mail_tmp"];
    $tQuery = "SELECT 
	  `mail_tmp_name_ru`,
	  `mail_tmp_body`,
	  `mail_tmp_sms`
	  FROM `tbl_mail_tmp` 
	  WHERE `mail_tmp_name` = '".$mail_tmp."'
      ";
  $ver = mysql_query("SET NAMES utf8");
  $ver = mysql_query($tQuery);
  $mail_tmp = mysql_result($ver,0,"mail_tmp_body");
  $mail_tmp_name = mysql_result($ver,0,"mail_tmp_name_ru");
  $tmp_sms  = mysql_result($ver,0,"mail_tmp_sms");
}
//echo $tQuery;
if ($template_name=="")$template_name="print";
//$temp_header = "template/".$template_name."_header.html";
//$temp_fields = "template/".$template_name."_fields.html";
//=================================================================
$ver = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	  `klienti_id`,
	  `klienti_name_1`,
	  `klienti_phone_1`,
	  `klienti_email`
	  FROM `tbl_klienti`
	  WHERE 
	    (`klienti_group`='1' or
	    `klienti_group`='2' or
	    `klienti_group`='3' or
	    `klienti_group`='10')
	    and
	    (`klienti_spam`like'%ALL%')
	  LIMIT $klient_last, 1
";
//echo $tQuery; WHERE   `klienti_id`='".$klienti_id."'
$ver = mysql_query($tQuery);
if(!$ver){
echo "SENDING NEWS IS ENDING - OK<br>";
echo "Sending : $klient_last messages<br>";
echo "Tmp : $mail_tmp_name<br>";
exit();
}
//==================================================================
//sms tmp replace

$my_sms = $_REQUEST['my_sms'];
$my_email = $_REQUEST['my_email'];
$tmp_sms = str_replace("&my_sms",$_REQUEST['my_sms'],$tmp_sms);
$tmp_sms = str_replace("&date_now",date("Y-m-d G:i:s"),$tmp_sms);
$tmp_sms = str_replace("&klienti_name_1",mysql_result($ver,0,"klienti_name_1"),$tmp_sms);


$mail_tmp = str_replace("&my_email",$_REQUEST['my_email'],$mail_tmp);
$mail_tmp = str_replace("&date_now",date("Y-m-d G:i:s"),$mail_tmp);
$mail_tmp = str_replace("&klienti_name_1",mysql_result($ver,0,"klienti_name_1"),$mail_tmp);


$fields_for_out = "</table>";

echo $mail_tmp,"<br><br>";
//echo $tmp_header;

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
$m_setup = array();
$count=0;
while ($count<mysql_num_rows($setup)){
 $m_setup[mysql_result($setup,$count,0)]=mysql_result($setup,$count,1);
 $count++;
}
//echo $mysql_result($ver,0,"mail_tmp_name_ru");
//======================END MAIL IF NEED===================================================================
$mail_key = 0;
echo "Sysinfo ----------------------------------<br>";
if(!empty(mysql_result($ver,0,"klienti_email"))){
echo "<br><b>",mysql_result($ver,0,"klienti_email"),"</b>",
      "<br>Mail sep : ",strpos(mysql_result($ver,0,"klienti_email"),"@");
  
    if(strpos(mysql_result($ver,0,"klienti_email"),"@") > 0
	and strpos(mysql_result($ver,0,"klienti_email")," ") < 1
	and strpos(mysql_result($ver,0,"klienti_email"),",") < 1){
	  
	    echo "<br>Enother key not found";
	    $mail_key = 1;
	}
}
//echo $mail_key;

if(isset($_REQUEST["mail"]) and $mail_key>0){
  $m->From($m_setup['email name'].";".$m_setup['email']);
  $m->smtp_on($m_setup['email smtp'],$m_setup['email login'],$m_setup['email pass'],$m_setup['email port']);//465 587
  $m->Priority(2);
  $m->Body($mail_tmp);
  $m->text_html="text/html";
  $m->Subject($mail_tmp_name);
  $m->To(mysql_result($ver,0,"klienti_email"));
  echo "<br>Sending....";
  $error = $m->Send();
  //echo "<br>",$m->Send();
 // echo "<br>",mysql_result($ver,0,"klienti_email"),"<br>";
    if($error==1){echo "<br>Email sended OK";}
      else{echo "<br><b>Email DONT send!!! Error number - ", $error; }
}
//======================END SMS IF NEED===================================================================
if(isset($_REQUEST["sms"]) and !empty(mysql_result($ver,0,"klienti_phone_1"))){
  $m_sms->From($m_setup['email sms name'].";".$m_setup['email']);
  $m_sms->smtp_on($m_setup['email smtp'],$m_setup['email login'],$m_setup['email pass'],$m_setup['email port']);//465 587
  $m_sms->Priority(2);
  $m_sms->Body($tmp_sms);
  $m_sms->text_html="text/html";
  $m_sms->Subject($m_setup['email sms name'].";".$m_setup['email sms login'].";".$m_setup['email sms pass']);
  $m_sms->To(mysql_result($ver,0,"klienti_phone_1").$m_setup['email sms web']);
  $error = $m_sms->Send();
    if($error==1){echo "<br>SMS sended OK";}
      else{echo "<br><b>SMS DONT send!!! Error number - ", $error; }
      
      echo "<br> SMS <br>";
}
  echo "<br><br>next...";
  $klient_last++;
 // echo 'Refresh: 5; url=send_mail_all.php?my_sms='.$my_sms.'&my_email='.$my_email.'&klient_last='.$klient_last.'&mail_tmp='.$_REQUEST['mail_tmp'].'&action=next';
 $add_str="";
 if(isset($_REQUEST['mail'])) $add_str .="mail=mail&";
 if(isset($_REQUEST['sms'])) $add_str .="sms=sms&";
 
  header('Refresh: 300; url=send_mail_all.php?'.$add_str.'my_sms='.$my_sms.'&my_email='.$my_email.'&klient_last='.$klient_last.'&mail_tmp='.$_REQUEST['mail_tmp'].'&action=next');
?>
