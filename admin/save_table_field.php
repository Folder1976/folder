<?php
include 'init.lib.php';
connect_to_mysql();
session_start();
if (!session_verify($_SERVER["PHP_SELF"],"none")){
  exit();
}
//include 'nakl.lib.php';


require("JsHttpRequest.php");
$JsHttpRequest=new JsHttpRequest("utf8");//"windows-1251");


$table = $_REQUEST["table"];
$name = $_REQUEST["name"];
$value = $_REQUEST["value"];
$w_id = $_REQUEST["w_id"];
$w_value = $_REQUEST["w_value"];


header ('Content-Type: text/html; charset=utf8');

if($name == 'tovar_alias'){
      include "../class/class_alias.php";
      $Alias = new Alias($folder);
      
      $Alias->saveProductAlias($value,$w_value);
      
      echo 'Alias change';
}else{
  $ver = mysql_query("SET NAMES utf8");
  $tQuery = "UPDATE `".$table."` SET `".$name."`='".$value."' WHERE `".$w_id."`='".$w_value."'";
  $ver = mysql_query($tQuery);
  
  if (!$ver)
  {
    echo "\nQuery error Status ".$tQuery;
    exit();
  }
  $http=" echo -> ".$table." ".$name." ".$value." ".$w_id." ".$w_value."<br>".$tQuery;
  echo $http;
}
?>
