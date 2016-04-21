<?php
include '../config/config.php';

if(!isset($_POST['key'])){
    die('Нет ключа');
}else{
    $key = $_POST['key'];
}


if($key == 'edit'){
    
    $sql = "DELETE FROM `tbl_attribute_to_tovar` WHERE `tovar_id`='".$_POST['product_id']."' AND attribute_id = '".$_POST['id']."'";
    //$sql = "DELETE FROM `tbl_attribute_to_tovar` WHERE `tovar_id`='".$_POST['product_id']."'";
    $folder->query($sql);
    
    $sql = "INSERT INTO tbl_attribute_to_tovar SET
                attribute_id = '".$_POST['id']."',
                attribute_value = '".$_POST['value']."',
                tovar_id = '".$_POST['product_id']."';";
	$folder->query($sql);	  
  echo $sql;  
    echo 'ajax_edit_attribute.php сохранил';
}


function translitArtkl($str) {
    $rus = array('и','і','є','Є','ї','\"','\'','.',' ','А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
    $lat = array('u','i','e','E','i','','','','-','A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya');
   return str_replace($rus, $lat, $str);
}

?>