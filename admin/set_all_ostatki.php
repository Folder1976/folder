<?php
include 'init.lib.php';
include 'nakl.lib.php';

$steep = 10;
$count =0;

connect_to_mysql();
session_start();

require("JsHttpRequest.php");
$JsHttpRequest=new JsHttpRequest("utf8");//"windows-1251");


header ('Content-Type: text/html; charset=utf8');

if(isset($_REQUEST['count'])) $count = $_REQUEST['count'];

$ver = mysql_query("SET NAMES utf8");
$tQuery = "SELECT `tovar_id`,`tovar_artkl`,`tovar_name_1`
	    FROM `tbl_tovar`
	    ORDER BY `tovar_id` DESC
	    LIMIT ".($count).", $steep
	   ";
	  // echo $tQuery;
$ver = mysql_query($tQuery);

echo "<header><title>RESET Warehouse</title></header>";

while($tmp = mysqli_fetch_assoc($ver)){
    reset_warehouse_on_tovar_id($tmp['tovar_id']);
    echo "<br>".$tmp['tovar_id']." - ".$tmp['tovar_artkl']." ".$tmp['tovar_name_1'];
}
$count += $steep;


if(mysql_num_rows($ver)>0){
header ('Refresh: 2; url=set_all_ostatki.php?count='.$count);
}else{
echo "END";
}




?>
