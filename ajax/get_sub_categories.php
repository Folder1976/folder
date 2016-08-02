<?php
include '../config/config.php';

global $folder;

include '../class/class_category.php';
$Category = new Category($folder);

include '../class/class_alias.php';
$Alias = new Alias($folder);


if(session_id()){
}else{
  session_start();
}

if(!isset($_SESSION[BASE.'userlevel'])) $_SESSION[BASE.'userlevel']	 = 10;

$return = array();

if(isset($_POST['brand']) AND $_POST['brand'] > 0){
    $sql = "SELECT distinct tovar_inet_id_parent AS categ_id
          FROM `tbl_tovar` WHERE `brand_id` = ".(int)$_POST['brand'] .";";
    $r = $folder->query($sql);
    
    if($r->num_rows > 0){
        
        while($tmp = $r->fetch_assoc()){

            $tmp = $Category->getCategoryInfo($tmp['categ_id'],'PRODUCTS_COUNT');
            
            if($_SESSION[BASE.'userlevel'] >= $tmp['level']){
                $return[$tmp['id']] = $tmp;
            }
        }
        echo json_encode($return);
    
        return false;
    }
}



if(isset($_SESSION['all_products_id'])){
    $sql = "SELECT distinct tovar_inet_id_parent AS categ_id FROM `tbl_tovar` WHERE `tovar_id` IN (".$_SESSION['all_products_id'].");";
    $r = $folder->query($sql);
    
    if($r->num_rows > 0){
        
        while($tmp = $r->fetch_assoc()){
        
            $tmp = $Category->getCategoryInfo($tmp['categ_id'],'PRODUCTS_COUNT');
            
            if($_SESSION[BASE.'userlevel'] >= $tmp['level']){
                $return[$tmp['id']] = $tmp;
            }
        
        }
        echo json_encode($return);
    
        return false;
    }
}
?>