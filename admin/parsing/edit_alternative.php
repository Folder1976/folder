<?php
include '../config/config.php';
//include '../simple_html_dom/simple_html_dom.php';


if(!isset($_POST['key'])){
    die('Нет ключа');
}else{
    $key = $_POST['key'];
}

if($key == 'add_brand'){
    
    
    $sql = 'INSERT INTO tbl_brand_alternative SET
            brand_id = "'.$_POST['brand_id'].'",
            postav_id = "'.$_POST['postav_id'].'",
            brand_name = "'.$_POST['brand_name'].'"
            on duplicate key update
            brand_name = "'.$_POST['brand_name'].'"
            ;';
           
    $folder->query($sql) or die('Ошибка - '.$sql);
}

if($key == 'add_category'){
    
    
    $sql = 'INSERT INTO tbl_category_alternative SET
            category_id = "'.$_POST['category_id'].'",
            postav_id = "'.$_POST['postav_id'].'",
            breadcrumbs = "'.$_POST['breadcrumbs'].'";';
    //echo $sql;
    $folder->query($sql) or die('Ошибка - '.$sql);
}

?>