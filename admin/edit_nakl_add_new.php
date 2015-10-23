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
$date = date("Y-m-d G:i:s");
$klienti_id="";
$klienti_name = "klient >>>";

if(!isset($_REQUEST['klienti_id'])){
      if(isset($_REQUEST['klienti_id2'])){
	  $klient = mysql_query("SET NAMES utf8");
	  $klient = mysql_query("SELECT `klienti_id`,`klienti_name_1` FROM `tbl_klienti` WHERE `klienti_id`='".$_REQUEST['klienti_id2']."'");
	 // echo "SELECT `klienti_id`,`klienti_name_1` FROM `tbl_klienti` WHERE `klienti_id`='".$_REQUEST['klienti_id2']."'";
	  $klienti_id = mysql_result($klient,0,"klienti_id");
	  $klienti_name = mysql_result($klient,0,"klienti_name_1");
	  
      }
$status = mysql_query("SET NAMES utf8");
$status = mysql_query("SELECT `operation_status_id`,`operation_status_name` FROM `tbl_operation_status` ORDER BY `operation_status_name` ASC");
if (!$status)
{
  echo "Query error - Status";
  exit();
}
$firm = mysql_query("SET NAMES utf8");
$firm = mysql_query("SELECT * FROM `tbl_firms`");
if (!$firm)
{
  echo "Query error - Firm";
  exit();
}
header ('Content-Type: text/html; charset=utf8');
echo "<title>NEW</title>";
echo "<header><link rel='stylesheet' type='text/css' href='sturm.css'></header>";
//==================JAVA===========================================
echo "\n<script src='JsHttpRequest.js'></script>";
echo "\n<script type='text/javascript'>";
//===================JAVA=OPEN=FIND==============================
    echo "
    function find_window_script(tbl,id,name,sel_name,target){
    var div_mas =  document.getElementById('find_window');
      div_mas.innerHTML=sel_name+'<br><input type=\"text\"  style=\"width:600px\" onKeyPress=\"find_script(\''+tbl+'\',\''+id+'\',\''+name+'\',this.value)\">';
     div_mas.innerHTML+='<br><select id=\"find_sel\" size=15 style=\"width:600px\" ondblclick=\"set_select_value(\''+target+'\')\"></select>';
     }";
//===============================================================
    echo "
    function set_select_value(target){
    var div_id =  document.getElementById(target);
    var sel =  document.getElementById('find_sel');
    var div_text =  document.getElementById(target+'_text');
      div_id.value=sel.value;
      div_text.value=sel[sel.selectedIndex].text;
     }";
//===============================================================
echo "   function find_window_script_klient_group(tbl,id,name,sel_name,target,find){
//alert(' `klienti_group`=\''+find+'\'');
	    find_window_script(tbl,id,name,sel_name,target);
	    find_script(tbl,id,name,' `klienti_group`=\''+find+'\'');
}
";
    echo "
    function find_script(tbl,id,name,find){
    var div_mas =  document.getElementById('find_sel');
    div_mas.options.length=0;
    var req=new JsHttpRequest();
      req.onreadystatechange=function(){
      if(req.readyState==4){
	var responce=req.responseText;
	var str1=responce.split('||');
	var str2='';
	  var count=0;
	  while(str1[count]){
	  str2=str1[count].split('|');
	    div_mas.options[count]=new Option(str2[1],str2[0]);;
	    count++;
	    }
    }}
    req.open(null,'find_sort.php',true);
    req.send({table:tbl,table_id:id,table_name:name,find_str:find});";
    echo "}";

echo "</script>";

$iKlientGroupSelect=4; //default id for All
if(isset($_REQUEST["_klienti_group"])) $iKlientGroupSelect = $_REQUEST["_klienti_group"];

$klienti_group = mysql_query("SET NAMES utf8");
$klienti_group = mysql_query("SELECT `klienti_group_id`,`klienti_group_name` FROM `tbl_klienti_group` ORDER BY `klienti_group_name` ASC");
if (!$klienti_group)
{
  echo "Query error - Klienti Group";
  exit();
}




echo "<table class='menu_top' width=100%><tr><td width='33%'></td><td align='center' width='33%'>";
echo "<table width='100px'><tr><td colspan=2 align=center>";
echo "<form method='post' action='edit_nakl_add_new.php'>";
echo "<input type='submit' name='_add' value='",$m_setup['menu add'],"'/>";
echo "</td><tr>";
//==========================================================
echo "<tr><td>";
echo 	$m_setup['menu date-time'],"</td><td><input type='text'  style='width:200px'  name='date' value='" . $date . "'/>";
echo "</td></tr>";
//==========================================================
echo "<tr><td>";
echo 	$m_setup['menu buyer'];
echo "</td><td><select name='buyer' style='width:200px'>";
$count=0;
while ($count < mysql_num_rows($firm))
{
  echo "\n<option ";
	if (1 == mysql_result($firm,$count,"firms_id")) echo "selected ";
  echo "value=" . mysql_result($firm,$count,"firms_id") . ">" . mysql_result($firm,$count,"firms_name");
  echo "</option>";
  $count++;
}
echo "</select>";
echo "</td></tr>";
//==========================================================
echo "<tr><td>";
echo 	$m_setup['menu klient'],"</td><td>
	<input type='hidden'  name='klienti_id' id='operation_klient' value='$klienti_id'/>
	<input type='text' disabled  style='width:300px'  name='operation_klient' id='operation_klient_text' value='$klienti_name'/>";
echo " <a href='#none' onClick='find_window_script(\"tbl_klienti\",\"klienti_id\",\"klienti_name_1\",\"Klienti find/Sort\",\"operation_klient\");'> [",$m_setup['menu find'],"] </a>";
//==========Select klienti group=========================================
echo "<select name='_klienti_group' style='width:240px' OnChange='find_window_script_klient_group(\"tbl_klienti\",\"klienti_id\",\"klienti_name_1\",\"Klienti find/Sort\",\"operation_klient\",this.value);'>";
$count=0;
while ($count < mysql_num_rows($klienti_group))
{
  echo "\n<option ";
	if ($iKlientGroupSelect == mysql_result($klienti_group,$count,"klienti_group_id")) {echo "selected ";}
  echo "value=" . mysql_result($klienti_group,$count,"klienti_group_id") . ">" . mysql_result($klienti_group,$count,"klienti_group_name");
  echo "</option>";
  $count++;
}
echo "</select>";

//===============================================================================
echo "</td></tr>";
//==========================================================
echo "<tr><td>";
echo 	$m_setup['menu status'];
echo "</td><td><select name='status' style='width:200px'>";
$count=0;
while ($count < mysql_num_rows($status))
{
  echo "\n<option ";
	if (2 == mysql_result($status,$count,"operation_status_id")) echo "selected ";
  echo "value=" . mysql_result($status,$count,"operation_status_id") . ">" . mysql_result($status,$count,"operation_status_name");
  echo "</option>";
  $count++;
}
echo "</select>";
echo "</td></tr>";
//==========================================================
echo "<tr><td>";
echo 	$m_setup['menu memo'],"</td><td><input type='text'  style='width:600px'  name='memo' value=''/>";
echo "</td></tr>";
//==========================================================
echo "<tr><td>";
echo 	$m_setup['menu user'],"</td><td>
	<input type='hidden'  style='width:50px'  name='user' value='",$_SESSION[BASE.'userid'],"'/>
	<input type='text' disabled style='width:300px'  name='user' value='",$_SESSION[BASE.'username'],"'/>";
echo "</td></tr>";
//==========================================================

echo "</table>";

 echo " <div id='find_window'></div><br>
  <div id='find_div'></div>
  <div id='view'></div>";
echo "</form>";

echo "</td><td align=left>";


echo "</td></tr></table>";

}else{
  $ver = mysql_query("SET NAMES utf8");
  $tQuery = "INSERT INTO `tbl_operation` SET ";
  $tQuery .= "`operation_data`='".$_REQUEST['date']."',";
  $tQuery .= "`operation_klient`='".$_REQUEST['klienti_id']."',";
  $tQuery .= "`operation_prodavec`='".$_REQUEST['buyer']."',";
  $tQuery .= "`operation_sotrudnik`='".$_SESSION[BASE.'userid']."',";
  $tQuery .= "`operation_data_edit`='".$date."',";
  $tQuery .= "`operation_status`='".$_REQUEST['status']."',";
  $tQuery .= "`operation_summ`='0',";
  $tQuery .= "`operation_memo`='".$_REQUEST['memo']."',";
  $tQuery .= "`operation_inet_id`='0',";
  $tQuery .= "`operation_dell`='0',";
  $tQuery .= "`operation_beznal_rah_date`='".$_REQUEST['date']."',";
  $tQuery .= "`operation_beznal_nakl_date`='".$_REQUEST['date']."'";
//echo $tQuery;
  $ver = mysql_query($tQuery);
    if (!$ver){
      echo "Query error";
      exit();
    }
 $iKlient_id=mysql_insert_id();
 
echo "Add new nakl #",$iKlient_id; 
header ('Refresh: 1; url=' . 'edit_nakl.php?operation_id=' . $iKlient_id . '&_klienti_group=0');
}


?>
