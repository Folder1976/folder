<?php

include 'init.lib.php';
connect_to_mysql();
session_start();
if (!session_verify($_SERVER["PHP_SELF"],"+")){
  exit();
}

// ======= Массив товара =================================================
$sql = "SELECT * FROM tbl_tovar WHERE tovar_id = '".$_GET['tovar_id']."';";
$r = $folder->query($sql) or die(mysql_error());
$tovar = $r->fetch_assoc();

 

//==================================SETUP===========================================
if ($_SESSION[BASE.'lang'] <1){
  $_SESSION[BASE.'lang']=1;
}
$setup_1 = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	  `setup_menu_name`, 
	  `setup_menu_".$_SESSION[BASE.'lang']."`
	  FROM `tbl_setup_menu`
	  WHERE 
	  `setup_menu_name` like '%menu%'

";
//echo $tQuery;
$setup_1 = mysql_query($tQuery);
$count=0;
while ($count<mysql_num_rows($setup_1)){
 $setup[mysql_result($setup_1,$count,0)]=mysql_result($setup_1,$count,1);
 $count++;
}


//==================================SETUP=MENU==========================================
$count = 0;
$this_page_name = "edit_tovar.php";
$this_table_id_name = "tovar_id";
$this_table_name_name = "tovar_name_1";
$this_table_name = "tbl_tovar";

$return_page = "";
if(isset($_REQUEST['_return_page'])) $return_page=$_REQUEST['_return_page'];


$sort_find = "";
if(isset($_REQUEST["_sort_find"])) $sort_find=$_REQUEST["_sort_find"];

$sort_find_deliv = "";
if(isset($_REQUEST["_sort_find_deliv"])) $sort_find_deliv=$_REQUEST["_sort_find_deliv"];


$iKlient_id = $_GET[$this_table_id_name];

//$return_page .= $iKlient_id;

$iKlient_count = 0;

$ver = mysql_query("SET NAMES utf8");
$ver = mysql_query("SELECT * FROM " . $this_table_name . " WHERE " . $this_table_id_name . " = " . $iKlient_id);
if (!$ver)
{
  echo "Query error - ", $this_table_name;
  exit();
}

$lang = mysql_query("SET NAMES utf8");
$lang = mysql_query("SELECT * FROM tbl_web_lang ORDER BY `web_lang_id` ASC");# WHERE klienti_id = " . $iKlient_id);
if (!$lang)
{
  echo "Query error - tbl_web_lang";
  exit();
}


$dimension = mysql_query("SET NAMES utf8");
$dimension = mysql_query("SELECT * FROM tbl_tovar_dimension ORDER BY `dimension_name` ASC");# WHERE klienti_id = " . $iKlient_id);
if (!$dimension)
{
  echo "Query error - tbl_dimension";
  exit();
}

$tovar_parent = mysql_query("SET NAMES utf8");
$tovar_parent = mysql_query("SELECT * FROM tbl_parent ORDER BY `tovar_parent_name` ASC");# WHERE klienti_id = " . $iKlient_id);
if (!$tovar_parent)
{
  echo "Query error - tbl_parent";
  exit();
}

$parent_inet = mysql_query("SET NAMES utf8");
$parent_inet = mysql_query("SELECT `parent_inet_id`,`parent_inet_1`,`parent_inet_type` FROM tbl_parent_inet ORDER BY `parent_inet_1` ASC");# WHERE klienti_id = " . $iKlient_id);
if (!$parent_inet)
{
  echo "Query error - tbl_parent";
  exit();
}

