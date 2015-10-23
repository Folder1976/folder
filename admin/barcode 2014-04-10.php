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
	  *
	  FROM `tbl_setup`
	  
";
//echo $tQuery;
$setup = mysql_query($tQuery);
$m_setup = array();
$count=0;
while ($count<mysql_num_rows($setup)){
 $m_setup[mysql_result($setup,$count,0)]=mysql_result($setup,$count,1);
 $count++;
}

header ('Content-Type: text/html; charset=utf-8');
$ware="";
$tovar_id="";
echo "  <title>barcode</title>    
      <link rel='stylesheet' medis='screen' type='text/css' href='sturm.css'>
";
//==================================SETUP=MENU==========================================
$tovar = mysql_query("SET NAMES utf8");
if(isset($_REQUEST["operation_id"]) and !empty($_REQUEST["operation_id"])){
      $tQuery = "SELECT 
		`tovar_barcode`,
		`tovar_artkl`,
		`price_tovar_".$m_setup['web default price']."` as price,
		`currency_name_shot`,
		`tovar_name_".$m_setup['price default lang']."` as name
		FROM `tbl_tovar`,`tbl_price_tovar`,`tbl_currency`,`tbl_operation_detail`
		WHERE 
		`price_tovar_id`=`tovar_id` and
		`currency_id`=`price_tovar_curr_".$m_setup['web default price']."` and
		`tovar_id` = `operation_detail_tovar` and
		`operation_detail_operation`='".$_REQUEST["operation_id"]."'
		
      ";

}
if(isset($_REQUEST["tovar_id"]) and !empty($_REQUEST["tovar_id"]) and !isset($_REQUEST["ware"])){
      $tQuery = "SELECT 
		`tovar_barcode`,
		`tovar_artkl`,
		`price_tovar_".$m_setup['price default price']."` as price,
		`currency_name_shot`,
		`tovar_name_".$m_setup['price default lang']."` as name
		FROM `tbl_tovar`,`tbl_price_tovar`,`tbl_currency`
		WHERE 
		`price_tovar_id`=`tovar_id` and
		`currency_id`=`price_tovar_curr_".$m_setup['price default price']."` and
		`tovar_id` = '".$_REQUEST["tovar_id"]."'
		
      ";
      

      
      }

     //echo $_REQUEST["tovar_id"]; 
if(isset($_REQUEST["tovar_id"]) and !empty($_REQUEST["tovar_id"]) and isset($_REQUEST["ware"])){
   $ware = $_REQUEST["ware"];
   $tovar_id = $_REQUEST["tovar_id"];

    $tmp=0;
    $tovar_tmp = explode("*",$tovar_id);
    $find="";
    while(($tmp+1)<count($tovar_tmp)){
	$find .="`tovar_id`='".$tovar_tmp[$tmp]."' or ";
    $tmp++;
    }
	$find = substr($find,0,-4);
      
      $tQuery = "SELECT 
		`tovar_barcode`,
		`tovar_artkl`,
		`price_tovar_".$m_setup['price default price']."` as price,
		`currency_name_shot`,
		`tovar_name_".$m_setup['price default lang']."` as name
		FROM `tbl_tovar`,`tbl_price_tovar`,`tbl_currency`,`tbl_warehouse_unit`
		WHERE 
		`price_tovar_id`=`tovar_id` and
		`tovar_id`=`warehouse_unit_tovar_id` and
		`warehouse_unit_".$ware."` > '0' and
		`currency_id`=`price_tovar_curr_".$m_setup['price default price']."` and
		($find)
		ORDER BY name ASC
      ";
      }
      

$tovar = mysql_query($tQuery);
$count = 0;
if(isset($_REQUEST['start']))$count = $_REQUEST['start'];
//echo mysql_num_rows($tovar);
echo " <header> <script>
      function print_list(){
	 window.print();
      }
      </script></header>

<body onload=\"print_list()\">
      ";
echo "<table class=\"barcode\" >";
if($count < mysql_num_rows($tovar)){
      
      $name = explode($m_setup['tovar name sep'],mysql_result($tovar,$count,"name"));
      $artkl=mysql_result($tovar,$count,"tovar_artkl"); //explode($m_setup['tovar artikl-size sep'],mysql_result($tovar,$count,"tovar_artkl"));
  
      
      echo "<tr><td rowspan='4' width=\"20px\" ></td> <td align=\"center\" height=\"15px\">
      <b>",$artkl,"</b>
	";
      echo "</td></tr><tr><td align=\"center\" width=\"150px\" height=\"33px\">";
      
      if(isset($_REQUEST['key']) and !empty($_REQUEST["key"])){
	  if($_REQUEST['key']=="price" or $_REQUEST['key']=="price_ware"){
	  //echo strlen($name[0]);
		if(strlen($name[0])>110){
		    echo "<font style=\"font-size:7px;\">",$name[0],"</font>";
		}elseif(strlen($name[0])>65){
		    echo "<font style=\"font-size:10px;\">",$name[0],"</font>";
		}elseif(strlen($name[0])>40){
		    echo "<p font-size=\"12px\">",$name[0],"</p>";
		}else{
		    echo "<p font-size=\"14px\">",$name[0],"</p>";
		}
	  }
	  
      }else{
	  echo "<img height=\"50px\" width=\"100px\" src=\"http://barcode.tec-it.com/barcode.ashx?code=Code128&modulewidth=fit&data=",mysql_result($tovar,$count,"tovar_barcode"),"&dpi=96&imagetype=gif&rotation=0&color=&bgcolor=&fontcolor=&quiet=0&qunit=mm\" alt=\"",mysql_result($tovar,$count,"tovar_artkl"),"\"/>";
      }
      echo "</td></tr>";
      
	  if($_REQUEST['key']=="price"){
	  echo "<tr><td align=\"center\" valign=\"middle\" height=\"25px\">
		<b><font size=4>",mysql_result($tovar,$count,"price")," ",mysql_result($tovar,$count,"currency_name_shot"),
		"</font></b></td></tr>
		<tr><td height=\"5px\"></td><td></td></tr>";
	  }
		
      $count++;
      

}
  //  echo $count , " ", mysql_num_rows($tovar);
echo "</table></body>";
    
    //if(!isset($_REQUEST['tovar_id'])){
	    $key=null;
	    $operation_id=null;
	      if(isset($_REQUEST['key'])) $key = $_REQUEST['key'];
	      if(isset($_REQUEST['operation_id'])) $operation_id = $_REQUEST['operation_id'];
 
     
	if($count < mysql_num_rows($tovar)){
	    header ('Refresh: 0; url=' . $_SERVER["PHP_SELF"]."?key=$key&operation_id=$operation_id&ware=$ware&tovar_id=$tovar_id&start=$count");
	    }
      //}

?>
