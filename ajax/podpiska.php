<?php
header ('Content-Type: text/html; charset=utf8');
include '../config/config.php';

global $folder;

$return = array();

if(isset($_GET['email']) AND $_GET['email'] != '' AND strpos($_GET['email'],'@') !== false){

    $sql = 'INSERT INTO tbl_emails SET email = \''.mysqli_real_escape_string($folder, $_GET['email']).'\';';
    $folder->query($sql);

    $return['title'] = 'OK';
    $return['msg'] = 'Емаил успешно добавлен.';
    
}else{
    
    $return['title'] = 'Ошибка';
    $return['msg'] = 'Что-то не так с емайлом.';
        
}

echo json_encode($return);
            
?>