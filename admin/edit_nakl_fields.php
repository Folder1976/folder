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
//$iStatusSelect = $_POST["iStatus"];
$iKlient_id = $_GET["operation_id"];

$iPrice = "";
if(isset($_REQUEST["price"])) $iPrice=$_REQUEST["price"];

$this_table_name = "tbl_operation_detail";
$this_table_id_name = "operation_detail_operation";
$return_page = "edit_nakl_fields.php?operation_id=" . $iKlient_id;
$warehouse_count=0;
//echo $iKlient_id , " " , $return_page;
$color_null = "transparent";
$color_from = "#87ff8f";
$color_to = "#ffa0a0";
$color_tovar1 = "#ADD8E6";
$color_tovar2 = "#ADD8D0";
$color_tovar_now = $color_tovar1;
$warehouse_row_limit = 15;


$tQuery = "SELECT `price_id`,`price_name` FROM `tbl_price`";
$price = mysql_query("SET NAMES utf8");
$price = mysql_query($tQuery);//,`tbl_klienti`, `tbl_operation_status` WHERE `operation_klient`=`klienti_id` and `operation_status`=`operation_status_id` " . $tQuery . " ORDER BY `operation_data` DESC, `operation_id` DESC ");
if (!$price)
{
  echo "Query error PriceName";
  exit();
  
}

$tQuery = "SELECT * FROM `tbl_warehouse` ORDER BY `warehouse_sort` ASC";
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
 
  $Fields .= "`warehouse_unit_" . mysql_result($warehouse,$warehouse_count,"warehouse_id")  . "`,";
   $warehouse_count++;
}

$Fields .= " `operation_detail_id`,`operation_detail_operation`,`operation_detail_tovar`,`operation_detail_discount`,`operation_detail_from`,`operation_detail_to`,`operation_detail_item`,`operation_detail_memo`,"; //Operation
$Fields .= "`operation_detail_price`,`operation_detail_summ`,`operation_detail_zakup`,"; //Operation
$Fields .= "`tovar_id`,`tovar_artkl`,`tovar_name_1`"; //Tovar
$ver = mysql_query("SET NAMES utf8");

$sort = "";
if(isset($_REQUEST['sort'])){
  $sort = "ORDER BY `".$_REQUEST['sort']."` ASC";
}else{
  $sort = "ORDER BY `tovar_name_1` ASC, `tovar_artkl` ASC";
}

$tQuery = "SELECT " . $Fields . " FROM `tbl_operation_detail`,`tbl_tovar`,`tbl_warehouse_unit` 
	  WHERE 
	  `warehouse_unit_tovar_id`=`tovar_id` and 
	  `operation_detail_dell`='0' and 
	  `operation_detail_tovar`=`tovar_id` and 
	  `operation_detail_operation`='" . $iKlient_id . "' 
	  GROUP BY `operation_detail_id` 
	  $sort";
$ver = mysql_query($tQuery) or die ($tQuery.' '.mysql_error());

$count = 0;
$fields_nom="";
while($count < mysql_num_rows($ver)){
    $fields_nom .= mysql_result($ver,$count,"operation_detail_id")."*";
$count++;
}
$count = 0;

header ('Content-Type: text/html; charset=utf8');
echo "<header><link rel='stylesheet' type='text/css' href='sturm.css'></header>";
//==================JAVA===========================================
echo "\n<script src='JsHttpRequest.js'></script>";
echo "\n<script type='text/javascript'>";
//================================SET COLOR=====================================
echo "var fields_nom = '$fields_nom';
  
  function setcolorfrom(tovar,count_war,from_to){
	if (from_to==1){
	    var a_sel = 'from_'+tovar;
	    var set_col = '" , $color_from , "';
	}else{
	    var a_sel = 'to_'+tovar;
	    var set_col = '" , $color_to , "';
	}

	var count=1;
	while(count<=count_war){
	   // alert(tovar);
	    var td_null = document.getElementById(tovar+'_'+count);
	   // alert(tovar+'_'+count+' '+td_null+' '+from_to);
	    if(td_null.bgColor == set_col){
	    
		td_null.bgColor = '",$color_null,"';
	    }
	count++;
	}
	
	
	
	var sel = document.getElementById(a_sel);";
  echo "\nvar res = sel.options[sel.selectedIndex].value;";
  echo "\nvar td_set = document.getElementById(tovar+'_'+res);";
  echo "\ntd_set.bgColor=set_col;";
echo "\n}";

//================================SET SUM ALL==============suma po vsej nakladnoj
      echo "function start(){
		  set_sum_all();
	  
	  var res = fields_nom.split('*');
	  count=0;
	  while(res.length>count+1){
  	      setcolorfrom(res[count]," , mysql_num_rows($warehouse) ,",1);
 	      setcolorfrom(res[count]," , mysql_num_rows($warehouse) ,",2);
	  count++;} 
		  
  
  }";
		  
      echo "function set_sum_all(){
	  var res = fields_nom.split('*');
	  var summ = 0;
	  count=0;
	  while(res.length>count+1){
  	      summ = summ + Number(document.getElementById('4_'+res[count]).value);
	  count++;}     
	  document.getElementById('_operation_summ').innerHTML=summ.toFixed(2);
      }
      ";
