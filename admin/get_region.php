<?php

require("JsHttpRequest.php");
$JsHttpRequest=new JsHttpRequest("utf8");

$test = $_REQUEST["country"];
$http = "input type - ".$test;
echo $http;
/*sleep(rand(1,3));

$country_id = @intval($_GET['country_id']);

$regions[] = array('id'=>'1','title'=>'DATA SET');

$result = array('type'=>'success', 'regions'=>$regions);

print json_encode($result);
*/


?>