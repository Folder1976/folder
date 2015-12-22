<?php
    include_once ('../config/config.php');
    $uploaddir = UPLOAD_DIR;
    global $folder, $setup;
    
    if(!isset($_GET['tovar_id'])){
        echo 'no tovar id';
        die();
    }
  
    $tovar_id = $_GET['tovar_id'];
    
    include ("../../class/class_product.php");
    $Product = new Product($folder);
    include "../../class/class_alias.php";
    $Alias = new Alias($folder);
    include ("../class/class_product_edit.php");
    $ProductEdit = new ProductEdit($folder);
    include ("../class/class_brand.php");
    $Brand = new Brand($folder);
  
   
  
    $category_id = $Product->getCategoryID($tovar_id);
  
    $category_alias = $Alias->getCategoryAlias($category_id);
  
    $tovar_artkl = $ProductEdit->getProductArtkl($tovar_id);
  
    $brand = $Brand->getBrandCodeOnProductId($tovar_id);
  
    $alias = $category_alias.'/'.$brand.'/'.$tovar_artkl;
  
    str_replace('//','/',$alias);
  
    echo $alias;
    
?>
