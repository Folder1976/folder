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
require("JsHttpRequest.php");
$JsHttpRequest=new JsHttpRequest("utf8");//"windows-1251");

$count = 0;

$s_name="";
$s_value="";
$s_vhere="";
$s_sql_string="";
$s_sql_string_where = "";

//$ver = mysql_query("SET NAMES utf8");

$empty = $post = array();
foreach ($_POST as $varname => $varvalue){
   if(substr($varname,0,1) != "_"){
    $post[$varname] = $varvalue;
    
  // echo $post[$varname], " = > " , $varname, "<br>";
    if ($s_value == "") {
      $s_value .=  "'" . $post[$varname] . "'";
    }else{
      $s_value .=  ",'" . $post[$varname] . "'";
    }
    
    if ($s_name == ""){
      $s_name  .=  " " .  $varname . "";
    }else{
      $s_name  .=  " " .  $varname . "";
    }
}
}

  $s_sql_string = "UPDATE `tbl_klienti` SET `klienti_setup`='$s_name' WHERE `klienti_id`='".$_REQUEST['_id_value']."'";
  
  $ver = mysql_query("SET NAMES utf8");
  $ver = mysql_query($s_sql_string);// . $s_sql_string_where);

header ('Refresh: 1; url=edit_klient.php?klienti_id='. $_REQUEST['_id_value']);

?>
