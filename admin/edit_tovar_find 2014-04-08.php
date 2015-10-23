<?php

include 'init.lib.php';

connect_to_mysql();
session_start();
if (!session_verify($_SERVER["PHP_SELF"],"none")){
  exit();
}

//echo "11111111111111111111111111111111111111111111111111111111111111111111<br>";
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

$iKlient_id = $_GET["operation_id"];

$for_link = "";
$find_str = "";

if(isset($_GET["_find1"]) and !empty($_GET["_find1"])) $find_str=$_GET["_find1"];

$find_str2 = "";
if(isset($_GET["_find2"]) and !empty($_GET["_find2"]))$find_str2=$_GET["_find2"];

if($find_str=="find-str"){
    $find_str=$m_setup['menu find-str'];
    $for_link .= "_find1=find-str";
}else{
    $for_link .= "_find1=".$find_str;
}

if($find_str2=="find-str"){
  $find_str2=$m_setup['menu find-nakl'];
    $for_link .= "&_find2=find-str";
  }else{
    $for_link .= "&_find2=".$find_str2;
  }

$find_supplier = "";
if(isset($_REQUEST["_supplier"]) and !empty($_REQUEST["_supplier"])) $find_supplier=$_REQUEST["_supplier"];
$for_link .= "&_supplier=".$find_supplier;


$find_parent = "";
if(isset($_REQUEST["_parent"]) and !empty($_REQUEST["_parent"])) $find_parent=$_REQUEST["_parent"];
$for_link .= "&_parent=".$find_parent;


$tmp_from = "";
if(isset($_REQUEST["_from"]) and !empty($_REQUEST["_from"])) $tmp_from=$_REQUEST["_from"];
$for_link .= "&_from=".$tmp_from;

$tmp_to = "";
if(isset($_REQUEST["_to"]) and !empty($_REQUEST["_to"])) $tmp_to=$_REQUEST["_to"];
$for_link .= "&_from=".$tmp_to;





$iPrice = 1;

$find_str_sql="";
$this_table_name = "tbl_operation_detail";
$this_table_id_name = "operation_detail_operation";
$return_page = "edit_tovar_find.php?operation_id=" . $iKlient_id."&_from=".$tmp_from."&_to=".$tmp_to."&_find1=".$find_str."&_supplier=".$find_supplier."&_parent=".$find_parent;
$warehouse_count=0;
//echo $iKlient_id , " " , $return_page;
$color_null = "transparent";
$color_from = "#87ff8f";
$color_to = "#ffa0a0";
$color_tovar1 = "#ADD8E6";
$color_tovar2 = "#ADD8D0";
$color_tovar_now = $color_tovar1;
$warehouse_row_limit = 15;

$tmp= 1;

$tQuery = "SELECT `price_id`,`price_name` FROM `tbl_price`";
$price = mysql_query("SET NAMES utf8");
$price = mysql_query($tQuery);//,`tbl_klienti`, `tbl_operation_status` WHERE `operation_klient`=`klienti_id` and `operation_status`=`operation_status_id` " . $tQuery . " ORDER BY `operation_data` DESC, `operation_id` DESC ");
if (!$price)
{
  echo "Query error PriceName";
  exit();
  
}
$tQuery = "SELECT `klienti_id`,`klienti_name_1` FROM `tbl_klienti` WHERE `klienti_group` = '5' ORDER BY `klienti_name_1` ASC";
$supplier = mysql_query("SET NAMES utf8");
$supplier = mysql_query($tQuery);//,`tbl_klienti`, `tbl_operation_status` WHERE `operation_klient`=`klienti_id` and `operation_status`=`operation_status_id` " . $tQuery . " ORDER BY `operation_data` DESC, `operation_id` DESC ");
if (!$supplier)
{
  echo "Query error Supplier";
  exit();
}

if ($iKlient_id!=0){
$tQuery = "SELECT `klienti_price`,`klienti_discount` FROM `tbl_klienti`,`tbl_operation` WHERE `klienti_id` = `operation_klient` and `operation_id`='".$iKlient_id."'";
$klient = mysql_query("SET NAMES utf8");
$klient = mysql_query($tQuery);
if (!$klient)
{
  echo "Query error Klient";
  exit();
}
$klient_disc=mysql_result($klient,0,"klienti_discount");
$klient_price=mysql_result($klient,0,"klienti_price");
}else{
$klient_disc=0;
$klient_price=2;
}

