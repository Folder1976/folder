<?php

include 'init.lib.php';
connect_to_mysql();
session_start();
if (!session_verify($_SERVER["PHP_SELF"],"+")){
  exit();
}
  //Alias
  include "../class/class_alias.php";
  $Alias = new Alias($folder);
//echo $_REQUEST['_dell'];

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
//$iStatusSelect = $_POST["iStatus"];
//$iKlient_id = $_GET["operation_id"];
$find_str = "none"; 
if(isset($_REQUEST["_find"])){$find_str=$_REQUEST["_find"];}
$find_nakl = "none"; 
if(isset($_REQUEST["_nakl"])){$find_nakl=$_REQUEST["_nakl"];}

$find_supplier = ""; 
if(isset($_REQUEST["_supplier"])){$find_supplier=$_REQUEST["_supplier"];}

$find_parent = ""; 
if(isset($_REQUEST["_parent"])){$find_parent=$_REQUEST["_parent"];}

$find_currency = "0"; 
if(isset($_REQUEST["_currency"])){$find_currency=$_REQUEST["_currency"];}

//$find_supplier = $_GET["_supplier"];
//$find_parent = $_GET["_parent"];

$find_str_sql="";
//$this_table_name = "tbl_operation_detail";
//$this_table_id_name = "operation_detail_operation";
$return_page = "edit_tovar_table.php";//?operation_id=" . $iKlient_id."&_from=".$tmp_from."&_to=".$tmp_to."&_find=".$find_str."&_supplier=".$find_supplier."&_parent=".$find_parent;
$warehouse_count=0;
//echo $iKlient_id , " " , $return_page;
$color_null = "transparent";
$color_from = "peachpuff";
$color_to = "darkseagreen1";
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
$tQuery = "SELECT * FROM `tbl_currency` ORDER BY `currency_id` ASC";
$curremcy = mysql_query("SET NAMES utf8");
$currency = mysql_query($tQuery);//,`tbl_klienti`, `tbl_operation_status` WHERE `operation_klient`=`klienti_id` and `operation_status`=`operation_status_id` " . $tQuery . " ORDER BY `operation_data` DESC, `operation_id` DESC ");
if (!$currency)
{
  echo "Query error Currency";
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

$tQuery = "SELECT `tovar_parent_id`,`tovar_parent_name` FROM `tbl_parent` ORDER BY `tovar_parent_name` ASC";// WHERE `enti_group` = '5'";
$parent = mysql_query("SET NAMES utf8");
$parent = mysql_query($tQuery);//,`tbl_klienti`, `tbl_operation_status` WHERE `operation_klient`=`klienti_id` and `operation_status`=`operation_status_id` " . $tQuery . " ORDER BY `operation_data` DESC, `operation_id` DESC ");
if (!$parent)
{
  echo "Query error Parent";
  exit();
}
$tQuery = "SELECT * FROM `tbl_tovar_dimension` ORDER BY `dimension_name` ASC";// WHERE `enti_group` = '5'";
$dim = mysql_query("SET NAMES utf8");
$dim = mysql_query($tQuery);//,`tbl_klienti`, `tbl_operation_status` WHERE `operation_klient`=`klienti_id` and `operation_status`=`operation_status_id` " . $tQuery . " ORDER BY `operation_data` DESC, `operation_id` DESC ");
if (!$dim)
{
  echo "Query error Parent";
  exit();
}

$Fields = "";
if ($find_str=="")
{
//echo "[No find string]";
//exit();
}else{
//echo "[Finding String]";
$find_str_sql .= " and (upper(tovar_name_1) like '%" . mb_strtoupper($find_str,'UTF-8') . "%' or upper(tovar_artkl) like '%" . mb_strtoupper($find_str,'UTF-8') . "%')";
}

if ($find_supplier==""){$find_supplier=0;}
if ($find_supplier==0)
{
//echo "[No find Supplier]";
//exit();
}else{
//echo "[Finding Supplier]";
$find_str_sql .= " and (tovar_supplier='" . $find_supplier . "')";
}
if ($find_currency==0)
{
//echo "[No find Supplier]";
//exit();
}else{
//echo "[Finding Supplier]";
$find_str_sql .= " and (`price_tovar_curr_1`='" . $find_currency . "') ";
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
$table_find="";
//echo $find_nakl;
if($find_nakl != "none" and $find_nakl != ""){
$table_find = ", `tbl_operation_detail` ";
$find_str_sql .= " and `operation_detail_tovar`=`tovar_id` 
		   and `operation_detail_operation`='$find_nakl'
		  
";
}
$Fields .= "`tovar_id`,
	    `tovar_artkl`,
	    `tovar_name_1`,
	    `tovar_name_2`,
	    `tovar_name_3`,
	    `tovar_memo`,
	    `tovar_dimension`,
	    `tovar_barcode`,
	    `tovar_inet_id`,
	    `tovar_parent`,
	    `tbl_warehouse_unit`.*,
	    `tbl_price_tovar`.*"; //Tovar
$ver = mysql_query("SET NAMES utf8");
$tQuery = "SELECT " . $Fields . " FROM `tbl_warehouse_unit`, `tbl_tovar`,`tbl_price_tovar` $table_find 
	      WHERE `price_tovar_id`=`tovar_id` and
		    `warehouse_unit_tovar_id` = `tovar_id`
		    " . $find_str_sql . "";
//echo "<br>",$tQuery;
$ver = mysql_query($tQuery);
if (!$ver)
{
  echo "\nQuery error List";
  exit();
}
//echo mysql_num_rows($ver));

//header ('Content-Type: text/html; charset=utf8');
echo "<header><title>Edit table tovar</title><link rel='stylesheet' type='text/css' href='sturm.css'></header>";

//echo "<header><link rel='stylesheet' type='text/css' href='sturm.css'></header>";
//==================JAVA===========================================
echo "<script src='JsHttpRequest.js'></script>";
echo "<script type='text/javascript'>";
//================================SET COLOR=====================================
//================================SET PRICE===============kogda vibor konkretnoj ceni
$count=0;
$fields_id = "";
while($count<mysql_num_rows($ver)){ 
    $fields_id .= mysql_result($ver,$count,"tovar_id")."*";
$count++;
}
$count=0;
echo "
var fields_id = '$fields_id';
    function update(value,name){
     var tovar = name.split('*');
    var table = name.split('_');
    var id='';
    if (table[0]=='price'){
      table='tbl_price_tovar';
      id='price_tovar_id';
    }else{
      table='tbl_tovar';
      id='tovar_id';
    }
    
 //   document.getElementById('test').innerHTML = 'value='+value+'<br>tovar[0]='+tovar[0]+'<br>tovar[1]='+tovar[1];
  
  if(tovar[0]=='menu'){
	if(value==2){ //EDIT
	  var params='tovar_id='+tovar[1];
	  var win = window.open('edit_tovar.php?'+params,'_blank');
	  win.focus();
	  }
	if(value==1){ //COPY
	 edit_table_call('edit_table.php',tovar[1],'copy','tbl_tovar','tovar_id');
	 //window.location.reload(true);
	}
	if(value==3){ //DELL
	 edit_table_call('edit_table.php',tovar[1],'dell','tbl_tovar','tovar_id');
	 //window.location.reload(true);
 	}
    }else{
      var req=new JsHttpRequest();
      req.onreadystatechange=function(){
        if(req.readyState==4){
	var responce=req.responseText;
	document.getElementById('test').innerHTML=responce;
	
      }}
      //alert('table='+table+'&name='+tovar[0]+'&value='+value+'&w_id='+id+'&w_value='+tovar[1]);
      req.open(null,'save_table_field.php',true);
      req.send({table:table,name:tovar[0],value:value,w_id:id,w_value:tovar[1]});
      
    }
    }";
echo "function edit_table_call(webe,row,key,tbl,id){
      if(key=='dell'){
 	  document.getElementById('tovar_name_1*'+row).value = 'delleted';
	  var sender = {_dell:key,_table_name:tbl,_id_name:id,_id_value:row};
	}else{
	  var sender = {_copy:key,_table_name:tbl,_id_name:id,_id_value:row};
	}
      var req=new JsHttpRequest();
      req.onreadystatechange=function(){
      window.location.reload(true);
      if(req.readyState==4){
	var responce=req.responseText;
	document.getElementById('test').innerHTML=responce+'0000';
	window.opener.location.reload();
      }}
      document.getElementById('test').innerHTML='***';
      req.open(null,'edit_table.php',true);
      req.send(sender);
    }";
    
echo "function set_coef_all_base(){
	    set_price_auto(\"all\",\"coef\");
}
";
    
echo "function set_coef_all(){
	 var tovar = fields_id.split('*');
	 var count = 0;
	 //while(tovar.length > (count+1)){
	    set_price_auto(fields_id,\"coef\");
	 count++;
	 //} 
	location.reload();
}
";
echo "function set_price_all(){
	 var tovar = fields_id.split('*');
	 var count = 0;
	// while(tovar.length > (count+1)){
	    set_price_auto(fields_id,\"price\");
	 count++;
	 //} 
	location.reload();
}
";

echo "function set_kurs(value){
	 var tovar = fields_id.split('*');
	 var count = 0;
	 var tmp = 0;
	 while(tovar.length > (count+1)){
	    tmp = document.getElementById('price_tovar_1*'+tovar[count]).value;
	    tmp = tmp / value;
	    document.getElementById('price_tovar_1*'+tovar[count]).value = tmp;
	  update(tmp,'price_tovar_1*'+tovar[count]);
	 count++;
	 } 
}
";
echo "function set_kurs_select(value){
	 var tovar = fields_id.split('*');
	 var count = 0;
	 //alert(value);
	 while(tovar.length > (count+1)){
	    document.getElementById('price_tovar_curr_1*'+tovar[count]).selectedIndex = value;
	    update(value,'price_tovar_curr_1*'+tovar[count]);
	 count++;
	 }
}
";
echo "function set_field(value,field){
	 var tovar = fields_id.split('*');
	 var count = 0;
	// alert(field+'*'+tovar[count]);
	 while(tovar.length > (count+1)){
	    document.getElementById(field+'*'+tovar[count]).value = value;
	    update(value,field+'*'+tovar[count]);
	 count++;
	 }
}
";
echo "function set_price_auto(tovar,key){
  
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
echo "function PressKey(key){
	  var value = document.getElementById(key).value
	  //alert(value.replace(',','.'));
	  value = value.replace(',','.');
	  document.getElementById(key).value = value;
}
";
echo "function restore_zakup(key,kurs){
	  var value = document.getElementById('price_tovar_2*'+key).value;
	  var cof = document.getElementById('price_tovar_cof_2*'+key).value;
	  var num = value/kurs/cof;
	  //alert(value+' '+key+' '+kurs+' '+cof);
	  document.getElementById('price_tovar_1*'+key).value = num.toFixed(2);
	  update(num.toFixed(2),'price_tovar_1*'+key);
}
";
    echo "\n</script></header>";
