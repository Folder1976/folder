<?php
include 'init.lib.php';
connect_to_mysql();
session_start();

if (!session_verify($_SERVER["PHP_SELF"],"+")){
  exit();
}
echo '<h3><a href=\'/admin/setup.php\'>>> Настройки</a></h3>';

//==================================SETUP===========================================
if (!isset($_SESSION[BASE.'lang'])){
  $_SESSION[BASE.'lang']=1;
}
$setup = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	  `setup_menu_name`, 
	  `setup_menu_".$_SESSION[BASE.'lang']."`
	  FROM `tbl_setup_menu`
";
echo $tQuery."<br>";
$setup = mysql_query($tQuery);
$m_setup = array();
$count=0;
while ($count<mysql_num_rows($setup)){
 $m_setup[mysql_result($setup,$count,0)]=mysql_result($setup,$count,1);
 $count++;
}
//print_r(var_dump($m_setup));
//==================================SETUP=MENU==========================================
$count = 0;
$this_page_name = "edit_klient.php";
$this_table_id_name = "klienti_id";
$this_table_name_name = "klienti_name_1";
$this_table_name = "tbl_klienti";

$return_page = "";
if(isset($_REQUEST['_return_page'])) $return_page=$_REQUEST['_return_page'];

$sort_find = "";
if(isset($_REQUEST["_sort_find"])) $sort_find=$_REQUEST["_sort_find"];

$sort_find_deliv = "";
if(isset($_REQUEST["_sort_find_deliv"]))$sort_find_deliv=$_REQUEST["_sort_find_deliv"];

$iKlient_id = "";
if(isset($_REQUEST[$this_table_id_name]))$iKlient_id=$_REQUEST[$this_table_id_name];

if(!$iKlient_id) $iKlient_id=1;
$iKlient_count = 0;

$ver = mysql_query("SET NAMES utf8");
$ver = mysql_query("SELECT * FROM " . $this_table_name . " WHERE " . $this_table_id_name . " = " . $iKlient_id);
//echo "SELECT * FROM " . $this_table_name . " WHERE " . $this_table_id_name . " = " . $iKlient_id;
if (!$ver)
{
  echo "Query error - ", $this_table_name;
  exit();
}
$kli_grp = mysql_query("SET NAMES utf8");
$kli_grp = mysql_query("SELECT `klienti_group_id`,`klienti_group_name` FROM `tbl_klienti_group` ORDER BY `klienti_group_name` ASC");// WHERE `klienti_group_id`= '".mysql_result($ver,0,"klienti_group")."'");
if (!$kli_grp)
{
  echo "Query error - tbl_klienti_group";
  exit();
}
$tovar = mysql_query("SET NAMES utf8");
$tovar = mysql_query("SELECT * FROM `tbl_tovar` LIMIT 0,1");
if (!$tovar)
{
  echo "Query error - tbl_tovar";
  exit();
}
$price = mysql_query("SET NAMES utf8");
$price = mysql_query("SELECT * FROM tbl_price");# WHERE klienti_id = " . $iKlient_id);
if (!$price)
{
  echo "Query error - tbl_price";
  exit();
}
$price_tovar = mysql_query("SET NAMES utf8");
$price_tovar = mysql_query("SELECT * FROM tbl_price_tovar");# WHERE klienti_id = " . $iKlient_id);
if (!$price_tovar)
{
  echo "Query error - tbl_price_tovar";
  exit();
}
$deliv = mysql_query("SET NAMES utf8");
$deliv = mysql_query("SELECT `delivery_id`,`delivery_name` FROM `tbl_delivery` WHERE `delivery_id`='".mysql_result($ver,0,"klienti_delivery_id")."'");# WHERE klienti_id = " . $iKlient_id);
if (!$deliv)
{
  echo "Query error - tbl_price";
  exit();
}

//===========================================================================================================

header ('Content-Type: text/html; charset=utf8');
echo "<header><link rel='stylesheet' type='text/css' href='sturm.css'></header>";
echo "\n<script src='JsHttpRequest.js'></script>";
echo "\n<script type='text/javascript'>";
//===================JAVA================================
    echo "
       function info(msg){
	  document.getElementById('info').innerHTML = msg;
	  if(msg==''){
	  	  document.getElementById('info').style.display = 'none';
	  }else{
	  	  document.getElementById('info').style.display = 'block';
	  	  //alert(msg);
	  }
    }
    
    
    function find_window_script(tbl,id,name,sel_name,target){
    var div_mas =  document.getElementById('find_window');
      div_mas.innerHTML=sel_name+'<br><input type=\"text\"  style=\"width:600px\" onKeyPress=\"find_script(\''+tbl+'\',\''+id+'\',\''+name+'\',this.value)\">';
     div_mas.innerHTML+='<br><select id=\"find_sel\" size=30 style=\"width:600px\" ondblclick=\"set_select_value(\''+target+'\')\"></select>';
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
    if(find.length > 2){
    info('Wait...');
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
	    info('');
    }}
    req.open(null,'find_sort.php',true);
    req.send({table:tbl,table_id:id,table_name:name,find_str:find})
    }}";

    echo "\n</script>";
