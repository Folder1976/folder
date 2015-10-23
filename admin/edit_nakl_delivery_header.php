<?php

include 'init.lib.php';
connect_to_mysql();
session_start();
if (!session_verify($_SERVER["PHP_SELF"],"none")){
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
	  WHERE 
	  `setup_menu_name` like '%menu%'

";
//echo $tQuery;
$setup = mysql_query($tQuery);
$m_setup = array();
$count=0;
while ($count<mysql_num_rows($setup)){
 $m_setup[mysql_result($setup,$count,0)]=mysql_result($setup,$count,1);
 $count++;
}
//==================================SETUP=MENU==========================================
 $count = 0;
$this_page_name = "edit_nakl_delivery_header.php";
$this_table_id_name = "operation_id";
//$this_table_name_name = "operation_id"; //"`operation_data`, `operation_klient`, `operation_summ`";

$this_table_name = "tbl_operation";
$deliv = $_GET["delivery"];
$iKlient_id = $_GET[$this_table_id_name];
//$get_klient_group = $_GET['_klienti_group'];
$iKlient_count = 0;

$ver = mysql_query("SET NAMES utf8");
$ver = mysql_query("SELECT * FROM 
		    `tbl_operation`,
		    `tbl_delivery`,
		    `tbl_klienti` 
		    WHERE 
		    `operation_id` = '" . $iKlient_id . "' 
		    and `klienti_id`=`operation_klient`
		    and `klienti_delivery_id`=`delivery_id`");

if (!$ver)
{
  echo "Query error - Operation";
  exit();
}

$stat = mysql_query("SET NAMES utf8");
$stat = mysql_query("SELECT `operation_status_id`,`operation_status_name` FROM `tbl_operation_status` ORDER BY `operation_status_name` ASC ");
if (!$stat)
{
  echo "Query error 5";
  exit();
}
$status="";
//==================STATUS==================================================================================================
   $status = "<select style='width:300px' id='stat_".mysql_result($ver,0,'operation_id')."' style='width:100px' onChange='set_new_status(".$iKlient_id.",this.value)'>";
    $count1=0;
    while ($count1 < mysql_num_rows($stat))
    {
    $status.= "<option ";
	if (mysql_result($ver,0,"operation_status") == mysql_result($stat,$count1,"operation_status_id")) $status.=  "selected ";
    $status.=  "value=" . mysql_result($stat,$count1,"operation_status_id") . ">" . mysql_result($stat,$count1,"operation_status_name") . "</option>";
    $count1++;
    }
  $status.= "</select>";
 //=====================================================================================================================

$temp_header = "template/delivery_header.html";
$tmp_header = file_get_contents($temp_header);


header ('Content-Type: text/html; charset=utf8');
//header ('Content-Type: image/jpeg');
echo "<header><link rel='stylesheet' type='text/css' href='sturm.css' media='all'></header>";
echo  "<script src='JsHttpRequest.js'></script>
	   <script type='text/javascript'>
	  function set_new_status(value,value2){
	    var req=new JsHttpRequest();
	    req.onreadystatechange=function(){
	    if(req.readyState==4){
	      var responce=req.responseText;
		div_mas.innerHTML=responce;
	      }}
	    req.open(null,'set_status.php',true);
	    req.send({nakl:value,stat:value2});
	 }

  function set_nakl_field(){
  
    var field = 'operation_memo';
    var value = $iKlient_id;
    var value2 	= '|",$m_setup['menu data delivery']," : ' + document.getElementById('data').value;
	value2 += ', ",$m_setup['menu ttn']," : ' + document.getElementById('ttn').value;
	value2 += ', ",$m_setup['menu warehouse nom']," : ' + document.getElementById('warehouse').value;
	value2 += ' ' + document.getElementById('coment').value + '|';
   	
    var req=new JsHttpRequest();
    //alert(field+' '+value+' '+value2); 
    req.open(null,'set_nakl_field.php',true);
    req.send({nakl:value,stat:value2,edit:field});
    }
    
    </script>";

    /*
  <tr><td><a href='edit_nakl_print.php?tmp=print&operation_id=&iKlient_id' target='_blank'>&nbsp &print_sale &nbsp</a></td></tr>
    <tr><td><a href='edit_nakl_print.php?tmp=warehouse&operation_id=&iKlient_id' target='_blank'>&nbsp &print_warehouse &nbsp</a></td></tr>
    <tr><td><a href='edit_nakl_print.php?tmp=bay&operation_id=&iKlient_id' target='_blank'>&nbsp &print_bay &nbsp</a></td></tr>
    <tr><td><a href='send_mail.php?operation_id=&iKlient_id&key=delivery'>&nbsp &send_mail &nbsp</a></td></tr>*/
 
$tmp_header = str_replace("&print_sale",$m_setup['menu print sale'],$tmp_header);
$tmp_header = str_replace("&print_warehouse",$m_setup['menu print ware'],$tmp_header);
$tmp_header = str_replace("&print_bay",$m_setup['menu print bay'],$tmp_header);
$tmp_header = str_replace("&send_mail",$m_setup['menu send mail'],$tmp_header);

$tmp_header = str_replace("&status_menu",$m_setup['menu status'],$tmp_header);
$tmp_header = str_replace("&phone_menu",$m_setup['menu phone'],$tmp_header);
$tmp_header = str_replace("&klient_menu",$m_setup['menu klient'],$tmp_header);
$tmp_header = str_replace("&operation_menu",$m_setup['menu nakl'],$tmp_header);

$tmp_header = str_replace("&data_menu",$m_setup['menu date'],$tmp_header);
$tmp_header = str_replace("&memo_menu",$m_setup['menu memo'],$tmp_header);
$tmp_header = str_replace("&warehouse_menu",$m_setup['menu warehouse nom'],$tmp_header);
$tmp_header = str_replace("&delivery_menu",$m_setup['menu delivery'],$tmp_header);
$tmp_header = str_replace("&iKlient_id",mysql_result($ver,0,"operation_id"),$tmp_header);
$tmp_header = str_replace("&delivery",mysql_result($ver,0,"delivery_name"),$tmp_header);
$tmp_header = str_replace("&operation_memo",mysql_result($ver,0,"operation_memo"),$tmp_header);
$tmp_header = str_replace("&klienti_memo",mysql_result($ver,0,"klienti_memo"),$tmp_header);
$tmp_header = str_replace("&klienti_name_1",mysql_result($ver,0,"klienti_name_1"),$tmp_header);
$tmp_header = str_replace("&klienti_phone_1",mysql_result($ver,0,"klienti_phone_1"),$tmp_header);
$tmp_header = str_replace("&status",$status,$tmp_header);
//$tmp_header = str_replace("&script",$script,$tmp_header);
//$tmp_header = str_replace("&currency_name_shot",mysql_result($ver,0,"currency_name_shot"),$tmp_header);
//$tmp_header = str_replace("&tovar_description",mysql_result($ver,0,"tovar_description"),$tmp_header);

echo $tmp_header;
?>
