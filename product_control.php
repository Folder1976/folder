<?php

//Получаем список товаров для вывода
function user_item_view($tovar_id){
   global $setup;
   global $Alias;
   global $Attribute;
   global $Product;
   global $folder;
   global $currency_name;
   
   //Разделитель артикула на Артикул и размер
   $separator = $setup['tovar artikl-size sep'];
   
   //Разделитель Названия и Коментария в названии товара
   $separator_comment = $setup['tovar name sep'];
   
   //Глобальный фильтр по атрибутам
   $attribute_filter = array();
   
   //Получим товары по ID
   $sql = 'SELECT tovar_artkl FROM tbl_tovar WHERE tovar_id = \''.$tovar_id.'\'';
   $tovars = $folder->query($sql);
   
   if($tovars->num_rows == 0){
      echo 'Товара не найдено';
      die();
   }
   
   //Возьмем артикл и запросим все товары по этому артиклу
   $tmp = $tovars->fetch_assoc();
   $artkl = $tmp['tovar_artkl'];
   if(strpos($tmp['tovar_artkl'],$separator) !== false){
            $x = explode($separator, $tmp['tovar_artkl']);
            $artkl = $x[0];
            $size = $x[1];
   }
  
      
   //Теперь возмем все товары под этим артикулом
   //Получим товары по артиклу
   $sql = 'SELECT tovar_id, tovar_artkl, tovar_name_1 as tovar_name, price_tovar_2 as price2, price_tovar_curr_2 as curr2 FROM tbl_tovar
         LEFT JOIN tbl_price_tovar ON tovar_id = price_tovar_id
         WHERE tovar_artkl = \''.$artkl.'\' OR tovar_artkl LIKE \''.$artkl.$separator.'%\';';
  //echo $sql;
   $tovars = $folder->query($sql);
   unset($artkl);
   unset($size);
 
   //Загоняем товары в массив
   $product = array();
   while($tmp = $tovars->fetch_assoc()){
    
        //Разбиваем атрикл на тело и размер
        $artkl = $tmp['tovar_artkl'];
        $size = "нет";
        if(strpos($tmp['tovar_artkl'],$separator) !== false){
            $x = explode($separator, $tmp['tovar_artkl']);
            $artkl = $x[0];
            $size = $x[1];
        }
      
         //Берем аттрибуты товара и сверяем попадает он под фильтр ($no_filter == false)
         $attributes = $Attribute->getAttributesOnTovarID($tmp['tovar_id']);

         if($attributes){
            foreach($attributes as $index => $value){
                  if($value['filter'] == 1){
                     $attribute_filter[$index]['title'] = $value['attribute_name'];
                     $attribute_filter[$index]['value'][$value['attribute_value']] = $value['attribute_value'];
                  }
                  if($value['char'] == 1){
                     $product['attributes'][$index]['name'] = $value['attribute_name'];
                     $product['attributes'][$index]['value'] = $value['attribute_value'];
                  }
            }
         }
         
         //Массив пишем по ключу Артикл
         $product['artkl'] = $artkl;
         $product['name'] = $tmp['tovar_name'];
         $product['id'] = $tmp['tovar_id'];
         $product['alias'] = $Alias->getProductAlias($tmp['tovar_id']);
         //$product['img'] = $Product->getProductPicOnArtkl($artkl);
         $product['memo'] = $Product->getProductMemo($tmp['tovar_id']);
         $product['size'][$size]['id'] = $tmp['tovar_id'];
         $product['size'][$size]['size'] = $size;
         $product['size'][$size]['price'] = $Product->getProductPrice($tmp['tovar_id']);
         $product['size'][$size]['curr'] = $tmp['curr2'];
         $product['size'][$size]['items'] = tovar_on_ware($tmp['tovar_id']);
         
         if(isset($products[$artkl]['total'])){
            $product[$artkl]['total'] += tovar_on_ware($tmp['tovar_id']);
         }else{
            $product[$artkl]['total'] = tovar_on_ware($tmp['tovar_id']);
         }
      
        
      
      }
      
      //Загружаем фотки
      if(isset($product) AND count($product) > 0){
         $product['photos'] = getPhotos($artkl,$product['name']);
      }
      
    //Берем темплейт и возвращаем его 
    ob_start();
      require('template/product_form_001.tpl');
    $sResult = ob_get_contents();
    ob_end_clean();
    return $sResult;
 }
 
 function getPhotos($artkl, $name){
   $photo = '';
   $no_photo = '<img src="'.HOST_URL.'/resources/img/no_photo.png" width = "300">';
   $directory = "resources/products/".$artkl.""; //название папки с изображениями

	    $allowed_types=array('jpg','jpeg','gif','png'); //типы изображений
	    $file_parts=array();
	    $ext='';
	    $i=0;
	 if(@opendir($directory)!=false) 
	 {
	 
	      $dir_handle = @opendir($directory) or exit();//die("There is an error with your image directory!");
	 
	      while ($file = readdir($dir_handle)) //поиск
	      {
	        if($file=='.' || $file == '..' || strpos($file,'large') !== false || strpos($file,'small') !== false) continue; //пропустить ссылки на другие папки
	        $file_parts = explode('.',$file); //разделение имени файла и размещение его в массиве
	        $ext = strtolower(array_pop($file_parts)); //расширение
                  
               
               
          	if(in_array($ext,$allowed_types))
	        {
	                  $photo .= '<a class="example-image-link"
                                 href="'.HOST_URL.'/'.$directory."/".str_replace("medium","large",$file).'" data-lightbox="example-set" data-title="'.$name.'">
                                 <img class="example-image" src="'.HOST_URL.'/'.$directory."/".$file.'" alt="'.$name.'"></a>';
	            $i++;
	        }
	    }
            
	    closedir($dir_handle); //закрыть папку
	    
	    $photo_all =  $photo.'<div class="clear"></div>';
	 }
         
         //Если в массив не влетело ни одно фото
         if($i == 0) $photo_all = $no_photo.'<div class="clear"></div>';
         
         return $photo_all;
 }
?>