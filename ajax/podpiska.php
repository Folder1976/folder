<?php
header ('Content-Type: text/html; charset=utf8');
include '../config/config.php';

global $folder;

$return = array();

if(isset($_GET['email']) AND $_GET['email'] != '' AND strpos($_GET['email'],'@') !== false){
    
    
    if(isset($_GET['key']) AND $_GET['key'] == 'tovar'){
        
        $_GET['product_artkl'] = str_replace('***', '#', $_GET['product_artkl']);
        
        $sql = 'INSERT INTO tbl_tovar_wait SET email = \''.mysqli_real_escape_string($folder, $_GET['email']).'\',
                                                tovar_artkl = "'.mysqli_real_escape_string($folder, $_GET['product_artkl']).'";';
        $folder->query($sql);
    
        $return['title'] = 'OK';
        $return['msg'] = 'Памятка создана.';
    }else{
        $sql = 'INSERT INTO tbl_emails SET email = \''.mysqli_real_escape_string($folder, $_GET['email']).'\';';
        $folder->query($sql);
    
        $return['title'] = 'OK';
        $return['msg'] = 'Емаил успешно добавлен.';
    }
    
}else{
    
    $return['title'] = 'Ошибка';
    $return['msg'] = 'Что-то не так с емайлом.';
        
}

echo json_encode($return);
            
?>