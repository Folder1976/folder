<?php

include 'init.lib.php';
include 'money2str.lib.php';
connect_to_mysql();
error_reporting(0);
session_start();
if (!session_verify($_SERVER["PHP_SELF"],"none")){
  exit();
}
$operation_id = $_GET["operation_id"];
$template_name = $_GET["tmp"];
//include 'nak.lib.php';
    header ('Content-Type: text/html; charset=utf8');
    echo "<header>
    <script src='JsHttpRequest.js'></script>
    <link rel='stylesheet' type='text/css' href='sturm.css'>
    
    <script>
      function print_list(){
	 window.print();
      }
  function open_excel(){
  var user = ".$operation_id.";
  //alert('ggg');
  var req=new JsHttpRequest();
      req.onreadystatechange=function(){
      //alert(req.readyState);
      if(req.readyState==4){
	var responce=req.responseText;
	//alert('[[['+responce);
	document.location.href = responce;
      }}
      req.open(null,'get_excel_beznal_rah.php',true);
      req.send({_operation_id:user});  
    }
      </script>
    
    
    </header>
      <title>Print#</title>
      
      <body onload=\"print_list()\">
      
      ";



if ($template_name=="")$template_name="print";
$temp_header = "template/".$template_name."_header.html";
$temp_fields = "template/".$template_name."_fields.html";

//==================================MAIL===========================================
$setup = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	  `setup_name`, 
	  `setup_param`
	  FROM `tbl_setup`
	  ";
$setup = mysql_query($tQuery);
$m_setup = array();
$count=0;
while ($count<mysql_num_rows($setup)){
 $m_setup[mysql_result($setup,$count,0)]=mysql_result($setup,$count,1);
 $count++;
}
//=================================================================
//==================================SETUP===========================================
/*if ($_SESSION[BASE.'lang'] <1){
  $_SESSION[BASE.'lang']=1;
}*/
//echo $m_setup['print default lang'];
$setup = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	  `setup_menu_name`, 
	  `setup_menu_".$m_setup['print default lang']."`
	  FROM `tbl_setup_menu`

";
//echo $tQuery;
$setup = mysql_query($tQuery);

$count=0;
while ($count<mysql_num_rows($setup)){
 $m_setup[mysql_result($setup,$count,0)]=mysql_result($setup,$count,1);
 $count++;
}
//==================================SETUP=MENU==========================================
//echo $m_setup['print default lang'];
//==================================================================
$w_house = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	  *
	  FROM `tbl_warehouse`
";

$w_house = mysql_query($tQuery);