$tovar_supplier = mysql_query("SET NAMES utf8");
$tovar_supplier = mysql_query("SELECT `klienti_id`,`klienti_name_1`,`klienti_phone_1` FROM tbl_klienti WHERE `klienti_group`='5' ORDER BY `klienti_name_1` ASC");# WHERE klienti_id = " . $iKlient_id);
if (!$tovar_supplier)
{
  echo "Query error - tbl_Supplier";
  exit();
}
$tQuery="SELECT * FROM `tbl_description` WHERE `description_tovar_id`='".$iKlient_id."'";
$tovar_description = mysql_query("SET NAMES utf8");
$tovar_description = mysql_query($tQuery);# WHERE klienti_id = " . $iKlient_id);
//echo $tQuery;
if (!$tovar_description)
{
  echo "Query error - tbl_Description";
  exit();
}

$price_name = mysql_query("SET NAMES utf8");
$price_name = mysql_query("SELECT * FROM `tbl_price` ORDER BY `price_id` ASC");# WHERE klienti_id = " . $iKlient_id);
if (!$price_name)
{
  echo "Query error - tbl_price";
  exit();
}
$price = mysql_query("SET NAMES utf8");
$price = mysql_query("SELECT * FROM tbl_price_tovar WHERE `price_tovar_id`='".$iKlient_id."'");# WHERE klienti_id = " . $iKlient_id);
if (!$price)
{
  echo "Query error - tbl_price_tovar";
  exit();
}
$curr = mysql_query("SET NAMES utf8");
$curr = mysql_query("SELECT * FROM `tbl_currency`");# WHERE klienti_id = " . $iKlient_id);
if (!$curr)
{
  echo "Query error - tbl_price_tovar";
  exit();
}



header ('Content-Type: text/html; charset=utf8');
echo "<header><link rel='stylesheet' type='text/css' href='sturm.css'>";
echo "<link rel='stylesheet' type='text/css' href='css/style.css'>";
echo "\n<script src='JsHttpRequest.js'></script>";
echo "\n<script src='../js/jquery-2.1.4.min.js'></script>";
echo "<!--script src='attribute/attribute_get.js'></script--></header>";
echo "\n<script type='text/javascript'>
    ";
//===================JAVA================================
//==================TREE =================================
//find_window_tree(\"tbl_parent_inet\",\"parent_inet_id\",\"parent_inet_1\",\"".$setup['menu parent inet']. " - ".$setup['menu find']."\",\"tovar_inet_id_parent\",\"0\"
    echo "function set_tree_select(id){
	  document.getElementById('tovar_inet_id_parent').value=id;
    }
      ";
    
    echo "
    function find_window_tree(tbl,id,name,sel_name,target,find){
    if(find==0){
	var div_mas =  document.getElementById('find_window');
    }else{
	var div_mas =  document.getElementById('find_window*'+find);
    }
    var info='';
     var req=new JsHttpRequest();
     find='`parent_inet_parent`=\''+find+'\'';
      req.onreadystatechange=function(){
       if(req.readyState==4){
	var responce=req.responseText;
	//alert(responce);
	var str1=responce.split('||');
	var str2='';
	  var count=0;
	  while(str1[count]){
	  str2=str1[count].split('|');
	    info += '<table class=\"menu_top\" cellspacing=\"0\" cellpadding=\"0\">';
	    info += '<tr><td colspan=\"2\"><a href=\'#none\' onClick=\'find_window_tree(\"'+tbl+'\",\"'+id+'\",\"'+name+'\",\"'+sel_name+'\",\"'+target+'\",\"'+str2[0]+'\");\'> [+] </a>';
	    info += '<a href=\'#none\' onClick=\'set_tree_select('+str2[0]+');\'>'+str2[1]+'</a></td></td>';
	    info += '<tr><td width=\"20px\"></td><td><div id=\'find_window*'+str2[0]+'\'></div></td>';
	    count++;
	    }
	   // alert(info);
	    div_mas.innerHTML = info;
    }}
    req.open(null,'find_sort_tree.php',true);
    req.send({table:tbl,table_id:id,table_name:name,find_str:find});
 }
 ";