//==================END JAVA ============================================
    
echo "<title>Klient edit</title>";
echo "\n<body>\n";


//========================================================================================================
echo "\n<form method='get' action='" , $this_page_name , "'>";
echo "\n<table border = 0 cellspacing='0' cellpadding='0'>";

echo "\n<tr><td>",$m_setup['menu selected'],":</td><td>"; # Group klienti
echo "\n<input type='hidden'  style='width:50px'  name='klienti_id' id='klienti_id' value='" . mysql_result($ver,0,"klienti_id") . "' OnChange='submit();'/>";
echo "\n<input type='text'  style='width:400px'  id='klienti_id_text' value='" . mysql_result($ver,0,"klienti_name_1") . "' OnClick='submit();'/>
	<input type='button' style='width:50px' onClick='submit();' value='",$m_setup['menu select'],"'>
   <a href='#none' onClick='find_window_script(\"tbl_klienti\",\"klienti_id\",\"klienti_name_1\",\"Klient for edit find/Sort\",\"klienti_id\")'> [",$m_setup['menu find'],"] </a>
    </td></tr></table><br><br>";
echo "\n</form>";
//========================================================================================================

echo "\n<form method='post' action='edit_table.php'>";
echo "\n<input type='submit' name='_save' value='",$m_setup['menu save'],"'/>";
echo "\n<input type='submit' name='_add' value='",$m_setup['menu add'],"'/>";
echo "\n<input type='submit' name='_dell' value='",$m_setup['menu dell'],"'/>";
//echo "\n<input type='submit' name='_select' value='",$m_setup['menu select and re'],"'/>";
echo "<a href='edit_nakl_add_new.php?klienti_id2=",$iKlient_id,"' target='_blank'>[",$m_setup['menu new nakl'],"]</a>";
//$return_page

echo "\n<input type='hidden' name='_id_value' value='"  , $iKlient_id  , "'/>";
echo "\n<input type='hidden' name='_id_name' value='" , $this_table_id_name , "'/>";
echo "\n<input type='hidden' name='_table_name' value='" , $this_table_name , "'/>";
echo "\n<input type='hidden' name='_select' value='" , $return_page , "'/>";

echo "\n<input type='hidden' name='_page_to_return' value='" , $this_page_name , "?" , $this_table_id_name, "='/>";

echo "\n<table border = 1 cellspacing='0' cellpadding='0'><tr><td>";//table dla find div
echo "\n<table border = 1 cellspacing='0' cellpadding='0'><tr>";