//================== END JAVA ================================
echo "\n<body>\n";//<p  style='font-family:vendana;font-size=22px'>";
//============FIND====================================================================================
echo "<table class=\"menu_top\"><tr><td>
      <form method='POST' action='edit_tovar_table.php'>";
echo "\n<input type='hidden' name='operation_id' value='"  , $iKlient_id  , "'/>";
echo "\nПостачальник:<select name='_supplier' style='width:150px' onChange='submit();'>";
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
echo "\nНакладна:<select name='_parent' style='width:350px' onChange='submit();'>";
    $count=0;
    while ($count < mysql_num_rows($parent))
    {
    echo "\n<option ";
	if ($find_parent == mysql_result($parent,$count,"tovar_parent_id")) echo "selected ";
    echo "value=" . mysql_result($parent,$count,"tovar_parent_id") . ">" . mysql_result($parent,$count,"tovar_parent_name") . "</option>";
    $count++;
    }
echo "</select><br>";
//echo $find_currency;
echo "\nВалюта:<select name='_currency' style='width:80px' onChange='submit();'>";
    $count=0;
    while ($count < mysql_num_rows($currency))
    {
    echo "\n<option ";
	if ($find_currency == mysql_result($currency,$count,"currency_id")) echo "selected ";
    echo "value=" . mysql_result($currency,$count,"currency_id") . ">" . mysql_result($currency,$count,"currency_name") . "</option>";
    $count++;
    }