//==================FIND==================================
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
echo "function set_price_auto(tovar,key){
      alert('Pleace reload this page');
       var req=new JsHttpRequest();
      req.onreadystatechange=function(){
      if(req.readyState==4){
	var responce=req.responseText;
	//alert(responce);
      }}
      req.open(null,'edit_tovar_price.php',true);
      req.send({tovar_id:tovar,operation:key});

}
";
    echo "\n</script>";
//==================END JAVA ============================================
$tovar_artikl =  mysql_result($ver,0,"tovar_artkl") ;
echo "<title>Tovar EDIT</title>";
echo "\n<body>\n";
 //========================================================================================================
echo "<form enctype='multipart/form-data' method='post' action='load_photo.php'>";
echo "<table border = 0 cellspacing='0' cellpadding='0'>";
echo "<tr><td>Load photo:</td><td>"; # Group name 1
echo "<input type='hidden' name='MAX_FILE_SIZE' value='",1048*1048*1048,"'>";
echo "<input type='hidden' name='tovar_id' id='tovar_id' value='",$iKlient_id,"'>";
echo "<input type='hidden' name='tovar_artkl' id='tovar_artkl' value='",$tovar_artikl,"'>";
echo "<input type='hidden' name='type' value='tovar'>";
echo "<input type='file' min='1' max='999' multiple='true' style='width:200px'  name='userfile[]' OnChange='submit();'/></td></tr>";
echo "</table></form>"; 
echo "</body>";
//========================================================================================================

echo "\n<table border = 1 cellspacing='0' cellpadding='0'><tr><td valign=\"top\" width=\"750px\">";

echo "\n<form method='post' action='edit_tovar_save.php'>";
echo "\n<input type='submit' name='_add' value='",$setup['menu add'],"'/>";
echo "\n<input type='submit' name='_save' value='",$setup['menu save'],"'/>";
echo "\n<input type='submit' name='_dell' value='",$setup['menu dell'],"'/>";
echo "<br><a href='edit_tovar_history.php?tovar_id=",mysql_result($ver,$count,'tovar_id')," ' target='_blank'>&nbsp;&nbsp;|&nbsp;",$setup['menu history'],"&nbsp;|&nbsp;</a>";
echo "<a href='barcode.php?tovar_id=",mysql_result($ver,$count,'tovar_id')," ' target='_blank'>&nbsp;",$setup['menu print barcode'],"&nbsp;|</a>";
echo "<a href='barcode.php?key=price&tovar_id=",mysql_result($ver,$count,'tovar_id')," ' target='_blank'>&nbsp;",$setup['menu print price'],"&nbsp;|</a>
      <a href='barcode.php?key=price_ware&tovar_id=",mysql_result($ver,$count,'tovar_id')," ' target='_blank'>&nbsp;",$setup['menu print war']," 1&nbsp;|</a>
      <a href=\"barcode.php?key=ware&tovar_id=",mysql_result($ver,$count,'tovar_id')," target=\"_blank\">",$setup['menu print war']," 2</a>";
 
//$return_page  <a href='barcode.php?tovar_id=".mysql_result($ver,$count,'operation_detail_tovar')." ' target='_blank'>barcode</a>

echo "\n<input type='hidden' name='_id_value' value='"  , $iKlient_id  , "'/>";
echo "\n<input type='hidden' name='_id_name' value='" , $this_table_id_name , "'/>";
echo "\n<input type='hidden' name='_table_name' value='" , $this_table_name , "'/>";
echo "\n<input type='hidden' name='_select' value='" , $return_page , "'/>";

echo "\n<input type='hidden' name='_page_to_return' value='" , $this_page_name , "?" , $this_table_id_name, "='/>";

echo "\n<table border = 1 cellspacing='0' cellpadding='0'><tr>";