//================================OTMENA ENTER=================suma po odnoj pozicii


//================================SET SUM=================suma po odnoj pozicii
echo "\nfunction setsumm(a){";
     echo "\n var price = document.getElementById(1+'_'+a).value;";
     echo "\n var item = document.getElementById(2+'_'+a).value;";
     echo "\n var disc = document.getElementById(3+'_'+a).value;";
     echo "\n summ = price/100*(100-disc)*item;";
     echo "\ndocument.getElementById(4+'_'+a).value = summ.toFixed(2);";
     echo "set_sum_all();
     
	      }";
//================================SET PRICE===============kogda vibor konkretnoj ceni
echo "function nakl_change(id) {
	document.getElementById('info_'+id).innerHTML = '".$m_setup['menu wait']."';
	var key = 'edit';    
	var price = document.getElementById('1_'+id).value;
	var item = document.getElementById('2_'+id).value;
	var disc = document.getElementById('3_'+id).value;
	var sum = document.getElementById('4_'+id).value;
	var from = document.getElementById('from_'+id).selectedIndex;
	var to = document.getElementById('to_'+id).selectedIndex;
	
	    var req=new JsHttpRequest();
	    req.onreadystatechange=function(){

		  if(req.readyState==4){
		  var responce=req.responseText;
		  document.getElementById('info_'+id).innerHTML = responce;
		}}
	    req.open(null,'edit_table_nakl.php',true);
	    req.send({edit:key,tovar:id});
}";

echo "function setprice(value,a){
	    var req=new JsHttpRequest();
	    req.onreadystatechange=function(){
	if(req.readyState==4){
	  
	  var responce=req.responseText;
	  var res = responce.split('*');
	  if(document.getElementById('usd2uah').checked){
	      var curr_sum = res[0]*1; 
	  }else{
	      var curr_sum = res[0] * res[2]; //mnozim na kurs
	  }
	    document.getElementById('1_'+a).value=curr_sum.toFixed(2);
	    document.getElementById('price_info_'+a).value=res[0]+'['+res[1]+'] * '+res[2];
	    //document.getElementById('1_'+a).value=res[0]+'['+res[1]+'] * '+res[2];
	    //alert('ggg'+curr_sum+' '+res[0]);
	    setsumm(a);
	    }}
	    req.open(null,'get_price_op_det.php',true);
	    req.send({price:value,tovar:a});
	    }";
//================================SET DISCOUNT ALL=========== kogda vibor obczej ceni
echo "function set_discount_all(value){
	    var res = fields_nom.split('*');
	  count1=0;
	  while(res.length>(count1+1)){
	      document.getElementById('3_'+res[count1]).value=value;
	      setsumm(res[count1]);
	  count1++;}
    
}";
//================================SET PRICE ALL=========== kogda vibor obczej ceni
    echo "function setprice_all(value){
  	    var res = fields_nom.split('*');
	    count=0;
	    while(res.length>count+1){
		document.getElementById('price_'+res[count]).selectedIndex=value-1;
		setprice(value,res[count]);
	      count++;}
	  }";
//================================SET COLOR ALL=====================================
    echo "function setcolorfrom_all(this_value,value2,value){
	  if (value==1){
	    $('.from_all').val(this_value);
	  }else{
	    $('.to_all').val(this_value);
	  }
	  //setcolorfrom(res[count],value2,value);
    
}";
echo " 