$tQuery = "SELECT `tovar_parent_id`,`tovar_parent_name` FROM `tbl_parent` ORDER BY `tovar_parent_name` ASC";// WHERE `enti_group` = '5'";
$parent = mysql_query("SET NAMES utf8");
$parent = mysql_query($tQuery);//,`tbl_klienti`, `tbl_operation_status` WHERE `operation_klient`=`klienti_id` and `operation_status`=`operation_status_id` " . $tQuery . " ORDER BY `operation_data` DESC, `operation_id` DESC ");
if (!$parent)
{
  echo "Query error Parent";
  exit();
}
//echo $return_page;
if ($tmp_from=='' and $tmp_to==''){
$tQuery = "SELECT `operation_status_from_as_new`,`operation_status_to_as_new` FROM `tbl_operation_status`,`tbl_operation`  WHERE `operation_status` = `operation_status_id` and `operation_id`='".$iKlient_id."'";
$from_to = mysql_query("SET NAMES utf8");
$from_to = mysql_query($tQuery);//,`tbl_klienti`, `tbl_operation_status` WHERE `operation_klient`=`klienti_id` and `operation_status`=`operation_status_id` " . $tQuery . " ORDER BY `operation_data` DESC, `operation_id` DESC ");
//echo $tQuery;
if (!$from_to)
  {
    echo "Query error From-To";
    exit();
  }
  if ($tmp_from=='') $tmp_from = mysql_result($from_to,0,"operation_status_from_as_new");
  if ($tmp_to=='') $tmp_to = mysql_result($from_to,0,"operation_status_to_as_new");
}
//echo $tmp_from;

$tQuery = "SELECT `warehouse_id`,`warehouse_name`,`warehouse_shot_name` FROM `tbl_warehouse` ORDER BY `warehouse_sort` ASC";
$warehouse = mysql_query("SET NAMES utf8");
$warehouse = mysql_query($tQuery);//,`tbl_klienti`, `tbl_operation_status` WHERE `operation_klient`=`klienti_id` and `operation_status`=`operation_status_id` " . $tQuery . " ORDER BY `operation_data` DESC, `operation_id` DESC ");
if (!$warehouse)
{
  echo "Query error Warehouse";
  exit();
}

$Fields = "";
$warehouse_count=0;
while ($warehouse_count < mysql_num_rows($warehouse))
{
  
  $Fields .= "`warehouse_unit_" . mysql_result($warehouse,$warehouse_count,"warehouse_id") . "`,";
  $warehouse_count++;
}
//=========================== find string=========================================================
$find_flag=0;
$table="";
if ($find_str=="" or $find_str==$m_setup['menu find-str'])
{
//echo "[No find string]";
//exit();
}else{
  $find_str_sql = " and (upper(tovar_name_1) like '%" . mb_strtoupper($find_str,'UTF-8') . "%' or upper(tovar_artkl) like '%" . mb_strtoupper($find_str,'UTF-8') . "%')";
   $find_flag=1;
 }
if ($find_str2=="" or $find_str2==$m_setup['menu find-nakl'])
{
//echo "[No find string]";
//exit();
}else{
  $find_str_sql = " and `tovar_id`=`operation_detail_tovar` and `operation_detail_operation`='".$find_str2."' and `operation_detail_dell`='0'";
  $find_flag=1;
  $table=",`tbl_operation_detail`";
  }

if ($find_supplier==""){$find_supplier=0;}
if ($find_supplier==0)
{

}else{
    $find_str_sql .= " and (tovar_supplier='" . $find_supplier . "')";
}

if ($find_parent==""){$find_parent=1;}
if ($find_parent==1)
{
//echo "[No find Parent]";
//exit();
}else{
//echo "[Finding Parent]";
$find_str_sql .= " and (tovar_parent='" . $find_parent . "')";
} 
//==================================================================================================
$Fields .= "`tovar_id`,`tovar_artkl`,`tovar_name_1`,`tovar_memo`,`tovar_inet_id`"; //Tovar
$ver = mysql_query("SET NAMES utf8");

