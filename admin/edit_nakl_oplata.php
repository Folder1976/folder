<?php
include 'init.lib.php';
connect_to_mysql();
session_start();
if (!session_verify($_SERVER["PHP_SELF"],"+")){
  exit();
}

//==================================SETUP===========================================
if ($_SESSION[BASE.'lang'] <1){
  $_SESSION[BASE.'lang']=1;
}
$setup = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	  `setup_menu_name`, 
	  `setup_menu_".$_SESSION[BASE.'lang']."`
	  FROM `tbl_setup_menu`
";
global $setup;

//echo $tQuery;

$setup = mysql_query($tQuery);
$m_setup = array();
$count=0;
while ($count<mysql_num_rows($setup)){
 $m_setup[mysql_result($setup,$count,0)]=mysql_result($setup,$count,1);
 $count++;
}
/*
$setup = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	  *
	  FROM `tbl_setup`
";
$setup = mysql_query($tQuery);
$count=0;
while ($count<mysql_num_rows($setup)){
 $m_setup[mysql_result($setup,$count,0)]=mysql_result($setup,$count,1);
 $count++;
}*/
//==================================SETUP=MENU==========================================
$count = 0;
$this_page_name = "edit_nakl_oplata.php";
$this_table_id_name = "operation_id";
$this_table_name_name = "klienti_name_1";
//$return_page = $_GET['_return_page'];

$this_table_name = "tbl_operation";
//$sort_find = $_GET["_sort_find"];
//$sort_find_deliv = $_GET["_sort_find_deliv"];
$iKlient_id = $_REQUEST[$this_table_id_name];
//echo $iKlient_id;

$iKlient_count = 0;



$ver = mysql_query("SET NAMES utf8");
$ver = mysql_query("SELECT *,`klienti_id`,`klienti_name_1`,`klienti_phone_1` FROM " . $this_table_name . ",`tbl_klienti` WHERE `" . $this_table_id_name . "` = '" . $iKlient_id."' and `operation_klient`=`klienti_id`");
//echo "SELECT *,`klienti_name_1`,`klienti_phone_1` FROM " . $this_table_name . ",`tbl_klienti` WHERE `" . $this_table_id_name . "` = '" . $iKlient_id."' and `operation_klient`=`klienti_id`";
if (!$ver)
{
  echo "Query error - ", $this_table_name;
  exit();
}

$saldo = mysql_query("SET NAMES utf8");
$saldo = mysql_query("SELECT SUM(operation_summ) as summ_all FROM `tbl_operation` 
		      WHERE 
		      `operation_klient`='".mysql_result($ver,0,"klienti_id")."' and
		      `operation_dell`='0'");
if (!$saldo)
{
  echo "Query error - SALDO";
  exit();
}


$stat = mysql_query("SET NAMES utf8");
$stat = mysql_query("SELECT `operation_status_id`,`operation_status_name` FROM `tbl_operation_status` ORDER BY `operation_status_name` ASC ");
if (!$stat)
{
  echo "Query error";
  exit();
}
$h_users = mysql_query("SET NAMES utf8");
$h_users = mysql_query("SELECT * FROM `tbl_klienti` WHERE `klienti_setup` like '%habibulin%' ORDER BY `klienti_name_1` ASC ");
if (!$h_users)
{
  echo "Query error";
  exit();
}
$bank = mysql_query("SET NAMES utf8");
$bank = mysql_query("SELECT * FROM `tbl_bank` WHERE `bank_operation`='0' ORDER BY `bank_date` DESC ");
//echo "<br>","SELECT `* FROM `tbl_bank` WHERE `bank_operation`='0' ORDER BY `bank_date` DESC ";
if (!$bank)
{
  echo "Query error";
  exit();
}
//==================================Habibulin parent==========================================
  $parent = mysql_query("SET NAMES utf8");
  $tQuery = "SELECT * FROM `tbl_habibulin_parent`
	    ORDER BY `habibulin_parent_id` DESC LIMIT 0, 10
	    ";
 $parent = mysql_query($tQuery); 
  
  //==============================================================
//header ('Content-Type: text/html; charset=utf8');
echo "<title>",$m_setup['menu money'],"</title>";
echo "<header><link rel='stylesheet' type='text/css' href='sturm.css'></header>";
//==================JAVA===========================================
echo "\n<script src='JsHttpRequest.js'></script>";

echo "\n<script type='text/javascript'>";

echo "\nfunction revers_summ(){";
    echo "\nvar summ =  document.getElementById('_summ');";    
    echo "\nvar summ_tmp=summ.value;";
    echo "\nsumm.value =  summ_tmp-summ_tmp-summ_tmp;";  
echo "}"; //main
//==================SET SUMM =======================
echo "\nfunction set_summ(sum){";
    echo "var summ =  document.getElementById('_summ');    
	  var memo =  document.getElementById('_memo');    
	  var sel  =  document.getElementById('_bank');
	  memo.value = sel[sel.selectedIndex].text;;
	  sum = sum.split('*');
	  summ.value=sum[1]-(sum[1]*2);";
    
echo "}"; //main
//===================HABIBULIN=======================
echo "function habibulin_new_save(nakl,value){
   // alert('budet skoro pisat v habibulina');
}