//======================================group================================================================================
if(strpos($_SESSION[BASE.'usersetup'],'tovar_parent')>0){
echo "\n<td>",$setup['menu group'],":</td><td>"; # Group klienti
echo "\n<select name='tovar_parent' id='tovar_parent' style='width:400px'>";# OnChange='submit();'>";
$count=0;
while ($count < mysql_num_rows($tovar_parent))
{
  echo "\n<option ";
	#echo mysql_result($ver,0,"klienti_group") , " " , mysql_result($kli_grp,$count,"klienti_group_id");
	if (mysql_result($ver,0,"tovar_parent") == mysql_result($tovar_parent,$count,"tovar_parent_id")) echo "selected ";
  
  echo "value=" . mysql_result($tovar_parent,$count,"tovar_parent_id") . ">" . mysql_result($tovar_parent,$count,"tovar_parent_name") . "</option>";
  $count++;
}
echo "</select></td>";
echo "<td><a href='edit_nakl-group.php?tovar_parent_id=", mysql_result($ver,0,'tovar_parent'),"' target='_blank'>",$setup['menu edit'],"</a>
    <a href='#none' onClick='find_window_script(\"tbl_parent\",\"tovar_parent_id\",\"tovar_parent_name\",\"".$setup['menu group']. " - ".$setup['menu find']."\",\"tovar_parent\"),0'> [",$setup['menu find'],"] </a>
    </td>";
/*echo "<td rowspan=\"20\" valign=\"top\">";

$count8=0;
$rowcount=5;
$id = mysql_result($ver,0,"tovar_inet_id");
while($count8<20){
    
    if($rowcount==0){
    echo "<br>";
    $rowcount=5;
    }
    echo "<img src=\"http://folder.com.ua/resources/products/$id/$id.$count8.large.jpg\" height=\"100px\">";

$rowcount--;   
$count8++;
 } 
echo "</td>";
echo "</tr>";*/
}
//=====================================================================================================================
if(strpos($_SESSION[BASE.'usersetup'],'tovar_code')>0){
echo "\n<tr><td>",$setup['menu code'],":</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='tovar_code' value='" . mysql_result($ver,0,"tovar_code") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}
if(strpos($_SESSION[BASE.'usersetup'],'tovar_barcode')>0){
echo "\n<tr><td>",$setup['menu barcode'],":</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='tovar_barcode' value='" . mysql_result($ver,0,"tovar_barcode") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}

include "../class/class_alias.php";
$Alias = new Alias($folder);
echo "\n<tr><td>Alias:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='tovar_alias' value='" . $Alias->getProductAlias($_GET['tovar_id']) . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";



if(strpos($_SESSION[BASE.'usersetup'],'tovar_artkl')>0){
echo "\n<tr><td>",$setup['menu artkl'],":</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='tovar_artkl' value='" . mysql_result($ver,0,"tovar_artkl") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}
if(strpos($_SESSION[BASE.'usersetup'],'tovar_size')>0){
echo "\n<tr><td>",$setup['menu size'],":</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='tovar_size' value='" . mysql_result($ver,0,"tovar_size") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}
if(strpos($_SESSION[BASE.'usersetup'],'tovar_name_1')>0){
echo "\n<tr><td>",$setup['menu name1']," ",mysql_result($lang,0,"web_lang_lang"),":</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='tovar_name_1' value='" . mysql_result($ver,0,"tovar_name_1") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}

if(strpos($_SESSION[BASE.'usersetup'],'tovar_name_2')>0){
echo "\n<tr><td>",$setup['menu name1']," ",mysql_result($lang,1,"web_lang_lang"),":</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='tovar_name_2' value='" . mysql_result($ver,0,"tovar_name_2") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}
if(strpos($_SESSION[BASE.'usersetup'],'tovar_name_3')>0){
echo "\n<tr><td>",$setup['menu name1']," ",mysql_result($lang,2,"web_lang_lang"),":</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='tovar_name_3' value='" . mysql_result($ver,0,"tovar_name_3") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}