$sort = "";

if(isset($_REQUEST['sort'])){
  $sort = "ORDER BY `".$_REQUEST['sort']."` ASC";
}else{
  $sort = "ORDER BY `tovar_name_1` ASC, `tovar_artkl` ASC";
}

$tQuery = "SELECT " . $Fields . " 
	  FROM `tbl_tovar`,`tbl_warehouse_unit`".$table." 
	  WHERE `warehouse_unit_tovar_id`=`tovar_id`" . $find_str_sql . "
	  $sort";
//echo $tQuery;

if($find_flag==1)
  {
    $ver = mysql_query($tQuery);
    if (!$ver)
      {
	echo "\nQuery error List";
	exit();
      }
  }

header ('Content-Type: text/html; charset=utf8');
echo "<header><title>Find tovar</title><link rel='stylesheet' type='text/css' href='sturm.css'></header>";

//echo "<header><link rel='stylesheet' type='text/css' href='sturm.css'></header>";
//==================JAVA===========================================
echo "\n<script src='JsHttpRequest.js'></script>";
echo "\n<script type='text/javascript'>";
//================================SET COLOR=====================================
$count=0;
$fields_id = "";
while($count<mysql_num_rows($ver)){ 
    $fields_id .= mysql_result($ver,$count,"tovar_id")."*";
$count++;
}
$count=0;
echo "
var fields_id = '$fields_id'";

echo "\nfunction setcolorfrom(tovar,count_war,from_to){";
  echo "\nif (from_to==1){";
  echo "\nvar a_sel = 'from_'+tovar;";
  echo "\nvar set_col = '" , $color_from , "';";
  echo "\n}else{";
  echo "\nvar a_sel = 'to_'+tovar;";
  echo "\nvar set_col = '" , $color_to , "';";
  echo "\n}";
    echo "\nvar count=1;";
     echo "\n while(count<=count_war){";
     echo "\n var td_null = document.getElementById(tovar+'_'+count);";
     echo "\n if (td_null.bgColor == set_col){";
     echo "\ntd_null.bgColor = '",$color_null,"';}";
     echo "\n count++;}";
 
  echo "\nvar sel = document.getElementById(a_sel);";
  echo "\nvar res = sel.options[sel.selectedIndex].value;";
  echo "\nvar td_set = document.getElementById(tovar+'_'+res);";
  echo "\ntd_set.bgColor=set_col;";
echo "\n}";

//================================SET SUM ALL==============suma po vsej nakladnoj
      echo "\nwindow.onload = function(){";
	$count=0;
      while ($count < mysql_num_rows($ver))
	{
           echo "\nsetprice(",$klient_price,",",mysql_result($ver,$count,"tovar_id"),");";
           echo "\nsetcolorfrom(" ,mysql_result($ver,$count,"tovar_id"), "," , mysql_num_rows($warehouse) ,",1);";
	   echo "\nsetcolorfrom(" ,mysql_result($ver,$count,"tovar_id"), "," , mysql_num_rows($warehouse) ,",2);";
	$count++;
	}
       echo "setsumm_all();
       
       }";//parent.frames.edit_nakl_fields_",$iKlient_id,".document.location.reload();
      
      echo "\n function setsumm_all(){";
       echo "\nvar allSumFil = document.getElementsByName('input');";
         echo "\n count=0;";
     echo "\n}";
//================================SET SUM=================suma po odnoj pozicii
echo "\nfunction setsumm(a){";
     echo "\n var price = document.getElementById(1+'_'+a).value;";
     echo "\n var item = document.getElementById(2+'_'+a).value;";
     echo "\n var disc = document.getElementById(3+'_'+a).value;";
     echo "\n summ = price/100*(100-disc)*item;";
     echo "\ndocument.getElementById(4+'_'+a).value = summ.toFixed(2);";
     echo "\n}";
