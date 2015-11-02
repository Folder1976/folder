<?php
header("Content-Type: text/html; charset=UTF-8");
include '../init.lib.php';
connect_to_mysql();
if(!isset($_SESSION)){ 
 //session_sart(); 
} 

if(!isset($_GET['key']) AND $_GET['key'] =='kljh@sagflhfd@slgh #dfgh;dfsht098342-[054 y]3-5t y]-[23 []-943[f9h p34'){
  echo "none";
  die();
}

$inet_warehouse = "16";
$inet_price = "6";

$sql = "SELECT W.warehouse_unit_".$inet_warehouse." as ware,
		T.tovar_id,
		T.tovar_inet_id_parent,
		T.tovar_name_1,
		P.price_tovar_".$inet_price." as price,
		P.price_tovar_2 as price_rozn,
		P.price_tovar_1 as price_zakup,
		T.tovar_artkl,
		T.tovar_memo,
		T.tovar_dimension,
		D.dimension_name
		FROM tbl_warehouse_unit W
	LEFT JOIN tbl_tovar T ON T.tovar_id = W.warehouse_unit_tovar_id
        LEFT JOIN tbl_price_tovar P ON P.price_tovar_id = T.tovar_id
	LEFT JOIN tbl_tovar_dimension D ON D.dimension_id = T.tovar_dimension
	WHERE W.warehouse_unit_16 > 0
	ORDER BY T.tovar_artkl ASC";

$r = mysql_query("SET NAMES utf8");
$r = mysql_query($sql);

$out = array();
while($tmp = mysql_fetch_assoc($r)){
  $out[$tmp['tovar_id']]['tovar_id'] 			= $tmp['tovar_id'];
  $out[$tmp['tovar_id']]['tovar_inet_id_parent'] 	= $tmp['tovar_inet_id_parent'];	
  $out[$tmp['tovar_id']]['tovar_name_1'] 		= $tmp['tovar_name_1'];
  $out[$tmp['tovar_id']]['ware'] 			= $tmp['ware'];
  $out[$tmp['tovar_id']]['price'] 			= $tmp['price'];
  $out[$tmp['tovar_id']]['price_rozn'] 			= $tmp['price_rozn'];
  $out[$tmp['tovar_id']]['price_zakup'] 		= $tmp['price_zakup'];
  $out[$tmp['tovar_id']]['tovar_artkl'] 	      	= $tmp['tovar_artkl'];
  $out[$tmp['tovar_id']]['tovar_memo'] 			= $tmp['tovar_memo'];
  $out[$tmp['tovar_id']]['tovar_dimension'] 	      	= $tmp['tovar_dimension'];
  $out[$tmp['tovar_id']]['dimension_name'] 	      	= $tmp['dimension_name'];
}

echo json_encode($out,true);


    
?>