if(strpos($_SESSION[BASE.'usersetup'],'tovar_memo')>0){
echo "\n<tr><td>",$setup['menu memo'],":</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='tovar_memo' value='" . mysql_result($ver,0,"tovar_memo") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}

//======================================dimension================================================================================
if(strpos($_SESSION[BASE.'usersetup'],'tovar_dimension')>0){
echo "\n<td>",$setup['menu dimension'],":</td><td>"; # Group klienti
echo "\n<select name='tovar_dimension' style='width:100px'>";# OnChange='submit();'>";
$count=0;
while ($count < mysql_num_rows($dimension))
{
  echo "\n<option ";
	if (mysql_result($ver,0,"tovar_dimension") == mysql_result($dimension,$count,"dimension_id")) echo "selected ";
  echo "value=" . mysql_result($dimension,$count,"dimension_id") . ">" . mysql_result($dimension,$count,"dimension_name")  . "</option>";
  $count++;
}
echo "</select></td>";
echo "<td><a href='edit_tovar_dimension.php?klienti_id=", mysql_result($ver,0,'tovar_dimension'),"' target='_blank'>",$setup['menu edit'],"</a></td>";
echo "<td></td>";
echo "</tr>";
}
//=====================================================================================================================

//====================================Supplier==================================================================================
if(strpos($_SESSION[BASE.'usersetup'],'tovar_supplier')>0){
echo "\n<td>",$setup['menu suppliter'],":</td><td>"; # Group klienti
echo "\n<select name='tovar_supplier' style='width:400px'>";# OnChange='submit();'>";
$count=0;
while ($count < mysql_num_rows($tovar_supplier))
{
  echo "\n<option ";
	if (mysql_result($ver,0,"tovar_supplier") == mysql_result($tovar_supplier,$count,"klienti_id")) echo "selected ";
  echo "value=" . mysql_result($tovar_supplier,$count,"klienti_id") . ">" . mysql_result($tovar_supplier,$count,"klienti_name_1")  . "(" . mysql_result($tovar_supplier,$count,"klienti_phone_1") . ")</option>";
  $count++;
}
echo "</select></td>";
echo "<td><a href='edit_klient.php?klienti_id=", mysql_result($ver,0,'tovar_supplier'),"' target='_blank'>",$setup['menu edit'],"</a></td>";
echo "<td></td>";
echo "</tr>";
}
//=====================================================================================================================
if(strpos($_SESSION[BASE.'usersetup'],'tovar_min_order')>0){
echo "\n<tr><td>",$setup['menu min order'],":</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='tovar_min_order' value='" . mysql_result($ver,0,"tovar_min_order") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}
//======================================parent inet===============================================================================
if(strpos($_SESSION[BASE.'usersetup'],'tovar_inet_id_parent')>0){
echo "\n<td>",$setup['menu parent inet'],":</td><td>"; # Group klienti
echo "\n<select name='tovar_inet_id_parent' id='tovar_inet_id_parent' style='width:400px'>";# OnChange='submit();'>";
$count=0;
while ($count < mysql_num_rows($parent_inet))
{
  echo "\n<option ";
	#echo mysql_result($ver,0,"klienti_group") , " " , mysql_result($kli_grp,$count,"klienti_group_id");
	if (mysql_result($ver,0,"tovar_inet_id_parent") == mysql_result($parent_inet,$count,"parent_inet_id")){
	    echo "selected ";
	    $parent_inet_type = mysql_result($parent_inet,$count,"parent_inet_type");
	    $parent_inet_id = mysql_result($parent_inet,$count,"parent_inet_id");
	 }
  
  echo "value=" . mysql_result($parent_inet,$count,"parent_inet_id") . ">".mysql_result($parent_inet,$count,"parent_inet_id")." - " . mysql_result($parent_inet,$count,"parent_inet_1") . "</option>";
  $count++;
}
echo "</select></td>";
echo "<td><a href='edit_parent_inet.php?parent_inet_id=", mysql_result($ver,0,'tovar_inet_id_parent'),"' target='_blank'>",$setup['menu edit'],"</a>
    <a href='#none' onClick='find_window_script(\"tbl_parent_inet\",\"parent_inet_id\",\"parent_inet_1\",\"".$setup['menu parent inet']. " - ".$setup['menu find']."\",\"tovar_inet_id_parent\")'> [",$setup['menu find'],"] </a>
    <a href='#none' onClick='find_window_tree(\"tbl_parent_inet\",\"parent_inet_id\",\"parent_inet_1\",\"".$setup['menu parent inet']. " - ".$setup['menu find']."\",\"tovar_inet_id_parent\",\"0\");'> [+] </a>
    
    </td>";
echo "<td></td>";
echo "</tr>";
}
//=====================================================================================================================
if(strpos($_SESSION[BASE.'usersetup'],'tovar_seazon')>0){
echo "\n<tr><td>Tovar seazon:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='tovar_seazon' value='" . mysql_result($ver,0,"tovar_seazon") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}
if(strpos($_SESSION[BASE.'usersetup'],'tovar_purchase_currency')>0){
echo "\n<tr><td>Tovar Purchase Cur:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px' disabled name='tovar_purchase_currency' value='" . mysql_result($ver,0,"tovar_purchase_currency") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}
if(strpos($_SESSION[BASE.'usersetup'],'tovar_sale_currency')>0){
echo "\n<tr><td>Tovar Sale Cur:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px' disabled name='tovar_sale_currency' value='" . mysql_result($ver,0,"tovar_sale_currency") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}
if(strpos($_SESSION[BASE.'usersetup'],'tovar_last_edit')>0){
echo "\n<tr><td>Tovar Last Edit:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px' disabled name='tovar_last_edit' value='" . mysql_result($ver,0,"tovar_last_edit") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}
if(strpos($_SESSION[BASE.'usersetup'],'tovar_inet_id')>0){
echo "\n<tr><td>Tovar Inet ID:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px' name='tovar_inet_id' value='" . mysql_result($ver,0,"tovar_inet_id") . "'/></td>";
echo "<td>
      <a href=\"get_pic_from_jobe.php?id=",mysql_result($ver,0,"tovar_inet_id"),"\" target=\"_blank\">Find pic on Job site</a> </td>";
echo "<td></td>";
echo "</tr>";
}