//================================SET PRICE===============kogda vibor konkretnoj ceni
echo "\nfunction setprice(value,a){";
    echo "\nvar req=new JsHttpRequest();    ";
    echo "\nreq.onreadystatechange=function(){";
    echo "\nif(req.readyState==4){";
    echo "\n var responce=req.responseText;       ";
    echo "\n var res = responce.split('*');";
    echo "\n var curr_sum = res[0] * res[2];";
      echo "\ndocument.getElementById('1_'+a).value=curr_sum.toFixed(2);";
      echo "\nvar price_out = curr_sum / res[3]";
      echo "\ndocument.getElementById('price_info_'+a).value=res[0]+'['+res[1]+'] * '+res[2] + ' ('+ price_out.toFixed(2)+')';";
      echo "\nsetsumm(a);";   
    echo "\n}}";
    
    echo "\nreq.open(null,'get_price.php',true);
      ";
    echo "\nreq.send({price:value,tovar:a});
    ";
    echo "\n}";
//================================SET PRICE ALL=========== kogda vibor obczej ceni
    echo "function reload_list(){
    
        }
      ";
    echo "\nfunction setprice_all(value){";
    echo "\nvar req=new JsHttpRequest();";
    echo "\nreq.onreadystatechange=function(){";
    echo "\nif(req.readyState==4){";
    echo "\n var responce=req.responseText;";
    echo "\n var res = responce.split('*');";
    echo "\n count=0;";
      echo "\nwhile(res[0]>count){";
      echo "\ndocument.getElementById('price_'+res[count+1]).selectedIndex=value-1;";
      echo "\nsetprice(value,res[count+1]);";
      echo "\ncount++;}";
    echo "\n}}";
    echo "\nreq.open(null,'get_operation_detail.php',true);";
    echo "\nreq.send({nakl:",$iKlient_id,"});";
    echo "\n}
    
    function setclear(a){
      if(a==1){
	  if(document.getElementById('_find1').value=='",$m_setup['menu find-str'],"'){
		document.getElementById('_find2').value='",$m_setup['menu find-nakl'],"';
		document.getElementById('_find1').value='';
	    }
      }else{
	    document.getElementById('_find1').value='",$m_setup['menu find-str'],"';
	    document.getElementById('_find2').value='';
      }

    }
    ";
/*
print_barcode('')
 <br><a href=\"barcode.php?operation_id=-1&tovar_id=$fields_id\" target=\"_blank\">",$m_setup['menu print barcode'],"</a>
 <br><a href=\"barcode.php?key=price&operation_id=-1&tovar_id=$fields_id\" target=\"_blank\">",$m_setup['menu print price'],"</a>
 <br><a href=\"barcode.php?key=price_ware&operation_id=-1&tovar_id=$fields_id\" target=\"_blank\">",$m_setup['menu print price war'],"</a>
*/

echo "function print_barcode(key){
	  var sel = document.getElementById('print_from').value;
	  window.open('barcode.php?key='+key+'&ware='+sel+'&tovar_id='+fields_id,'newwin');
}
";    
    
    
    
    echo "\n</script></header>";
//================== END JAVA ================================



echo "\n<body>\n";//<p  style='font-family:vendana;font-size=22px'>";
//============FIND====================================================================================
echo "\n<form method='get' action='edit_tovar_find.php'>
<table class='menu_top'><tr>";

echo "<td><input type='hidden' name='operation_id' value='"  , $iKlient_id  , "'/>";
echo $m_setup['menu find'],":</td><td><input type='text' style='font-size:large;width:350px' id='_find1' name='_find1' value='" , $find_str , "' onChange='submit();' onClick='setclear(1);'/>";
echo "</td><td>";
echo $m_setup['menu find order'],":</td><td><input type='text' style='font-size:large;width:70px' id='_find2' name='_find2' value='" , $find_str2 , "' onChange='submit();' onClick='setclear(2);'/>";
echo "</td><td>";
//=================FROM=ALL===================================================================================================
  echo "",$m_setup['menu from'],":</td><td><select class=\"nak_field_warehouse_from\" style='width:150px' name='_from' onChange='submit()'>";
    $count1=0;
    while ($count1 < mysql_num_rows($warehouse))
    {
    echo "\n<option ";
	#echo mysql_result($ver,0,"klienti_group") , " " , mysql_result($kli_grp,$count,"klienti_group_id");
	if ($tmp_from == mysql_result($warehouse,$count1,"warehouse_id")) echo "selected ";
    echo "value=" . mysql_result($warehouse,$count1,"warehouse_id") . ">" . mysql_result($warehouse,$count1,"warehouse_name") . "</option>";
    $count1++;
    }
