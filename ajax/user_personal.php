<?php
include '../config/config.php';
if(session_id()){
}else{
  session_start();
}
global $folder;

$return = array();

$name = mysqli_real_escape_string($folder, $_POST['name']);
$email = mysqli_real_escape_string($folder, $_POST['email']);
$phone = mysqli_real_escape_string($folder, $_POST['phone']);
$id = mysqli_real_escape_string($folder, $_POST['id']);
$sity = mysqli_real_escape_string($folder, $_POST['sity']);
$address = mysqli_real_escape_string($folder, $_POST['address']);

if($email == ''){
    $return['msg'] = 'Укажите Ваш емаил!';
    $return['err'] = true;        
    echo json_encode($return);
    die();
}

    $sql = "SELECT `klienti_id` FROM `tbl_klienti` WHERE `klienti_email` = '".$email."' AND `klienti_id` <> '$id';";
    $r = $folder->query($sql);
    
    if($r->num_rows > 0){
        $return['msg'] = 'Этот емаил уже занят!';
        $return['err'] = true;
        echo json_encode($return);
    die();
    }

    $sql = "UPDATE `tbl_klienti`SET 
            `klienti_name_1` = '".$name."', 
            `klienti_phone_1` = '".$phone."',
            `klienti_email` = '".$email."',
            `klienti_ip` = '".$_SERVER['REMOTE_ADDR']."',
            `klienti_sity` = '".$sity."',
            `klienti_adress` = '".$address."'
            WHERE `klienti_id` = '$id';
        ";

        if($folder->query($sql)){
     
            //Нормальная регистрация
            $return['msg'] = 'Данные сохранил успешно!';
            $return['err'] = false;        
            echo json_encode($return);
            die();
        }
        
    $return['msg'] = 'Чтото пошло не так! Сохранить не получилось!';
    $return['err'] = true;        
    echo json_encode($return);
            
?>