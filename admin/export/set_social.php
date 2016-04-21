<?php

include '../config/config.php';

global $folder;


if(!isset($_POST['id'])){
    echo 'жопа';
    die();
}

$value = 0;
if(isset($_POST['value'])) $value = $_POST['value'];

$id = $_POST['id'];


$tmp = explode('*', $id);

if(isset($tmp[0]) AND isset($tmp[1]) AND is_numeric($tmp[1])){
    
    $sql = 'UPDATE tbl_tovar SET ' . $tmp[0] . '=' . $value . ' WHERE tovar_id = \''.$tmp[1].'\';';
    //echo $sql;
    
    $folder->query($sql) or die('error update_social ' .$sql);
    return true;
}

echo 'прилетело чтото не понятное';
die();

?>