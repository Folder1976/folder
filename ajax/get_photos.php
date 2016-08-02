<?php

include '../config/config.php';



global $folder;

include '../admin/class/class_product_edit.php';
$ProductEdit = new ProductEdit($folder);


$key = 0;
if(isset($_POST['key'])) $key = $_POST['key'];

$image = 0;
if(isset($_POST['image'])) $image = $_POST['image'];

$id = 0;
if(isset($_POST['id']) AND is_numeric($_POST['id'])) $id = (int)$_POST['id'];


    if($id > 0){
       
       if($key == 'get'){
            //Поулчим артикул
            $art = $ProductEdit->getProductArtkl($id);
         
            //Выгрузим все его фото
            echo $ProductEdit->getProductPicOnArtkl($art);
              
              //getProductBrotherOnArtikl($product_artkl)
              //getProductPicOnArtkl($artkl)
              
       }elseif($key == 'set'){
            
            $art = $ProductEdit->getProductArtkl($id);
            
            $sql = 'UPDATE tbl_tovar_pic SET pic_name = "'.$image.'"
                        WHERE tovar_artkl = "'.$art.'";';
            $folder->query($sql);
        
            $return = array();
            $return['image'] = $image;
            $return['target'] = $art;
            
            echo json_encode($return);
            
        
       }
       
       
       
    }


?>