//====================================================================================================================
if(strpos($_SESSION[BASE.'usersetup'],'klienti_group')>0){
echo "\n<td>",$m_setup['menu group'],":</td><td>"; # Group klienti
echo "\n<select name='klienti_group' style='width:400px'>";# OnChange='submit();'>";
$count=0;
while ($count < mysql_num_rows($kli_grp))
{
  echo "\n<option ";
	if (mysql_result($ver,0,"klienti_group") == mysql_result($kli_grp,$count,"klienti_group_id")) echo "selected ";
  
  echo "value=" . mysql_result($kli_grp,$count,"klienti_group_id") . ">" . mysql_result($kli_grp,$count,"klienti_group_name") . "</option>";
  $count++;
}
echo "</select></td>";
echo "<td><a href='edit_klienti_group.php?klienti_group_id=", mysql_result($ver,0,'klienti_group'),"' target='_blank'>",$m_setup['menu edit'],"</a>
      <td></td>";
echo "</tr>";

/*echo "<td>",$m_setup['menu group'],":</td><td>"; # Group klienti
echo "<input type='hidden'  style='width:0px'  name='klienti_group' id='klienti_group' value='" . mysql_result($kli_grp,0,"klienti_group_id") . "'/>";
echo "<input type='text'  style='width:400px'  id='klienti_group_text' value='" . mysql_result($kli_grp,0,"klienti_group_name") . "'/>";
echo "</td>
    <td><a href='edit_klienti_group.php?klienti_group_id=", mysql_result($ver,0,'klienti_group'),"' target='_blank'>",$m_setup['menu edit'],"</a>
    <a href='#none' onClick='find_window_script(\"tbl_klienti_group\",\"klienti_group_id\",\"klienti_group_name\",\"Group find/Sort\",\"klienti_group\")'> [",$m_setup['menu find'],"] </a>

      </td>";
echo "<td></td>";
echo "</tr>";*/
}
//=====================================================================================================================
if(strpos($_SESSION[BASE.'usersetup'],'klienti_delivery_id')>0){
echo "\n<td>",$m_setup['menu delivery'],":</td><td>"; # Group klienti
echo "\n<input type='hidden'  style='width:0px'  name='klienti_delivery_id' id='klienti_delivery_id' value='" . mysql_result($ver,0,"klienti_delivery_id") . "'/>";
echo "\n<input type='text'  style='width:400px'  id='klienti_delivery_id_text' value='" . mysql_result($deliv,0,"delivery_name") . "'/>";

echo "</td>
      <td><a href='edit_delivery.php?delivery_id=", mysql_result($ver,0,'klienti_delivery_id'),"' target='_blank'>",$m_setup['menu edit'],"</a>
      <a href='#none' onClick='find_window_script(\"tbl_delivery\",\"delivery_id\",\"delivery_name\",\"Delivery find/Sort\",\"klienti_delivery_id\")'> [",$m_setup['menu find'],"] </a>
      </td>";
echo "<td></td>";
echo "</tr>";
}
//======================================================================================================================

if(strpos($_SESSION[BASE.'usersetup'],'klienti_name_1')>0){
echo "\n<tr><td>",$m_setup['menu name2']," 1:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_name_1' value='" . mysql_result($ver,0,"klienti_name_1") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}
if(strpos($_SESSION[BASE.'usersetup'],'klienti_name_2')>0){
echo "\n<tr><td>",$m_setup['menu name2']," 2:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_name_2' value='" . mysql_result($ver,0,"klienti_name_2") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}
if(strpos($_SESSION[BASE.'usersetup'],'klienti_name_3')>0){
echo "\n<tr><td>",$m_setup['menu name2']," 3:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_name_3' value='" . mysql_result($ver,0,"klienti_name_3") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}
if(strpos($_SESSION[BASE.'usersetup'],'klienti_pass')>0){
echo "\n<tr><td>",$m_setup['menu pass'],":</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_pass' placeholder='Оставьте пустым если не хотите менять!' value=''/></td>"; //" . mysql_result($ver,0,"klienti_pass") . "
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}
if(strpos($_SESSION[BASE.'usersetup'],'klienti_adress')>0){
echo "\n<tr><td>",$m_setup['menu adress'],":</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_adress' value='" . mysql_result($ver,0,"klienti_adress") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}
if(strpos($_SESSION[BASE.'usersetup'],'klienti_sity')>0){
echo "\n<tr><td>",$m_setup['menu sity'],":</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_sity' value='" . mysql_result($ver,0,"klienti_sity") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}
if(strpos($_SESSION[BASE.'usersetup'],'klienti_region')>0){
echo "\n<tr><td>",$m_setup['menu region'],":</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_region' value='" . mysql_result($ver,0,"klienti_region") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}
if(strpos($_SESSION[BASE.'usersetup'],'klienti_index')>0){
echo "\n<tr><td>",$m_setup['menu index'],":</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_index' value='" . mysql_result($ver,0,"klienti_index") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}
if(strpos($_SESSION[BASE.'usersetup'],'klienti_country')>0){
echo "\n<tr><td>",$m_setup['menu country'],":</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_country' value='" . mysql_result($ver,0,"klienti_country") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}
if(strpos($_SESSION[BASE.'usersetup'],'klienti_phone_1')>0){
echo "\n<tr><td>",$m_setup['menu phone']," 1:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_phone_1' value='" . mysql_result($ver,0,"klienti_phone_1") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}
if(strpos($_SESSION[BASE.'usersetup'],'klienti_phone_2')>0){
echo "\n<tr><td>",$m_setup['menu phone']," 2:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_phone_2' value='" . mysql_result($ver,0,"klienti_phone_2") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}
if(strpos($_SESSION[BASE.'usersetup'],'klienti_phone_3')>0){
echo "\n<tr><td>",$m_setup['menu phone']," 3:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_phone_3' value='" . mysql_result($ver,0,"klienti_phone_3") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}
if(strpos($_SESSION[BASE.'usersetup'],'klienti_email')>0){
echo "\n<tr><td>",$m_setup['menu email'],":</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_email' value='" . mysql_result($ver,0,"klienti_email") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}
if(strpos($_SESSION[BASE.'usersetup'],'klienti_memo')>0){
echo "\n<tr><td>Klient Memo:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_memo' value='" . mysql_result($ver,0,"klienti_memo") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}
if(strpos($_SESSION[BASE.'usersetup'],'klienti_edit')>0){
echo "\n<tr><td>Klient Edit:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_edit' value='" . mysql_result($ver,0,"klienti_edit") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}
if(strpos($_SESSION[BASE.'usersetup'],'klienti_inet_id')>0){
echo "\n<tr><td>Klient Inet ID (access level):</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_inet_id' value='" . mysql_result($ver,0,"klienti_inet_id") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}
if(strpos($_SESSION[BASE.'usersetup'],'klienti_prioritet')>0){
echo "\n<tr><td>Klient Prioritet:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_prioritet' value='" . mysql_result($ver,0,"klienti_prioritet") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}
if(strpos($_SESSION[BASE.'usersetup'],'klienti_spam')>0){
echo "\n<tr><td>Klient Spam:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_spam' value='" . mysql_result($ver,0,"klienti_spam") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}
if(strpos($_SESSION[BASE.'usersetup'],'klienti_ip')>0){
echo "\n<tr><td>Klient IP:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_ip' value='" . mysql_result($ver,0,"klienti_ip") . "'/></td>";
echo "<td><a href='add_to_banlist.php?ip=" . mysql_result($ver,0,"klienti_ip") . "&klient_id=$iKlient_id'>BAN</a></td>";
echo "<td></td>";
echo "</tr>";
}
if(strpos($_SESSION[BASE.'usersetup'],'klienti_setup')>0){
echo "\n<tr><td>Klient Setup:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px' disabled name='klienti_setup' value='" . mysql_result($ver,0,"klienti_setup") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}