echo "\n</table>";//</form>"; 
//=========================================================================================================================
//=========================================================================================================================
//============================================PRICE=============================================================================
//=========================================================================================================================

echo "\n<table border = 1 cellspacing='0' cellpadding='0'>";

//======================================================================================================================
$count=0;
while ($count < mysql_num_rows($price_name)){
  
  if(strpos($_SESSION[BASE.'usersetup'],"price_tovar_".strval($count+1))>0){
  
    echo "\n
    <tr>
      <td>",mysql_result($price_name,$count,"price_name"),":</td>
      <td> 
	<input style='width:100px' type='text' name='price_tovar_".strval($count+1),"' id='price_tovar_".strval($count+1),"' value='",number_format(mysql_result($price,0,"price_tovar_".strval($count+1)),2,".",""),"'/>
      </td>
      <td> 
	<select name='price_tovar_curr_",$count+1,"' style='width:100px'>";# OnChange='submit();'>";
	$count_t=0;
      while ($count_t < mysql_num_rows($curr)){
      echo "<option ";
	if (mysql_result($curr,$count_t,"currency_id") == mysql_result($price,0,"price_tovar_curr_".strval($count+1))) echo "selected ";
	echo "value=" . mysql_result($curr,$count_t,"currency_id")  . ">" . mysql_result($curr,$count_t,"currency_name")."</option>";
	$count_t++;
      }
      echo "</select></td>";
  
         $html = "<td>
         <input type='text' style='width:40px;background:#9e9e9e;text-align:right' name='price_tovar_cof_".strval($count+1)."' id='price_tovar_cof_".strval($count+1)."' 
	value='".number_format(mysql_result($price,0,"price_tovar_cof_".strval($count+1)),"3",".","")."' 
	onChange='update(this.value,this.id)'/>
	</td>";
	
	/*if($count == 0){
	$html .="<td>
		<a href=\"javascript:set_price_auto('$iKlient_id','coef');\">".$setup['menu generate coef']."</a>
		</td><td>
		<a href=\"javascript:set_price_auto('$iKlient_id','price');\">".$setup['menu generate price']."</a>
		".$setup['menu generate warning']."
		</td>";
	}*/
	
	$html .= "</tr>";
	
	echo $html;
  }
$count++;
}
echo "\n<input type='hidden' name='_price_count' value='",$count,"'/>";

