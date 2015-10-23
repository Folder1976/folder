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
$this_page_name = "edit_nakl_head.php";
$this_table_id_name = "operation_id";
$this_table_name_name = "operation_id"; //"`operation_data`, `operation_klient`, `operation_summ`";

$this_table_name = "tbl_operation";
//$sort_find = $_GET["_sort_find"];
//$get_klient_group = $_GET['klienti_group'];
$iKlientGroupSelect = $_GET["_klienti_group"];
$iKlient_id = $_GET[$this_table_id_name];
$sort_find = "";
if(isset($_REQUEST["_sort_find"])) $sort_find=$_REQUEST["_sort_find"];
$iKlient_count = 0;


$ver = mysql_query("SET NAMES utf8");
$ver = mysql_query("SELECT * FROM `tbl_operation` WHERE `operation_id` = '" . $iKlient_id . "' and `operation_dell`='0'");

if (!$ver)
{
  echo "Query error - Operation";
  exit();
}
$sotrudnik = mysql_query("SET NAMES utf8");
$sotrudnik = mysql_query("SELECT `klienti_name_1` FROM `tbl_klienti` WHERE `klienti_id` = '".mysql_result($ver,0,"operation_sotrudnik")."'");

if (!$sotrudnik)
{
  echo "Query error - Sotrudnik<br>","SELECT `klienti_name_1` FROM `tbl_klienti` WHERE `klienti_id` = '".mysql_result($ver,0,"operation_sotrudnik")."'";
  exit();
}
$prodavec = mysql_query("SET NAMES utf8");
$prodavec = mysql_query("SELECT * FROM `tbl_firms` ORDER BY `firms_name` ASC");

if (!$prodavec)
{
  echo "Query error - Prodavec";
  exit();
}

$level = mysql_query("SET NAMES utf8");
$tQuery="SELECT `operation_klient`,SUM(`operation_summ`) as summ_all FROM `tbl_operation`,`tbl_operation_status` WHERE `operation_klient` = '" . mysql_result($ver,0,"operation_klient") . "' and `operation_dell`='0' and `operation_status`=`operation_status_id` and `operation_status_level`='1' GROUP BY `operation_klient`";
$level = mysql_query($tQuery);
//echo $tQuery;
if (!$level)
{
  echo "Query error";
  exit();
}
$summ_all = mysql_query("SET NAMES utf8");
$summ_all = mysql_query("SELECT SUM(`operation_summ`) AS summ_all FROM `tbl_operation` WHERE `operation_klient` = '" . mysql_result($ver,0,"operation_klient") . "'");

if (!$summ_all)
{
  echo "Query error - Operation";
  exit();
}

$status = mysql_query("SET NAMES utf8");
$status = mysql_query("SELECT `operation_status_id`,`operation_status_name` FROM `tbl_operation_status`");
if (!$status)
{
  echo "Query error - Status";
  exit();
}

$klienti = mysql_query("SET NAMES utf8");
$sort_find_where = "";
if ($sort_find != null){
$sort_find_where = " WHERE upper(klienti_name_1) like '%" . mb_strtoupper($sort_find,'UTF-8') . "%' or `klienti_phone_1` like '%" . $sort_find . "%'";
$iKlientGroupSelect=0;
}

$field_select = "`klienti_id`,";
$field_select .= "`klienti_name_1`,";
$field_select .= "`klienti_phone_1`,";
$field_select .= "`klienti_group`";
//$field_select .= "`klienti_prioritet`,";
//$field_select .= "`klienti_saldo`";

if ($iKlientGroupSelect == 0){
  $klienti = mysql_query("SELECT ".$field_select." FROM `tbl_klienti`" . $sort_find_where);// WHERE `klienti_group`='" . $iKlientGroupSelect . "'");
}else{
  $klienti = mysql_query("SELECT ".$field_select." FROM `tbl_klienti` WHERE `klienti_group`='" . $iKlientGroupSelect . "'");
}
if (!$klienti)
{
  echo "Query error - Klienti";
  exit();
}

$klienti_group = mysql_query("SET NAMES utf8");
$klienti_group = mysql_query("SELECT `klienti_group_id`,`klienti_group_name` FROM `tbl_klienti_group`");
if (!$klienti_group)
{
  echo "Query error - Klienti Group";
  exit();
}


