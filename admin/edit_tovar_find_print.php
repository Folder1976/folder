<?php

include 'init.lib.php';
include 'money2str.lib.php';

connect_to_mysql();
session_start();
if (!session_verify($_SERVER["PHP_SELF"],"none")){
  exit();
}


//=======================================================
if ($_SESSION[BASE.'lang'] <1){
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
$setup = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	  `setup_name`, 
	  `setup_param`
	  FROM `tbl_setup`
";

$setup = mysql_query($tQuery);
$count=0;
while ($count<mysql_num_rows($setup)){
 $m_setup[mysql_result($setup,$count,0)]=mysql_result($setup,$count,1);
 $count++;
}
//==================================SETUP=MENU==========================================

$count = 0;

$iKlient_id = -1;
if(isset($_REQUEST["operation_id"]))$iKlient_id=$_REQUEST["operation_id"];

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
$long_name = "operation_detail_";
$this_table_id_name = "operation_detail_operation";
$return_page = "edit_tovar_find.php?operation_id=" . $iKlient_id."&_shop=".$shop_selected."&_from=".$tmp_from."&_to=".$tmp_to."&_find1=".$find_str."&_supplier=".$find_supplier."&_parent=".$find_parent;
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
		      $s_empty .= " and (`warehouse_unit_".$post[$varname]."` ";
		  }else{
		      $s_value .=  " or `warehouse_id`='" . $post[$varname] . "'";
		      $s_list .= ",".$post[$varname];
		      $return_page .= "&ware*".$post[$varname]."=".$post[$varname];
		      $s_empty .= "+`warehouse_unit_".$post[$varname]."` ";
		  }
    
	    }
    }


$tQuery = "SELECT `tovar_parent_id`,`tovar_parent_name` 
	  FROM `tbl_parent` 
	  WHERE `tovar_parent_id`='$find_parent'";
	 // echo $tQuery;
$parent = mysql_query("SET NAMES utf8");
$parent = mysql_query($tQuery);//,`tbl_klienti`, `tbl_operation_status` WHERE `operation_klient`=`klienti_id` and `operation_status`=`operation_status_id` " . $tQuery . " ORDER BY `operation_data` DESC, `operation_id` DESC ");
if (!$parent)
{
  echo "Query error Parent";
  exit();
}
$tQuery = "SELECT `shop_id`,`shop_name_".$m_setup['print default lang']."` as shop_name 
	  FROM `tbl_shop` 
	  WHERE `shop_id`='$shop_selected'";
	 // echo $tQuery;
$shop = mysql_query("SET NAMES utf8");
$shop = mysql_query($tQuery);//,`tbl_klienti`, `tbl_operation_status` WHERE `operation_klient`=`klienti_id` and `operation_status`=`operation_status_id` " . $tQuery . " ORDER BY `operation_data` DESC, `operation_id` DESC ");
if (!$shop)
{
  echo "Query error Parent";
  exit();
}
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

