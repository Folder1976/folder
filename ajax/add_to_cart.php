<?php

include '../config/config.php';
if(session_id()){
}else{
  session_start();
}
global $folder;

include_once '../class/class_user.php';
$User = new User($folder);

$user_key = $User->getActiveUserKey();

$price      = mysqli_real_escape_string($folder, $_POST['price']);
$delive_days = mysqli_real_escape_string($folder, $_POST['delive_days']);
$items      = mysqli_real_escape_string($folder, $_POST['items']);
$postav_id      = mysqli_real_escape_string($folder, $_POST['postav_id']);
$prod_id    = mysqli_real_escape_string($folder, $_POST['prod_id']);


//Проверим на ошибки то что прилетело
if(!is_numeric($items) OR !is_numeric($price) OR !is_numeric($delive_days) OR !is_numeric($prod_id)){
    $return['msg'] = 'Чтото пошло не так!';
    $return['err'] = true;        
    echo json_encode($return);
    die();
}

//Проверим нет ли этого товара уже в корзине
$sql = 'SELECT order_id, order_item FROM tbl_orders WHERE
            order_customer = \''.$user_key.'\' AND
            order_product_id = \''.$prod_id.'\' AND
            product_price = \''.$price.'\' AND
            product_postav_id = \''.$postav_id.'\' AND
            delivery_days = \''.$delive_days.'\';';
$r = $folder->query($sql) or die('error add_to_cart ' .$sql);

//Если товар есть - Обновим поличество, если нет то добавим
if($r->num_rows > 0){
    $row = $r->fetch_assoc();
    $sql = 'UPDATE tbl_orders SET
            order_item = \''.((int)$items + (int)$row['order_item']).'\'
            WHERE
            order_id = \''.$row['order_id'].'\';';
}else{
    $sql = 'INSERT INTO tbl_orders SET
            order_customer = \''.$user_key.'\',
            order_product_id = \''.$prod_id.'\',
            order_item = \''.$items.'\',
            product_price = \''.$price.'\',
            product_postav_id = \''.$postav_id.'\',
            delivery_days = \''.$delive_days.'\'
            ';
}
$r = $folder->query($sql) or die('error add_to_cart ' .$sql);


//Получим сумму заказа по этому пользователю.
$sql = 'SELECT order_item, product_price FROM tbl_orders WHERE order_customer = \''.$user_key.'\';';
$r = $folder->query($sql) or die('error add_to_cart');

$summ = 0;
if($r->num_rows > 0){
    while($row = $r->fetch_assoc()){
        $summ += ((int)$row['order_item'] * (float)$row['product_price']);
    }
}


    $return['msg'] = 'Товар добавлен в корзину';
    $return['summ'] = $summ;
    $return['err'] = false;        
    echo json_encode($return);
            
?>