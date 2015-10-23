<?php
//echo "Import ogders \n TURN OFF";
//exit();
connect_to_mysql();
include 'init.lib.php';
session_start();
if (!session_verify($_SERVER["PHP_SELF"])){
  exit();
}


header ('Content-Type: text/html; charset=utf8');
echo "<header><link rel='stylesheet' type='text/css' href='sturm.css'></header>";

/*$tmp_sql= mysql_query("SET NAMES utf8");		
$tmp_sql = mysql_query("SELECT `operation_status_to_as_new`,`operation_status_from_as_new` FROM `tbl_operation_status` WHERE `operation_status_id`='15'");
$from = mysql_result($tmp_sql,0,"operation_status_from_as_new");
$to = mysql_result($tmp_sql,0,"operation_status_to_as_new");
$tmp = file_get_contents("http://sturm.com.ua/get_orders.php?pass=KLJGbsfgv8y9JKbhlis&orders_status=1");
$tmpmas = explode("||",$tmp);
//echo "start";  

$user_id=-1;
$count=0;
while ($tmpmas[$count]){
    $tmp_orders = explode("*",$tmpmas[$count]);
 
 		$orders_id=$tmp_orders[0];
		$orders_user_id=$tmp_orders[1];
		$orders_datatime=$tmp_orders[2];
		$orders_sum=$tmp_orders[3];
		$orders_status=$tmp_orders[4];
		$orders_items_id=$tmp_orders[5];
		$orders_delivery_id=$tmp_orders[6];
		$orders_paid=$tmp_orders[7];
		$orders_comment=$tmp_orders[8];
		$products_barcode=$tmp_orders[9];
		$user_email=$tmp_orders[10];
		$user_last_name=$tmp_orders[11];
		$user_name=$tmp_orders[12];
		$user_patronymic=$tmp_orders[13];
		$user_country=$tmp_orders[14];
		$user_city=$tmp_orders[15];
		$user_street=$tmp_orders[16];
		$user_house_number=$tmp_orders[17];
		$user_room_number=$tmp_orders[18];
		$user_floor_number=$tmp_orders[19];
		$user_security_code=$tmp_orders[20];
		$user_phone=$tmp_orders[21];
		$user_mobile=$tmp_orders[22];
		$user_products_price_groups_id=$tmp_orders[23];
		$orders_items_product_count=$tmp_orders[24];
		$orders_items_product_price=$tmp_orders[25];
*/
$nakl= mysql_query("SET NAMES utf8");		
$nakl = mysql_query("SELECT `operation_id` FROM `tbl_operation` WHERE `operation_dell`='0' and`operation_inet_id`='".$orders_id."'");
if (!mysql_result($nakl,0,"operation_id")){ //if not operation - get user ID or create new user adn create operation.
  
  $user = mysql_query("SET NAMES utf8");
 $user = mysql_query("SELECT `klienti_id` FROM `tbl_klienti` WHERE `klienti_inet_id`='".$orders_user_id."'");
 //echo "<br>find - ",mysql_result($user,0,"klienti_id");

 if (!mysql_result($user,0,"klienti_id")){ // if noew user - add him to base
 //echo "addnew";
      $sql_str = "
	INSERT INTO `tbl_klienti` (
	`klienti_group`,
	`klienti_name_1`,
	`klienti_name_2`,
	`klienti_name_3`,
	`klienti_pass`,
	`klienti_adress`,
	`klienti_sity`,
	`klienti_region`,
	`klienti_index`,
	`klienti_country`,
	`klienti_phone_1`,
	`klienti_phone_2`,
	`klienti_phone_3`,
	`klienti_email`,
	`klienti_memo`,
	`klienti_edit`,
	`klienti_inet_id`,
	`klienti_delivery_id`,
	`klienti_spam`,
	`klienti_price`,
	`klienti_discount`
	)VALUES(
	'3',
	'".$user_last_name." ".$user_name." ".$user_patronymic."',
	'".$user_last_name." ".$user_name." ".$user_patronymic."',
	'".$user_last_name." ".$user_name." ".$user_patronymic."',
	'',
	'".$user_street." ".$user_house_number." / ".$user_room_number."',
	'".$user_city."',
	'',
	'',
	'".$user_country."',
	'".$user_mobile."',
	'".$user_phone."',
	'',
	'".$user_email."',	
	'".date("Y-m-d G:i:s")."',
	'',
	'".$orders_user_id."',
	'".$orders_delivery_id."',
	'ALL;',
	'2',
	'0'
	)";
	$insert = mysql_query("SET NAMES utf8");
	$insert = mysql_query($sql_str);
	$user_id = mysql_insert_id();
 }else{
 //echo " nofind";
	$user_id = mysql_result($user,0,"klienti_id");
 }
   $sql_str="
    INSERT INTO `tbl_operation`
   (
   `operation_data`,
   `operation_klient`,
   `operation_prodavec`,
   `operation_sotrudnik`,
   `operation_data_edit`,
   `operation_status`,
   `operation_summ`,
   `operation_memo`,
   `operation_inet_id`,
   `operation_dell`
   )VALUES(
   '".date("Y-m-d G:i:s")."',
   '".$user_id."',
   '1',
   '1',
   '".date("Y-m-d G:i:s")."',
   '15',
   '0',
   '".$orders_comment."',
   '".$orders_id."',
   '0'
   )
   
   ";
   $tmp_sql= mysql_query("SET NAMES utf8");	
   $tmp_sql = mysql_query($sql_str);
   $operation_id = mysql_insert_id();
 }else{
 $operation_id = mysql_result($nakl,0,"operation_id");
 }
 
