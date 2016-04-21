<?php
echo '<h1>Починяем линки фото к товарам</h1>';
set_time_limit(0);
echo '<h3><a href=\'/admin/setup.php\'>>> Настройки</a></h3>';


include_once('class/class_product_edit.php');
$ProductEdit = new ProductEdit($folder);


$sql = 'SELECT tovar_id FROM tbl_tovar;';
$r = $folder->query($sql) or die($sql);

$artkls = array();

while($tovar = $r->fetch_assoc()){
    
    $tmp = $ProductEdit->getProductArtkl($tovar['tovar_id']);
    $artkls[$tmp] = $tmp;

}

echo 'У этих артиклей нет фото!<br>';
foreach($artkls as $art){
    
    if(file_exists(UPLOAD_DIR.$art)){
        if(file_exists(UPLOAD_DIR.$art.'/'.$art.'.0.small.jpg')){
            //echo '<br><font color="green">' .UPLOAD_DIR. $art.'/'.$art.'.0.small.jpg'.'</font>';
            
            $sql = 'INSERT INTO tbl_tovar_pic SET
                        tovar_artkl = "'.$art.'",
                        pic_name = "'.$art.'/'.$art.'.0.small.jpg'.'"
                        on duplicate key update
                        pic_name = "'.$art.'/'.$art.'.0.small.jpg'.'";';
            $folder->query($sql) or die($sql);
            
        }else{
            echo '<br><font color="red">' . $art.'</font>';
        }
    }else{
        echo '<br><font color="red">' . $art.'</font>';
    }
    
}


?>