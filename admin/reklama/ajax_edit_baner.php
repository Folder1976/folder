<?php
include '../config/config.php';

$type = $_POST['type'];

//Если прилетел средний банер
if($type == 'medium'){
    
    $sql = 'UPDATE tbl_baner SET
            baner_name = \''.$_POST['name'].'\',
            baner_url = \''.$_POST['url'].'\',
            is_view = \''.$_POST['view'].'\',
            baner_place = \''.trim($_POST['place'], '*').'\'
            
            WHERE baner_id = \''.trim($_POST['id'], '*').'\'
            ';
    //echo $sql;
    $folder->query($sql) or die($sql);

}elseif($type == 'large'){
    
    $sql = 'UPDATE tbl_baner SET
            baner_name = \''.$_POST['name'].'\',
            baner_url = \''.$_POST['burl'].'\',
            baner_header = \''.$_POST['header'].'\',
            baner_title = \''.$_POST['title'].'\',
            baner_price = \''.$_POST['price'].'\',
            baner_slogan = \''.$_POST['slogan'].'\',
            is_view = \''.$_POST['view'].'\'
            
            WHERE baner_id = \''.trim($_POST['id'], '*').'\'
            ';
    echo $sql;
    $folder->query($sql) or die($sql);
    
    
}


?>