echo "</select>";


echo "String:<input type='text' style='width:250px' name='_find' value='" , $find_str , "' onChange='submit();'>";
echo "Nakl:<input type='text' style='width:50px' name='_nakl' value='" , $find_nakl , "' onChange='submit();'>";

echo "\n</form>";
echo "</td><td>";

if(strpos($_SESSION[BASE.'usersetup'],'gen_coef')>0){
echo "<button name=\"gen_coef\" onClick=\"set_coef_all();\">",$m_setup['menu generate coef'],"</button>";
}
if(strpos($_SESSION[BASE.'usersetup'],'gen_coef')>0){
echo "<button name=\"gen_coef\" onClick=\"set_coef_all_base();\">",$m_setup['menu generate coef base'],"</button>";
}
if(strpos($_SESSION[BASE.'usersetup'],'gen_price')>0){
echo "<button name=\"gen_price\" onClick=\"set_price_all();\">",$m_setup['menu generate price'],"</button>";
}

echo "</td></table>";
//=====================================================================================================



echo "\n<form method='post' action='edit_table_table.php'>";
//echo "\n<input type='submit' name='_add' value='add'/>";
//$return_page
echo "\n<input type='hidden' name='_id_value' value='"  , $iKlient_id  , "'/>";
echo "\n<input type='hidden' name='_id_name' value='" , $this_table_id_name , "'/>";
echo "\n<input type='hidden' name='_table_name' value='" , $this_table_name , "'/>";
echo "\n<input type='hidden' name='_select' value='" , $return_page , "'/>";
  
