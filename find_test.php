<?php
include 'init.lib.php';
include 'init.lib.user.php';
session_start();
  if ($_REQUEST['lang']){
    $_SESSION[BASE.'lang'] = $_REQUEST['lang'];
  }else if (!$_SESSION[BASE.'lang']){
    $_SESSION[BASE.'lang']=1;
  }
connect_to_mysql();


header ('Content-Type: text/html; charset=utf8');
echo "<header>
      <script language='javascript' src='ajax_framework.js'></script>
      <link rel='stylesheet' type='text/css' href='admin/sturm.css'></header>
      <script src='admin/JsHttpRequest.js'></script>
";
      
  echo "<body>
 
";

echo "<table width=100% class='menu_top' cellspacing='0' cellpadding='0'><tr>";
echo "<td align=center>";
user_menu_logo();
echo "</td><td align=center>";
user_menu_top();
echo "</td><td align=center>";
user_menu_lang();
echo "</td></tr></table>";    

//=========================SERCH AJAX
echo "<table width=100%><tr><td align=center>
<form id='searchForm' name='searchForm' method='post' action='javascript:insertTask();'>
<div class='searchInput'>
<input name='searchq' type='text' id='searchq' size='30' style='width:600px' onkeyup='javascript:searchNameq()'/>
<input type='button' name='submitSearch' id='submitSearch' value='Search' onclick='javascript:searchNameq()'/>
</div></form>
</td></tr><tr><td>
<div id='search-result'></div>
</td></tr></table>
";

//<div id='msg'>Type something into the input field</div>";