";
echo "\n</script></header>";
//================== END JAVA ================================
echo "\n<body onload=\"start();\">";

echo "<form method=\"post\" name=\"nakl_fields_$iKlient_id\" action=\"edit_table_nakl.php\" onsubmit=\"return false;\">";
echo "<table width=\"100%\"><tr>
      <td width=\"150px\">
      <input type=\"hidden\" name=\"_save\" id=\"_save\" value=\"\"/>

      <input type=\"submit\" value=\"save\" onClick=\"document.getElementById('_save').value='save';this.form.submit();\"/>
      </td>";
//echo "\nSUMM:<input type='text' style='width:100px' id='_summ' value='0'/>";
 //==================PRICE=ALL==================================================================================================
	if ($iPrice < 1)$iPrice=2; 
  echo "<td width=\"230px\">Set to all - Price:";
   echo "\n<select style='width:100px' id='price_0' onChange='setprice_all(this.value)'>";# OnChange='submit();'>";
    $count1=0;
    while ($count1 < mysql_num_rows($price))
    {
    echo "\n<option ";
	  if ($iPrice == mysql_result($price,$count1,"price_id")) echo "selected ";
    echo "value=" . mysql_result($price,$count1,"price_id") . ">" . mysql_result($price,$count1,"price_name") . "</option>";
    $count1++;
    }
echo "</select></td>";
//===================DISCOUNT TO ALL==================================================================================================
echo "<td width=\"150px\">Discount:";
echo "<input type=\"text\" id=\"discount_0\" value=\"0\" style=\"width:40px;\" onChange=\"set_discount_all(this.value);\"/>
      <input type=\"button\"/ value=\">>\">
      </td>";
 
//=================FROM=ALL===================================================================================================
  echo "<td width=\"170px\"> From:<select class=\"nak_field_warehouse_from\" style='width:130px' id='from_0' onChange='setcolorfrom_all(this.value,".mysql_num_rows($warehouse).",1)'>";# OnChange='submit();'>";
    $count1=0;
    while ($count1 < mysql_num_rows($warehouse))
    {
    echo "\n<option ";
	#echo mysql_result($ver,0,"klienti_group") , " " , mysql_result($kli_grp,$count,"klienti_group_id");
	if (mysql_result($ver,0,"operation_detail_from") == mysql_result($warehouse,$count1,"warehouse_id")) echo "selected ";
    echo "value=" . mysql_result($warehouse,$count1,"warehouse_id") . ">" . mysql_result($warehouse,$count1,"warehouse_name") . "</option>";
    $count1++;
    }
echo "</select></td>";
//==================TO=ALL==================================================================================================
   echo "<td width=\"170px\"> To:<select class=\"nak_field_warehouse_to\"  style='width:130px' id='to_0' onChange='setcolorfrom_all(this.value,".mysql_num_rows($warehouse).",2)'>";# OnChange='submit();'>";
    $count1=0;
    while ($count1 < mysql_num_rows($warehouse))
    {
    echo "\n<option ";
	#echo mysql_result($ver,0,"klienti_group") , " " , mysql_result($kli_grp,$count,"klienti_group_id");
	if (mysql_result($ver,0,"operation_detail_to") == mysql_result($warehouse,$count1,"warehouse_id")) echo "selected ";
    echo "value=" . mysql_result($warehouse,$count1,"warehouse_id") . ">" . mysql_result($warehouse,$count1,"warehouse_name") . "</option>";
    $count1++;
    }
echo "</select></td>";
//==================SUMM=ALL==================================================================================================
echo "<td>
    <input type=\"checkbox\" name=\"usd2uah\" id=\"usd2uah\" ";
    if(isset($_REQUEST['usd2uah'])) echo " checked ";
echo "value=\"usd2uah\">",$m_setup['menu usd2uah'],"
</td>";


echo "<td align=\"right\">
      ".$m_setup['menu summ']." : </td>
      <td align=\"center\" class=\"large_font\">
      <div id=\"_operation_summ\"></div>
      </td></tr>
      </table>

";
//=====================================================================================================================
 
echo "\n<input type='hidden' name='_id_value' value='"  , $iKlient_id  , "'/>";
echo "\n<input type='hidden' name='_id_name' value='" , $this_table_id_name , "'/>";
echo "\n<input type='hidden' name='_table_name' value='" , $this_table_name , "'/>";
echo "\n<input type='hidden' name='_select' value='" , $return_page , "'/>";
  