";

//===================STATUS================================
    echo "\nfunction set_new_status(value,value2){";
    echo "\nvar div_mas =  document.getElementById('div_view_'+value);";    
    echo "\ndiv_mas.innerHTML='wait...';";
      echo "\nvar req=new JsHttpRequest();";
      echo "\nreq.onreadystatechange=function(){";
      echo "\nif(req.readyState==4){";
	echo "\n var responce=req.responseText;";
	echo "\ndiv_mas.innerHTML=responce;";
    echo "\n}}";
    echo "\nreq.open(null,'set_status.php',true);";
    echo "\nreq.send({nakl:value,stat:value2});";
    echo "\n}";

echo "</script>";
echo "\n<body>\n";

//========================================================================================================
echo "\n<table border = 1 cellspacing='0' cellpadding='0'><tr><td>";

echo "\n<form method='post' action='edit_table.php'>";
echo "\n<input type='submit' name='_add' value='",$m_setup['menu add'],"'/>";
  
  echo "</td><td>";
  echo "<a href='edit_nakl_print.php?tmp=print&operation_id=",$iKlient_id,"'>",$m_setup['menu print sale'],"</a>";
  echo "<br><a href='edit_nakl_print.php?tmp=warehouse&operation_id=",$iKlient_id,"'>",$m_setup['menu print ware'],"</a>";
  echo "<br><a href='edit_nakl_print.php?tmp=bay&operation_id=",$iKlient_id,"'>",$m_setup['menu print bay'],"</a>";
  echo "<br><a href='edit_nakl_print.php?tmp=analytics&operation_id=",$iKlient_id,"'>",$m_setup['menu print analytics'],"</a>";
  echo "<br><br><a href='edit_nakl_delive.php?operation_id=",$iKlient_id,"'>",$m_setup['menu delivery'],"</a>";
  echo "<br><br><a href='send_mail.php?operation_id=",$iKlient_id,"' >",$m_setup['menu send mail'],"</a>";
  
echo "\n<input type='hidden' name='_id_value' value='"  , $iKlient_id  , "'/>";
echo "\n<input type='hidden' name='_id_name' value='" , $this_table_id_name , "'/>";
echo "\n<input type='hidden' name='_table_name' value='" , $this_table_name , "'/>";
echo "\n<input type='hidden' name='_select' value='" , $return_page , "'/>";

echo "\n<input type='hidden' name='_page_to_return' value='" , $this_page_name , "?" , $this_table_id_name, "='/>";
echo "</td></tr><tr>";
 

echo "\n<td>",$m_setup['menu money'],":</td>"; # Group klienti
echo "<td><input type='text' style='width:50px'  name='_operation_nakl' value='",mysql_result($ver,0,"operation_id"),"'/>
      
</td>";
echo "<td></td>";
echo "</tr>";

$date = date("Y-m-d G:i:s");
echo "\n<tr><td>",$m_setup['menu date-time'],":</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='operation_data' value='" . $date . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";

echo "\n<tr><td>",$m_setup['menu klient'],":</td><td>"; # Group name 1
echo "\n<input type='hidden'  style='width:400px'  name='operation_klient' value='" . mysql_result($ver,0,"operation_klient") . "'/>";
echo "\n",mysql_result($ver,0,"klienti_name_1")," (",mysql_result($ver,0,"klienti_phone_1"),")";
echo "</td><td></td>";
echo "<td></td>";
echo "</tr>";


echo "\n<tr><td>",$m_setup['menu buyer'],":</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='operation_prodavec' value='" . mysql_result($ver,0,"operation_prodavec") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";