header ('Content-Type: text/html; charset=utf8');
echo "<title>Operation edit</title>";
echo "<header><link rel='stylesheet' type='text/css' href='sturm.css'></header>";
//==================JAVA===========================================
echo "\n<script src='JsHttpRequest.js'></script>";
echo "\n<script type='text/javascript'>";
//===================STATUS================================
    echo "\nfunction set_new_status(value,value2){";
    echo "\nvar div_mas =  document.getElementById('_tmp');";    
    echo "\ndiv_mas.value='wait...';";
      echo "\nvar req=new JsHttpRequest();";
      echo "\nreq.onreadystatechange=function(){";
      echo "\nif(req.readyState==4){";
	echo "\n var responce=req.responseText;";
	echo "\ndiv_mas.value=responce;";
	echo "\ntop.location.reload();"; // dopisat reload====================================================
 	echo "\nlocation.reload();"; // dopisat reload====================================================
    echo "\n}}";
    echo "\nreq.open(null,'set_status.php',true);";
    echo "\nreq.send({nakl:value,stat:value2});";
    echo "\n}";
//=============SET NAKL FIELD====================================
        echo "function set_nakl_field(value,value2,field){
		if(field == 'operation_on_web'){
		    value2 = document.getElementById(field).checked;
		}
	      var div_mas =  document.getElementById('_tmp');";    
    echo "\ndiv_mas.value='wait...';";
      echo "\nvar req=new JsHttpRequest();";
      echo "\nreq.onreadystatechange=function(){";
      echo "\nif(req.readyState==4){";
	echo "\n var responce=req.responseText;";
	echo "\ndiv_mas.value=responce;";
	echo "\nlocation.reload();";
    echo "\n}}";
    echo "\nreq.open(null,'set_nakl_field.php',true);";
    echo "\nreq.send({nakl:value,stat:value2,edit:field});";
    echo "\n}";
//==================END JAVA ============================================

