<?php
    //include_once ('../config/config.php');
    //$uploaddir = UPLOAD_DIR;
    global $folder, $setup;
  
    //$tovar_id = $_GET['tovar_id'];
    
    include ("../class/class_product.php");
    $Product = new Product($folder);
    include "../class/class_alias.php";
    $Alias = new Alias($folder);
    //include ("class/class_product_edit.php");
    //$ProductEdit = new ProductEdit($folder);
    //include ("class/class_brand.php");
    //$Brand = new Brand($folder);
    include ("../class/class_category.php");
    $Category= new Category($folder);
  
  
  ?>
  <h1>Модуль востановления алиасов</h1>
  
  <ul>
    <li><a href="main.php?func=restore_alias&restore_parent_null">Востановить алиасы категорий (только у которых нет)</a></li>
    <li><a href="main.php?func=restore_alias&restore_parent_all">Востановить алиасы категорий (ВСЕ)</a></li>
    <li>&nbsp;</li>
    <li><a href="main.php?func=restore_alias&restore_tovar_null">Востановить алиасы товаров (только у которых нет)</a></li>
    <li><a href="main.php?func=restore_alias&restore_tovar_all">Востановить алиасы товаров (ВСЕ)</a></li>
  </ul>
  
  <?php 
  $count = 0;
  if(isset($_GET['restore_parent_null']) OR isset($_GET['restore_parent_all'])){
        $categorys = array();
        
        if(isset($_GET['restore_parent_all'])){
            $categorys = $Category->getAllCategoryId();
        }
        
        if(isset($_GET['restore_parent_null'])){
            $categorys = $Category->getNoAliasCategoryId();
        }
   
        foreach($categorys as $id => $value){
          
          $Alias->updateCategoryAlias($id);
          
          $count++;
          
        }
   
  }
  
  if(isset($_GET['restore_tovar_null']) OR isset($_GET['restore_tovar_all'])){
     
      $products_id = array();
        
        if(isset($_GET['restore_tovar_all'])){
            $products_id = $Product->getAllProductsId();
        }
        
        if(isset($_GET['restore_tovar_null'])){
            $products_id = $Product->getAllNoAliasProductsId();
        }
   
        foreach($products_id as $id => $value){
 
          $Alias->updateProductAlias($id);
          
          $count++;
          
        }
    
  }
  echo '<h2>Обновил - '.$count.'.</h2>';
  /*
    $category_id = $Product->getCategoryID($tovar_id);
  
    $category_alias = $Alias->getCategoryAlias($category_id);
  
    $tovar_artkl = $ProductEdit->getProductArtkl($tovar_id);
  
    $brand = $Brand->getBrandCodeOnProductId($tovar_id);
  
    $alias = $category_alias.'/'.$brand.'/'.$tovar_artkl;
  
    str_replace('//','/',$alias);
  
    echo $alias;
    */

?>
