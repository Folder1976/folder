<?php
include '../config/config.php';

$key = $_POST['key'];

$_POST['brand_code'] = str_replace('@*@', '&', $_POST['brand_code']);
$_POST['brand_name'] = str_replace('@*@', '&', $_POST['brand_name']);

//Если прилетел средний банер
if($key == 'edit'){
    
    $sql = 'UPDATE tbl_brand SET
            '.$_POST['fild'].' = \''.$_POST['value'].'\'
            WHERE brand_id = \''.$_POST['id'].'\'
            ';
    echo $sql;
    $folder->query($sql) or die($sql);

}elseif($key == 'dell'){
    
    $sql = 'DELETE FROM tbl_brand 
            WHERE brand_id = \''.$_POST['id'].'\'
            ';
    echo $sql;
    $folder->query($sql) or die($sql);
    
    
}elseif($key == 'add'){
  
    $sql = 'INSERT INTO tbl_brand SET
            brand_code = \''.$_POST['brand_code'].'\',
            brand_name = \''.$_POST['brand_name'].'\',
            country_id = \''.$_POST['country_id'].'\'
            ';
    echo $sql;
    $folder->query($sql) or die($sql);
    
}


?>