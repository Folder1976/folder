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
		`orders_user_id`,
		`orders_datatime`,
		`orders_sum`,
		`orders_status`,
		`orders_items_id`,
		`orders_delivery_id`,
		`orders_paid`,
		`orders_comment`,
		`products_barcode`,
		`user_email`,
		`user_last_name`,
		`user_name`,
		`user_patronymic`,
		`user_country`,
		`user_city`,
		`user_street`,
		`user_house_number`,
		`user_room_number`,
		`user_floor_number`,
		`user_security_code`,
		`user_phone`,
		`user_mobile`,
		`user_products_price_groups_id`,
		`orders_items_product_count`,
		`orders_items_product_price`
		
";
//`orders_items_product_description`
//echo "start";
	if ($_REQUEST['orders_type'] != 'all') {
			$l_sql_include = " AND `orders_type` = '" . $_REQUEST['orders_type'] . "' ";
		} else {
			$l_sql_include = null;
		}
		switch ($_REQUEST['orders_status']) {
			case 1:
				$l_sql = "SELECT ". $sql_fields."
				
						FROM
								
								`" . DB_PREFIX . "orders`,
								`" . DB_PREFIX . "users`,
								`" . DB_PREFIX . "orders_items`
						LEFT OUTER JOIN
								`" . DB_PREFIX . "products` ON `orders_items_product_id` = `products_id`
						WHERE
								`orders_user_id` = `user_id`
						AND
								`orders_id` = `orders_items_order_id`
						AND
								`orders_status` = '".$_REQUEST['orders_status']."'
						ORDER BY
								`orders_id`";
								//echo $l_sql;
				break;
			case 'any':
				$l_sql = "SELECT ". $sql_fields."
						FROM
							`" . DB_PREFIX . "orders`,
							`" . DB_PREFIX . "users`,
							`" . DB_PREFIX . "orders_items`
						LEFT OUTER JOIN
							`" . DB_PREFIX . "products`
							ON
								`orders_items_product_id` = `products_id`
						WHERE
								`orders_user_id` = `user_id`
								" . $l_sql_include . "
						AND
								`orders_id` = `orders_items_order_id`
						
						ORDER BY
									`orders_id`";
									//echo "2";
				break;
			default:
				$l_sql = "SELECT ". $sql_fields."
						FROM								
								`" . DB_PREFIX . "orders`,
								`" . DB_PREFIX . "users`,
								`" . DB_PREFIX . "orders_items`
						LEFT OUTER JOIN
								`" . DB_PREFIX . "products` ON `orders_items_product_id` = `products_id`
						WHERE
								`orders_user_id` = `user_id`
								" . $l_sql_include . "
						AND
								`orders_id` = `orders_items_order_id`
						ORDER BY
								`orders_id`";
								//echo "3";
				break;

			}
		
		
$orders = mysql_query("SET NAMES utf8");
$orders = mysql_query($l_sql); 
if (!$orders){
  echo "Query error - orders select";
}
//echo "222";
$http ="";
$count=0;
  while ($count < mysql_num_rows($orders)){
   		$http .= mysql_result($orders,$count,"orders_id") . "*";
		$http .= mysql_result($orders,$count,"orders_user_id") . "*";
		$http .= mysql_result($orders,$count,"orders_datatime") . "*";
		$http .= mysql_result($orders,$count,"orders_sum") . "*";
		$http .= mysql_result($orders,$count,"orders_status") . "*";
		$http .= mysql_result($orders,$count,"orders_items_id") . "*";
		$http .= mysql_result($orders,$count,"orders_delivery_id") . "*";
		$http .= mysql_result($orders,$count,"orders_paid") . "*";
		$http .= mysql_result($orders,$count,"orders_comment") . "*";
		$http .= mysql_result($orders,$count,"products_barcode") . "*";
		$http .= mysql_result($orders,$count,"user_email") . "*";
		$http .= mysql_result($orders,$count,"user_last_name") . "*";
		$http .= mysql_result($orders,$count,"user_name") . "*";
		$http .= mysql_result($orders,$count,"user_patronymic") . "*";
		$http .= mysql_result($orders,$count,"user_country") . "*";
		$http .= mysql_result($orders,$count,"user_city") . "*";
		$http .= mysql_result($orders,$count,"user_street") . "*";
		$http .= mysql_result($orders,$count,"user_house_number") . "*";
		$http .= mysql_result($orders,$count,"user_room_number") . "*";
		$http .= mysql_result($orders,$count,"user_floor_number") . "*";
		$http .= mysql_result($orders,$count,"user_security_code") . "*";
		$http .= mysql_result($orders,$count,"user_phone") . "*";
		$http .= mysql_result($orders,$count,"user_mobile") . "*";
		$http .= mysql_result($orders,$count,"user_products_price_groups_id") . "*";
		$http .= mysql_result($orders,$count,"orders_items_product_count") . "*";
		$http .= mysql_result($orders,$count,"orders_items_product_price") . "*";
		$http .= mysql_result($orders,$count,"orders_items_product_description") . "||";
  $count++;
  }
echo $http;


?>
