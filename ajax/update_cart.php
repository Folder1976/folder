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

$line   = (int)mysqli_real_escape_string($folder, $_POST['line']);
$val    = (int)mysqli_real_escape_string($folder, $_POST['val']);

//Проверим на ошибки то что прилетело
if(!is_numeric($line) OR !is_numeric($val)){
    $return['msg'] = 'Прилетели не номера!';
    $return['err'] = true;        
    echo json_encode($return);
    die();
}

//Проверим нет ли этого товара уже в корзине
$sql = 'SELECT order_customer, product_price FROM tbl_orders WHERE order_id = \''.$line.'\';';
$r = $folder->query($sql) or die('error update_cart ' .$sql);

//Если товар есть - Обновим поличество, если нет то добавим
if($r->num_rows > 0){
    $row = $r->fetch_assoc();
    $user_key = $row['order_customer'];
    $price = $row['product_price'];
    
    if($val > 0){

        $sql = 'UPDATE tbl_orders SET order_item = \''.(int)$val.'\'
            WHERE order_id = \''.$line.'\';';

    }else{

        $sql = 'DELETE FROM tbl_orders
            WHERE
            order_id = \''.$line.'\';';

    }
    //echo $sql;
    $r = $folder->query($sql) or die('error update_cart ' .$sql);
    
}else{
    $return['msg'] = 'Корзины нет!';
    $return['err'] = true;        
    echo json_encode($return);
    die();
}

//Получим сумму заказа по этому пользователю.
$sql = 'SELECT order_item, product_price FROM tbl_orders WHERE order_customer = \''.$user_key.'\';';
$r = $folder->query($sql) or die('error add_to_cart');

$summ = 0;
if($r->num_rows > 0){
    while($row = $r->fetch_assoc()){
        $summ += ((int)$row['order_item'] * (float)$row['product_price']);
    }
}


    $return['msg'] = 'Количество изменено';
    $return['total'] = $val * $price;
    $return['summ'] = $summ;
    $return['err'] = false;        
    echo json_encode($return);
            
?>