echo "\n<input type='hidden' name='_page_to_return' value='" , $return_page ,"'/>";

echo "\n<table width=100% class='menu_top' cellspacing='0' cellpadding='0' style='border-left:1px solid;border-right:1px solid;border-top:1px solid'>"; //class='table'
echo "<tr class=\"nak_header_nakl\">
      <th width=30px height=\"50px\"><a href=\"edit_nakl_fields.php?operation_id=$iKlient_id&sort=operation_detail_id\">nakl >></a></th>
      <th width=100px><a href=\"edit_nakl_fields.php?operation_id=$iKlient_id&sort=tovar_artkl\">Artikl >></a></th>
      <th><a href=\"edit_nakl_fields.php?operation_id=$iKlient_id&sort=tovar_name_1\">Name >></a></th>
      <th width=10px>Zk</th>
      <th width=50px>Price</th>
      <th width=20px>Item</th>
      <th width=20px>Disc.</th>
      <th width=50px>Summ</th>
      <th width=32px>add</th>
      
      <th width=100px>From/To</th>";
      
      $tmp = 0;
      while($tmp < mysql_num_rows($warehouse)){
	  echo "<th class=\"ware_".mysql_result($warehouse,$tmp,"warehouse_id")."\" width='10px'>";
	      echo "<div class='rotatedBlok' width='15px'>",
	      mysql_result($warehouse,$tmp,"warehouse_shot_name"),
	      "</div>";
	  
	  echo "</th>";
      $tmp++;
      }
echo "<th width=10px></th>";
echo "<th width=50px></th>";
echo "</tr>";