echo "</table>";//</form>";
//=========================================================================================================================
//=========================================================================================================================
echo '<!--Фильтры и атрибуты-->';

echo '</td><td valign="top" rowspan = "2"><b>Аттрибуты товара</b><br><br>';
  
  include ("../class/class_category.php");
  $Category = new Category($folder);
  $attr_group_id = $Category->getCategoryAttributeGroupID(mysql_result($ver,0,'tovar_inet_id_parent'));
  $group_name = $Category->getAttributeGroupNameOnCategoryID($attr_group_id);
  
  //echo "<pre>"; print_r(var_dump($group_name));
  
  $id = $_GET['tovar_id'];
  
  $sql = "SELECT A.attribute_id, A.attribute_name, T.attribute_value FROM tbl_attribute A
	  LEFT JOIN tbl_attribute_to_group G ON A.attribute_id = G.attribute_id
	  LEFT JOIN tbl_attribute_to_tovar T ON T.attribute_id = A.attribute_id AND T.tovar_id = '$id'
	  WHERE G.attribute_group_id = '$attr_group_id' 
	  ORDER BY G.attribute_sort ASC;";
  //echo $sql;
  $group = $folder->query($sql) or die(mysql_error());
  
  echo '<ul class = "attribute_list"><b>'.$group_name.'</b> <a href="'.HOST_URL.'/admin/main.php?func=attribute_group_edit&attribute_group_id='.$attr_group_id.'" target = "_blank"> редактировать</a>';
	  while($attr = $group->fetch_assoc()){
	      echo '<li>
		  <input type = "text" name = "attr*'.$attr['attribute_id'].'" id = "attr*'.$attr['attribute_id'].'" value = "'.$attr['attribute_value'].'" placeholder = "'.$attr['attribute_name'].'">
		  '.$attr['attribute_name'].'</li>';
	  }
  echo '</ul>';

echo '</td>
  <td valign="top" rowspan = "2">
  <div id="find_window"></div><br>
  <div id="find_div"></div>
  <div id="view"></div>
  </td>
  </tr><tr>
  <td>';
//============================================DESCRIPTION=============================================================================
//=========================================================================================================================


echo "<table border = 1 cellspacing='0' cellpadding='0'>";
//======================================================================================================================
 // echo "<tr>";
  
 $count=0;
//while ($count<mysql_num_rows($lang)){
  while ($count<1){
    $lang_id = mysql_result($lang,$count,"web_lang_id");
    $lang_name = mysql_result($lang,$count,"web_lang_lang");
   // echo "description_".$count+1;
      echo "<tr><td>",$lang_name,"<br> 
	<textarea cols='85' rows='40' name='description_",($count+1),"'>",mysql_result($tovar_description,0,"description_".($count+1)),"</textarea>
	</td></td>";
	
    $count++;}
echo "\n<input type='hidden' name='_description_count' value='",$count,"'/>
 
  </table></form>";
  //echo "
  //</td><td valign='top'>
  //<div id='find_window'></div><br>
  //<div id='find_div'></div>
  //<div id='view'></div>
  echo "</td></tr></table> ";
  