while ($warehouse_count < mysql_num_rows($warehouse))
{
if(mysql_result($warehouse,$warehouse_count,"warehouse_summ") == true){
    if($tmp==1){
      if($s_list==""){
	  $s_empty .= " and (`warehouse_unit_".mysql_result($warehouse,$warehouse_count,"warehouse_id")."` ";
      }else{
	  $s_empty .= "+`warehouse_unit_".mysql_result($warehouse,$warehouse_count,"warehouse_id")."` ";
      }
    }    
 }
    if($tmp==1){
      if($s_list==""){
	  $s_list .= mysql_result($warehouse,$warehouse_count,"warehouse_id");
      }else{
	  $s_list .= ",".mysql_result($warehouse,$warehouse_count,"warehouse_id");
      }
    }    
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
$Fields .= "`tovar_id`,`tovar_artkl`,`tovar_name_1`,`tovar_memo`,`price_tovar_2`,`tovar_inet_id`,`dimension_name`"; //Tovar
$ver = mysql_query("SET NAMES utf8");

$sort = "";

if(isset($_REQUEST['sort'])){
  $sort = "ORDER BY `".$_REQUEST['sort']."` ASC";
}else{
  $sort = "ORDER BY `tovar_name_1` ASC, `tovar_artkl` ASC";
}
  if(isset($_REQUEST['ware_empty'])){
      $s_empty .= ") <> '0' ";
  }else{
      $s_empty="";
  }  
    
$tQuery = "SELECT " . $Fields . " 
	  FROM `tbl_tovar`,`tbl_warehouse_unit`".$table.",`tbl_parent`,`tbl_price_tovar`,`tbl_tovar_dimension` 
	  WHERE 
	    `warehouse_unit_tovar_id`=`tovar_id` and 
	    `price_tovar_id`=`tovar_id` and
	    `tovar_parent`=`tovar_parent_id` and
	    `tovar_dimension`=`dimension_id`
	    " . $s_empty . "
	    " . $sort_parent_tovar . "
	    " . $find_str_sql . "
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


echo "<header><title>".mysql_result($parent,$count,"tovar_parent_name")."</title>
    <link rel='stylesheet' type='text/css' href='sturm.css'>
        <script>
      function print_list(){
	 window.print();
      }
      </script>
    </header>
      <body onload=\"print_list()\"> ";


echo "<p style='display:table-header-group'>
      
	<table width='800px' cellspacing='0' cellpadding='0' class='menu_top'><tr>
	  <td align='center'><font size=3><b>
	  ".mysql_result($parent,$count,"tovar_parent_name")." 
	  </b></font>&nbsp&nbsp&nbsp&nbsp(".date("Y-m-d G:i:s").")</td></tr>
	  <tr><td><font size=2.5>
	    ".$m_setup['print vidal']." : 
	  </font></td></tr>
	  <tr><td><font size=2.5>
	    ".$m_setup['print otrimal']." :
	  </font></td></tr>  
	  </table>  
	
      </p>";
echo "\n<table width='800px' cellspacing='0' cellpadding='0' class='find_print'>"; //class='table'
echo "<tr class=\"find_print\">
      <th width=20px height=\"50px\"><a href=\"edit_tovar_find_print.php?operation_id=$iKlient_id&$for_link&sort=tovar_id\">N</a></th>
      <th width=100px><a href=\"edit_tovar_find_print.php?operation_id=$iKlient_id&$for_link&sort=tovar_artkl\">".$m_setup['menu artkl']."</a></th>
      <th width=600px><a href=\"edit_tovar_find_print.php?operation_id=$iKlient_id&$for_link&sort=tovar_name_1\">".$m_setup['menu name1']."</a></th>
      ";
 if($_REQUEST["_print"] == "printmag"){
      echo "<th width=40px>".$m_setup['print vimir']."</th>";
	    //<th width=40px>".$m_setup['print items']."</th>";
      }
      $tmp = 0;
      while($tmp < mysql_num_rows($warehouse)){
	  echo "<th class=\"ware_".mysql_result($warehouse,$tmp,"warehouse_id")."\" width='15px'>";
	      echo "<div class='rotatedBlok' width='15px'>",
	      mysql_result($warehouse,$tmp,"warehouse_shot_name"),
	      "</div>";
	  
	  echo "</th>";
      $tmp++;
      }

if($_REQUEST["_print"] == "printmag"){
      echo  "<th width=40px>".$m_setup['print price']."</th>";
	   // <th width=140px>".$m_setup['menu memo']."</th>";
  }
echo "</tr>";
//echo mysql_num_rows($ver);

$count=0;
$i = 1;
$summ=0;
$items_all=0;
$summ_all=0;
while ($count < mysql_num_rows($ver))
{
$id_tmp=mysql_result($ver,$count,"tovar_id");
      if ($i == 1){
	  $i = 2;
      }else{
	  $i = 1;
      }
  echo "<tr class=\"nak_field_$i\">";
  echo "<td width=20px><a class=\"find_print\" href='edit_tovar_history.php?tovar_id=",mysql_result($ver,$count,'tovar_id')," ' target='_blank'>
  ".($count+1)." </a>";
 // if(mysql_result($ver,$count,"tovar_inet_id")>0) echo "<br>web";
  echo "<input type='hidden' name='",$long_name,"tovar*",$id_tmp,"' value='" , $id_tmp , "'/>";
  echo "<input type='hidden' name='",$long_name,"operation*",$id_tmp,"' value='" , $iKlient_id, "'/>
  </td>";
 
  echo "<td><b><a class=\"find_print\" href='edit_tovar.php?tovar_id=", $id_tmp," ' target='_blank'>&nbsp;", mysql_result($ver,$count,'tovar_artkl'), "</a>&nbsp;</b></td>";
  echo "<td><a class=\"find_print\" href='edit_tovar.php?tovar_id=", $id_tmp," ' target='_blank'>", mysql_result($ver,$count,'tovar_name_1'), "</a></td>";
  if($_REQUEST["_print"] == "printmag"){
      echo "<td><a class=\"find_print\" align=\"center\" href='edit_tovar.php?tovar_id=", $id_tmp," ' target='_blank'>", mysql_result($ver,$count,'dimension_name'), "</a></td>";
  }

  
  $warehouse_count=0;
  $warehouse_count_row = 0;
  $items=0;
  while ($warehouse_count < mysql_num_rows($warehouse))
  {
	$warehouse_unit= mysql_result($warehouse,$warehouse_count,"warehouse_id");
    //if(isset($_REQUEST['ware*'.$warehouse_unit])){
	  echo "<td width=20px id=\"" , mysql_result($ver,$count,"tovar_id") , "_" , mysql_result($warehouse,$warehouse_count,"warehouse_id")  , "\"
	  class=\"ware_".mysql_result($warehouse,$warehouse_count,"warehouse_id")."\"
	  style=\"border-left:1px solid;border-top:1px solid;\"
	  align=\"center\">";
	  $items += mysql_result($ver,$count,"warehouse_unit_" . $warehouse_unit);
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
  $items_all += $items;
  $summ =mysql_result($ver,$count,'price_tovar_2') * $items;
  $summ_all += $summ;  
  
if($_REQUEST["_print"] == "printmag"){
      echo "<td align=\"right\"><a class=\"find_print\" href='edit_tovar.php?tovar_id=", $id_tmp," ' target='_blank'>", number_format(mysql_result($ver,$count,'price_tovar_2'),2,".",""), "</a></td>";
     // echo "<td align=\"right\">", number_format($summ,2,".",""), "</a></td>";
      //echo "<td>&nbsp</td>";
   }

    echo "\n</tr>";
$count++;
}
if($_REQUEST["_print"] == "printmag"){
echo "<tr><td colspan=4><b>".$m_setup['print summ']."</b></td>
      <td align=\"center\"><b>$items_all</b></td>
      <td>&nbsp</td>
      <td align=\"right\"><b>".number_format($summ_all,2,".","")."</b></td>
      <td>&nbsp</td></tr>

      <tr><td colspan=4><b>".$m_setup['print pdv']."</b></td>
      <td>&nbsp</td>
      <td>&nbsp</td>
      <td><b>0.00</b></td>
      <td>&nbsp</td></tr>

      <tr><td colspan=4><b>".$m_setup['print summ']." + ".$m_setup['print pdv']."</b></td>
      <td>&nbsp</td>
      <td>&nbsp</td>
      <td><b>".number_format($summ_all,2,".","")."</b></td>
      <td>&nbsp</td></tr>

      <tr><td colspan=10><b>
      ".$m_setup['print string']." : " .money2str_ru($summ_all)."
      </b></td></tr>
      ";
}

echo "\n</table>";
echo "\n<td><input type='hidden' name='end*-1' value='end'/>";

echo "<p style='display:table-header-group'>
      
	<table width='800px' cellspacing='0' cellpadding='0' class='menu_top'><tr><td>&nbsp</td><td></td></tr>
	
	  <tr><td><font size=2.5>
	    ".$m_setup['print vidal']." : 
	  </font></td><td><font size=2.5>
	    ".$m_setup['print otrimal']." :
	  </font></td></tr>  
	  </table>  
	
      </p>";



echo "\n</body>";
//print_r("test");

//print_r(phpinfo());
?>