/*<select id='info' size='10' name='c' style='visibility:hidden;position:absolute;z-index:999;'
	onChange='getObj('find_str').value=ot=this.options[this.selectedIndex].value'
	onKeyUp = 'PressKey2(event)' onDblClick='this.form.submit()'>
";*/
     
      
/*      
echo    "<script type='text/javascript'>";
//===================JAVA================================
    echo "
    function find_window_script(a){
    var div_mas =  document.getElementById('find_window');
      if(a==1){
	div_mas.innerHTML='>><select id=\"find_sel\" size=30 style=\"width:600px\" ondblclick=\"set_select_value(\'find_str\')\"></select>';
      }else{
	div_mas.innerHTML='';
      }
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
    function find_script(find){
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
    req.open(null,'_user_find.php',true);
    req.send({find_str:find});";
    echo "}";

    echo "\n</script>";
//==================END JAVA ============================================
echo "<body>
 
";

echo "<table width=100% class='menu_top' cellspacing='0' cellpadding='0'><tr>";
echo "<td align=center>";
user_menu_logo();
echo "</td><td align=center>";
user_menu_top();
echo "</td><td align=center>";
user_menu_lang();
echo "</td></tr></table>";

echo "\n<form method='post'>";
echo ">><input type='text 'id='find_str' style='width:600px' onKeyPress='find_script(this.value)' onClick='find_window_script(1)' onBlur='find_window_script(2)'></form>
 <br><div id='find_window'></div><br>
 <br>find_div - <div id='find_div'></div>
 <br>view - <div id='view'></div>";
//<br><div id='result_zone'></div><br>
/*
$count = 0;
$this_page_name = "edit_klient.php";
$this_table_id_name = "klienti_id";
$this_table_name_name = "klienti_name_1";
$return_page = $_GET['_return_page'];

$this_table_name = "tbl_klienti";
$sort_find = $_GET["_sort_find"];
$sort_find_deliv = $_GET["_sort_find_deliv"];
$iKlient_id = $_GET[$this_table_id_name];

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
$kli_grp = mysql_query("SELECT `klienti_group_id`,`klienti_group_name` FROM `tbl_klienti_group` WHERE `klienti_group_id`= '".mysql_result($ver,0,"klienti_group")."'");
if (!$kli_grp)
{
  echo "Query error - tbl_klienti_group";
  exit();
}
$price = mysql_query("SET NAMES utf8");
$price = mysql_query("SELECT * FROM tbl_price");# WHERE klienti_id = " . $iKlient_id);
if (!$price)
{
  echo "Query error - tbl_price";
  exit();
}
$deliv = mysql_query("SET NAMES utf8");
$deliv = mysql_query("SELECT `delivery_id`,`delivery_name` FROM `tbl_delivery` WHERE `delivery_id`='".mysql_result($ver,0,"klienti_delivery_id")."'");# WHERE klienti_id = " . $iKlient_id);
if (!$deliv)
{
  echo "Query error - tbl_price";
  exit();
}
//==========================================================================================================
/*if ($sort_find != null){
$sort_find_where = " WHERE upper(delivery_name) like '%" . mb_strtoupper($sort_find_deliv,'UTF-8') . "%'";
}
$deliv = mysql_query("SET NAMES utf8");
$deliv = mysql_query("SELECT delivery_id, delivery_name FROM tbl_delivery"  . $sort_find_where); 
if (mysql_num_rows($deliv)==0){
  $deliv = mysql_query("SET NAMES utf8");
  $deliv = mysql_query("SELECT delivery_id, delivery_name FROM tbl_delivery"); 
}
if (!$deliv){
  echo "Query error - header select";
  exit();
}
//===========================================================================================================

//==========================================================================================================
/*if ($sort_find != null){
$sort_find_where = " WHERE upper(" . $this_table_name_name . ") like '%" . mb_strtoupper($sort_find,'UTF-8') . "%'";
}
$list = mysql_query("SET NAMES utf8");
$list = mysql_query("SELECT " . $this_table_id_name . "," . $this_table_name_name . " FROM ". $this_table_name  . $sort_find_where); 
if (mysql_num_rows($list)==0){
  $list = mysql_query("SET NAMES utf8");
  $list = mysql_query("SELECT " . $this_table_id_name . "," . $this_table_name_name . " FROM ". $this_table_name); 
}
if (!$list){
  echo "Query error - header select";
  exit();
}
//===========================================================================================================

header ('Content-Type: text/html; charset=utf8');
echo "<header><link rel='stylesheet' type='text/css' href='sturm.css'></header>";
echo "\n<script src='JsHttpRequest.js'></script>";
echo "\n<script type='text/javascript'>";
//===================JAVA================================
    echo "
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
    
echo "<title>Klient edit</title>";
echo "\n<body>\n";


//========================================================================================================
echo "\n<form method='get' action='" , $this_page_name , "'>";
echo "\n<table border = 0 cellspacing='0' cellpadding='0'>";
/*echo "\n<tr><td>Sort/Find Klient:</td><td>"; # Group name 1
echo "\n<input type='text' style='width:400px'  name='_sort_find' value='" . $sort_find . "'  method='get' OnChange='submit();'/></td></tr>";

/*echo "\n<tr><td>Sort/Find Deliv:</td><td>"; # Group name 1
echo "\n<input type='text' style='width:400px'  name='_sort_find_deliv' value='" . $sort_find_deliv . "'  method='get' OnChange='submit();'/></td></tr>";

echo "\n<tr><td>" , $this_table_name , ":</td><td>"; # List
echo "\n<select name='" . $this_table_id_name . "' style='width:400px' method='get' OnChange='submit();'>";# OnChange='submit();'>";

$count=0;
while ($count < mysql_num_rows($list))
{
  echo "\n<option ";
	#echo mysql_result($ver,0,"klienti_group") , " " , mysql_result($kli_grp,$count,"klienti_group_id");
	if (mysql_result($ver,0,$this_table_id_name) == mysql_result($list,$count,$this_table_id_name)) echo "selected ";
  
  echo "value=" . mysql_result($list,$count,$this_table_id_name) . ">" . mysql_result($list,$count,$this_table_name_name) . "</option>";
  $count++;
}
echo "</select></td></tr>";

echo "\n<tr><td>Klient EDIT:</td><td>"; # Group klienti
echo "\n<input type='hidden'  style='width:50px'  name='klienti_id' id='klienti_id' value='" . mysql_result($ver,0,"klienti_id") . "' OnChange='submit();'/>";
echo "\n<input type='text'  style='width:400px'  id='klienti_id_text' value='" . mysql_result($ver,0,"klienti_name_1") . "' OnClick='submit();'/>
	<input type='button' style='width:50px' onClick='submit();' value='Select'>
   <a href='#none' onClick='find_window_script(\"tbl_klienti\",\"klienti_id\",\"klienti_name_1\",\"Klient for edit find/Sort\",\"klienti_id\")'> [find] </a>
    </td></tr></table><br><br>";
echo "\n</form>";
//========================================================================================================

echo "\n<form method='post' action='edit_table.php'>";
echo "\n<input type='submit' name='_add' value='add'/>";
echo "\n<input type='submit' name='_save' value='save'/>";
echo "\n<input type='submit' name='_dell' value='dell'/>";
echo "\n<input type='submit' name='_select' value='select and return'/>";

//$return_page

echo "\n<input type='hidden' name='_id_value' value='"  , $iKlient_id  , "'/>";
echo "\n<input type='hidden' name='_id_name' value='" , $this_table_id_name , "'/>";
echo "\n<input type='hidden' name='_table_name' value='" , $this_table_name , "'/>";
echo "\n<input type='hidden' name='_select' value='" , $return_page , "'/>";

echo "\n<input type='hidden' name='_page_to_return' value='" , $this_page_name , "?" , $this_table_id_name, "='/>";

echo "\n<table border = 1 cellspacing='0' cellpadding='0'><tr><td>";//table dla find div
echo "\n<table border = 1 cellspacing='0' cellpadding='0'><tr>";

echo "\n<td>Klient Group:</td><td>"; # Group klienti
echo "\n<input type='hidden'  style='width:0px'  name='klienti_group' id='klienti_group' value='" . mysql_result($kli_grp,0,"klienti_group_id") . "'/>";
echo "\n<input type='text'  style='width:400px'  id='klienti_group_text' value='" . mysql_result($kli_grp,0,"klienti_group_name") . "'/>";
/*
echo "\n<select name='klienti_group' id='klienti_group' style='width:400px'>";# OnChange='submit();'>";
$count=0;
while ($count < mysql_num_rows($kli_grp))
{
  echo "\n<option ";
	#echo mysql_result($ver,0,"klienti_group") , " " , mysql_result($kli_grp,$count,"klienti_group_id");
	if (mysql_result($ver,0,"klienti_group") == mysql_result($kli_grp,$count,"klienti_group_id")) echo "selected ";
  
  echo "value=" . mysql_result($kli_grp,$count,"klienti_group_id") . ">" . mysql_result($kli_grp,$count,"klienti_group_name") . "</option>";
  $count++;
}
echo "</select></td>";
echo "</td>
    <td><a href='edit_klienti_group.php?klienti_group_id=", mysql_result($ver,0,'klienti_group'),"' target='_blank'>Edit</a>
    <a href='#none' onClick='find_window_script(\"tbl_klienti_group\",\"klienti_group_id\",\"klienti_group_name\",\"Group find/Sort\",\"klienti_group\")'> [find] </a>

      </td>";
echo "<td></td>";
echo "</tr>";
//=====================================================================================================================
echo "\n<td>Klient Delivery:</td><td>"; # Group klienti
echo "\n<input type='hidden'  style='width:0px'  name='klienti_delivery_id' id='klienti_delivery_id' value='" . mysql_result($ver,0,"klienti_delivery_id") . "'/>";
echo "\n<input type='text'  style='width:400px'  id='klienti_delivery_id_text' value='" . mysql_result($deliv,0,"delivery_name") . "'/>";

/*echo "\n<select name='klienti_delivery_id' id='klienti_delivery_id' style='width:400px'>";# OnChange='submit();'>";
$count=0;
while ($count < mysql_num_rows($deliv))
{
  echo "\n<option ";
	if (mysql_result($ver,0,"klienti_delivery_id") == mysql_result($deliv,$count,"delivery_id")) echo "selected ";
  
  echo "value=" . mysql_result($deliv,$count,"delivery_id") . ">" . mysql_result($deliv,$count,"delivery_name") . "</option>";
  $count++;
}
echo "</select>";

echo "</td>
      <td><a href='edit_delivery.php?delivery_id=", mysql_result($ver,0,'klienti_delivery_id'),"' target='_blank'>Edit</a>
      <a href='#none' onClick='find_window_script(\"tbl_delivery\",\"delivery_id\",\"delivery_name\",\"Delivery find/Sort\",\"klienti_delivery_id\")'> [find] </a>
      </td>";
echo "<td></td>";
echo "</tr>";
//======================================================================================================================


echo "\n<tr><td>Klient Name 1:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_name_1' value='" . mysql_result($ver,0,"klienti_name_1") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";

echo "\n<tr><td>Klient Name 2:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_name_2' value='" . mysql_result($ver,0,"klienti_name_2") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";


echo "\n<tr><td>Klient Name 3:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_name_3' value='" . mysql_result($ver,0,"klienti_name_3") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";

echo "\n<tr><td>Klient pass:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_pass' value='" . mysql_result($ver,0,"klienti_pass") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";


echo "\n<tr><td>Klient Adress:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_adress' value='" . mysql_result($ver,0,"klienti_adress") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";


echo "\n<tr><td>Klient Sity:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_sity' value='" . mysql_result($ver,0,"klienti_sity") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";


echo "\n<tr><td>Klient Region:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_region' value='" . mysql_result($ver,0,"klienti_region") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";


echo "\n<tr><td>Klient Index:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_index' value='" . mysql_result($ver,0,"klienti_index") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";


echo "\n<tr><td>Klient Country:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_country' value='" . mysql_result($ver,0,"klienti_country") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";


echo "\n<tr><td>Klient Phone 1:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_phone_1' value='" . mysql_result($ver,0,"klienti_phone_1") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";


echo "\n<tr><td>Klient Phone 2:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_phone_2' value='" . mysql_result($ver,0,"klienti_phone_2") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";


echo "\n<tr><td>Klient Phone 3:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_phone_3' value='" . mysql_result($ver,0,"klienti_phone_3") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";


echo "\n<tr><td>Klient e-mail:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_email' value='" . mysql_result($ver,0,"klienti_email") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";


echo "\n<tr><td>Klient Memo:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_memo' value='" . mysql_result($ver,0,"klienti_memo") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";


echo "\n<tr><td>Klient Edit:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_edit' value='" . mysql_result($ver,0,"klienti_edit") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";


echo "\n<tr><td>Klient Inet ID:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_inet_id' value='" . mysql_result($ver,0,"klienti_inet_id") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";


echo "\n<tr><td>Klient Prioritet:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_prioritet' value='" . mysql_result($ver,0,"klienti_prioritet") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";


echo "\n<tr><td>Klient Spam:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_spam' value='" . mysql_result($ver,0,"klienti_spam") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";

echo "\n<tr><td>Klient IP:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_ip' value='" . mysql_result($ver,0,"klienti_ip") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";

echo "\n<tr><td>Klient Setup:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_setup' value='" . mysql_result($ver,0,"klienti_setup") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";

//=====================================================================================================================
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
echo "<td><a href='edit_price.php?price_id=", mysql_result($ver,0,'klienti_price'),"' target='_blank'>Edit</a></td>";
echo "<td></td>";
echo "</tr>";
//======================================================================================================================


echo "\n<tr><td>Klient Discount:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='klienti_discount' value='" . mysql_result($ver,0,"klienti_discount") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
echo "\n</table></form>"; 
  echo "
  </td><td valign='top'>
  <div id='find_window'></div><br>
  <div id='find_div'></div>
  <div id='view'></div>
  </td></tr></table> ";
echo "\n</body>";

?>
