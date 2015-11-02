<?php

//Получаем список товаров для вывода
function tovar_view($getName,$title,$setup){
   global $Alias;
   global $Attribute;
   global $Product;
   global $Category;
   global $curr_name;
   global $currency_name;
   
   //Разделитель артикула на Артикул и размер
   $separator = $setup['tovar artikl-size sep'];
   
   //Разделитель Названия и Коментария в названии товара
   $separator_comment = $setup['tovar name sep'];
   
   //Глобальный фильтр по атрибутам
   $attribute_filter = array();
   
   //Проверим был ли выбран какойто фильтр
   $no_filter = true;
   $filter = array();
   foreach($_GET as $index => $value){
      if(strpos($index,'filter') !== false){
         $no_filter = false;
         
         $i = explode('-',$index);
         $filter[$i[1]] = $value;
      }
   }

   
   
   //Загоняем товары в массив
   $products = array();
   $artikles = array();
   while($tmp = mysql_fetch_assoc($getName)){
         //Разбиваем атрикл на тело и размер
        $artkl = $tmp['tovar_artkl'];
        $size = "none";
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
                     $products[$artkl]['attributes'][$index]['name'] = $value['attribute_name'];
                     $products[$artkl]['attributes'][$index]['value'] = $value['attribute_value'];
                  }
            }
         }
//echo '<pre>'; print_r(var_dump($attribute_filter));         
         //Ключ проверки товара по фильтру
         $tovar_filtered = true;
         
         //если есть ключи для фильтрации вовара
         if($no_filter == false AND !empty($attributes)){
            foreach($attributes as $index => $value){
               if($value['filter'] == 1){
                  if(isset($filter[$index])){
                     if(!array_key_exists($value['attribute_value'], $filter[$index])){
                        $tovar_filtered = false;
                     }
                  }
               }
            }
         }
        
         //Если товар не прошел проверку на фильтр
         if($tovar_filtered == false){
            unset($products[$artkl]);
            continue;
         }
        
         //Массив пишем по ключу Артикл
         $products_info['parent'][$tmp['tovar_inet_id_parent']] = $tmp['tovar_inet_id_parent'];
  
         //Считаем количество артиклей в подкатегориях
         if(!isset($artikles[$artkl])){
            if(isset($products_parent_items[$tmp['tovar_inet_id_parent']])){
               $products_parent_items[$tmp['tovar_inet_id_parent']]++;   
            }else{
               $products_parent_items[$tmp['tovar_inet_id_parent']] = 1;
            }
            $artikles[$artkl] = $artkl;
         }
         
         $products[$artkl]['name'] = $tmp['tovar_name'];
         $products[$artkl]['id'] = $tmp['tovar_id'];
         $products[$artkl]['alias'] = HOST_URL.'/'.$Alias->getProductAlias($tmp['tovar_id']);
         $products[$artkl]['img'] = $Product->getProductPicOnArtkl($artkl);
         $products[$artkl]['memo'] = $Product->getProductMemoShort($tmp['tovar_id']);
         $products[$artkl]['size'][$size]['id'] = $tmp['tovar_id'];
         $products[$artkl]['size'][$size]['size'] = $size;
         $products[$artkl]['size'][$size]['price'] = $Product->getProductPrice($tmp['tovar_id']);
         $products[$artkl]['size'][$size]['curr'] = $tmp['curr2'];
         $products[$artkl]['size'][$size]['items'] = tovar_on_ware($tmp['tovar_id']);
         
         //Общее количество
         if(isset($products[$artkl]['total'])){
            $products[$artkl]['total'] += tovar_on_ware($tmp['tovar_id']);
         }else{
            $products[$artkl]['total'] = tovar_on_ware($tmp['tovar_id']);
         }
         
         //Наименьшая цена на морду 
         $tmp_price = $Product->getProductPrice($tmp['tovar_id']);;
         if(isset($products[$artkl]['price']) AND $tmp_price < $products[$artkl]['price']){
            $products[$artkl]['price'] = $tmp_price;
            $products[$artkl]['curr'] = $tmp['curr2'];
         }else{
            $products[$artkl]['price'] = $tmp_price;
            $products[$artkl]['curr'] = $tmp['curr2'];
         }
      }
    
      //Убираем из массива товары с нулевым остатком
      if(VIEW_EMPTY_PRODUCT == false){
         foreach($products as $index => $product){
           if($product['total'] > 0){
              
           }else{
              unset($products[$index]);
           }
         }
      }
       
      //Возмем подкаталоги
      if(isset($products_info)){
         $products_info['parent'] = $Category->getCategoriesInfo($products_info['parent']);
      }
      //echo "<pre>"; print_r(var_dump($products)); 
     
         
      //Уберем фильтры которые имеют по одному варианту
      foreach($attribute_filter as $index => $value){
         if(count($value['value']) < 2){
            unset($attribute_filter[$index]);
         }
       }
      
    //Берем темплейт и возвращаем его 
    ob_start();
      require('template/product_list_001.tpl');
    $sResult = ob_get_contents();
    ob_end_clean();
    return $sResult;
 }
?>