echo "</select>";
//=================FROM=ALL===================================================================================================
echo "</td><td rowspan=\"2\">
  FROM - <select  style='width:150px' name='print_from' id='print_from'>";
	  $count1=0;
	  while ($count1 < mysql_num_rows($warehouse))
	  {
	    echo "<option 
	    value=" . mysql_result($warehouse,$count1,"warehouse_id") . ">" . mysql_result($warehouse,$count1,"warehouse_name") . "</option>";
	  $count1++;
	  }
	  echo "</select>
 <br><a href=\"javascript:print_barcode('')\" >",$m_setup['menu print barcode'],"</a>
 <br><a href=\"javascript:print_barcode('price')\" >",$m_setup['menu print price'],"</a>
 <br><a href=\"javascript:print_barcode('price_ware')\" >",$m_setup['menu print price war'],"</a>
</tr><tr><td>";//========================
echo "",$m_setup['menu parent'],":</td><td><select name='_parent' style='width:350px' onChange='submit();'>";
    $count=0;
    while ($count < mysql_num_rows($parent))
    {
    echo "\n<option ";
	if ($find_parent == mysql_result($parent,$count,"tovar_parent_id")) echo "selected ";
    echo "value=" . mysql_result($parent,$count,"tovar_parent_id") . ">" . mysql_result($parent,$count,"tovar_parent_name") . "</option>";
    $count++;
    }

echo "</select>";
echo "</td><td>";//========================
echo "",$m_setup['menu suppliter'],":</td><td><select name='_supplier' style='width:150px' onChange='submit();'>";
      echo "\n<option ";
	if ($find_supplier == 0) echo "selected ";
	  echo "value=0>ALL</option>";
    $count=0;
    while ($count < mysql_num_rows($supplier))
    {
    echo "\n<option ";
	if ($find_supplier == mysql_result($supplier,$count,"klienti_id")) echo "selected ";
    echo "value=" . mysql_result($supplier,$count,"klienti_id") . ">" . mysql_result($supplier,$count,"klienti_name_1") . "</option>";
    $count++;
    }

echo "</select>";
echo "</td><td>";//========================

//==================TO=ALL==================================================================================================
   echo "",$m_setup['menu to'],":</td><td><select class=\"nak_field_warehouse_to\" name='_to' style='width:150px' onChange='submit()'>";# OnChange='submit();'>";
    $count1=0;
    while ($count1 < mysql_num_rows($warehouse))
    {
    echo "\n<option ";
	#echo mysql_result($ver,0,"klienti_group") , " " , mysql_result($kli_grp,$count,"klienti_group_id");
	if ($tmp_to == mysql_result($warehouse,$count1,"warehouse_id")) echo "selected ";
    echo "value=" . mysql_result($warehouse,$count1,"warehouse_id") . ">" . mysql_result($warehouse,$count1,"warehouse_name") . "</option>";
    $count1++;
    }
echo "</select>";
//=====================================================================================================================
echo "</td></tr></table>";//========================

