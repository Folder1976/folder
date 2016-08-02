<?php

//include '../init.lib.php';
//include '../nakl.lib.php';
connect_to_mysql();


global $setup;

$ProductsID = array();
//echo '<pre>'; print_r(var_dump($setup));
include '../class/class_category.php';
$Category = new Category($folder);


$count = 0;

$iKlient_id = -1;
$operation_id = 0;
if(isset($_REQUEST["operation_id"])){
  $iKlient_id=$_REQUEST["operation_id"];
  $operation_id=$_REQUEST["operation_id"];
}
$shop_selected = 0;
$sort_parent="";
$sort_parent_tovar = "";
if(isset($_REQUEST["_shop"]))  $shop_selected=$_REQUEST["_shop"];
if($shop_selected!=0){
$sort_parent_tovar = " and `tovar_parent_shop`='".$shop_selected."' ";
$sort_parent = " WHERE `tovar_parent_shop`='".$shop_selected."' or `tovar_parent_shop`='0'";
}

$for_link = "";

$find_str = "";
if(isset($_GET["_find1"]) and !empty($_GET["_find1"])) $find_str=$_GET["_find1"];
$find_str = str_replace(" ","%",$find_str);

$find_str2 = "";
if(isset($_GET["_find2"]) and !empty($_GET["_find2"]))$find_str2=$_GET["_find2"];



if($find_str=="find-str"){
    $find_str=$setup['menu find-str'];
    $for_link .= "_find1=find-str";
}else{
    $for_link .= "_find1=".$find_str;
}

