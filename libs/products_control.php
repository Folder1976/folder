<?php
function user_item_list_view($id,$setup, $key = 'All'){
  global $folder;
  $Category = new Category($folder);
  $children = $Category->getCategoryChildren($id);
   
   if ($_SESSION[BASE.'lang'] <1){
     $_SESSION[BASE.'lang']=1;
   }
   
   if($key == 'All'){
      $sql = "SELECT 	`tovar_inet_id_parent`,
               `tovar_artkl`,
               `tovar_name_".$_SESSION[BASE.'lang']."` AS tovar_name,
               `tovar_id`,
               `tovar_inet_id`,
               `price_tovar_curr_".$setup['web default price']."` as curr1,
               `price_tovar_curr_".$_SESSION[BASE.'userprice']."` as curr2
               FROM 
               `tbl_tovar`
               LEFT JOIN tbl_price_tovar ON price_tovar_id = tovar_id
               WHERE 
               `tovar_inet_id_parent` IN (".implode(",", $children).")
               and `tovar_inet_id` > 0
               ORDER BY `tovar_name_1` ASC
               "; //LIMIT $start, $step;
   }elseif($key == 'FIND'){
      $searchq = $id;
      $sql = "SELECT 	`tovar_inet_id_parent`,
               `tovar_artkl`,
               `tovar_name_".$_SESSION[BASE.'lang']."` AS tovar_name,
               `tovar_id`,
               `tovar_inet_id`,
               `price_tovar_curr_".$setup['web default price']."` as curr1,
               `price_tovar_curr_".$_SESSION[BASE.'userprice']."` as curr2
               FROM 
               `tbl_tovar`
               LEFT JOIN tbl_price_tovar ON price_tovar_id = tovar_id
               LEFT JOIN tbl_klienti ON klienti_id = tovar_supplier
               WHERE 
                (upper(`tovar_artkl`) LIKE '%".mb_strtoupper(addslashes($searchq),'UTF-8')."%' or
                upper(`tovar_name_1`) LIKE '%".mb_strtoupper(addslashes($searchq),'UTF-8')."%' or
                upper(`klienti_name_1`) LIKE '%".mb_strtoupper(addslashes($searchq),'UTF-8')."%' or
                upper(`klienti_name_2`) LIKE '%".mb_strtoupper(addslashes($searchq),'UTF-8')."%' or
                upper(`tovar_name_2`) LIKE '%".mb_strtoupper(addslashes($searchq),'UTF-8')."%')
			   and `tovar_inet_id` > 0
               ORDER BY `tovar_name` ASC
               "; //LIMIT $start, $step;
   }else{
      $sql = "SELECT 	`tovar_inet_id_parent`,
               `tovar_artkl`,
               `tovar_name_".$_SESSION[BASE.'lang']."` AS tovar_name,
               `tovar_id`,
               `tovar_inet_id`,
               `price_tovar_curr_".$setup['web default price']."` as curr1,
               `price_tovar_curr_".$_SESSION[BASE.'userprice']."` as curr2
               FROM 
               `tbl_tovar`
               LEFT JOIN tbl_price_tovar ON price_tovar_id = tovar_id
               WHERE
                `tovar_inet_id` > 0
               ORDER BY `tovar_id` DESC
               LIMIT 0, $key
               "; //LIMIT $start, $step;
   }
   
   //echo $sql;
   $getName = $folder->query($sql);
  
 
    
      
      if (!$getName){
         echo "Query error - tbl_price - ",$sql;
         exit();
      }
 
      if($getName->num_rows > 0){     
        $data = tovar_view($getName,"none",$setup);
        $data['breadcrumb'] = $Category->getCategoryBreadcrumb($id);
        $data['children'] = $children;
        $data['category_id'] = $id;
        $data['category_info'] = $Category->getCategoryInfo($id);
        //$data['products_count'] = $counts['products'];
        
        return $data;
      }else{
        return false;
      }
}
    //Получаем список товаров для вывода
    function tovar_view($getName,$title,$setup){
       global $Alias;
       global $folder;
       global $Attribute;
       global $Product;
       global $Brand;
       global $Category;
       global $curr_name;
       global $currency_name;
       $max_price = 0;
       $min_price = 100000;
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
       while($tmp = $getName->fetch_assoc()){
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
             $products[$artkl]['url'] = $products[$artkl]['alias'];
             $products[$artkl]['img'] = $Product->getProductPicOnArtkl($artkl);
             //$products[$artkl]['memo'] = $Product->getProductMemoShort($tmp['tovar_id']);
             $products[$artkl]['size'][$size]['id'] = $tmp['tovar_id'];
             $products[$artkl]['size'][$size]['size'] = $size;
             $products[$artkl]['size'][$size]['price'] = $Product->getProductPrice($tmp['tovar_id']);
             $products[$artkl]['size'][$size]['curr'] = $tmp['curr2'];
             $products[$artkl]['size'][$size]['items'] = $Product->getProductOnWare($tmp['tovar_id']);
             
             //Будем брать описание если оно не пустое.
             $tmp_memo = $Product->getProductMemoShort($tmp['tovar_id']);
             if($tmp_memo != ''){
                $products[$artkl]['memo']  = $tmp_memo;
             }
             
             //Общее количество
             if(isset($products[$artkl]['total'])){
                $products[$artkl]['total'] += $Product->getProductOnWare($tmp['tovar_id']);
             }else{
                $products[$artkl]['total'] = $Product->getProductOnWare($tmp['tovar_id']);
             }
             
             //Наименьшая цена на морду 
             $tmp_price = $Product->getProductPrice($tmp['tovar_id']);
			  if($tmp_price > 0){
			  if($max_price < $tmp_price) $max_price = $tmp_price;
			  if($min_price > $tmp_price AND $tmp_price > 0) $min_price = $tmp_price;
		  
			  if(isset($products[$artkl]['price']) AND $tmp_price < $products[$artkl]['price']){
				 $products[$artkl]['price'] = $tmp_price;
				 $products[$artkl]['curr'] = $tmp['curr2'];
			  }else{
				 $products[$artkl]['price'] = $tmp_price;
				 $products[$artkl]['curr'] = $tmp['curr2'];
			  }
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
           
          //Общий прогон по товарам
          $brands = array();
          $country = array();
          foreach($products as $artkl => $product){
                //Создаем массив брендов и стран
                $tmp = $Brand->getBrandCodeOnProductArtkl($artkl);
                $brands[$tmp['brand_code']] = $tmp['brand_name'];
                $country[$tmp['country_id']] = $tmp['CountryName'];
                
                $products[$artkl]['brand_country'] = $tmp['brand_name'].', '.$tmp['CountryName'];
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
          
          $step = 15;
          if(isset($_GET['step'])) $step = mysqli_escape_string($folder, $_GET['step']);
          
          $page = 1;
          if(isset($_GET['page'])) $page = mysqli_escape_string($folder, $_GET['page']);
          
          $start = ($page - 1) * $step;
          
          //$start, $step;
          $x = 0;
          $count = 0;
          foreach($products as $index => $val){
            if($x >= $start AND $x < ($start + $step)){
              
            }else{
              unset($products[$index]);
            }
            $count++;
            $x++;
          }
         
          
          $data = array();
          $data['products'] = $products;
          $data['brands']   = $brands;
          $data['country']  = $country;
          $data['attribute_filter']  = $attribute_filter;
          $data['products_info']  = $products_info;
          $data['products_parent_items'] = $products_parent_items;
          $data['max_price'] = $max_price;
          $data['min_price'] = $min_price;
          $data['products_count'] = $count;
		 // echo '<br>=='.$min_price;
          return $data;
   }
?>