echo "\n</form>";
//=====================================================================================================


  if($find_flag==1)
  {
echo "\n<form method='post' action='edit_table_nakl.php'>";
//echo "\n<input type='submit' name='_add' value='add'/>";
//$return_page
echo "\n<input type='hidden' name='_id_value' value='"  , $iKlient_id  , "'/>";
echo "\n<input type='hidden' name='_id_name' value='" , $this_table_id_name , "'/>";
echo "\n<input type='hidden' name='_table_name' value='" , $this_table_name , "'/>";
echo "\n<input type='hidden' name='_select' value='" , $return_page , "'/>";
  
echo "\n<input type='hidden' name='_page_to_return' value='" , $return_page , "'/>";

echo "\n<table width=100% cellspacing='0' cellpadding='0' style='border-left:1px solid;border-right:1px solid;border-top:1px solid' class='menu_top'>"; //class='table'
echo "<tr class=\"nak_header_find\">
      <th width=30px><a href=\"edit_tovar_find.php?operation_id=$iKlient_id&$for_link&sort=tovar_id\">id >></a></th>
      <th width=100px><a href=\"edit_tovar_find.php?operation_id=$iKlient_id&$for_link&sort=tovar_artkl\">Artikl >></a></th>
      <th><a href=\"edit_tovar_find.php?operation_id=$iKlient_id&$for_link&sort=tovar_name_1\">Name >></a></th>
      <th width=90px>Price</th>
      <th width=50px>Item</th>
      <th width=30px>Disc.</th>
      <th width=70px>Summ</th>
      <th width=32px>add</th>
      <th width=20px></th>
      <th width=100px>From/To</th>
      <th width=90px></th>
       <th width=130px>Memo</th></tr>";
//echo mysql_num_rows($ver);
$count=0;
$i = 1;
while ($count < mysql_num_rows($ver))
{
$id_tmp=mysql_result($ver,$count,"tovar_id");
      if ($i == 1){
	  $i = 2;
      }else{
	  $i = 1;
      }
  echo "<tr class=\"nak_field_$i\">";
  echo "<td  width=50px><a class=\"small\" href='edit_tovar_history.php?tovar_id=",mysql_result($ver,$count,'tovar_id')," ' target='_blank'>",$m_setup['menu history'],"&nbsp;</a>";
  
  echo "<input type='hidden' name='operation_detail_tovar*",$id_tmp,"' value='" , $id_tmp , "'/>";
  echo "<input type='hidden' name='operation_detail_operation*",$id_tmp,"' value='" , $iKlient_id, "'/>";
 
  echo "<td><b><a class=\"medium_bold\" href='edit_tovar.php?tovar_id=", $id_tmp," ' target='_blank'>&nbsp;", mysql_result($ver,$count,'tovar_artkl'), "</a>&nbsp;</b></td>";
  echo "<td><b><a class=\"medium_regular\" href='edit_tovar.php?tovar_id=", $id_tmp," ' target='_blank'>", mysql_result($ver,$count,'tovar_name_1'), "</a></b></td>";

  echo "\n<td><input class=\"nak_field_price\" type='text' id='1_",$id_tmp,"' name='operation_detail_price*",$id_tmp,"' value='' onChange='setsumm(",$id_tmp,")'/></td>";
  echo "\n<td><input class=\"nak_field_item\" type='text' id='2_",$id_tmp,"' name='operation_detail_item*",$id_tmp,"' value='' onChange='setsumm(",$id_tmp,")'/></td>";
  echo "\n<td><input class=\"nak_field_discount\" type='text' id='3_",$id_tmp,"' name='operation_detail_discount*",$id_tmp,"' value='",$klient_disc,"' onChange='setsumm(",$id_tmp,")'/></td>";
  echo "\n<td><input class=\"nak_field_summ\" type='text' id='4_",$id_tmp,"' name='operation_detail_summ*",$id_tmp,"' value=''/></td>";
  echo "\n<td align=center>
	      <input class=\"nak_field_add\" type='submit' name='_add' value=' + ' onclick='reload_list();'/></td>";

//=================FROM====================================================================================================
  echo "<td>from:<br>to:</td><td style='border-bottom:1px solid'><select name='operation_detail_from*",$id_tmp,"' class=\"nak_field_warehouse_from\" id='from_" , $id_tmp, "' style='width:100px' onChange='setcolorfrom(" ,$id_tmp, "," , mysql_num_rows($warehouse) ,",1)'>";# OnChange='submit();'>";
    $count1=0;
    while ($count1 < mysql_num_rows($warehouse))
    {
    echo "\n<option ";
	#echo mysql_result($ver,0,"klienti_group") , " " , mysql_result($kli_grp,$count,"klienti_group_id");
	if ($tmp_from == mysql_result($warehouse,$count1,"warehouse_id")) echo "selected ";
    echo "value=" . mysql_result($warehouse,$count1,"warehouse_id") . ">" . mysql_result($warehouse,$count1,"warehouse_name") . "</option>";
    $count1++;
    }
echo "</select><br>";
//==================TO===================================================================================================
   echo "<select name='operation_detail_to*",$id_tmp,"' class=\"nak_field_warehouse_to\" id='to_" , $id_tmp, "' style='width:100px' onChange='setcolorfrom(" ,$id_tmp, "," , mysql_num_rows($warehouse) ,",2)'>";# OnChange='submit();'>";
    $count1=0;
    while ($count1 < mysql_num_rows($warehouse))
    {
    echo "\n<option ";
	#echo mysql_result($ver,0,"klienti_group") , " " , mysql_result($kli_grp,$count,"klienti_group_id");
	if ($tmp_to == mysql_result($warehouse,$count1,"warehouse_id")) echo "selected ";
    echo "value=" . mysql_result($warehouse,$count1,"warehouse_id") . ">" . mysql_result($warehouse,$count1,"warehouse_name") . "</option>";
    $count1++;
    }
echo "</select></td>";
//=====================================================================================================================
    //==================PRICE===================================================================================================
	if ($iPrice < 1)$iPrice=2; 
  echo "<td>";
  echo "<input type='text' id='price_info_",$id_tmp,"' value='non' class=\"nak_field_$i\" style='font-size:x-small;width:120px;'/>";
  echo "<select class=\"nak_field_$i\" style='font-size:x-small;width:120px' id='price_",$id_tmp,"' onChange='setprice(this.value,",$id_tmp,")'>";# OnChange='submit();'>";
    $count1=0;
    while ($count1 < mysql_num_rows($price))
    {
    echo "\n<option ";
	  if ($klient_price == mysql_result($price,$count1,"price_id")) echo "selected ";
    echo "value=" . mysql_result($price,$count1,"price_id") . ">" . mysql_result($price,$count1,"price_name") . "</option>";
    $count1++;
    }
echo "</select></td>";
//=====================================================================================================================
  
  echo "\n<td style='border-bottom:1px solid'>";
  echo "\n<input type='text' style='width:130px;' class=\"nak_field_$i\" name='operation_detail_memo*",mysql_result($ver,$count,"tovar_id"),"' value=''/>";
  echo "</td>";
  echo "\n</tr>";
  echo "\n<tr class=\"nak_field_$i\">";
  echo "\n<td style='border-bottom:1px solid'>-</td><td style='border-bottom:1px solid'>";
  if(mysql_result($ver,$count,"tovar_inet_id")>0) echo "web";
  echo "</td>";
  echo "\n<td style='border-bottom:1px solid' colspan='10' valing='top' align='right'>";
  echo "\n<table width=60% cellspacing='0' cellpadding='0'><tr class=\"nak_field_$i\">";
  
  $warehouse_count=0;
  $warehouse_count_row = 0;
  while ($warehouse_count < mysql_num_rows($warehouse))
  {
      $warehouse_unit= mysql_result($warehouse,$warehouse_count,"warehouse_id");
 
  echo "<td id=\"" , mysql_result($ver,$count,"tovar_id") , "_" , mysql_result($warehouse,$warehouse_count,"warehouse_id")  , "\"
	  class=\"ware_".mysql_result($warehouse,$warehouse_count,"warehouse_id")."\"
	  style=\"border-left:1px solid;border-top:1px solid;\"";
 
  echo " width=", 100 / mysql_num_rows($warehouse) ,"%>";  
       
   // "<font class=\"ware_".mysql_result($warehouse,$warehouse_count,"warehouse_id")."\">";
    echo mysql_result($warehouse,$warehouse_count,"warehouse_shot_name") . ":&nbsp";
   
    if (mysql_result($ver,$count,"warehouse_unit_" . $warehouse_unit)>0) echo "<font color='black'><b>";
     elseif (mysql_result($ver,$count,"warehouse_unit_" . $warehouse_unit)<0) echo "<font color='red'><b>";
    echo mysql_result($ver,$count,"warehouse_unit_" . $warehouse_unit);
   //  "</font>";
    echo "</td>";
    if ($warehouse_count_row>$warehouse_row_limit){
      echo"\n</tr><tr>";
      $warehouse_count_row=0;}
  $warehouse_count++;
  $warehouse_count_row++;

  }

  echo "\n</tr></table>";
  echo "\n</td>";
  echo "\n</tr>";
$count++;
}

echo "\n</table>";
echo "\n<td><input type='hidden' name='end*-1' value='end'/>";

echo "\n</form>";
}
echo "\n</body>";
//print_r("test");

//print_r(phpinfo());
?>