$fields = mysql_query("SET NAMES utf8");
if($template_name=="wareanalytics"){
//echo $template_name;
if ($operation_id==0){

    echo "<table width=100% class='menu_top'><tr><td align=center><table>";
 	$tmp=0;
	while($tmp<mysql_num_rows($w_house)){
	  echo "<tr><td><a href='edit_nakl_print.php?tmp=wareanalytics&operation_id=",mysql_result($w_house,$tmp,"warehouse_id"),"' target='_blank'>";
	  echo mysql_result($w_house,$tmp,"warehouse_name")," (",mysql_result($w_house,$tmp,"warehouse_memo"),")";
	  echo "</a></td><td>";
	  echo "<a href='edit_nakl_print.php?tmp=wareanalytics&operation_id=",mysql_result($w_house,$tmp,"warehouse_id"),"&minus=1' target='_blank'>",
		mysql_result($w_house,$tmp,"warehouse_name")," - ",$m_setup['menu minus'],"</a>";
	  echo "</td></tr>";
	$tmp++;
	}
    echo "</table></td></tr></table>";
exit();
}
//echo "hhh",$m_setup['print default lang'];
$tQuery = "SELECT 
	  `tovar_artkl`, 
	  `tovar_name_".$m_setup['print default lang']."` as tovar_name_1,
	  `warehouse_unit_".$operation_id."` as operation_detail_item,
	  (`price_tovar_1`*`currency_ex`*`warehouse_unit_".$operation_id."`) AS price_tovar_1
	  FROM `tbl_warehouse_unit`,`tbl_tovar`,`tbl_price_tovar`,`tbl_currency`
	  WHERE 
	  `price_tovar_id`=`tovar_id` and
	  `currency_id`=`price_tovar_curr_1` and
	  `warehouse_unit_tovar_id`=`tovar_id` and
	  ";
	if(isset($_REQUEST['minus'])){
	  $tQuery.= " `warehouse_unit_".$operation_id."` < '0' ";
	}else{
	  $tQuery.= " `warehouse_unit_".$operation_id."` > '0' ";
	}
    $tQuery .= "  ORDER BY `tovar_name_1` ASC, `tovar_artkl` ASC";
    //echo $tQuery;
}else{

$ver = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	  `tbl_operation`.*,
	  `tbl_firms`.*,
	  `klienti_name_1`,
	  `klienti_phone_1`,
	  `delivery_name`
	  FROM `tbl_operation`,`tbl_klienti`,`tbl_firms`,`tbl_delivery` 
	  WHERE 
	  `operation_klient`=`klienti_id` and 
	  `operation_id`='".$operation_id."' and
	  `operation_prodavec`=`firms_id` and
	  `delivery_id`=`klienti_delivery_id`
	  
";
//echo $tQuery;
$ver = mysql_query($tQuery);
//==================================================================
$tQuery = "SELECT 
	  `tovar_artkl`, 
	  `tovar_name_".$m_setup['print default lang']."` as tovar_name_1,
	  (`price_tovar_1`*`currency_ex`*`operation_detail_item`) AS price_tovar_1,
	  (`price_tovar_1`*`currency_ex`) as price_tovar_bay,
	  `operation_detail_price`,
	  `operation_detail_item`,
	  `operation_detail_discount`,
	  `operation_detail_summ`,
	  `operation_detail_memo`,
	  `operation_detail_from`,
	  `operation_detail_to`,
	  `tbl_warehouse_unit`.*
	  FROM `tbl_operation_detail`,`tbl_tovar`,`tbl_price_tovar`,`tbl_currency`,`tbl_warehouse_unit`
	  WHERE 
	  `operation_detail_tovar`=`tovar_id` and 
	  `warehouse_unit_tovar_id`=`tovar_id` and
	  `price_tovar_id`=`tovar_id` and
	  `currency_id`=`price_tovar_curr_1` and
	  `operation_detail_operation`='".$operation_id."' and
	  `operation_detail_dell` = '0'
	  ORDER BY `tovar_name_1` ASC, `tovar_artkl` ASC
";
//echo $tQuery;
}
$fields = mysql_query($tQuery);

//==================================================================
//echo $tQuery;

$tmp_header = file_get_contents($temp_header);    
 	$tmp=mysql_num_rows($w_house)-1;
	while($tmp>=0){
	    if ($operation_id==mysql_result($w_house,$tmp,"warehouse_id")){
		$tmp_header = str_replace("&warehouse_id",mysql_result($w_house,$tmp,"warehouse_id"),$tmp_header);
		$tmp_header = str_replace("&warehouse_name",mysql_result($w_house,$tmp,"warehouse_name"),$tmp_header);
		$tmp_header = str_replace("&warehouse_memo",mysql_result($w_house,$tmp,"warehouse_memo"),$tmp_header);
		$tmp_header = str_replace("&warehouse_shot_name",mysql_result($w_house,$tmp,"warehouse_shot_name"),$tmp_header);
		$tmp=-1;    
	    }
	$tmp--;
	}
	//echo "kk",mysql_result($ver,0,"operation_id");