//=====================================================================================================================
if(strpos($_SESSION[BASE.'usersetup'],'klienti_price')>0){
echo "\n<td>Klient Price:</td><td>"; # Group klienti
echo "\n<select name='klienti_price' style='width:400px'>";# OnChange='submit();'>";
$count=0;
while ($count < mysql_num_rows($price))
{
  echo "\n<option ";
	if (mysql_result($ver,0,"klienti_price") == mysql_result($price,$count,"price_id")) echo "selected ";
  
  echo "value=" . mysql_result($price,$count,"price_id") . ">" . mysql_result($price,$count,"price_name") . "</option>";
  $count++;
}
echo "</select></td>";
echo "<td><a href='edit_price.php?price_id=", mysql_result($ver,0,'klienti_price'),"' target='_blank'>",$m_setup['menu find'],"</a></td>";
echo "<td></td>";
echo "</tr>";
}
//======================================================================================================================

if(strpos($_SESSION[BASE.'usersetup'],'klienti_discount')>0){
echo "\n<tr><td>Klient Discount:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_discount' value='" . mysql_result($ver,0,"klienti_discount") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}
echo "\n</table></form>"; 
  echo "
  </td><td valign='top'>
  <div id='find_window'></div><br>
  <div id='find_div'></div>
  <div id='view'></div>
  </td></tr></table> ";