//===========================PHOTO========================
  if ($parent_inet_type==2){
    $link="GR".$parent_inet_id;
  }else{
      //Разделитель артикула на Артикул и размер
      $separator = $setup['tovar artikl-size sep'];
      //Разбиваем атрикл на тело и размер
        $artkl = $tovar_artikl;
        $size = "none";
        if(strpos($tovar_artikl,$separator) !== false){
            $x = explode($separator, $tovar_artikl);
            $artkl = $x[0];
            $size = $x[1];
        }
	$link = $artkl;
  }
  //echo $parent_inet_type;
    $path_to = "../resources/products/".$link."/";
    	
    	if(isset($_REQUEST['dellphoto'])){ 
	  $tmp2 = explode(".",$_REQUEST['dellphoto']);
	  $tmp = substr($_REQUEST['dellphoto'],0,-9);
	//echo $path_to.$tmp2[3].".small.jpg";
	$massiv = glob($path_to."*.".$tmp2[3].".*.jpg");
	$x=-1;
	  while ($x++ < count($massiv)-1){
	    echo unlink($massiv[$x]);
	    }

	echo unlink($tmp,"small.jpg");
	  if($tmp2[3]==0){	
	    $massiv = glob($path_to."*.small.jpg");
	    if(count($massiv)>0){
	     rename(substr($massiv[0],0,-9)."small.jpg",$tmp."small.jpg");
	     rename(substr($massiv[0],0,-9)."medium.jpg",$tmp."medium.jpg");
	     rename(substr($massiv[0],0,-9)."large.jpg",$tmp."large.jpg");
	    }
	  }
      }
    	if(isset($_REQUEST['mainphoto'])){ 
	  $tmp2 = explode(".",$_REQUEST['mainphoto']);
	  $tmp = substr($_REQUEST['mainphoto'],0,-9);
	  $massiv = glob($path_to."*.".$tmp2[3].".*.jpg");
	  if($tmp2[3]>0){	
	    $massiv = glob($path_to."*.small.jpg");
	    if(count($massiv)>0){
	     rename($tmp."small.jpg",$tmp."small_tmp.jpg");
	     rename($tmp."medium.jpg",$tmp."medium_tmp.jpg");
	     rename($tmp."large.jpg",$tmp."large_tmp.jpg");
	    
    	     rename(substr($massiv[0],0,-9)."small.jpg",$tmp."small.jpg");
	     rename(substr($massiv[0],0,-9)."medium.jpg",$tmp."medium.jpg");
	     rename(substr($massiv[0],0,-9)."large.jpg",$tmp."large.jpg");

	     rename($tmp."small_tmp.jpg",substr($massiv[0],0,-9)."small.jpg");
	     rename($tmp."medium_tmp.jpg",substr($massiv[0],0,-9)."medium.jpg");
	     rename($tmp."large_tmp.jpg",substr($massiv[0],0,-9)."large.jpg");
	    }
	  }
      }

      
     $massiv = glob($path_to."*.small.jpg");
     $photo = "<table><tr>";
    $x=-1;
    $count=0;
        while ($x++ < count($massiv)-1){
	  if($count > 5){
	      $photo .=  "</tr><tr>";
	      $count=0;}
	      $count++;
	  $photo .= "<td><a href='edit_tovar.php?tovar_id=".$iKlient_id."&dellphoto=".$massiv[$x]."'>".$setup['menu dell']."</a>|";
	  $photo .= "<a href='edit_tovar.php?tovar_id=".$iKlient_id."&mainphoto=".$massiv[$x]."'>".$setup['menu main']."</a>|<br>";
	  $photo .= "<img src='".$massiv[$x]."' width='150'></td>";
	  }
    $photo .= "</tr></table>";
    echo $photo;
  
echo "\n</body>";

?>