$tmp_header = str_replace("&operation_data",mysql_result($ver,0,"operation_data"),$tmp_header);
$tmp_header = str_replace("&operation_id",mysql_result($ver,0,"operation_id"),$tmp_header);
$tmp_header = str_replace("&operation_beznal_rah_date",mysql_result($ver,0,"operation_beznal_rah_date"),$tmp_header);
$tmp_header = str_replace("&operation_beznal_nakl_date",mysql_result($ver,0,"operation_beznal_nakl_date"),$tmp_header);
$tmp_header = str_replace("&operation_beznal_rah",mysql_result($ver,0,"operation_beznal_rah"),$tmp_header);
$tmp_header = str_replace("&operation_beznal_nakl",mysql_result($ver,0,"operation_beznal_nakl"),$tmp_header);
$tmp_header = str_replace("&operation_beznal_memo",mysql_result($ver,0,"operation_beznal_memo"),$tmp_header);
$tmp_header = str_replace("&operation_beznal_pidstava",mysql_result($ver,0,"operation_beznal_pidstava"),$tmp_header);
$tmp_header = str_replace("&operation_memo",mysql_result($ver,0,"operation_memo"),$tmp_header);
$tmp_header = str_replace("&operation_summ",number_format(mysql_result($ver,0,"operation_summ"),2,".",""),$tmp_header);

$summ_str = money2str_ru(mysql_result($ver,0,"operation_summ"));

$tmp_header = str_replace("&operation_str_summ",$summ_str,$tmp_header);
$tmp_header = str_replace("&klienti_name_1",mysql_result($ver,0,"klienti_name_1"),$tmp_header);
$tmp_header = str_replace("&klienti_phone_1",mysql_result($ver,0,"klienti_phone_1"),$tmp_header);
$tmp_header = str_replace("&delivery_name",mysql_result($ver,0,"delivery_name"),$tmp_header);
$tmp_header = str_replace("&firms_name",mysql_result($ver,0,"firms_name"),$tmp_header);
$tmp_header = str_replace("&print bank rah",mysql_result($ver,0,"firms_rah"),$tmp_header);
$tmp_header = str_replace("&print bank zkpo",mysql_result($ver,0,"firms_zkpo"),$tmp_header);
$tmp_header = str_replace("&print bank mfo",mysql_result($ver,0,"firms_mfo"),$tmp_header);
$tmp_header = str_replace("&print bank bank",mysql_result($ver,0,"firms_bank"),$tmp_header);

$tmp_header = str_replace("&date_now",date("Y-m-d G:i:s"),$tmp_header);


$tmp_fields = file_get_contents($temp_fields);
$fields_for_out = "<table class='print' width='100%'><thead style='display:table-header-group'>";
$fielss_tmp_str="";
    //==================Fields=Header=====================================
    $fielss_tmp_str_name = $tmp_fields;
    //$style = "<>";
    $fielss_tmp_str_name = str_replace("&count","<b>N",$fielss_tmp_str_name);
    $fielss_tmp_str_name = str_replace("&tovar_artkl","<b>".$m_setup['menu artkl'],$fielss_tmp_str_name);
    $fielss_tmp_str_name = str_replace("&tovar_name_1","<b>".$m_setup['menu name1'],$fielss_tmp_str_name);
    $fielss_tmp_str_name = str_replace("&price_tovar_1_as_price","<b>".$m_setup['print price'],$fielss_tmp_str_name);
    $fielss_tmp_str_name = str_replace("&operation_detail_price","<b>".$m_setup['print price'],$fielss_tmp_str_name);
    $fielss_tmp_str_name = str_replace("&operation_detail_item","<b>".$m_setup['print items'],$fielss_tmp_str_name);
    $fielss_tmp_str_name = str_replace("&operation_detail_discount","<b>".$m_setup['print discount'],$fielss_tmp_str_name);
    $fielss_tmp_str_name = str_replace("&operation_detail_summ","<b>".$m_setup['print summ'],$fielss_tmp_str_name);
    $fielss_tmp_str_name = str_replace("&operation_detail_from","<b>".$m_setup['print from'],$fielss_tmp_str_name);
    $fielss_tmp_str_name = str_replace("&price_tovar_1_summ","<b>".$m_setup['print summ'],$fielss_tmp_str_name);
    $fielss_tmp_str_name = str_replace("&price_tovar_1","<b>".$m_setup['print bay'],$fielss_tmp_str_name);
    $fielss_tmp_str_name = str_replace("&summ_upper","<b>".$m_setup['print upper summ'],$fielss_tmp_str_name);
    $fielss_tmp_str_name = str_replace("&plus_persent","<b>".$m_setup['plus_persent'],$fielss_tmp_str_name);
    $fielss_tmp_str_name = str_replace("&my_persent","<b>".$m_setup['my_persent'],$fielss_tmp_str_name);
    $fielss_tmp_str_name = str_replace("&my_summ","<b>".$m_setup['my_summ'],$fielss_tmp_str_name);
    $fielss_tmp_str_name = str_replace("&operation_detail_to","<b>".$m_setup['print to'],$fielss_tmp_str_name);
    $fielss_tmp_str_name = str_replace("&memo","<b>".$m_setup['menu memo'],$fielss_tmp_str_name);
