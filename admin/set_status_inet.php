<?php
include 'init.lib.php';

connect_to_mysql();

session_start();
if (!session_verify($_SERVER["PHP_SELF"])){
  exit();
}

include 'config.php';
header ('Content-Type: text/html; charset=utf8');

//echo "gggg";

if ($_REQUEST['pass'] != 'KLJGbsfgv8y9JKbhlis') {
echo "FUCK OFF!";
exit();
}


$dbhost = HOST;
$dbname = DB;
$dbuser = USER_DB;
$dbpasswd = PASS_DB;

$dbcnx = mysql_connect($dbhost, $dbuser, $dbpasswd);
  if (!$dbcnx)
  {
  echo "Not connect to MySQL";
  exit();
  }

  if (!mysql_select_db($dbname,$dbcnx))
  {
  echo "No base present";
  exit();
  }
//echo "conn - ok <br>";

require("JsHttpRequest.php");
$JsHttpRequest=new JsHttpRequest("utf8");
//echo "js<br>";
$sql_fields = "
		`orders_id`,
		`orders_status`,
";

if($_REQUEST['status']==1)$new_status=6;// view
if($_REQUEST['status']==2)$new_status=2;// view
if($_REQUEST['status']==3)$new_status=3;// view
if($_REQUEST['status']==4)$new_status=4;// view
if($_REQUEST['status']==5)$new_status=5;// view
//if($_REQUEST['status']==6)$new_status=1;// view
if($_REQUEST['status']==7)$new_status=7;// view
if($_REQUEST['status']==8)$new_status=8;// view
if($_REQUEST['status']==9)$new_status=9;// view
if($_REQUEST['status']==10)$new_status=10;// view
if($_REQUEST['status']==0)$new_status=11;// view
if($_REQUEST['status']==15)$new_status=12;// view
if($_REQUEST['status']=="")$new_status=11;// view
if(!$_REQUEST['status'])$new_status=11;// view


			$l_sql = "UPDATE `" . DB_PREFIX . "orders` SET 
				`orders_status`='".$new_status."'
				WHERE `orders_id` = '".$_REQUEST['orders_id']."'";
$orders = mysql_query("SET NAMES utf8");
$orders = mysql_query($l_sql); 

if (!$orders){
  echo "Query error - orders select";
}
echo "new status for ", $_REQUEST['orders_id'];


?>