$i=1;
while ($count < mysql_num_rows($ver))
{
      if ($i == 1){
	  $i = 2;
      }else{
	  $i = 1;
      }
  
  $id_tmp_tovar=mysql_result($ver,$count,"operation_detail_tovar");
  $id_tmp = mysql_result($ver,$count,"operation_detail_id");
 
  echo "\n<tr class=\"nak_field_$i\">";
  //echo "\n<td width=50px><a href='edit_tovar.php?tovar_id=", $id_tmp_tovar," ' target='_blank'>",mb_substr($m_setup['menu edit'],0,10),".&nbsp</a></td>";
    echo "<td width=50px><a class=\"small\" href='edit_tovar_history.php?tovar_id=",$id_tmp_tovar," ' target='_blank'>",
    $count+1,"<br>",$m_setup['menu history'],"&nbsp;</a></td>";
    //echo "<td>$count</td>";
    
  echo "\n<td><input type='hidden' name='operation_detail_tovar*",$id_tmp,"' value='" , $id_tmp_tovar , "'/>";
  echo "\n<input type='hidden' name='operation_detail_operation*",$id_tmp,"' value='" , mysql_result($ver,$count,"operation_detail_operation") , "'/>";
  
  echo "\n<b><a class=\"small_name\" href='edit_tovar.php?tovar_id=", $id_tmp_tovar," ' target='_blank'>&nbsp;", mysql_result($ver,$count,'tovar_artkl'), "</a>&nbsp;</b></td>";
  echo "\n<td><b><a class=\"small_name\" href='edit_tovar.php?tovar_id=", $id_tmp_tovar," ' target='_blank'>", mysql_result($ver,$count,'tovar_name_1'), "</a></b>
  
  </td>";
  

  
 $key = "type='hidden'";
 if(strpos($_SESSION[BASE.'usersetup'],'view_zakup')>0)$key = "";
  echo "<td><input $key class=\"nak_field_zakup\" disabled style='text-align:right' type='text' name='operation_detail_zakup*",$id_tmp,"' value='" , number_format(mysql_result($ver,$count,'operation_detail_zakup'),2,".",""), "' onChange='setsumm(",$id_tmp,")'/></td>";
  echo "<td><input class=\"nak_field_price\" style='text-align:right' type='text' id='1_",$id_tmp,"' name='operation_detail_price*",$id_tmp,"' value='" , number_format(mysql_result($ver,$count,'operation_detail_price'),2,".",""), "' onChange='setsumm(",$id_tmp,")'/></td>";
  echo "\n<td><input class=\"nak_field_item\" style='width:50px;text-align:center' type='text' id='2_",$id_tmp,"' name='operation_detail_item*",$id_tmp,"' value='" , mysql_result($ver,$count,'operation_detail_item'), "' onChange='setsumm(",$id_tmp,")'/></td>";
  echo "\n<td><input class=\"nak_field_discount\" style='width:30px;text-align:center' type='text' id='3_",$id_tmp,"' name='operation_detail_discount*",$id_tmp,"' value='" ,  mysql_result($ver,$count,'operation_detail_discount'), "' onChange='setsumm(",$id_tmp,")'/></td>";
  echo "\n<td><input class=\"nak_field_summ\" style='width:90px;text-align:right' type='text' id='4_",$id_tmp,"' name='operation_detail_summ*",$id_tmp,"' value='" ,  number_format(mysql_result($ver,$count,'operation_detail_summ'),2,".",""), "'/></td>";
  echo "\n<td align=\"center\"><input class=\"nak_field_add_all\"  type=\"submit\" value=\"++\" onClick=\"document.getElementById('_save').value='save';this.form.submit();\"/></td>";

//=================FROM====================================================================================================
  echo "
	<td style='border-bottom:1px solid'><select name='operation_detail_from*",$id_tmp,"' style='width:100px;' class=\"from_all nak_field_warehouse_from\"  id='from_",$id_tmp,"' onChange='setcolorfrom(",$id_tmp,"," , mysql_num_rows($warehouse) ,",1)' >";# OnChange='submit();'>";
    $count1=0;
    while ($count1 < mysql_num_rows($warehouse))
    {
    echo "\n<option ";
	#echo mysql_result($ver,0,"klienti_group") , " " , mysql_result($kli_grp,$count,"klienti_group_id");
	if (mysql_result($ver,$count,"operation_detail_from") == mysql_result($warehouse,$count1,"warehouse_id")) echo "selected ";
    echo "value=" . mysql_result($warehouse,$count1,"warehouse_id") . ">" . mysql_result($warehouse,$count1,"warehouse_name") . "</option>";
    $count1++;
    }
echo "</select><br>";
//==================TO===================================================================================================
   echo "<select name='operation_detail_to*",$id_tmp,"' style='width:100px;' class=\"to_all nak_field_warehouse_to\"  id='to_",$id_tmp,"' onChange='setcolorfrom(",$id_tmp,"," , mysql_num_rows($warehouse) ,",2)'>";# OnChange='submit();'>";
    $count1=0;
    while ($count1 < mysql_num_rows($warehouse))
    {
    echo "\n<option ";
	#echo mysql_result($ver,0,"klienti_group") , " " , mysql_result($kli_grp,$count,"klienti_group_id");
	if (mysql_result($ver,$count,"operation_detail_to") == mysql_result($warehouse,$count1,"warehouse_id")) echo "selected ";
    echo "value=" . mysql_result($warehouse,$count1,"warehouse_id") . ">" . mysql_result($warehouse,$count1,"warehouse_name") . "</option>";
    $count1++;
    }
echo "</select></td>";
//=====================================================================================================================

 // echo "\n</tr>";
 // echo "\n<tr class=\"nak_field_$i\">";\n<td style='border-bottom:1px solid'>-</td><
  //echo "td style=\"border-bottom:1px solid\" align=\"center\" valing=\"middle\"><b>",$count+1,"</b></td>";
  //echo "\n<td style=\"border-bottom:1px solid\" colspan=\"11\" valing=\"top\" align=\"right\">  ";
  //echo "\n<table width='60%' cellspacing='0' cellpadding='0' class=\"nak_field_$i\"><tr>
  //echo "<td><nobr><div id='info_",$id_tmp,"'></div></td>";// class=\"nak_field_$i\">";
  
    $warehouse_count=0;
  $warehouse_count_row = 0;
  while ($warehouse_count < mysql_num_rows($warehouse))
  {
	$warehouse_unit= mysql_result($warehouse,$warehouse_count,"warehouse_id");
      
	  echo "<td id=\"" , mysql_result($ver,$count,"operation_detail_id") , "_" , mysql_result($warehouse,$warehouse_count,"warehouse_id")  , "\"
	  class=\"ware_".mysql_result($warehouse,$warehouse_count,"warehouse_id")."\"
	  style=\"border-left:1px solid;border-top:1px solid;\"
	  align=\"center\">";
	  if (mysql_result($ver,$count,"warehouse_unit_" . $warehouse_unit)>0) echo "<font color='black'><b>";
	  elseif (mysql_result($ver,$count,"warehouse_unit_" . $warehouse_unit)<0) echo "<font color='red'><b>";

	  if(isset($_REQUEST['set_rezervi'])){
	      $tQuery = "SELECT SUM(operation_detail_item) as REZERV
			 FROM `tbl_operation_detail`
			 WHERE
			 `operation_detail_tovar`='$id_tmp' and
			 `operation_detail_dell`='0' and
			 `operation_detail_to`='7' and
			 `operation_detail_from`='$warehouse_unit'";
			 //echo $tQuery,"<br>";
	      $reserv = mysql_query("SET NAMES utf8");
	      $reserv = mysql_query($tQuery);
		      echo mysql_result($ver,$count,"warehouse_unit_" . $warehouse_unit) + mysql_result($reserv,0,0);
	  }else{
	      echo mysql_result($ver,$count,"warehouse_unit_" . $warehouse_unit);
	  }

	  
	  
	  echo "</td>";
      $warehouse_count++;
      $warehouse_count_row++;
    
  }
  /*
  $warehouse_count=0;
  $warehouse_count_row = 0;
  while ($warehouse_count < mysql_num_rows($warehouse))
  {
   $warehouse_unit= mysql_result($warehouse,$warehouse_count,"warehouse_id");
   
  echo "<td id=\"",$id_tmp,"_" , mysql_result($warehouse,$warehouse_count,"warehouse_id")  , "\" 
	  class=\"ware_".mysql_result($warehouse,$warehouse_count,"warehouse_id")."\"
	  style=\"border-left:1px solid;border-top:1px solid;\"";
      
  echo " width=\"", 100 / mysql_num_rows($warehouse) ,"%\">";  
       
   // echo "<font size=\"2\" color=\"#555555\">";
   // echo mysql_result($warehouse,$warehouse_count,"warehouse_shot_name") . ":&nbsp";
   
    if (mysql_result($ver,$count,"warehouse_unit_" . $warehouse_unit)>0) echo "<font color='black'><b>";
     elseif (mysql_result($ver,$count,"warehouse_unit_" . $warehouse_unit)<0) echo "<font color='red'><b>";
 
    echo mysql_result($ver,$count,"warehouse_unit_" . $warehouse_unit);
    //echo "</font>";
    echo "</td>";
    if ($warehouse_count_row>$warehouse_row_limit){
     // echo"\n</tr><tr>";
      $warehouse_count_row=0;}
      $warehouse_count++;
     $warehouse_count_row++;

 }
  */
 // echo "\n</tr></table>";
  echo "\n</td>";
     //==================PRICE===================================================================================================
	if ($iPrice < 1)$iPrice=2; 
  echo "\n<td style='border-bottom:1px solid'>";
  echo "\n<input type='text' id='price_info_",$id_tmp,"' value='non' style='font-size:xx-small;width:100px;height:16px;' class=\"nak_field_$i\"/>";
  echo "\n<select style='width:100px;height:16px;font-size:xx-small;' class=\"nak_field_$i\" id='price_",$id_tmp,"' onChange='setprice(this.value,",$id_tmp,")'>";# OnChange='submit();'>";
    $count1=0;
    while ($count1 < mysql_num_rows($price))
    {
    echo "\n<option ";
	  if ($iPrice == mysql_result($price,$count1,"price_id")) echo "selected ";
    echo "value=" . mysql_result($price,$count1,"price_id") . ">" . mysql_result($price,$count1,"price_name") . "</option>";
    $count1++;
    }
echo "</select></td>";
//=====================================================================================================================
 
  echo "\n<td style='border-bottom:1px solid'>";
  echo "\n<input type='text' style='width:100px;' class=\"nak_field_$i\" name='operation_detail_memo*",$id_tmp,"' value='" , mysql_result($ver,$count,"operation_detail_memo") , "'/>";
  echo "</td>";
  echo "\n</tr>";
$count++;
}

echo "\n</table>";
echo "\n<td><input type='hidden' name='end*-1' value='end'/>";
echo "\n</form>";
echo "\n</body>";
//print_r("test");

//print_r(phpinfo());
?>
