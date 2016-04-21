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


if(isset($_POST['brand']) AND $_POST['brand'] > 0){
    $sql = "SELECT distinct attribute_value, C.id
                     FROM tbl_attribute_to_tovar A
                     LEFT JOIN tbl_colors C ON C.color = A.attribute_value
                        LEFT JOIN tbl_tovar T ON T.tovar_id = A.tovar_id
                    WHERE attribute_id = 2 AND T.brand_id = ".(int)$_POST['brand'] .";";
  
    $r = $folder->query($sql);
    
    if($r->num_rows > 0){
        
        $attr = array();
        while($tmp = $r->fetch_assoc()){
            
            if($tmp['id'] < 10){
                $tmp['id'] = '00'.$tmp['id'];
            }elseif($tmp['id'] < 100){
                $tmp['id'] = '0'.$tmp['id'];
            }
            
            $attr[$tmp['id']] = $tmp['attribute_value'];
        
        }
        
        echo json_encode($attr);
    
        return false;
    }
}






if(isset($_SESSION['all_products_id'])){
    $sql = "SELECT distinct attribute_value, C.id
                     FROM tbl_attribute_to_tovar A
                     LEFT JOIN tbl_colors C ON C.color = A.attribute_value
                    WHERE attribute_id = 2 AND `tovar_id` IN (".$_SESSION['all_products_id'].");";
    $r = $folder->query($sql);
    
    if($r->num_rows > 0){
        
        $attr = array();
        while($tmp = $r->fetch_assoc()){
            
            if($tmp['id'] < 10){
                $tmp['id'] = '00'.$tmp['id'];
            }elseif($tmp['id'] < 100){
                $tmp['id'] = '0'.$tmp['id'];
            }
            
            $attr[$tmp['id']] = $tmp['attribute_value'];
        
        }
        
        echo json_encode($attr);
    
        return false;
    }
}
?>