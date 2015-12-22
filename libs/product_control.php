<?php

//Получаем список товаров для вывода
function user_item_view($tovar_id){
   global $setup;
   global $Alias;
   global $Attribute;
   global $Product;
   global $Category;
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
   
   $min_price = array();
   $min_price['price'] = 100000;
   
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
   $sql = 'SELECT T.tovar_id,
                  T.tovar_artkl,
                  T.tovar_name_1 as tovar_name,
                  P.price_tovar_2 as price2,
                  P.price_tovar_curr_2 as curr2,
                  T.tovar_size_table,
                  T.tovar_video_url,
                  T.tovar_inet_id_parent,
                  T.on_ware,
                  B.brand_code,
                  B.brand_name
            FROM tbl_tovar T
            LEFT JOIN tbl_price_tovar P ON T.tovar_id = P.price_tovar_id
            LEFT JOIN tbl_brand B ON B.brand_id = T.brand_id
            WHERE T.tovar_artkl = \''.$artkl.'\' OR T.tovar_artkl LIKE \''.$artkl.$separator.'%\';';
  //echo $sql;
   $tovars = $folder->query($sql);
   unset($artkl);
   unset($size);
 
   //Загоняем товары в массив
   $product = array();
   while($tmp = $tovars->fetch_assoc()){
    
        //Разбиваем атрикл на тело и размер
        $artkl = $tmp['tovar_artkl'];
         $size = 'none';
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
         $product['category_id'] = $tmp['tovar_inet_id_parent'];
         $product['artkl'] = $artkl;
         $product['name'] = $tmp['tovar_name'];
         $product['brand_code'] = $tmp['brand_code'];
         $product['brand_name'] = $tmp['brand_name'];
         $product['id'] = $tmp['tovar_id'];
         $product['alias'] = $Alias->getProductAlias($tmp['tovar_id']);
         //$product['img'] = $Product->getProductPicOnArtkl($artkl);
        
         $product['size'][$size]['id'] = $tmp['tovar_id'];
         $product['size'][$size]['size'] = $size;
         $product['size'][$size]['price'] = $Product->getProductPrice($tmp['tovar_id']);
         $product['size'][$size]['deliv'] = $Product->getProductDelivInfo($tmp['tovar_id']);
         $product['size'][$size]['curr'] = $tmp['curr2'];
         
         if(isset($product['size'][$size]['deliv']) AND $product['size'][$size]['deliv']){
            foreach($product['size'][$size]['deliv'] as $tmp1)
            if($min_price['price'] > $tmp1['price_1']){
                  $min_price['price']        = $tmp1['price_1'];
                  $min_price['delive_days']  = $tmp1['delivery_days'];
                  $min_price['postav_id']    = $tmp1['postav_id'];
            }
         }
         
         if($tmp['on_ware'] == 0){
            $product[$artkl]['total'] = 0;
            $product['size'][$size]['items'] = 0;
         }elseif($tmp['on_ware'] == 1){
            $product[$artkl]['total'] = 100;
            $product['size'][$size]['items'] = 100;
         }else{
            if(isset($products[$artkl]['total'])){
               $product['size'][$size]['items'] = $Product->getProductOnWare($tmp['tovar_id']);
               
               $product[$artkl]['total'] += $Product->getProductOnWare($tmp['tovar_id']);
            }else{
               $product[$artkl]['total'] = $Product->getProductOnWare($tmp['tovar_id']);
            }
         }
      
         //Видео, описание и таблица размеров - если не пусто - заполним значение
         if($tmp['tovar_size_table'] != ''){
            $product['tovar_size_table'] = $tmp['tovar_size_table'];
         }
         
         if($tmp['tovar_video_url'] != ''){
            $product['tovar_video_url'] = $tmp['tovar_video_url'];
         }
         
         $tmp_memo = $Product->getProductMemo($tmp['tovar_id']);
         if($tmp_memo != ''){
            $product['memo'] = $tmp_memo;
         }
        
      
      }
      
      //Загружаем фотки
      if(isset($product) AND count($product) > 0){
         $product['photos'] = getPhotos($artkl,$product['name']);
      }
      
   
   $product['breadcrumb'] = $Category->getCategoryBreadcrumb($product['category_id']); 
   $product['min_price'] = $min_price;
   
   return $product;  
    //Берем темплейт и возвращаем его 
    /*
    ob_start();
      require('template/product_form_001.tpl');
    $sResult = ob_get_contents();
    ob_end_clean();
    return $sResult;
   */
 }
 
 function getPhotos($artkl, $name){
   $photo = array();
   //$no_photo = '<img src="'.HOST_URL.'/resources/img/no_photo.png" width = "300">';
   $no_photo[] = ''.HOST_URL.'/resources/img/no_photo.png';
   $directory = "resources/products/".$artkl.""; //название папки с изображениями

	    $allowed_types=array('jpg','jpeg','gif','png'); //типы изображений
	    $file_parts=array();
	    $ext='';
	    $i=0;
	 if(@opendir($directory)!=false) 
	 {
	 
	      $dir_handle = @opendir($directory) or exit();//die("There is an error with your image directory!");
               
               $count=0;
               
	      while ($file = readdir($dir_handle)) //поиск
	      {
	        if($file=='.' || $file == '..' || strpos($file,'large') !== false || strpos($file,'small') !== false) continue; //пропустить ссылки на другие папки
	        $file_parts = explode('.',$file); //разделение имени файла и размещение его в массиве
	        $ext = strtolower(array_pop($file_parts)); //расширение
                  
               
               
          	if(in_array($ext,$allowed_types))
	        {
                        $alt = $name;
                        if($count > 0){
                           $alt .= ' картинка ' . $count++;
                        }else{
                           $count++;
                        }
                        
                        /*             
                        $photo .= '
			            <a class="example-image-link"
                                    href="'.HOST_URL.'/'.$directory."/".str_replace("medium","large",$file).'" data-lightbox="example-set" data-title="'.$alt.'">
                                    <img alt="'. $alt .'" class="example-image" src="'.HOST_URL.'/'.$directory."/".$file.'" alt="'.$name.'"></a>
                                    ';
                                    */
                        $photo[] = ''.HOST_URL.'/'.$directory."/".str_replace("medium","large",$file).'';
                                    
	            $i++;
	        }
	    }
            
	    closedir($dir_handle); //закрыть папку
	    
	    $photo_all =  $photo;//.'<div class="clear"></div>';
	 }
         
         //Если в массив не влетело ни одно фото
         if($i == 0) $photo_all = $no_photo;//.'<div class="clear"></div>';
         
         return $photo_all;
 }
?>