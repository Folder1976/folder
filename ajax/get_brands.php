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
    $sql = "SELECT distinct B.brand_id,
                        brand_code,
                        brand_name,
                        CountryID,
                        CountryName
                     FROM `tbl_tovar` T
                     LEFT JOIN tbl_brand B ON T.brand_id = B.brand_id
                     LEFT JOIN tbl_country C ON C.CountryID = B.country_id
                     WHERE B.brand_id = (".(int)$_POST['brand'].");";
    $r = $folder->query($sql);
    //echo $sql;
    if($r->num_rows > 0){
        
        $brands = array();
        $countries = array();
        $return = array();
        while($tmp = $r->fetch_assoc()){
        
            $brands[$tmp['brand_id']]['code'] = $tmp['brand_code'];
            $brands[$tmp['brand_id']]['name'] = $tmp['brand_name'];
            $countries[$tmp['CountryID']] = $tmp['CountryName'];
        
        }
        
        $return['brands'] = $brands;
        $return['countries'] = $countries;
        
        echo json_encode($return);
    
        return false;
    }
}



if(isset($_SESSION['all_products_id'])){
    $sql = "SELECT distinct B.brand_id,
                        brand_code,
                        brand_name,
                        CountryID,
                        CountryName
                     FROM `tbl_tovar` T
                     LEFT JOIN tbl_brand B ON T.brand_id = B.brand_id
                     LEFT JOIN tbl_country C ON C.CountryID = B.country_id
                     WHERE `tovar_id` IN (".$_SESSION['all_products_id'].");";
    $r = $folder->query($sql);
    //echo $sql;
    if($r->num_rows > 0){
        
        $brands = array();
        $countries = array();
        $return = array();
        while($tmp = $r->fetch_assoc()){
        
            $brands[$tmp['brand_id']]['code'] = $tmp['brand_code'];
            $brands[$tmp['brand_id']]['name'] = $tmp['brand_name'];
            $countries[$tmp['CountryID']] = $tmp['CountryName'];
        
        }
        
        $return['brands'] = $brands;
        $return['countries'] = $countries;
        
        echo json_encode($return);
    
        return false;
    }
}
?>