echo "\n<input type='hidden' name='_page_to_return' value='" , $return_page , "'/>";

echo "<div id='row*table'><table width=100% cellspacing='0' cellpadding='0' style='border-left:1px solid;border-right:1px solid;border-top:1px solid'></div>"; //class='table'
$html = "<tr>
      <th width=40px>id</th>
      <th></th>
      <th>Inet</th>
      <th>Barcod</th>
      <th width=100px>Artikl</th>
     <th width=100px>Alias</th>
      <th width=420px>RUS</th>
      <th width=70px>*sklad*</th>
     <th width=60px>Dim</th>
     <th width=60px>
	      <select style='width:60px'
	      onChange='set_kurs_select(this.value)'>";
	      $count1=0;
	      while ($count1 < mysql_num_rows($currency))
	      { $html .= "\n<option ";
		$html .= "value=" . mysql_result($currency,$count1,"currency_id") . ">".mysql_result($currency,$count1,"currency_name")."</option>";
		$count1++;
	      }
$html .= "</select><br>
     
	  <input type='text' style='width:45px'  id='zakup_kurs'
	  placeholder='курс закупа' onChange='set_kurs(this.value)' onkeyup='PressKey(this.id)'/>
      </th>";
     $count1=0;
       while ($count1<mysql_num_rows($price)){
      $html .= "<th width=60px>
		    <input type='text' style='width:45px'  id='pice-$count'
		    placeholder='цена' onChange='set_field(this.value,\"price_tovar_".($count1+1)."\")' onkeyup='PressKey(this.id)'/>
		    <br>".mysql_result($price,$count1,"price_name")."</th>";
		    
      $html .= "<th width=40px>
		    <input type='text' style='width:45px'  id='koef-$count'
		    placeholder='коэф' onChange='set_field(this.value,\"price_tovar_cof_".($count1+1)."\")' onkeyup='PressKey(this.id)'/>
		    <br>%</th>";
      $count1++;
      }
$html .= "
      <th>Memo</th>
      </tr>";
echo "<div id='row*0'>",$html,"</div>";      
      //****************************************************** ROWS