//===================JAVA=OPEN=FIND==============================
    echo "
    function find_window_script(tbl,id,name,sel_name,target){
    var div_mas =  document.getElementById('find_window');
      div_mas.innerHTML=sel_name+'<br><input type=\"text\"  style=\"width:300px\" onKeyPress=\"find_script(\''+tbl+'\',\''+id+'\',\''+name+'\',this.value)\">';
     div_mas.innerHTML+='<br><select id=\"find_sel\" size=5 style=\"width:300px\" ondblclick=\"set_select_value(\''+target+'\')\"></select>';
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

    echo "\n</script>";
//==================END JAVA ============================================

echo "\n<body>\n";
//echo "5465465";
echo "\n<table class='menu_top' cellspacing='0' cellpadding='0'><tr><td valign='top'>";

//========================================================================================================
echo "<table class='menu_top' cellspacing='0' cellpadding='0'><tr><td>";//class='menu_top'


echo "\n<form method='post' action='edit_table.php'>";


echo "\n<input type='hidden' name='_id_value' value='"  , $iKlient_id  , "'/>";
echo "\n<input type='hidden' name='_id_name' value='" , $this_table_id_name , "'/>";
echo "\n<input type='hidden' name='_table_name' value='" , $this_table_name , "'/>";

echo "\n<input type='hidden' name='_page_to_return' value='" , $this_page_name , "?" , $this_table_id_name, "='/>";

echo "\n<table border = 1 cellspacing='0' cellpadding='0'>";


// ============ ROW 1 ==============
echo "\n<tr><td><input type='hidden' name='_add' value='add'/></td>"; //key
echo "\n<td>N#/",$m_setup['menu date-time'],":</td><td>"; # Group name 1
echo "<input type='text' class='nakl_header' style='width:60px'  name='_operation_id' value='" . mysql_result($ver,0,"operation_id") . "'/>
      <input type='text' class='nakl_header' style='width:170px'  name='operation_data' value='" . mysql_result($ver,0,"operation_data") . "' onChange='set_nakl_field(",$iKlient_id,",this.value,\"operation_data\")'/>";
echo "</td><td>";
echo $m_setup['menu klient'],":";
echo "</td><td>";
echo "\n<select name='operation_klient' id='operation_klient' style='width:250px' onChange='set_nakl_field(",$iKlient_id,",this.value,\"operation_klient\")'>";
$count=0;
$klient_position=0;
while ($count < mysql_num_rows($klienti))
{
  echo "\n<option ";
	if (mysql_result($ver,0,"operation_klient") == mysql_result($klienti,$count,"klienti_id")){;
	echo "selected ";
	$klient_position=$count;
	}
  echo "value=" . mysql_result($klienti,$count,"klienti_id") . ">" . mysql_result($klienti,$count,"klienti_name_1");
  echo " (";
  echo mysql_result($klienti,$count,"klienti_phone_1");
  echo ")";
  echo "</option>";
  $count++;
}
echo "</select>";
echo "<a href='edit_klient.php?klienti_id=", mysql_result($ver,0,'operation_klient'),"' target='_blank'>",$m_setup['menu edit'],"</a>
    <a href='#none' onClick='find_window_script(\"tbl_klienti\",\"klienti_id\",\"klienti_name_1\",\"Klienti find/Sort\",\"operation_klient\")'> [",$m_setup['menu find'],"] </a>
";
echo "</td><td>";
$level_summ=0;
if(mysql_num_rows($level)) $level_summ=mysql_result($level,0,"summ_all");
//if(isset(mysql_result($level,0,'summ_all')) echo mysql_result($level,0,"summ_all");
//$level_summ=mysql_result($level,0,"summ_all");



echo "</td><td>";
echo "</td><td rowspan=\"4\" style=\"font-size:9px;\">";
  echo "<a href=\"edit_nakl_oplata.php?operation_id=",$iKlient_id,"\" target=\"_blank\">",$m_setup['menu money'],"</a>";
  echo "<br><a href=\"edit_nakl_print.php?tmp=print&operation_id=",$iKlient_id,"\" target=\"_blank\">",$m_setup['menu print sale'],"</a>";
  echo "<br><a href=\"edit_nakl_print.php?tmp=warehouse&operation_id=",$iKlient_id,"\" target=\"_blank\">",$m_setup['menu print ware'],"</a>";
  echo "<br><a href=\"edit_nakl_print.php?tmp=bay&operation_id=",$iKlient_id,"\" target=\"_blank\">",$m_setup['menu print bay'],"</a>";
  echo "<br><a href=\"edit_nakl_print.php?tmp=analytics&operation_id=",$iKlient_id,"\" target=\"_blank\">",$m_setup['menu print analytics'],"</a>";
  echo "<br><a href=\"barcode.php?operation_id=",$iKlient_id,"\" target=\"_blank\">",$m_setup['menu print barcode'],"</a>";
  echo "<br><a href=\"barcode.php?key=price&operation_id=",$iKlient_id,"\" target=\"_blank\">",$m_setup['menu print price'],"</a>";
  echo "<br><a href=\"barcode.php?key=price_ware&operation_id=",$iKlient_id,"\" target=\"_blank\">",$m_setup['menu print price war'],"</a>";
  echo "<br><a href=\"edit_nakl_delive.php?operation_id=",$iKlient_id,"\" target=\"_blank\">",$m_setup['menu delivery'],"</a>";
  echo "<br><a href=\"send_mail.php?operation_id=",$iKlient_id,"\" target=\"_blank\">",$m_setup['menu send mail'],"</a>";


echo "</td></tr>";




// ============ ROW 2 ==============

echo "\n<tr><td><input type='hidden' name='_save' value='save'/></td>"; //key
echo "\n<td>",$m_setup['menu status'],":</td><td>"; # Group name 1
echo "\n<select name='operation_status' style='width:235px' onChange='set_new_status(",$iKlient_id,",this.value)'>";
$count=0;
while ($count < mysql_num_rows($status))
{
  echo "\n<option ";
	if (mysql_result($ver,0,"operation_status") == mysql_result($status,$count,"operation_status_id")) echo "selected ";
  echo "value=" . mysql_result($status,$count,"operation_status_id") . ">" . mysql_result($status,$count,"operation_status_name");
  echo "</option>";
  $count++;
}
echo "</select>";
 echo "</td><td>",$m_setup['menu buyer'],":";
echo "</td><td>";
//echo "\n<input type='text'  style='width:100px'  name='operation_prodavec' value='" . mysql_result($ver,0,"operation_prodavec") . "'/>";
echo "\n<select name='operation_prodavec' style='width:200px' onChange='set_nakl_field(",$iKlient_id,",this.value,\"operation_prodavec\")'>";
$count=0;
while ($count < mysql_num_rows($prodavec))
{
  echo "\n<option ";
	if (mysql_result($ver,0,"operation_prodavec") == mysql_result($prodavec,$count,"firms_id")) echo "selected ";
  echo "value=" . mysql_result($prodavec,$count,"firms_id") . ">" . mysql_result($prodavec,$count,"firms_name");
  echo "</option>";
  $count++;
}
echo "</select>";
echo "system:<input type='text' id='_tmp'  style='width:80px' value='-----'>";
echo "<input type='checkbox'  id='operation_on_web' onChange='set_nakl_field(",$iKlient_id,",this.value,\"operation_on_web\")'";
	if(mysql_result($ver,0,"operation_on_web")>0) echo " checked ";
	echo "/> on web";
echo "</td><td>";
echo "</td><td>";
echo "</td></tr>";



// ============ ROW 3 ==============
echo "<tr><td>";
echo "</td><td>";
echo "(<a href='#none' onClick='set_new_status(".mysql_result($ver,0,'operation_id').",-1)'>",$m_setup['menu dell'],"</a>)";
echo "</td><td>";
echo $m_setup['menu level'],":<input type='text' disabled style='width:60px'  name='_klienti_prioritet' value='" . number_format($level_summ,2,".","") . "'/>";
echo $m_setup['menu saldo'],":<input type='text' disabled style='width:80px'  name='_klienti_saldo' value='" . number_format(mysql_result($summ_all,0,"summ_all"),2,".","") . "'/>";
echo "</td><td>";
echo $m_setup['menu edited'],":";
echo "</td><td>";
echo "<input type='hidden'  style='width:100px'  name='operation_sotrudnik' value='" . mysql_result($ver,0,"operation_sotrudnik") . "'/>
      <input type='text' disabled style='width:200px'  name='operation_sotrudnik' value='" . mysql_result($sotrudnik,0,"klienti_name_1") . "'/>
      <input type='text' disabled style='width:130px'  name='operation_data_edit' value='" . mysql_result($ver,0,"operation_data_edit") . "'/>";
echo ""; // pusto
echo "</td><td rowspan=\"2\" width=\"60px\" align=\"center\">";
echo "<img src='icon/find.jpg' name='_frame_resize' value='find' onclick='window.top.res()'>";
echo "</td>";
echo "</tr>";




// ============ ROW 4 ==============
echo "\n<tr><td><input type='hidden' name='_dell' value='dell'/></td>";//key
echo "<td>";
echo $m_setup['menu memo'],":";
echo "</td><td colspan='3'>";
echo "\n<input type='text' style='width:100%'  name='operation_memo' value='" . mysql_result($ver,0,"operation_memo") . "' onChange='set_nakl_field(",$iKlient_id,",this.value,\"operation_memo\")'/>";
echo "</td></tr>";

echo "\n</table></form>"; 


echo "</td><td>";

echo "<table border=1 cellspacing='0' cellpadding='0'><tr><td>";
echo "\n<form method='get' action='edit_nakl_header.php'>";
echo "\n<input type='hidden' name='operation_id' value='"  , $iKlient_id  , "'/>";
echo "\nSelect Group:";
echo "</td><td>";
echo "<select name='_klienti_group' style='width:200px' OnChange='submit();'>";
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
echo "</td><td>";
echo "<a href='edit_klienti_group.php?klienti_group_id=", $iKlientGroupSelect,"' target='_blank'>Edit</a>";

echo "</td></tr><tr><td>";

echo "\n<br>Find/Sort:";
echo "</td><td>";
echo "<input type='text' style='width:200px'  name='_sort_find' value='' OnChange='submit();'/></td></tr>";
//echo "<input type='text' style='width:200px'  name='_sort_find' value='" . $sort_find . "' OnChange='submit();'/></td></tr>";
echo "</td><td>";
echo "</td><td>";
echo "</td></tr></table>";
echo "</form>";


echo "</td></tr>";
echo "\n</table>"; 

  echo "
  </td><td valign='top'>
  <div id='find_window'></div><br>
  <div id='find_div'></div>
  <div id='view'></div>
  </td></tr></table> ";

echo "\n</body>";

function reload_page(){
header ("Location: http://sturm.com.ua/");
}

?>
