<?php
	include_once ('../config/config.php');
	
	$tovar_id = '';
	if(isset($_POST['tovar_id'])) $tovar_id = $_POST['tovar_id'];
	
	$postav_id = '';
	if(isset($_POST['postav_id'])) $postav_id = $_POST['postav_id'];
	
	$zakup = '';
	if(isset($_POST['zakup'])) $zakup = $_POST['zakup'];
	
	$price = '';
	if(isset($_POST['price'])) $price = $_POST['price'];
	
	$days = '';
	if(isset($_POST['days'])) $days = $_POST['days'];
	
	$operation = '';
	if(isset($_POST['operation'])) $operation = $_POST['operation'];
	
	$sql = 'SELECT klienti_name_1 AS klienti_name FROM tbl_klienti WHERE klienti_id = "'.$postav_id.'" LIMIT 0,1;';
	$r = $folder->query($sql) or die('add_product_to_order - ' . $sql);
	$postav = $r->fetch_assoc();
	
	
    $sql = "INSERT INTO tbl_operation_detail SET
				`operation_detail_operation` = '".$operation."',
				`operation_detail_tovar` = '".$tovar_id."',
				`operation_detail_item` = '1',
				`operation_detail_price` = '".$price."',
				`operation_detail_zakup` = '".$zakup."',
				`operation_detail_discount` = '0',
				`operation_detail_summ` = '".$price."',
				`operation_detail_memo` = 'Доставка: ".$days." дн., Поставщик: ".$postav['klienti_name']."',
				`operation_detail_from` = '1',
				`operation_detail_to` = '1',
				`operation_detail_dell` = '0',
				`delivery_days` = '".$days."',
				`product_postav_id` = '".$postav_id."'";
               
	$folder->query($sql) or die('add_product_to_order - ' . $sql);
	
	$sql = '
			UPDATE tbl_operation SET operation_summ = 
			(SELECT sum(operation_detail_summ) as total FROM tbl_operation_detail WHERE
				`operation_detail_operation` = "'.$operation.'" AND `operation_detail_dell` = "0")
			WHERE operation_id = "'.$operation.'";';
	$folder->query($sql) or die('add_product_to_order - ' . $sql);
	
?>


