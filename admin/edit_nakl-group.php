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
$this_page_name = "edit_nakl-group.php";
$this_table_id_name = "tovar_parent_id";
$this_table_name_name = "tovar_parent_name";
$this_table_name = "tbl_parent";

$return_page = "";
if(isset($_REQUEST['_return_page'])) $return_page=$_REQUEST['_return_page'];

$sort_find = "";
if(isset($_REQUEST["_sort_find"])) $sort_find=$_REQUEST["_sort_find"];

$iKlient_id = "";
if(isset($_REQUEST[$this_table_id_name]))$iKlient_id=$_REQUEST[$this_table_id_name];

if(!$iKlient_id) $iKlient_id=1;
$iKlient_count = 0;

$ver = mysql_query("SET NAMES utf8");
$ver = mysql_query("SELECT * FROM `$this_table_name` WHERE `$this_table_id_name` =  $iKlient_id");
if (!$ver)
{
  echo "Query error - ", $this_table_name;
  exit();
}

$list_all = mysql_query("SET NAMES utf8");
$list_all = mysql_query("SELECT * FROM `$this_table_name` ORDER BY `$this_table_name_name` ASC");
if (!$list_all)
{
  echo "Query error - ", $this_table_name, " AS ALL";
  exit();
}

$shop = mysql_query("SET NAMES utf8");
$shop = mysql_query("SELECT *
		    FROM `tbl_shop`
		    ORDER BY `shop_sort` ASC");
if (!$shop)
{
  echo "Query error - tbl_shop";
  exit();
}
$ware = mysql_query("SET NAMES utf8");
$ware = mysql_query("SELECT * FROM `tbl_warehouse` ORDER BY `warehouse_sort` ASC");
if (!$ware)
{
  echo "Query error - tbl_warehouse";
  exit();
}
//$warehouse = array();
//foreach ($r = mysql_fetch_assoc($ware)){
//  $warehouse[$r['warehouse_id']] = $r['warehouse_name'];/
//}
//===========================================================================================================

//header ('Content-Type: text/html; charset=utf8');
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
     
 echo "
 function set_ware_on_web(){
 var ware = ",mysql_num_rows($ware),";
 var count=0;
 var str='';
 
  while(count < ware){
      if(document.getElementById('_ware*'+count).checked){
	str += '1';
      }else{
	str += '0';
      }
    count++;   
  }
 document.getElementById('tovar_parent_nom').value = str;
 }
 ";    
     
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
    
echo "<title>Накл ред</title>";
echo "\n<body>\n";


//========================================================================================================
echo "\n<form method='get' action='" , $this_page_name , "'>";
echo "\n<table border = 0 cellspacing='0' cellpadding='0'>";

echo "\n<tr><td>",$m_setup['menu selected'],":</td><td>"; # Group klienti
echo "\n<input type='hidden'  style='width:50px'  name='tovar_parent_id' id='tovar_parent_id' value='" . mysql_result($ver,0,"tovar_parent_id") . "' OnChange='submit();'/>";
echo "\n<input type='text'  style='width:400px'  id='tovar_parent_id_text' value='" . mysql_result($ver,0,"tovar_parent_name") . "' OnClick='submit();'/>
	<input type='button' style='width:50px' onClick='submit();' value='",$m_setup['menu select'],"'>
   <a href='#none' onClick='find_window_script(\"tbl_parent\",\"tovar_parent_id\",\"tovar_parent_name\",\"Поиск накладных\",\"tovar_parent_id\")'> [",$m_setup['menu find'],"] </a>
    </td></tr></table></form>";
    
    
echo "\n<form method='get' action='" , $this_page_name , "'>";
echo "\n<table border = 0 cellspacing='0' cellpadding='0'>
    
    <tr><td>",$m_setup['menu selected']," list:</td><td>
    <select name='tovar_parent_id' id='tovar_parent_id' style='width:600px' OnChange='submit();'/>";
$count=0;
while ($count < mysql_num_rows($list_all))
{
  echo "\n<option ";
	if ($iKlient_id == mysql_result($list_all,$count,$this_table_id_name)) echo "selected ";
  
  echo "value=" . mysql_result($list_all,$count,$this_table_id_name) . ">" . mysql_result($list_all,$count,$this_table_name_name) . "</option>";
  $count++;
}
echo "</select></td></tr></table><br><br>";
    
    
echo "\n</form>";
//========================================================================================================

echo "\n<form method='post' action='edit_table.php'>";
echo "\n<input type='submit' name='_add' value='",$m_setup['menu add'],"'/>";
echo "\n<input type='submit' name='_save' value='",$m_setup['menu save'],"'/>";
echo "\n<input type='submit' name='_dell' value='",$m_setup['menu dell'],"'/>";

echo "\n<input type='hidden' name='_id_value' value='"  , $iKlient_id  , "'/>";
echo "\n<input type='hidden' name='_id_name' value='" , $this_table_id_name , "'/>";
echo "\n<input type='hidden' name='_table_name' value='" , $this_table_name , "'/>";
echo "\n<input type='hidden' name='_select' value='" , $return_page , "'/>";

echo "\n<input type='hidden' name='_page_to_return' value='" , $this_page_name , "?" , $this_table_id_name, "='/>";

echo "\n<table border = 1 cellspacing='0' cellpadding='0'><tr><td>";//table dla find div
echo "\n<table border = 1 cellspacing='0' cellpadding='0'><tr>";


//====================================================================================================================
echo "\n<td>",$m_setup['menu shop'],":</td><td>"; # Group klienti
echo "\n<select name='tovar_parent_shop' style='width:400px'>";# OnChange='submit();'>";
$count=0;
while ($count < mysql_num_rows($shop))
{
  echo "\n<option ";
	if (mysql_result($ver,0,"tovar_parent_shop") == mysql_result($shop,$count,"shop_id")) echo "selected ";
  
  echo "value=" . mysql_result($shop,$count,"shop_id") . ">" . mysql_result($shop,$count,"shop_name_".$_SESSION[BASE.'lang']) . "</option>";
  $count++;
}
echo "</select></td>";
echo "<td><a href='edit_shop.php?shop_id=", mysql_result($ver,0,'tovar_parent_shop'),"' target='_blank'>",$m_setup['menu edit'],"</a></td>";
echo "<td></td>";
echo "</tr>";
//====================================================================================================================
echo "\n<td>",$m_setup['print sklad'],":</td><td>"; # Group klienti
echo "\n<select name='tovar_parent_warehouse' style='width:400px'>";# OnChange='submit();'>";
$count=0;
while ($count < mysql_num_rows($ware))
{
  echo "\n<option ";
	if (mysql_result($ver,0,"tovar_parent_warehouse") == mysql_result($ware,$count,"warehouse_id")) echo "selected ";
  
  echo "value=" . mysql_result($ware,$count,"warehouse_id") . ">" . mysql_result($ware,$count,"warehouse_name") . "</option>";
  $count++;
}
echo "</select></td>";
echo "<td><a href='edit_warehouse.php?warehouse_id=", mysql_result($ver,0,'tovar_parent_warehouse'),"' target='_blank'>",$m_setup['menu edit'],"</a></td>";
echo "<td></td>";
echo "</tr>";

//======================================================================================================================
echo "\n<tr><td>",$m_setup['menu name2'],":</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='tovar_parent_name' value='" . mysql_result($ver,0,"tovar_parent_name") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";

//======================================================================================================================
echo "\n<tr><td>",$m_setup['menu memo'],":</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:600px'  name='tovar_parent_memo' value='" . mysql_result($ver,0,"tovar_parent_memo") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
//======================================================================================================================
echo "\n<tr><td>",$m_setup['menu level']," 1:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:40px'  name='tovar_parent_level' value='" . mysql_result($ver,0,"tovar_parent_level") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
//======================================================================================================================
echo "\n<tr><td>",$m_setup['menu level']," 2:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:40px'  name='tovar_parent_flag_1' value='" . mysql_result($ver,0,"tovar_parent_flag_1") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
//======================================================================================================================
echo "\n<tr><td valign=top>",$m_setup['menu inet view'],":</td><td>"; # Group name 1
$count=0;
$tmp = mysql_result($ver,0,"tovar_parent_nom");
//echo '<pre>'; print_r($ware);
while ($count < mysql_num_rows($ware))
{
	echo "<input type='checkbox' id='_ware*".(mysql_result($ware,$count,"warehouse_id"))."' value='_ware*".(mysql_result($ware,$count,"warehouse_id"))."' ";
		if($tmp{(mysql_result($ware,$count,"warehouse_id"))}>0) echo " checked ";
	      echo " onchange='set_ware_on_web();'> - ",mysql_result($ware,$count,"warehouse_name"),"<br>";
   $count++;
}


echo "</td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
//======================================================================================================================
echo "\n<tr><td>",$m_setup['menu setup'],":</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:200px'  name='tovar_parent_nom' id='tovar_parent_nom' value='" . mysql_result($ver,0,"tovar_parent_nom") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";

 	echo "</table></form>";
  
    echo "
  </td><td valign='top'>
  <div id='find_window'></div><br>
  <div id='find_div'></div>
  <div id='view'></div>
  </td></tr></table> ";
echo "
<div id='info' class='info'></div>";

echo "<br><h3>Полный список:</h3>";
$count=0;
while ($count < mysql_num_rows($list_all))
{
  echo "<a href='$this_page_name?$this_table_id_name=".mysql_result($list_all,$count,$this_table_id_name)."'>
  " . mysql_result($list_all,$count,$this_table_name_name) . "</a><br>";
  $count++;
}

echo "</body>";

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
