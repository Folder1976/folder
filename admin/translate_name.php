<?php
include 'init.lib.php';

connect_to_mysql();
session_start();


require("JsHttpRequest.php");
$JsHttpRequest=new JsHttpRequest("utf8");//"windows-1251");


header ('Content-Type: text/html; charset=utf8');

$sort = "";
if(!isset($_REQUEST['tovar_id'])){
    exit();
}
if($_REQUEST['tovar_id']=="all"){
    $sort = "";
}else{
    $sort = " WHERE `tovar_id`='".$_REQUEST['tovar_id']."'";
}

//=====================================================
$tovar = mysql_query("SET NAMES utf8");
$tQuery = "SELECT `tovar_name_1`,
		  `tovar_name_2`,
		  `tovar_name_3`,
		  `tovar_id`
	  FROM `tbl_tovar` 
	  $sort";
	  
$tovar = mysql_query($tQuery);

//=====================================================
$slovar = mysql_query("SET NAMES utf8");
$tQuery = "SELECT *
	  FROM `tbl_translate` 
	  ORDER BY `translate_id` ASC";
	  
$slovar = mysql_query($tQuery);


$count=0;
while($count < mysql_num_rows($tovar)){

    translate($tovar,$slovar,$count);

$count++;
}   


function translate($tovar,$slovar,$count) {
    
    $tovar_name[1] = mysql_result($tovar,$count,"tovar_name_1");
    $tovar_name[2] = mysql_result($tovar,$count,"tovar_name_2");
    $tovar_name[3] = mysql_result($tovar,$count,"tovar_name_3");
    echo $tovar_name[1],"<br>";
    
    $find="";
    $set="";
    
    $i=0;
    while($i < mysql_num_rows($slovar)){
      $name = mysql_result($slovar,$i,"translate_from");
	
	if($name==0)
	{
	      $find = mysql_result($slovar,$i,"translate_find");
	      $set = mysql_result($slovar,$i,"translate_set");
	      $tovar_name[1] = str_replace($find,$set,$tovar_name[1]);
	  // echo ": ",$find," -> ",$set,"<br>"; 
	   
      	      $find = mysql_result($slovar,$i,"translate_find");
	      $set = mysql_result($slovar,$i,"translate_set");
	      $tovar_name[2] = str_replace($find,$set,$tovar_name[2]);

      	      $find = mysql_result($slovar,$i,"translate_find");
	      $set = mysql_result($slovar,$i,"translate_set");
	      $tovar_name[3] = str_replace($find,$set,$tovar_name[3]);
	
	}else{
      	      $find = mysql_result($slovar,$i,"translate_find");
	      $set = mysql_result($slovar,$i,"translate_set");
	      $tovar_name[$name] = str_replace($find,$set,$tovar_name[$name]);
     	  // echo ": ",$find," -> ",$set,"<br>"; 

	 }
    
    $i++;
    }
  
    $update = mysql_query("SET NAMES utf8");
    $tQuery = "UPDATE `tbl_tovar` SET 
		`tovar_name_1`='".$tovar_name[1]."',
		`tovar_name_2`='".$tovar_name[2]."',
		`tovar_name_3`='".$tovar_name[3]."'
		 WHERE
		 `tovar_id`='".mysql_result($tovar,$count,"tovar_id")."'";
    $update = mysql_query($tQuery);
    //echo $tQuery," - $i<br>";
  
  
  
}


?>