$tmp=mysql_num_rows($w_house)-1;
    while($tmp>=0){
	  $tmp_id = mysql_result($w_house,$tmp,"warehouse_id");
    //echo $tmp," ",mysql_result($w_house,$tmp,"warehouse_shot_name"),"warehouse_unit_".($tmp_id),"<br>";
	  $fielss_tmp_str_name = str_replace("&warehouse_unit_".$tmp_id,"<b>".mb_substr(mysql_result($w_house,$tmp,"warehouse_shot_name"),0,5,"UTF-8"),$fielss_tmp_str_name);
    $tmp--;
    }
    
    $fields_for_out .= $fielss_tmp_str_name."</thead>";
    //=================Fields==============================================
$count = 0;
$summ_bay = 0;
$summ_sale = 0;
$items=0;
while ($count < mysql_num_rows($fields)){
    $fielss_tmp_str = $tmp_fields;
    $summ_bay += mysql_result($fields,$count,"price_tovar_bay")*mysql_result($fields,$count,"operation_detail_item");
    $summ_sale += mysql_result($fields,$count,"operation_detail_summ");
    $fielss_tmp_str = str_replace("&count",$count+1,$fielss_tmp_str);
    $fielss_tmp_str = str_replace("&tovar_artkl",mysql_result($fields,$count,"tovar_artkl"),$fielss_tmp_str);
    
    $tovar_name = mysql_result($fields,$count,"tovar_name_1");
    
    
    if($template_name!="bay" and $template_name!="warehouse"){
	$tovar_name = explode($m_setup['tovar name sep'], $tovar_name);
	$fielss_tmp_str = str_replace("&tovar_name_1",$tovar_name[0],$fielss_tmp_str);
    }else{
		if(strlen($tovar_name)>50){
		    $tovar_name = "<div class=\"barcode\" style=\"width:390px;height:14px;\" align=\"left\">
				  ".$tovar_name."
				  </div>";
		}
    
	$fielss_tmp_str = str_replace("&tovar_name_1",$tovar_name,$fielss_tmp_str);
    }
    $zakup =number_format(mysql_result($fields,$count,"price_tovar_bay"),2,".","");
    $summ = number_format(mysql_result($fields,$count,"operation_detail_summ"),2,".","");
    $price = number_format(mysql_result($fields,$count,"operation_detail_price"),2,".","");
    $items_det = mysql_result($fields,$count,"operation_detail_item");
    
    $fielss_tmp_str = str_replace("&operation_detail_price",$price,$fielss_tmp_str);
    $fielss_tmp_str = str_replace("&price_tovar_1_summ",$zakup*$items_det,$fielss_tmp_str);
    $fielss_tmp_str = str_replace("&price_tovar_1_as_price",$zakup,$fielss_tmp_str);
    $fielss_tmp_str = str_replace("&price_tovar_1",$zakup,$fielss_tmp_str);
    $fielss_tmp_str = str_replace("&summ_upper",$summ-$zakup,$fielss_tmp_str);
    $fielss_tmp_str = str_replace("&operation_detail_item",$items_det,$fielss_tmp_str);
    $fielss_tmp_str = str_replace("&operation_detail_discount",mysql_result($fields,$count,"operation_detail_discount"),$fielss_tmp_str);
    $fielss_tmp_str = str_replace("&operation_detail_summ",$summ,$fielss_tmp_str);
  
    $fielss_tmp_str = str_replace("&plus_persent","<b>".number_format(($summ-$zakup)/($summ/100),0),$fielss_tmp_str);
    $tmp = "<input type='text'  style='width:20px'  name='my_persent' value='1'/>";
    $fielss_tmp_str = str_replace("&my_persent","<b>".$tmp,$fielss_tmp_str);
    $fielss_tmp_str = str_replace("&my_summ","<b>".$m_setup['my_summ'],$fielss_tmp_str);
    
    $items +=mysql_result($fields,$count,"operation_detail_item");
 
    $tmp=mysql_num_rows($w_house)-1;
	while($tmp>=0){//ostatki po skladam
		  $tmp_id = mysql_result($w_house,$tmp,"warehouse_id");
	    if(mysql_result($fields,$count,"warehouse_unit_$tmp_id")=="0"){
		$fielss_tmp_str = str_replace("&warehouse_unit_$tmp_id","",$fielss_tmp_str);
	    }else{
		$fielss_tmp_str = str_replace("&warehouse_unit_$tmp_id",mysql_result($fields,$count,"warehouse_unit_$tmp_id"),$fielss_tmp_str);
	    }
	    
	    if (mysql_result($fields,$count,"operation_detail_to")==mysql_result($w_house,$tmp,"warehouse_id")){
		$fielss_tmp_str = str_replace("&operation_detail_to",mb_substr(mysql_result($w_house,$tmp,"warehouse_shot_name"),0,3,"UTF-8"),$fielss_tmp_str);
	    	//$tmp=-1;    
	    }
	$tmp--;
	}
	$tmp=mysql_num_rows($w_house)-1;
	while($tmp>=0){
	//echo "&warehouse_unit_$tmp<br>";
		  $tmp_id = mysql_result($w_house,$tmp,"warehouse_id");
	    if(mysql_result($fields,$count,"warehouse_unit_$tmp_id")=="0"){
		$fielss_tmp_str = str_replace("&warehouse_unit_$tmp_id","",$fielss_tmp_str);
	    }else{
		$fielss_tmp_str = str_replace("&warehouse_unit_$tmp_id",mysql_result($fields,$count,"warehouse_unit_$tmp_id"),$fielss_tmp_str);
	    }

	    if (mysql_result($fields,$count,"operation_detail_from")==mysql_result($w_house,$tmp,"warehouse_id")){
		$fielss_tmp_str = str_replace("&operation_detail_from",mb_substr(mysql_result($w_house,$tmp,"warehouse_shot_name"),0,3,"UTF-8"),$fielss_tmp_str);
	    	//$tmp=-1;    
	    }
	  //  echo "gg",$tmp;
	$tmp--;
	}

  $fields_for_out .= $fielss_tmp_str;
$count++;
}
$fields_for_out .= "</table>";

