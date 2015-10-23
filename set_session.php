<?php
include 'init.lib.php';

connect_to_mysql();
//session_start();

require("JsHttpRequest.php");
$JsHttpRequest=new JsHttpRequest("utf8");//"windows-1251");

$_SESSION[BASE.'chat'] = $_REQUEST["set"]

//$http = $_REQUEST["set"];
//echo $http;



?>
