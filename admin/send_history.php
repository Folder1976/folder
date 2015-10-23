<?php

include 'init.lib.php';
connect_to_mysql();
session_start();
if (!session_verify($_SERVER["PHP_SELF"],"none")){
  exit();
}
//include 'money2str.lib.php';
include "libmail.php";


//==================================SETUP===========================================
if ($_SESSION[BASE.'lang'] <1){
  $_SESSION[BASE.'lang']=1;
}
$setup = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	  `setup_menu_name`, 
	  `setup_menu_".$_SESSION[BASE.'lang']."`
	  FROM `tbl_setup_menu`
	  WHERE 
	  `setup_menu_name` like '%menu%'

";
//echo $tQuery;
$setup = mysql_query($tQuery);
$m1_setup = array();
$count=0;
while ($count<mysql_num_rows($setup)){
 $m1_setup[mysql_result($setup,$count,0)]=mysql_result($setup,$count,1);
 $count++;
}
//===================================================================================
$klient_id = $_REQUEST["klienti_id"];

$ver = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	    `operation_data`,
	    `operation_id`, 
	    `operation_summ`,
	    `operation_status`,
	    `operation_klient`,
	    `klienti_name_1`,
	    `klienti_id`,
	    `klienti_phone_1`,
	    `klienti_email`,
	    `operation_status_name`,
	    `operation_memo` 
	    FROM `tbl_operation`,
	    `tbl_klienti`,
	    `tbl_operation_status`
	    WHERE 
	    `operation_klient`=`klienti_id` and 
	    `operation_status`=`operation_status_id` and
	    `operation_dell`='0' and
	    `klienti_id`='$klient_id'
	    ORDER BY `operation_data` DESC, `operation_id` DESC ";
$ver = mysql_query($tQuery);
//echo $tQuery;

header ('Content-Type: text/html; charset=utf8');
$html = "<header><link rel='stylesheet' type='text/css' href='sturm.css' media='all'></header>
      <title>".$m1_setup['menu history']." ".mysql_result($ver,0,"klienti_name_1")."</title>";
echo "<br><a href='send_history.php?klienti_id=",mysql_result($ver,0,"klienti_id"),"&send=1'> ".$m1_setup['menu send mail']." ",mysql_result($ver,0,"klienti_email"),"</a><br><br>";

$html .= "".mysql_result($ver,0,"klienti_name_1").", saldo (&saldo)";

$html .="<table>";      
$count=0;
$summ=0;
while($count<mysql_num_rows($ver)){
    $html .= "<tr><td>".mysql_result($ver,$count,"operation_data")."</td>";
    $html .= "<td><a href='edit_nakl.php?operation_id=".mysql_result($ver,$count,"operation_id")."' target='_blank'>".mysql_result($ver,$count,"operation_id")."</a></td>";
    $html .= "<td>".mysql_result($ver,$count,"operation_summ")."</td>";
    $html .= "<td>".mysql_result($ver,$count,"operation_status_name")."</td>";
    $html .= "<td>".mysql_result($ver,$count,"operation_memo")."</td></tr>";
    $summ +=mysql_result($ver,$count,"operation_summ");
$count++;
}
$html .="</table>";   
$summ = number_format($summ,2,".","");
$html = str_replace("&saldo",$summ,$html);


if(isset($_REQUEST["send"]))
  {
  $mail_tmp = $html;
  //==================================MAIL===========================================
    $setup = mysql_query("SET NAMES utf8");
    $tQuery = "SELECT 
	  `setup_name`, 
	  `setup_param`
	  FROM `tbl_setup`
	  WHERE 
	  `setup_name` like '%email%'";
    $setup = mysql_query($tQuery);

    $m = new Mail("UTF-8");
    $m_setup = array();
    $count=0;
    while ($count<mysql_num_rows($setup)){
      $m_setup[mysql_result($setup,$count,0)]=mysql_result($setup,$count,1);
      $count++;
    }
//======================END MAIL IF NEED===================================================================
  $m->From($m_setup['email name'].";".$m_setup['email']);
  $m->smtp_on($m_setup['email smtp'],$m_setup['email login'],$m_setup['email pass'],$m_setup['email port']);//465 587
  $m->Priority(2);
  $m->Body($mail_tmp."<br>");
  $m->text_html="text/html";
  $m->Subject($m_setup['email name']." :".$m1_setup['menu history']);
  $m->To(mysql_result($ver,0,"klienti_email"));
  echo "Sending....";
  $error = $m->Send();
  //echo "<br>",$m->Send();
    if($error==1){echo "<br>Email sended OK";}
      else{echo "<br><b>Email DONT send!!! Error number - ", $error; }
}

echo $html;
?>
