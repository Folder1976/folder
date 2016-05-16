<?php
include '../config/config.php';
//include '../simple_html_dom/simple_html_dom.php';


if(!isset($_POST['key'])){
    die('Нет ключа');
}else{
    $key = $_POST['key'];
}


if($key == 'add'){
    
    if($_POST['name'] == ''){
        return '';
    }
    
    $sql = 'SELECT seo_alias FROM tbl_seo_url WHERE seo_url = "parent='.$_POST['parent_id'].'";';
    $r = $folder->query($sql) or die('Ошибка - '.$sql);
    
    if($r->num_rows == 0){
        return '';
    }
    $tmp = $r->fetch_assoc();
    $parent_alias = $tmp['seo_alias'];
    
     
    $code = translitArtkl($_POST['name']);
    $code = str_replace(' ', '-', $code);
    $code = str_replace('+', '-', $code);
    $code = trim($code);
    $code = strtolower($code);
  
    $sql = 'INSERT INTO tbl_parent_inet SET
                    parent_inet_parent = "'.$_POST['parent_id'].'",
                    parent_inet_1 = "'.$_POST['name'].'",
                    parent_inet_type = "1",
                    parent_inet_view = "0",
                    attribute_group_id = "1"
            ';
    $folder->query($sql) or die('Ошибка - '.$sql);
 
    $id = $folder->insert_id;
 
    $return = array();
    
    $alias = $parent_alias.'/'.$code;
    
    $sql = 'INSERT INTO tbl_seo_url SET
                    seo_alias = "'.$alias.'",
                    seo_url = "parent='.$id.'",
                    seo_main = "0"
            ';
    $folder->query($sql) or die('Ошибка - '.$sql);
    
    
    $return['id'] = $id;
    $return['name'] = $_POST['name'];
    $return['code'] = $code;
    $return['alias'] = $alias;
    
    include_once("../../class/class_category.php");
    $Category = new Category($folder);
    $Category->reloadCategoryPatch();
    
   echo json_encode($return); 
}


function translitArtkl($str) {
    $rus = array('и','і','є','Є','ї','\"','\'','.',' ','А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
    $lat = array('u','i','e','E','i','','','','-','A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya');
   return str_replace($rus, $lat, $str);
}

?>