if($find_str2=="find-str"){
  $find_str2=$setup['menu find-nakl'];
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
if(isset($_REQUEST["_from"]) and is_numeric($_REQUEST["_from"])) $tmp_from=$_REQUEST["_from"];
$for_link .= "&_from=".$tmp_from;

$tmp_to = "";
if(isset($_REQUEST["_to"]) AND is_numeric($_REQUEST["_to"])) $tmp_to=$_REQUEST["_to"];
$for_link .= "&_to=".$tmp_to;

$ware_empty = "";
if(isset($_REQUEST["ware_empty"])) $ware_empty=$_REQUEST["ware_empty"];
$for_link .= "&".$ware_empty;

$set_rezervi = "";
if(isset($_REQUEST["set_rezervi"])) $set_rezervi=$_REQUEST["set_rezervi"];
$for_link .= "&".$set_rezervi;

$usd2uah = "";
if(isset($_REQUEST["usd2uah"])) $usd2uah=$_REQUEST["usd2uah"];
$for_link .= "&".$usd2uah;

$iPrice = 1;

$find_str_sql="";
$this_table_name = "tbl_operation_detail";
$long_name = "operation_detail_";
$this_table_id_name = "operation_detail_operation";
$return_page = "main.php?func=export_universal&operation_id=" . $iKlient_id."&_shop=".$shop_selected."&_from=".$tmp_from."&_to=".$tmp_to."&_find1=".$find_str."&_supplier=".$find_supplier."&_parent=".$find_parent;
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

$s_value="";
$s_name="";
$s_list="";
$s_empty = "";
if(isset($_REQUEST['ware_empty'])) $return_page .= "&ware_empty=ware_empty";
//=============================================WAREHOUSE==============================
    foreach ($_REQUEST as $varname => $varvalue){
	    if(substr($varname,0,5) == "ware*"){
		  $post[$varname] = $varvalue;
		  if ($s_value == "") {
		      $s_value .=  " WHERE `warehouse_id`='" . $post[$varname] . "'";
		      $s_list .= $post[$varname];
		      $return_page .= "&ware*".$post[$varname]."=".$post[$varname];
		      $for_link .= "&ware*".$post[$varname]."=".$post[$varname];
		      $s_empty .= " and (`warehouse_unit_".$post[$varname]."` ";
		  }else{
		      $s_value .=  " or `warehouse_id`='" . $post[$varname] . "'";
		      $s_list .= ",".$post[$varname];
		      $return_page .= "&ware*".$post[$varname]."=".$post[$varname];
		      $for_link .= "&ware*".$post[$varname]."=".$post[$varname];
		      $s_empty .= "+`warehouse_unit_".$post[$varname]."` ";
		  }
    
	    }
    }

//=================================KURSI VALUT======================
if ($_SESSION[BASE.'lang'] <1){
  $_SESSION[BASE.'lang']=1;
}
$r = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	  `currency_id`,`currency_ex`
	  FROM `tbl_currency`
	  ";
$r = mysql_query($tQuery);
$m_curr = array();
$count=0;
while ($count<mysql_num_rows($r)){
 $m_curr[mysql_result($r,$count,0)]=mysql_result($r,$count,1);
 $count++;
}
//============================================================================
$tQuery = "SELECT `klienti_id`,`klienti_name_1` FROM `tbl_klienti` WHERE `klienti_group` = '6' ORDER BY `klienti_name_1` ASC";
$poluchil = mysql_query("SET NAMES utf8");
$poluchil = mysql_query($tQuery);

$tQuery = "SELECT `firms_id`,`firms_name` FROM `tbl_firms` ORDER BY `firms_name` ASC";
$vidal = mysql_query("SET NAMES utf8");
$vidal = mysql_query($tQuery);


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

$tQuery = "SELECT `shop_id`,`shop_name_".$_SESSION[BASE.'lang']."` as shop_name FROM `tbl_shop` ORDER BY `shop_sort` ASC";
$shop = mysql_query("SET NAMES utf8");
$shop = mysql_query($tQuery);//,`tbl_klienti`, `tbl_operation_status` WHERE `operation_klient`=`klienti_id` and `operation_status`=`operation_status_id` " . $tQuery . " ORDER BY `operation_data` DESC, `operation_id` DESC ");
if (!$shop)
{
  echo "Query error Shop";
  exit();
}

$klient_disc=0;
$klient_price=2;

$tQuery = "SELECT `tovar_parent_id`,`tovar_parent_name` 
	  FROM `tbl_parent` 
	  ".$sort_parent."
	  ORDER BY `tovar_parent_name` ASC";
	 // echo $tQuery;
$parent = mysql_query("SET NAMES utf8");
$parent = mysql_query($tQuery);//,`tbl_klienti`, `tbl_operation_status` WHERE `operation_klient`=`klienti_id` and `operation_status`=`operation_status_id` " . $tQuery . " ORDER BY `operation_data` DESC, `operation_id` DESC ");
if (!$parent)
{
  echo "Query error Parent";
  exit();
}
//echo $return_page;
if ($tmp_from=='' and $tmp_to=='' and $operation_id > 0){
$tQuery = "SELECT `operation_status_from_as_new`,`operation_status_to_as_new` FROM `tbl_operation_status`,`tbl_operation`  WHERE `operation_status` = `operation_status_id` and `operation_id`='".$iKlient_id."'";
//echo $tQuery;
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

    
//echo $s_name," ---- ",$s_value," === ",$s_list,"<br>";
//=============================================WAREHOUSE END==========================
$tQuery = "SELECT `warehouse_id`,`warehouse_name`,`warehouse_shot_name` FROM `tbl_warehouse` ORDER BY `warehouse_sort` ASC";
$warehouse_list = mysql_query("SET NAMES utf8");
$warehouse_list = mysql_query($tQuery);//,`tbl_klienti`, `tbl_operation_status` WHERE `operation_klient`=`klienti_id` and `operation_status`=`operation_status_id` " . $tQuery . " ORDER BY `operation_data` DESC, `operation_id` DESC ");


$tQuery = "SELECT `warehouse_id`,`warehouse_name`,`warehouse_shot_name`,`warehouse_summ` FROM `tbl_warehouse` $s_value ORDER BY `warehouse_sort` ASC";
$warehouse = mysql_query("SET NAMES utf8");
$warehouse = mysql_query($tQuery);//,`tbl_klienti`, `tbl_operation_status` WHERE `operation_klient`=`klienti_id` and `operation_status`=`operation_status_id` " . $tQuery . " ORDER BY `operation_data` DESC, `operation_id` DESC ");
if (!$warehouse)
{
  echo "Query error Warehouse";
  exit();
}

$Fields = "";
$warehouse_count=0;
$tmp = 0;
if($s_list=="") $tmp =1;

//=========================== find string=========================================================
$find_flag=0;
$table="";
if ($find_str=="" or $find_str==$setup['menu find-str']){
//echo "[No find string]";
//exit();
}else{
  $find_str_sql .= " (upper(tovar_name_1) like '%" . mb_strtoupper($find_str,'UTF-8') . "%' or upper(tovar_artkl) like '%" . mb_strtoupper($find_str,'UTF-8') . "%'";
  $find_str_sql .= " or upper(tovar_name_2) like '%" . mb_strtoupper($find_str,'UTF-8') . "%'";
  $find_str_sql .= " or upper(tovar_name_3) like '%" . mb_strtoupper($find_str,'UTF-8') . "%')";
   $find_flag=1;
 }

  if ($find_parent==""){$find_parent=1;}
  if ($find_parent==1){
//echo "[No find Parent]";
//exit();
}else{
//echo "[Finding Parent]";
$find_str_sql .= " (tovar_parent='" . $find_parent . "')";
} 
//==================================================================================================
$Fields .= "T.tovar_id,`tovar_artkl`,`tovar_name_1`,`tovar_memo`,`tovar_inet_id`, `social_fb`, `social_vk`, `tovar_last_edit`, `use_in_market`"; //Tovar
$ver = mysql_query("SET NAMES utf8");

$sort = "";

/*
if(isset($_REQUEST['sort'])){
  $sort = "ORDER BY `".$_REQUEST['sort']."` ASC";
}else{
  $sort = "ORDER BY `tovar_name_1` ASC, `tovar_artkl` ASC";
}
*/

if(isset($_GET['datasort'])){
  $sort = "ORDER BY tovar_last_edit DESC, `tovar_name_1` ASC, `tovar_artkl` ASC";
}else{
  $sort = "ORDER BY `tovar_name_1` ASC, `tovar_artkl` ASC";
}

  if(isset($_REQUEST['ware_empty'])){
      $s_empty .= ") <> '0' ";
  }else{
      $s_empty="";
  }  
 
 $inet_categ = '';
 if(isset($_GET['_category']) AND (int)$_GET['_category'] > 0){
    
    $tmp = $Category->getCategoryChildrenFull((int)$_GET['_category']);
    $categories = array($_GET['_category'] => $_GET['_category']);
 
  if($tmp){ 
      foreach($tmp as $index => $name){
        if(is_numeric($index)){
          $categories[$index] = $index;
        }
      }
  }
      if(count($categories) > 0){
        $inet_categ = ' AND tovar_inet_id_parent IN (' . implode(',', $categories) . ') ';
      }
  
  
 }

 $sort_brand = '';
 if(isset($_GET['_brand']) AND (int)$_GET['_brand'] > 0){
  $sort_brand = ' AND brand_id = "'.$_GET['_brand'].'" ';
 }
 $sort_user = '';
 if(isset($_GET['_user']) AND (int)$_GET['_user'] > 0){
  $sort_user = ' AND tovar_last_edit_user = "'.$_GET['_user'].'" ';
 }
 $sort_supp = '';
 if(isset($_GET['_supplier']) AND (int)$_GET['_supplier'] > 0){
  $sort_supp = ' AND TSI.postav_id = "'.$_GET['_supplier'].'" ';
 }
    
$tQuery = "SELECT " . $Fields . " 
	  FROM `tbl_tovar` T
        LEFT JOIN `tbl_parent` ON `tovar_parent`=`tovar_parent_id` 
	    LEFT JOIN `tbl_tovar_suppliers_items` TSI ON TSI.tovar_id=T.tovar_id 
	  WHERE 
	    " . $s_empty . "
	    " . $sort_parent_tovar . "
	    " . $find_str_sql . "
        " . $sort_brand . "
        " . $sort_user . "
        " . $inet_categ . " 
	    " . $sort_supp . " 
	  $sort";
//echo $tQuery;

if($find_flag==1 and $find_str_sql != "")
  {
    $ver = mysql_query($tQuery);
    
    if (!$ver)
      {
	echo "\nQuery error List";
	exit();
      }
  }
?>
<script>
  $(document).on('change', '.is_social', function(){
      var id = $(this).attr('id');
      var value = 0;
      
      if ($(this).is(":checked")) {
        value = 1;
      }
      
      //console.log(id);
      $.ajax({
        type: "POST",
        url: "export/set_social.php",
        dataType: "text",
        data: "id="+id+"&value="+value,
        beforeSend: function(){
        },
        success: function(msg){
          console.log(  msg );
        }
      });
    
  });
  
  </script>

<?php
//header ('Content-Type: text/html; charset=utf8');
echo "<header><title>Find tovar</title><link rel='stylesheet' type='text/css' href='sturm.css'></header>";

//echo "<header><link rel='stylesheet' type='text/css' href='sturm.css'></header>";
//==================JAVA===========================================
echo "\n<script src='JsHttpRequest.js'></script>";
echo "\n<script type='text/javascript'>";



//================================SET COLOR=====================================
$count=0;
$fields_id = "";
if ($find_str_sql != ""){
    while($count<mysql_num_rows($ver)){ 
	  $fields_id .= mysql_result($ver,$count,"tovar_id")."*";
	  $count++;
    }
}    
$count=0;
echo "
var fields_id = '$fields_id'";

echo "
    function setclear(a){
   // alert('gg');
      if(a==1){
	  if(document.getElementById('_find1').value=='",$setup['menu find-str'],"'){
		document.getElementById('_find2').value='",$setup['menu find-nakl'],"';
		document.getElementById('_find1').value='';
	    }
      }else{
	    document.getElementById('_find1').value='",$setup['menu find-str'],"';
	    document.getElementById('_find2').value='';
      }

    }


</script>

<body>\n";//<p  style='font-family:vendana;font-size=22px'>";
//============FIND====================================================================================

?>
<style>
  .menu_top{
    border: 1px solid black;
    
  }
  .menu_top td {
    border: 1px solid black;
   /* background-color: red;*/
    
  }
</style>

<?php


//<input type='hidden' name='MAX_FILE_SIZE' value='",1048*1048*1048,"'>
echo "<form method='get' action='main.php'>
      
      <table class='menu_top'><tr>";

echo "<td><input type='hidden' name='func' value='export_universal'/>
        <input type='hidden' name='operation_id' value='"  , $iKlient_id  , "'/>";
echo "<input type='button' name='test' value='",$setup['menu find'],"' onclick='submit();' tabindex='1'></td>
<td><input type='text' style='font-size:large;width:350px'
    placeholder = '% (знак процента) Для вывода всех товаров'
    id='_find1' name='_find1' value='" , $find_str , "' onChange='submit();' onClick='setclear(1);'/>";
echo "
      </td><td>";
        echo $setup['menu find order'],":</td>
      <td><input type='text' style='font-size:large;width:70px' id='_find2' name='_find2' value='" , $find_str2 , "' onChange='submit();' onClick='setclear(2);'/>";

//=====================================================================================
echo "</td>
    <td>";
echo "</td>";
echo "<td rowspan=\"2\" valing=\"top\">";
echo "<input type='button' name='export' class='export' id='export' value='Экспорт в эксель' onclick='submit();' tabindex='5'>";
echo "</td>";

     $ProductsID = array();

echo "</tr>";

//====================================================================================================================================
//====================================================================================================================================
//====================================================================================================================================
//====================================================================================================================================

echo "<tr><td valing=\"middle\">
      Категори:<br>
      Бренд:<br>
      Редактор:

      ";
//==========================SHOP==========================================================
echo "<!--a href='edit_shop.php?shop_id=$shop_selected' target='_blank'>",$setup['menu shop'],"[+]:</a>
      <a href='edit_nakl-group.php?tovar_parent_id=$find_parent' target='_blank'>",$setup['menu parent'],"[+]</a>
     <br>Категория на сайте:-->
      
      </td><td>
      
      <!--select name='_shop' style='width:350px' onChange='submit();'>";
          $count=0;
          while ($count < mysql_num_rows($shop))
          {
          echo "\n<option ";
          if ($shop_selected == mysql_result($shop,$count,"shop_id")) echo "selected ";
          echo "value=" . mysql_result($shop,$count,"shop_id") . ">" . mysql_result($shop,$count,"shop_name") . "</option>";
          $count++;
          }
      
      echo "</select><br-->";
//==========================PARENT============================================================
echo "<!--select name='_parent' id='_parent' style='width:350px' onChange='submit();'>";
    $count=0;
    while ($count < mysql_num_rows($parent))
    {
    echo "\n<option ";
	if ($find_parent == mysql_result($parent,$count,"tovar_parent_id")) echo "selected ";
    echo "value=" . mysql_result($parent,$count,"tovar_parent_id") . ">" . mysql_result($parent,$count,"tovar_parent_name") . "</option>";
    $count++;
    }
echo "</select-->";

      //Категории на сайте
      $categs = $Category->getAllCategoryIdAndUrl();
      echo "<select class=\"_category\" name=\"_category\" style='width:350px' onChange='submit();'>
					<option value=\"0\">Выбрать категорию = все</option>";
			foreach($categs as $value){ 
				if($value['parent_inet_id'] > 0){
                    if(isset($_GET['_category']) AND $_GET['_category'] == $value['parent_inet_id']){
                        echo "<option selected value='" . $value['parent_inet_id'] . "'>". $value['name']. ' -> '.$value['seo_alias'] . "</option>";
                    }else{
                        echo "<option value='" . $value['parent_inet_id'] . "'>". $value['name']. ' -> '.$value['seo_alias'] . "</option>";
                    }
				} 
			 } 
	  echo '</select><br>';

      //Бренд
      include 'class/class_brand.php';
      $Brand = new Brand($folder);
      $brands = $Brand->getBrands();
	  echo "<select class=\"_brand\" name=\"_brand\" style='width:350px' onChange='submit();'>
					<option value=\"0\">Выбрать Бренд = все</option>";
			foreach($brands as $value){ 
				if($value['brand_id'] > 0){
                    if(isset($_GET['_brand']) AND $_GET['_brand'] == $value['brand_id']){
                        echo "<option selected value='" . $value['brand_id'] . "'>". $value['brand_name'] . "</option>";
                    }else{
                        echo "<option value='" . $value['brand_id'] . "'>". $value['brand_name'] . "</option>";
                    }
				} 
			 } 
	  echo '</select><br>';
      
      //Пользователь
      include 'class/class_user.php';
      $User = new User($folder);
      $users = $User->getUsers();
   
	  echo "<select class=\"_user\" name=\"_user\" style='width:350px' onChange='submit();'>
					<option value=\"0\">Выбрать редактора = все</option>";
			foreach($users as $value){ 
				if($value['id'] > 0){
                    if(isset($_GET['_user']) AND $_GET['_user'] == $value['id']){
                        echo "<option selected value='" . $value['id'] . "'>". $value['name'] . "</option>";
                    }else{
                        echo "<option value='" . $value['id'] . "'>". $value['name'] . "</option>";
                    }
				} 
			 } 
	  echo '</select>';
		
//=============================SUPPLIER==========================================================
echo "</td><td>";//========================
echo "
    Сортировать по дате создания <input type='checkbox' name='datasort'  onChange='submit();' ";
      if(isset($_GET['datasort'])) echo ' checked ';
    echo "><br>  
    <a href='edit_klient.php?klienti_id=$find_supplier' target='_blank'>",$setup['menu suppliter'],"[+]:</a></td><td><select name='_supplier' style='width:150px' onChange='submit();'>";
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

//=====================================================================================================================
echo "</td></tr></table>";//========================
$tmp = 0;

echo "\n</form>";
//=====================================================================================================


  if($find_flag==1)
  {
echo "\n<form method='post' action='main.php?func=export_universal'>";
//echo "\n<input type='submit' name='_add' value='add'/>";
//$return_page
echo "\n<input type='hidden' name='_id_value' value='"  , $iKlient_id  , "'/>";
echo "\n<input type='hidden' name='_id_name' value='" , $this_table_id_name , "'/>";
echo "\n<input type='hidden' name='_table_name' value='" , $this_table_name , "'/>";
echo "\n<input type='hidden' name='_select' value='" , $return_page , "'/>";
  
echo "\n<input type='hidden' name='_page_to_return' value='" , $return_page , "'/>";

echo "\n<table width=100% cellspacing='0' cellpadding='0' style='border-left:1px solid;border-right:1px solid;border-top:1px solid' class='menu_top'>"; //class='table'
echo "<tr class=\"nak_header_find\">
      <th width=20px  height=\"50px\"><a href=\"main.php?func=export_universal&operation_id=$iKlient_id&$for_link&sort=tovar_id\">id >></a></th>
      <th width=100px><a href=\"main.php?func=export_universal&operation_id=$iKlient_id&$for_link&sort=tovar_artkl\">".$setup['menu artkl']."</a>
		      </th>
              <th>Социал</th>
      <th><a href=\"main.php?func=export_universal&operation_id=$iKlient_id&$for_link&sort=tovar_name_1\">".$setup['menu name1']."</a><br>
		      </th>
                         <th>Дата</th>
  
              
              ";

echo "<!--th width=10px></th-->";
echo "</tr>";
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
      
      if(isset($_REQUEST['_ostatki'])){
	  reset_warehouse_on_tovar_id($id_tmp);
      }
      
      $product_id = mysql_result($ver,$count,'tovar_id');
  $ProductsID[] = $product_id;
  echo "<tr class=\"nak_field_$i\">";
  echo "<td width=70px><a class=\"small\" href='edit_tovar_history.php?tovar_id=",$product_id," ' target='_blank'>",
    ($count+1) ;
    if(mysql_result($ver,$count,"tovar_inet_id")>0) echo "&nbsp;web&nbsp;&nbsp;";
  echo $setup['menu history'],"&nbsp;</a>";
  
  
  echo "<input type='hidden' name='",$long_name,"tovar*",$id_tmp,"' value='" , $id_tmp , "'/>";
  echo "<input type='hidden' name='",$long_name,"operation*",$id_tmp,"' value='" , $iKlient_id, "'/>
  </td>";
 
  echo "<td width=150px><b><a class=\"small_name\" href='edit_tovar.php?tovar_id=", $id_tmp," ' target='_blank'>&nbsp;", mysql_result($ver,$count,'tovar_artkl'), "</a>&nbsp;</b></td>";
  echo '<td width=130px>';
      //facebook
      echo "FB<input type='checkbox' id='social_fb*".$product_id."' class='is_social'";
        if(mysql_result($ver,$count,'social_fb') == 1) echo ' checked ';
      echo '>&nbsp;';
      //vk
      echo "VK<input type='checkbox' id='social_vk*".$product_id."' class='is_social'";
        if(mysql_result($ver,$count,'social_vk') == 1) echo ' checked ';
      echo '>&nbsp;';
      echo "YM<input type='checkbox' id='use_in_market*".$product_id."' class='is_social'";
        if(mysql_result($ver,$count,'use_in_market') == 1) echo ' checked ';
      echo '>';
  echo "</td>";
  
  echo "<td><b><a class=\"small_name\" href='edit_tovar.php?tovar_id=", $id_tmp," ' target='_blank'>", mysql_result($ver,$count,'tovar_name_1'), "</a></b></td>";
  echo '<td width=170px>';
         echo mysql_result($ver,$count,'tovar_last_edit');
  echo "</td>";


    echo "\n</tr>";
$count++;
}

echo "\n</table>";
echo "\n<td><input type='hidden' name='end*-1' value='end'/>";

echo "\n</form>";
}

echo "<div id='nakl_info' class='nakl_info'>
<table class='menu_top'> <tr><td align='right' colspan='2'>
<a href=\"javascript:print_nakl_close();\" ><b>close [X]</b></a><br>
</td></tr>
  <tr><td>".$setup['menu parent'],"
  </td><td><select id='_parent_view' style='width:200px'>";
    $count=0;
    while ($count < mysql_num_rows($parent))
    {
    echo "\n<option ";
	if ($find_parent == mysql_result($parent,$count,"tovar_parent_id")) echo "selected ";
    echo "value=" . mysql_result($parent,$count,"tovar_parent_id") . ">" . mysql_result($parent,$count,"tovar_parent_name") . "</option>";
    $count++;
    }

echo "</select>

  </td></tr>
  <tr><td>
	".$setup['print sklad']."
 </td><td>
	<select name='_ware' id='_ware' style='width:200px' >";
    $count=0;
    while ($count < mysql_num_rows($warehouse))
    {
	echo "\n<option value=" . mysql_result($warehouse,$count,"warehouse_id") . ">" . mysql_result($warehouse,$count,"warehouse_name") . "</option>";
    $count++;
    }
echo "</select>
</td></tr>
  <tr><td>
	".$setup['print vidal']."
 </td><td>
	<select name='_vidal' id='_vidal' style='width:200px' >";
    $count=0;
    while ($count < mysql_num_rows($vidal))
    {
	echo "\n<option value=" . mysql_result($vidal,$count,"firms_id") . ">" . mysql_result($vidal,$count,"firms_name") . "</option>";
    $count++;
    }
echo "</select>
</td></tr>
  <tr><td>
".$setup['print otrimal']."
</td><td>
<select name='_poluchil' id='_poluchil' style='width:200px' >";
    $count=0;
    while ($count < mysql_num_rows($poluchil))
    {
	echo "\n<option value=" . mysql_result($poluchil,$count,"klienti_id") . ">" . mysql_result($poluchil,$count,"klienti_name_1") . "</option>";
    $count++;
    }
echo "</select>
</td></tr>
<tr><td colspan=2 align='center'>
 <a href=\"javascript:print_nakl_excel();\" >
    <img src=\"../resources/img/excel.jpg\" width=\"150px\"></a> 
  </td></tr></table>

</div>
<div id='info' class='info'></div>";
echo "\n</body>";
//print_r("test");
//echo '<pre>'; print_r(var_dump($_SERVER));
//print_r(phpinfo());
 $_SESSION['export_products'] = implode(',',$ProductsID);
?>

<script>
    $(document).on('click', '#export', function(){  
      location.href = 'export/get_excel.php?producs=yes';
    });

</script>


<?php
/*
   $sql = 'SELECT distinct * FROM tbl_warehouse_unit GROUP BY warehouse_unit_tovar_id;';
    $res = $folder->query($sql) or die ($sql.'<br><br>'.mysql_error());
    $folder->query('DELETE FROM tbl_warehouse_unit;');
    while($tmp = $res->fetch_assoc()){
        echo '<br>'.$tmp['warehouse_unit_tovar_id'];
        $id = $tmp['warehouse_unit_tovar_id'];
        $sql = 'INSERT INTO tbl_warehouse_unit SET warehouse_unit_tovar_id = \''.$id.'\'';
        $folder->query($sql) or die ($sql.'<br><br>'.mysql_error());
    }
  */