//$color_tovar1 = "#ADD8E6";
//$color_tovar2 = "#ADD8D0";
$count=0;
$i=1;
while($count<mysql_num_rows($ver)){ 
      if ($i == 1){
	  $i = 2;
      }else{
	  $i = 1;
      }
$html="";
$html .= "<tr class=\"nak_field_$i\">"; 

$html .= "<td align='right' valign=\"top\"><font size='1'>"
    .mysql_result($ver,$count,"tovar_id").
    "</font></td>";
    //----
  $html .= "<td valign=\"top\">
    <select style='width:60px' id='menu*".mysql_result($ver,$count,"tovar_id")."'
    onChange='update(this.value,this.id)'>
      <option selected value=0>menu</option>
      <option value=1>".$m_setup['menu copy']."</option>
      <option value=2>".$m_setup['menu edit']."</option>
      <option value=3>".$m_setup['menu dell']."</option>
    </select></td>"; 

  //----  
  $html .= "<td valign=\"top\">
    <input type='text' style='width:45px' id='tovar_inet_id*".mysql_result($ver,$count,"tovar_id")."' 
      value='" . mysql_result($ver,$count,"tovar_inet_id") . "' onChange='update(this.value,this.id)'/>
    </td>";
  //----
  $html .= "<td valign=\"top\">
    <input type='text' style='width:45px' id='tovar_barcode*".mysql_result($ver,$count,"tovar_id")."' 
      value='" . mysql_result($ver,$count,"tovar_barcode") . "' onChange='update(this.value,this.id)'/>
    </td>";
  //----
  $html .= "<td valign=\"top\">
    <input type='text' style='width:100px' id='tovar_artkl*".mysql_result($ver,$count,"tovar_id")."' 
      value='" . mysql_result($ver,$count,"tovar_artkl") . "' onChange='update(this.value,this.id)'/>
    </td>";
  //----
  

  $html .= "<td valign=\"top\">
    <input type='text' style='width:100px' id='tovar_alias*".mysql_result($ver,$count,"tovar_id")."' 
      value='" . $Alias->getProductAlias(mysql_result($ver,$count,"tovar_id")) . "' onChange='update(this.value,this.id)'/>
    </td>";
  //----
  $html .= "<td>
    <input type='text' style='width:400px' id='tovar_name_1*".mysql_result($ver,$count,"tovar_id")."' 
      value='" . mysql_result($ver,$count,"tovar_name_1") . "' onChange='update(this.value,this.id)'/>
    ";
 /*
  //----
   $html .= "<br>&nbsp&nbsp&nbspUKR:
    <input type='text' style='width:350px' id='tovar_name_2*".mysql_result($ver,$count,"tovar_id")."' 
      value='" . mysql_result($ver,$count,"tovar_name_2") . "' onChange='update(this.value,this.id)'/>
    ";
  //----
   $html .= "<br>&nbsp&nbsp&nbspENG:
    <input type='text' style='width:350px' id='tovar_name_3*".mysql_result($ver,$count,"tovar_id")."' 
      value='" . mysql_result($ver,$count,"tovar_name_3") . "' onChange='update(this.value,this.id)'/>
    </td>";
   */ 
    
      //----
  $tmp = 1;
  $prodano = 0;
  $na_sklade=0;
  $zakazano =0;
  while($tmp < 14){
	
	if($tmp<>1 and $tmp <> 9){
	    $na_sklade += mysql_result($ver,$count,"warehouse_unit_$tmp");
	}else{
	  if($tmp==1)$prodano += mysql_result($ver,$count,"warehouse_unit_$tmp");
	  if($tmp==9)$zakazano += mysql_result($ver,$count,"warehouse_unit_$tmp");
	}
  $tmp++;
  }
  $html .= "<td valign=\"top\" width=70px>
    sk : <b>$na_sklade</b><br>
    zkz: $zakazano<br>
    prd: $prodano
    </td>";
//=============DIMENSION=============================================================================================
  $html .= "<td valign=\"top\">
    <select style='width:60px' id='tovar_dimension*".mysql_result($ver,$count,"tovar_id")."'
    onChange='update(this.value,this.id)'>";
      $count1=0;
      while ($count1 < mysql_num_rows($dim))
	{ $html .= "\n<option ";
	if (mysql_result($ver,$count,"tovar_dimension") == mysql_result($dim,$count1,"dimension_id")) $html .= "selected ";
      $html .= "value=" . mysql_result($dim,$count1,"dimension_id") . ">".mysql_result($dim,$count1,"dimension_name")."</option>";
      $count1++;
      }
    $html .= "</select></td>"; 
//=============ZAKUP VALUTA=============================================================================================
$curr_ex = 0;
$curr_name = "";
$html .= "<td valign=\"top\">
    <select style='width:60px' id='price_tovar_curr_1*".mysql_result($ver,$count,"tovar_id")."'
    onChange='update(this.value,this.id)'>";
      $count1=0;
      while ($count1 < mysql_num_rows($currency))
	{ $html .= "\n<option ";
	if (mysql_result($ver,$count,"price_tovar_curr_1") == mysql_result($currency,$count1,"currency_id")){
		$html .= "selected ";
		$curr_ex = mysql_result($currency,$count1,"currency_ex");
		$curr_name = mysql_result($currency,$count1,"currency_name_shot");
	      }
      $html .= "value=" . mysql_result($currency,$count1,"currency_id") . ">".mysql_result($currency,$count1,"currency_name")."</option>";
      $count1++;
      }
    $html .= "</select></td>"; 

//=============PRICE=============================================================================================
      $count1=0;
       while ($count1<mysql_num_rows($price)){
      $html .= "<td valign=\"top\">
	 <input type='text' style='width:60px;text-align:right' id='price_tovar_".mysql_result($price,$count1,"price_id")."*".mysql_result($ver,$count,"tovar_id")."' 
	value='".number_format(mysql_result($ver,$count,"price_tovar_".mysql_result($price,$count1,"price_id")),"2",".","")."' 
	onChange='update(this.value,this.id)' onkeyup='PressKey(this.id)'/>";
	if($count1==1){
	      $html .= "<br><input type='button' style='width:60px'  id='get_zakup_".mysql_result($price,$count1,"price_id")."*".mysql_result($ver,$count,"tovar_id")."'
			value = '<< $curr_name'
			onClick='restore_zakup(\"".mysql_result($ver,$count,"tovar_id")."\",\"".$curr_ex."\")'/>";
	}
      $html .= "</td>";
      $html .= "<td valign=\"top\">
         <input type='text' style='width:40px;background:#9e9e9e;text-align:right' id='price_tovar_cof_".mysql_result($price,$count1,"price_id")."*".mysql_result($ver,$count,"tovar_id")."' 
	value='".number_format(mysql_result($ver,$count,"price_tovar_cof_".mysql_result($price,$count1,"price_id")),"3",".","")."' 
	onChange='update(this.value,this.id)' onkeyup='PressKey(this.id)'/>
	</td>";
      $count1++;
      }
//=============PARENT=============================================================================================
/*  $html .= "<td>
    <select style='width:250px' id='tovar_parent*".mysql_result($ver,$count,"tovar_id")."'
    onChange='update(this.value,this.id)'>";
      $count1=0;
      while ($count1 < mysql_num_rows($parent))
	{ $html .= "\n<option ";
	if (mysql_result($ver,$count,"tovar_parent") == mysql_result($parent,$count1,"tovar_parent_id")) $html .= "selected ";
      $html .= "value=" . mysql_result($parent,$count1,"tovar_parent_id") . ">".mysql_result($parent,$count1,"tovar_parent_name")."</option>";
      $count1++;
      }
    $html .= "</select></td>"; 
    */
 //----
  $html .= "<td>
    <input type='text' style='width:100px' id='tovar_memo*".mysql_result($ver,$count,"tovar_id")."' 
      value='" . mysql_result($ver,$count,"tovar_memo") . "' onChange='update(this.value,this.id)'/>
    </td>";


$html .= "</tr>";
echo "<div id='row*".mysql_result($ver,$count,"tovar_id")."'>",$html,"</div>";
$count++;
}
echo "<div id='row*table_end'></table></div>
<div id='test'>-></div>
      \n</form>
      \n</body>";
?>