echo "\n<tr><td>",$m_setup['menu user'],":</td><td>"; # Group name 1
echo "\n<input type='hidden'  style='width:400px'  name='operation_sotrudnik' value='" . mysql_result($ver,0,"operation_sotrudnik") . "'/>";
//echo "<td>";
//==================HABIBULIN==================================================================================================
   echo "\n<select style='width:200px' size = '4' name='_habibulin' id='habibulin_".$iKlient_id."' onChange=\"habibulin_new_save(".$iKlient_id.",this.value)\">";
    $count1=0;
    while ($count1 < mysql_num_rows($h_users))
    {
    echo "\n<option ";
    if($_SESSION[BASE.'userid']==mysql_result($h_users,$count1,"klienti_id")) echo " selected ";
    //echo $_SESSION[BASE.'userid']," - > ",mysql_result($h_users,$count1,"klienti_id");
	echo "value=" . mysql_result($h_users,$count1,"klienti_id") . ">" . mysql_result($h_users,$count1,"klienti_name_1") . "</option>";
    $count1++;
    }
  echo "</select>";
//======================================================================
    echo "<select name='_habibulin_parent' size = '4' style='width:250px'>";
    $tmp=0;
    while ($tmp < mysql_num_rows($parent))
    {
	echo "\n<option ";
	if($tmp == 0) echo " selected ";
	echo "value=" . mysql_result($parent,$tmp,"habibulin_parent_id") . ">" . mysql_result($parent,$tmp,"habibulin_parent_name") 
	      . "</option>";
	$tmp++;
    }
echo "</select>";    
//=======================================================================   
echo "<textarea name=\"_habibulin_description\" rows=\"3\" cols=\"47\"></textarea>
  
  ";

echo "</td>";
echo "<td></td>";
echo "</tr>";


echo "\n<tr><td>",$m_setup['menu edited'],":</td><td>"; # Group name 1
echo "\n<input type='hidden'  style='width:400px'  name='operation_data_edit' value='" . $date . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";


echo "\n<tr><td>",$m_setup['menu change']," ",$m_setup['menu status'],":</td><td>"; # Group name 1
echo "\n<input type='hidden'  style='width:400px'  name='operation_status' value='9'/>";
//==================STATUS==================================================================================================
   echo "\n<select style='width:400px' id='stat_".$iKlient_id."' onChange='set_new_status(".$iKlient_id.",this.value)'>";
    $count1=0;
    while ($count1 < mysql_num_rows($stat))
    {
    echo "\n<option ";
	if (mysql_result($ver,0,"operation_status") == mysql_result($stat,$count1,"operation_status_id")) echo "selected ";
    echo "value=" . mysql_result($stat,$count1,"operation_status_id") . ">" . mysql_result($stat,$count1,"operation_status_name") . "</option>";
    $count1++;
    }
  echo "</select></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";


echo "\n<tr><td>",$m_setup['menu summ'],":</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:120px;font:24px Verdana, Geneva, sans-serif;' id='_summ' name='operation_summ' value='0.00' onChange='revers_summ()'/>
       <b>SALDO : ".number_format(mysql_result($saldo,0,0),2,'.','')."</b></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";


echo "\n<tr><td>",$m_setup['menu memo'],":</td><td>"; # Group name 1
echo "\n<textarea  style='width:800px'  name='operation_memo' id='_memo'>memo</textarea></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";

echo "\n<tr><td>",$m_setup['menu bank'],":</td><td>"; # Group name 1
//==================STATUS==================================================================================================
   echo "\n<select style='width:800px' name='_bank' id='_bank' size='20' onChange='set_summ(this.value)'>";
    $count1=0;
    while ($count1 < mysql_num_rows($bank))
    {
    echo "\n<option ";
    echo "value=" . mysql_result($bank,$count1,"bank_id") ."*". mysql_result($bank,$count1,"bank_sum") . ">" . 
	mysql_result($bank,$count1,"bank_date") . " - " .
	mysql_result($bank,$count1,"bank_sum") . " " .
	mysql_result($bank,$count1,"bank_curr") . " - " .
	mysql_result($bank,$count1,"bank_description") . "</option>";
    $count1++;
    }
  echo "</select></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";

echo "\n<tr><td>Inet id:</td><td>"; # Group name 1
echo "\n<input type='hidden'  style='width:400px'  name='operation_inet_id' value='0'/>";
echo "<div id='div_view_",$iKlient_id,"'></div></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";


echo "\n<tr><td>Dell:</td><td>"; # Group name 1
echo "\n<input type='hidden'  style='width:400px'  name='operation_dell' value='0'/></td>";

echo "<td></td>";
echo "<td></td>";
echo "</tr>";




echo "\n</table></form>"; 
echo "\n</body>";

?>