// fund position kode and price and add to nakl============================================================================
 $sql_str= "SELECT `tovar_id`,`price_tovar_2` FROM `tbl_tovar`,`tbl_price_tovar` WHERE `tovar_id`=`price_tovar_id` and `tovar_barcode`='".$products_barcode."'";
 $tmp_sql = mysql_query($sql_str);
//echo "<br>",$products_barcode," - ",mysql_result($tmp_sql,0,"tovar_id");
 if (!mysql_result($tmp_sql,0,"tovar_id")){
  $tovar_id = 15837;
  $tovar_price = 0;
 }else{
  $tovar_id = mysql_result($tmp_sql,0,"tovar_id");
  $tovar_price = mysql_result($tmp_sql,0,"price_tovar_2");
 }

 $sql_str= "INSERT INTO `tbl_operation_detail`
 (
 `operation_detail_operation`,
 `operation_detail_tovar`,
 `operation_detail_item`,
 `operation_detail_price`,
 `operation_detail_discount`,
 `operation_detail_summ`,
 `operation_detail_memo`,
 `operation_detail_from`,
 `operation_detail_to`,
 `operation_detail_dell`
 )VALUES(
 '".$operation_id."',
 '".$tovar_id."',
 '".$orders_items_product_count."',
 '".$tovar_price."',
 '0',
 '".$tovar_price*$orders_items_product_count."',
 '',
 '".$from."',
 '".$to."',
 '0'
 )
 ";
 $tmp_sql = mysql_query($sql_str);
  
 // Reset new summ ======================================================================================================
$nakl= mysql_query("SET NAMES utf8");		
$nakl = mysql_query("SELECT SUM(`operation_detail_summ`) FROM `tbl_operation_detail` WHERE `operation_detail_dell`='0' and `operation_detail_operation`='".$operation_id."'");
$operation_summ = mysql_result($nakl,0,0);
	  $sql_str= "UPDATE `tbl_operation`
	  SET `operation_summ`='".$operation_summ."'
	  WHERE `operation_id`='".$operation_id."'";
$tmp_sql = mysql_query($sql_str);
 //echo $products_barcode,"<br>", $tovar_id, " " , $tovar_price;
$count++;
}

  $nakl = mysql_query("SELECT `operation_inet_id` FROM `tbl_operation` WHERE `operation_dell`='0' and `operation_status`='15'");
 
 $count=0;
  while($count < mysql_num_rows($nakl)){
        $orders_tmp = mysql_result($nakl,$count,"operation_inet_id");
	$tmp2 = file_get_contents("http://sturm.com.ua/set_status_inet.php?pass=KLJGbsfgv8y9JKbhlis&orders_id=".$orders_tmp."&status=15");
  //echo $orders_tmp,"<br>";
  $count++;
  }
  
 if ($count>0){
    echo "<b><a href='operation_list.php?iStatus=15' target='_blank'>";
    echo "<b>New orders:", $count,"</a>";
  }else{
    echo "<b>NO ORDERS";
  }

  echo "<br><a href='get_orders_from_inet.php'>Reload</a>";

//header ('Refresh: 300; url=get_orders_from_inet.php');
?>