$tmp_header = str_replace("&items",number_format($items,0,".",""),$tmp_header);
$tmp_header = str_replace("&summ_bay",number_format($summ_bay,2,".",""),$tmp_header);
$tmp_header = str_replace("&summ_sale",number_format($summ_sale,2,".",""),$tmp_header);
$tmp_header = str_replace("&summ_upper_all",number_format($summ_sale-$summ_bay,2,".",""),$tmp_header);
$tmp_header = str_replace("&fields",$fields_for_out,$tmp_header);

$tmp_header = str_replace("&DELIVE PRINT",$m_setup['DELIVE PRINT'],$tmp_header);
$tmp_header = str_replace("&ANALITIC PRINT",$m_setup['ANALITIC PRINT'],$tmp_header);
$tmp_header = str_replace("&ANALITIC WARE PRINT",$m_setup['ANALITIC WARE PRINT'],$tmp_header);
$tmp_header = str_replace("&PACING PRINT",$m_setup['PACING PRINT'],$tmp_header);
$tmp_header = str_replace("&FORSHOP PRINT",$m_setup['menu print for shop'],$tmp_header);
$tmp_header = str_replace("&print nakl no prihod",$m_setup['print nakl no prihod'],$tmp_header);
$tmp_header = str_replace("&print nak no",$m_setup['print nak no'],$tmp_header);
$tmp_header = str_replace("&print rah no",$m_setup['print rah no'],$tmp_header);
$tmp_header = str_replace("&print print",$m_setup['print print'],$tmp_header);
$tmp_header = str_replace("&print vidal",$m_setup['print vidal'],$tmp_header);
$tmp_header = str_replace("&print supplier",$m_setup['print supplier'],$tmp_header);
$tmp_header = str_replace("&print otrimal",$m_setup['print otrimal'],$tmp_header);
$tmp_header = str_replace("&print delivery",$m_setup['print delivery'],$tmp_header);
$tmp_header = str_replace("&print summ",$m_setup['print summ'],$tmp_header);
$tmp_header = str_replace("&print pdv",$m_setup['print pdv'],$tmp_header);
$tmp_header = str_replace("&print string",$m_setup['print string'],$tmp_header);
$tmp_header = str_replace("&print items",$m_setup['print items'],$tmp_header);
$tmp_header = str_replace("&print bay",$m_setup['print bay'],$tmp_header);
$tmp_header = str_replace("&print sale",$m_setup['print sale'],$tmp_header);
$tmp_header = str_replace("&print memo",$m_setup['print memo'],$tmp_header);
$tmp_header = str_replace("&print upper summ",$m_setup['print upper summ'],$tmp_header);
$tmp_header = str_replace("&vid",$m_setup['print from date'],$tmp_header);
$tmp_header = str_replace("&print monydaj",$m_setup['print monydaj'],$tmp_header);
$tmp_header = str_replace("&print bank info rah",$m_setup['print bank info rah'],$tmp_header);
$tmp_header = str_replace("&print bank info zkpo",$m_setup['print bank info zkpo'],$tmp_header);
$tmp_header = str_replace("&print bank info mfo",$m_setup['print bank info mfo'],$tmp_header);
$tmp_header = str_replace("&print bank info bank",$m_setup['print bank info bank'],$tmp_header);
$tmp_header = str_replace("&print klient name from",$m_setup['print klient name from'],$tmp_header);
$tmp_header = str_replace("&print pidstava",$m_setup['print pidstava'],$tmp_header);


/*if(mysql_result($ver,0,"operation_beznal_firm")>0) $vidal = mysql_result($ver,0,"operation_beznal_firm");
$tQuery = "SELECT *
	  FROM `tbl_firms`
	  WHERE 
	  `firms_id`='".$vidal."'";
      $firms = mysql_query($tQuery);*/


// 


echo $tmp_header,"</body>";

if(isset($_REQUEST['next'])){
  $key = $_REQUEST['next'];

  if($key=="bay"){
	    header ('Refresh: 0; url=edit_nakl_print.php?next=declaration&operation_id='.$operation_id);
  }
  if($key=="declaration"){
	    $memo = mysql_result($ver,0,"operation_memo");
	    $memo = str_replace(" ","",$memo);
	    $memo = explode(":",$memo);
	    $memo = explode(",",$memo[2]);
	    header ('Refresh: 0; url=https://orders.novaposhta.ua/pformn.php?o='.$memo[0].'&num_copy=1&token=af85354bf7a0d367d707c087d8e20852');
	    //header ('Refresh: 0; url=https://print.novaposhta.ua/index.php?r=site/ttn&id='.$memo[0]);
  }
  
}

?>
