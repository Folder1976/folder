<?php

include 'init.lib.php';
connect_to_mysql();
session_start();
if (!session_verify($_SERVER["PHP_SELF"],"+")){
  exit();
}
$html_out ="";
//$habibulin_parent=1;

$klienti_sort = "";
$klient_group = 4;
if(isset($_REQUEST["klient_group"])) $klient_group=(int)mysql_real_escape_string($_REQUEST["klient_group"]);
if($klient_group != 4) $klienti_sort = " WHERE `klienti_group` = '$klient_group' ";
    
$klient_id = 0;
if(isset($_REQUEST["klient_id"])) $klient_id=(int)mysql_real_escape_string($_REQUEST["klient_id"]);

$klient_start = date("Y-m-d G:i:s");
if(isset($_REQUEST["klient_start"])) $klient_start=mysql_real_escape_string($_REQUEST["klient_start"]);

$klient_end = date("Y-m-d G:i:s");
if(isset($_REQUEST["klient_end"])) $klient_end=mysql_real_escape_string($_REQUEST["klient_end"]);

$klient_procent = 5;
if(isset($_REQUEST["klient_procent"])) $klient_procent=(int)mysql_real_escape_string($_REQUEST["klient_procent"]);

$klient_status = 0;
if(isset($_REQUEST["operation_status_id"])) $klient_status=(int)mysql_real_escape_string($_REQUEST["operation_status_id"]);

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
$tQuery = "SELECT `klienti_id`,`klienti_name_1` FROM `tbl_klienti` $klienti_sort  ORDER BY `klienti_name_1` ASC";
$sql_kli = mysql_query("SET NAMES utf8");
$sql_kli = mysql_query($tQuery);
if (!$sql_kli)
{
  echo "Query error Klienti";
  exit();
}
$tQuery = "SELECT `klienti_group_id`,`klienti_group_name` FROM `tbl_klienti_group` ORDER BY `klienti_group_name` ASC";
$sql_grp = mysql_query("SET NAMES utf8");
$sql_grp = mysql_query($tQuery);
if (!$sql_grp)
{
  echo "Query error Klienti Group";
  exit();
}
$tQuery = "SELECT `operation_status_id`,`operation_status_name` FROM `tbl_operation_status` ORDER BY `operation_status_name` ASC";
$sql_status = mysql_query("SET NAMES utf8");
$sql_status = mysql_query($tQuery);
if (!$sql_grp)
{
  echo "Query error Operation Status";
  exit();
}


$html_out .= "<header><title>*** Аналитика ***</title>
<link rel='stylesheet' type='text/css' href='sturm.css'></header>";

//==================JAVA===========================================
$html_out .= "\n<script src='JsHttpRequest.js'></script>";
$html_out .= "\n<script type='text/javascript'>";
//================================SET COLOR=====================================
//================================SET PRICE===============kogda vibor konkretnoj ceni

$html_out .= "
  function info(msg){
	  document.getElementById('info').innerHTML = msg;
	  if(msg==''){
	  	  document.getElementById('info').style.display = 'none';
	  }else{
	  	  document.getElementById('info').style.display = 'block';
	  }
  }

  function open_excel(){
  info('Нужно ждать!');
  var klient = document.getElementById('klient_id').value;
  var group = document.getElementById('klient_group').value;
  var start = document.getElementById('klient_start').value;
  var end = document.getElementById('klient_end').value;
  var procent = document.getElementById('klient_procent').value;
  var status = document.getElementById('operation_status_id').value;
  
  var req=new JsHttpRequest();
      req.onreadystatechange=function(){
      //alert(req.readyState);
      if(req.readyState==4){
	var responce=req.responseText;
	
	info('');
	  if(responce=='null'){
	    alert('Пусто!');
	  }else{
	    alert(responce);
	    document.location.href = responce;
	  }
      }}
      req.open(null,'get_excel_analitics.php',true);
      req.send({klient:klient,group:group,start:start,end:end,procent:procent,status:status});  
    }
  ";



    $html_out .= "\n</script></header>";
//================== END JAVA ================================



$html_out .= "\n<form method='get' action='get_analitics.php'>";

$html_out .= "<div id='row*table'>
	  <table class='main'>"; //class='table'

$html_out .= "<tr><td>Рахувати %: </td>"; 
  $html_out .= "<td>
    <input type='text' style='width:130px' name='klient_procent' id='klient_procent' 
      value='$klient_procent'/>
    </td></tr>";
  //----
$html_out .= "<tr><td>Дата начала: </td>"; 
  $html_out .= "<td>
    <input type='text' style='width:130px' name='klient_start' id='klient_start' 
      value='$klient_start'/>
    </td></tr>";
  //----
$html_out .= "<tr><td>Дата конца: </td>"; 
  $html_out .= "<td>
    <input type='text' style='width:130px' name='klient_end' id='klient_end' 
      value='$klient_end'/>
    </td></tr>";
  //----
  
 $html_out .= "<tr><td>Группа клиентов: </td><td>
    <select style='width:230px' name='klient_group' id='klient_group'
    onChange='submit();'>";
      $tmp=0;
      while ($tmp < mysql_num_rows($sql_grp))
	{ $html_out .= "\n<option ";
	if ($klient_group == mysql_result($sql_grp,$tmp,"klienti_group_id")) $html_out .= "selected ";
      $html_out .= "value=" . mysql_result($sql_grp,$tmp,"klienti_group_id") . ">".mysql_result($sql_grp,$tmp,"klienti_group_name")."</option>";
      $tmp++;
      }
    $html_out .= "</select></td>";
  //-----  
 $html_out .= "<tr><td>Клиент: </td><td>
    <select style='width:230px' name='klient_id' id='klient_id'
    onChange='submit();'>";
          $html_out .= "\n<option ";
	if ($klient_id == 0) $html_out .= "selected ";
	  $html_out .= "value=0> * ВСI *</option>";
      $tmp=0;
      while ($tmp < mysql_num_rows($sql_kli))
	{ $html_out .= "\n<option ";
	if ($klient_id == mysql_result($sql_kli,$tmp,"klienti_id")) $html_out .= "selected ";
      $html_out .= "value=" . mysql_result($sql_kli,$tmp,"klienti_id") . ">".mysql_result($sql_kli,$tmp,"klienti_name_1")."</option>";
      $tmp++;
      }
    $html_out .= "</select></td></tr>";
  //-----  
 $html_out .= "<tr><td>Статус: </td><td>
    <select style='width:230px' name='operation_status_id' id='operation_status_id'
    onChange='submit();'>";
      $tmp=0;
      while ($tmp < mysql_num_rows($sql_status))
	{ $html_out .= "\n<option ";
	if ($klient_status == mysql_result($sql_status,$tmp,"operation_status_id")) $html_out .= "selected ";
      $html_out .= "value=" . mysql_result($sql_status,$tmp,"operation_status_id") . ">".mysql_result($sql_status,$tmp,"operation_status_name")."</option>";
      $tmp++;
      }
    $html_out .= "</select></td></tr>";
  //-----  
 
 
  

  $html_out .= "<tr><td></td>
	  <td><a href=\"javascript:open_excel();\"><img src=\"../resources/img/excel.jpg\" width=\"150px\"></a>
    </td></tr>";

$html_out .= "</table>
      </div>";
echo $html_out;
echo "<div id='row*table_end'></table></div>
<div id='info' class='info'></div>
      \n</form>
      \n</body>";
?>