//=================================  SECURITY ==================================
  echo "<br>
	<form method='post' action='edit_security.php'>";
  echo "<input type='submit' name='_add' value='",$m_setup['menu save']," ",$m_setup['menu security setup'],"'/>";
  echo "<input type='hidden' name='_id_value' value='"  , $iKlient_id  , "'/>";
  echo "<br>";
  echo "<table border = 1 cellspacing='0' cellpadding='0'>";
  echo "<tr><td><b>",$m_setup['menu security setup'],"</b></td></tr>";
 
    if(strpos($_SESSION[BASE.'usersetup'],"ADMIN")>0){
	echo "<br><table border = 1 cellspacing='0' cellpadding='0'>
	      ";
	echo "<tr><td><input type='checkbox' name='",$_SESSION[BASE.'base'],"' value='",$_SESSION[BASE.'base'],"'";
		if(strpos(mysql_result($ver,0,'klienti_setup'),$_SESSION[BASE.'base'])>0) echo "checked";
	      echo "> - </td><td>",$m_setup['sys access to base'],"</td></tr>";

	echo "<tr><td><input type='checkbox' name='",$_SESSION[BASE.'base'],"can_shop' value='",$_SESSION[BASE.'base'],"can_shop'";
		if(strpos(mysql_result($ver,0,'klienti_setup'),$_SESSION[BASE.'base']."can_shop")>0) echo "checked";
	      echo "> - </td><td>",$m_setup['sys view can shop'],"</td></tr>";
	
	echo "<tr><td><input type='checkbox' name='NAKL_VIEW_ALL' value='NAKL_VIEW_ALL'";
		if(strpos(mysql_result($ver,0,'klienti_setup'),"NAKL_VIEW_ALL")>0) echo "checked";
	      echo "> - </td><td>",$m_setup['sys view all nakl'],"</td></tr>";
	
	echo "<tr><td><input type='checkbox' name='TOVAR_HIST_VIEW' value='TOVAR_HIST_VIEW'";
		if(strpos(mysql_result($ver,0,'klienti_setup'),"TOVAR_HIST_VIEW")>0) echo "checked";
	      echo "> - </td><td>",$m_setup['sys view tovar hist'],"</td></tr>";
	
	echo "<tr><td><input type='checkbox' name='gen_coef' value='gen_coef'";
		if(strpos(mysql_result($ver,0,'klienti_setup'),"gen_coef")>0) echo "checked";
	      echo "> - </td><td>",$m_setup['menu generate coef'],"</td></tr>";
	
	echo "<tr><td><input type='checkbox' name='view_zakup' value='view_zakup'";
		if(strpos(mysql_result($ver,0,'klienti_setup'),"view_zakup")>0) echo "checked";
	      echo "> - </td><td>",$m_setup['menu view zakup'],"</td></tr>";
	
	echo "<tr><td><input type='checkbox' name='gen_price' value='gen_price'";
		if(strpos(mysql_result($ver,0,'klienti_setup'),"gen_price")>0) echo "checked";
	      echo "> - </td><td>",$m_setup['menu generate price'],"</td></tr>";
	
	echo "<tr><td><input type='checkbox' name='analitics' value='analitics'";
		if(strpos(mysql_result($ver,0,'klienti_setup'),"analitics")>0) echo "checked";
	      echo "> - </td><td>",$m_setup['menu user analitics'],"</td></tr>";
	
	echo "<tr><td><input type='checkbox' name='habibulin' value='habibulin'";
		if(strpos(mysql_result($ver,0,'klienti_setup'),"habibulin")>0) echo "checked";
	      echo "> - </td><td>",$m_setup['menu user habibulin'],"</td></tr>";
	
	echo "<tr><td><input type='checkbox' name='ADMIN' value='ADMIN'";
		if(strpos(mysql_result($ver,0,'klienti_setup'),"ADMIN")>0) echo "checked";
	      echo "> - </td><td>",$m_setup['sys admin'],"</td></tr>";

	      
	 echo "</table>";
 	  
	 echo "<br><table border = 1 cellspacing='0' cellpadding='0'>
	      <tr><td>tbl_klienti</td><td><b>",$m_setup['table tbl_klienti']," - ",$m_setup['sys view and edit'],"</b></td></tr>";
 	 getTableFields($ver,mysql_result($ver,0,'klienti_setup'),$m_setup,null);
	 echo "</table>";
 	   
 	 echo "<br><table border = 1 cellspacing='0' cellpadding='0'>
	      <tr><td>tbl_price_tovar</td><td><b>",$m_setup['table tbl_price_tovar']," - ",$m_setup['sys view and edit'],"</b></td></tr>";  
 	 getTableFields($price_tovar,mysql_result($ver,0,'klienti_setup'),$m_setup,$price);
	 echo "</table>";
 	   
	 echo "<br><table border = 1 cellspacing='0' cellpadding='0'>
	      <tr><td>tbl_tovar</td><td><b>",$m_setup['table tbl_tovar']," - ",$m_setup['sys view and edit'],"</b></td></tr>";  
 	 getTableFields($tovar,mysql_result($ver,0,'klienti_setup'),$m_setup,null);
	 echo "</table>";
 	   
    }
 	echo "</table></form>";
  
  
echo "
<div id='info' class='info'></div>
</body>";

function getTableFields($ver,$str,$m_setup,$field) {
    	    $i=0;
	    $name='';
	    while(mysql_num_fields($ver) > $i){
	      $name = mysql_field_name($ver,$i);
	      echo "<td><input type='checkbox' name='$name' value='$name'";
		if(strpos($str,$name)>0) echo "checked";
	     
		echo ">$name</td>";
		 
		if(!isset($field)){
		  echo "<td>",$m_setup['table '.$name],"</td>";
		}else{
		 
		}
		
	      echo "</tr>";
	      $i++;
	    }
   
}

?>
