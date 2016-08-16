<script>window.jQuery || document.write('<script src="<?php echo SKIN_URL; ?>js/vendor/jquery-1.11.3.min.js"><\/script>')</script>
<?php
$time =microtime(true);
function timer($msg){
    global $time;
    $msg = ''.$msg.' - '.number_format((microtime(true) - $time),3,'.','');
    $time = microtime(true);
    //echo '<br>'.$msg;
    return $msg;
}
function user_item_list_view($id,$setup, $key = 'All'){
    global $folder;
    $mysqli = $folder;
    
$time = microtime(true);  

    $Category = new Category($folder);
    $children = $Category->getCategoryChildren($id);

$timer[] = timer('Категории');    

    if ($_SESSION[BASE.'lang'] <1){
      $_SESSION[BASE.'lang']=1;
    }
  
    $step = 15;
    if(isset($_GET['step'])) $step = mysqli_escape_string($folder, $_GET['step']);
    //if($step > 100) $step = 100;
    
    $page = 1;
    if(isset($_GET['page'])) $page = mysqli_escape_string($folder, $_GET['page']);
    
    $start = ($page - 1) * $step;
      
    //Если залетела страна - выберем бренды и будем фильтровать по брендам
    $brand_filter = '';
    $brands = array();
    $country = array();
    if(isset($_GET['country'])){
        foreach($_GET['country'] as $value){
            $country[] = (int)$value;
        }
        $sql = 'SELECT brand_id FROM tbl_brand WHERE country_id IN ('.implode(",", $country).');';
        $r = $folder->query($sql);
        if($r->num_rows > 0){
            while($tmp = $r->fetch_assoc()){
                $brands[] = $tmp['brand_id'];
            }
        }
    }
    //Если залетети бренды - добавим их к массиву от стран
    if(isset($_GET['brand'])){
        foreach($_GET['brand'] as $value){
            $brands[] = (int)$value;
        }
    }
    
    /*
     *SELECT T.tovar_id, attribute_value
        FROM `tbl_tovar`  T
        LEFT JOIN tbl_price_tovar ON price_tovar_id = T.tovar_id 
        LEFT JOIN tbl_attribute_to_tovar AT2 ON AT2.tovar_id = T.tovar_id AND attribute_id = 2
        WHERE `tovar_inet_id_parent` IN (320) and `tovar_inet_id` > 0 AND (AT2.attribute_value='Красный' OR AT2.attribute_value='Черный') GROUP BY tovar_name_1; 
        */    
    $attr_str = '';
    $join_str = '';
    if(isset($_GET['filter-2'])){
        $join_str .= ' LEFT JOIN tbl_attribute_to_tovar AT2 ON AT2.tovar_id = T.tovar_id AND AT2.attribute_id = 2 ';
        $attr_str .= ' AND (';
        foreach($_GET['filter-2'] as $value){
            $attr_str .= 'AT2.attribute_value = "'.$value.'" OR ';
        }
        $attr_str = trim($attr_str, ' OR').')';
        
    }
  //echo   $attr_str.$_GET['filter_2'];
    
    //Бренды! Фильтр или выборка
    if(count($brands) > 0){
        $brand_filter = " AND brand_id IN (".implode(",", $brands).") ";
    }elseif(isset($_POST['brand_id'])){
        $brand_filter = " AND brand_id = (".(int)$_POST['brand_id'].") ";
    }
    
     //====GetProductsCount==========================================================
    $count = 0;
    $products_id = array();
    if($key == 'All'){
        $sql = "SELECT T.tovar_id
               FROM 
               `tbl_tovar` T
               /*LEFT JOIN tbl_price_tovar ON price_tovar_id = T.tovar_id*/
               $join_str
               WHERE 
               `tovar_inet_id_parent` IN (".implode(",", $children).")
               and `tovar_inet_id` > 0 $brand_filter $attr_str
               GROUP BY tovar_name_1;
               ";
    
        $getCount = $folder->query($sql);
        $count = $getCount->num_rows;
        if($getCount->num_rows > 0){
            while($tmp = $getCount->fetch_assoc()){
                $products_id[] = $tmp['tovar_id'];
            }
        }
    }elseif($key == 'FIND'){
        $searchq = $id;
        
        
        //Проверим может такие бренды есть =========================
        $brands = array();
        $sql = 'SELECT distinct brand_id FROM tbl_brand WHERE
                    upper(`brand_code`) LIKE "%'.mb_strtoupper(addslashes($searchq),'UTF-8').'%" OR
                    upper(`brand_name`) LIKE "%'.mb_strtoupper(addslashes($searchq),'UTF-8').'%" ';
        
        $r = $mysqli->query($sql) or die('sql error = ijhafp2328rwydfgh '.$sql);
        if($r->num_rows){
            while($row = $r->fetch_assoc()){
                $brands[$row['brand_id']] = $row['brand_id'];
            }
        }
        
        $sql = 'SELECT distinct brand_id FROM tbl_brand_alternative WHERE
                    upper(`brand_name`) LIKE "%'.mb_strtoupper(addslashes($searchq),'UTF-8').'%" ';
        
        $r = $mysqli->query($sql) or die('sql error = ijha444fp8rwydfgh');
        if($r->num_rows){
            while($row = $r->fetch_assoc()){
                $brands[$row['brand_id']] = $row['brand_id'];
            }
        }
        $where_brands = '';
        if(count($brands) > 0){
            $where_brands = ' OR T.brand_id IN ('.implode(',',$brands).') ';
        }
        //end Проверим может такие бренды есть =========================
        
          //Проверим может такие поставщики есть =========================
        $postav = array();
        $sql = 'SELECT distinct klienti_id FROM tbl_klienti WHERE klienti_group = "5" AND 
                    upper(`klienti_name_1`) LIKE "%'.mb_strtoupper(addslashes($searchq),'UTF-8').'%" OR
                    upper(`klienti_name_2`) LIKE "%'.mb_strtoupper(addslashes($searchq),'UTF-8').'%" OR
                    upper(`klienti_name_3`) LIKE "%'.mb_strtoupper(addslashes($searchq),'UTF-8').'%" ';
        
        $r = $mysqli->query($sql) or die('sql error = ijhafp8rw4324cydfgh '.$sql);
        if($r->num_rows){
            while($row = $r->fetch_assoc()){
                $postav[$row['klienti_id']] = $row['klienti_id'];
            }
        }
        
        $where_postav = '';
        if(count($postav) > 0){
            
            //$sql = 'SELECT tovar_id FROM tbl_tovar_suppliers_items WHERE postav_id IN ('.implode(',',$postav).') ';
            
            $where_postav = ' OR T.tovar_id IN (SELECT tovar_id FROM tbl_tovar_suppliers_items WHERE postav_id IN ('.implode(',',$postav).')) ';
  
        }
        //end Проверим может такие поставщики есть =========================
        
        
        $sql = "SELECT T.tovar_id
               FROM 
               `tbl_tovar` T
               /*LEFT JOIN tbl_price_tovar ON price_tovar_id = T.tovar_id*/
               LEFT JOIN tbl_klienti ON klienti_id = tovar_supplier
               $join_str
               WHERE 
                ((upper(`tovar_artkl`) LIKE '%".mb_strtoupper(addslashes($searchq),'UTF-8')."%' or
                upper(`tovar_name_1`) LIKE '%".mb_strtoupper(addslashes($searchq),'UTF-8')."%' or
                upper(`klienti_name_1`) LIKE '%".mb_strtoupper(addslashes($searchq),'UTF-8')."%' or
                upper(`klienti_name_2`) LIKE '%".mb_strtoupper(addslashes($searchq),'UTF-8')."%' or
                upper(`tovar_name_2`) LIKE '%".mb_strtoupper(addslashes($searchq),'UTF-8')."%') $where_brands $where_postav)
			   and `tovar_inet_id` > 0 $brand_filter $attr_str 
               GROUP BY `tovar_name_1`";
        $getCount = $folder->query($sql) or die('sql error = ijhafp8rwydfgh ');
        $count = $getCount->num_rows;
        if($getCount->num_rows > 0){
            while($tmp = $getCount->fetch_assoc()){
                $products_id[] = $tmp['tovar_id'];
            }
        }
        
    }else{
         $sql = "SELECT T.tovar_id
               FROM 
               `tbl_tovar` T
               /*LEFT JOIN tbl_price_tovar ON price_tovar_id = T.tovar_id*/
               $join_str
               WHERE 
               `tovar_inet_id` > 0 $brand_filter $attr_str
               GROUP BY tovar_name_1
               ORDER BY T.tovar_id DESC
               LIMIT 0, 10000;
               ";
        //echo $sql;
        $getCount = $folder->query($sql);
        $count = $getCount->num_rows;
        if($getCount->num_rows > 0){
            while($tmp = $getCount->fetch_assoc()){
                $products_id[] = $tmp['tovar_id'];
            }
        }
        //$count = $key;
        $products_id[] = 0;
    }
  

$_SESSION['product_count'] = $count;

//Если прилетели фильтры - сессию товаров не обновляем!!! Она нужна для списка фильтров без изменений
if($brand_filter == '' AND $attr_str == ''){
    $_SESSION['all_products_id'] = implode(',', $products_id);
//    echo "<pre>";  print_r(var_dump( $_SESSION['all_products_id'] )); echo "</pre>";
}

$timer[] = timer('Всего товаров');
  //====GetProducts==========================================================
   if($key == 'All'){
      $sql = "SELECT 	`tovar_inet_id_parent`,
               `tovar_artkl`,
               `tovar_model`,
               `tovar_name_".$_SESSION[BASE.'lang']."` AS tovar_name,
               T.tovar_id,
               `tovar_inet_id`,
			   `tovar_last_edit_user`,
               `price_tovar_curr_".$setup['web default price']."` as curr1,
               `price_tovar_curr_".$_SESSION[BASE.'userprice']."` as curr2,
               TSI.price_1,
               sum(TSI.items) AS items,
			    T.use_in_market
               FROM 
               `tbl_tovar` T
                LEFT JOIN tbl_tovar_suppliers_items TSI ON TSI.tovar_id = T.tovar_id
                LEFT JOIN tbl_price_tovar ON price_tovar_id = T.tovar_id
               $join_str
               WHERE 
               `tovar_inet_id_parent` IN (".implode(",", $children).")
               and price_1 > 0
               and `tovar_inet_id` > 0 $brand_filter $attr_str
               GROUP BY tovar_name_1
               ORDER BY T.sort ASC, CASE items WHEN 0 THEN 1 ELSE 0 END ASC, price_1 ASC, `tovar_name_1` ASC
               LIMIT $start, $step";
               
   }elseif($key == 'FIND'){
      $searchq = $id;
      $sql = "SELECT 	`tovar_inet_id_parent`,
               `tovar_artkl`,
               `tovar_model`,
               `tovar_name_".$_SESSION[BASE.'lang']."` AS tovar_name,
               T.tovar_id,
               `tovar_inet_id`,
			   `tovar_last_edit_user`,
               `price_tovar_curr_".$setup['web default price']."` as curr1,
               `price_tovar_curr_".$_SESSION[BASE.'userprice']."` as curr2,
                TSI.price_1,
               sum(TSI.items) AS items,
			    T.use_in_market
               FROM 
               `tbl_tovar` T
               LEFT JOIN tbl_tovar_suppliers_items TSI ON TSI.tovar_id = T.tovar_id
               LEFT JOIN tbl_price_tovar ON price_tovar_id = T.tovar_id
               LEFT JOIN tbl_klienti ON klienti_id = tovar_supplier
               LEFT JOIN tbl_attribute_to_tovar AT ON AT.tovar_id = T.tovar_id
               $join_str
               WHERE 
                ((upper(`tovar_artkl`) LIKE '%".mb_strtoupper(addslashes($searchq),'UTF-8')."%' or
                upper(`tovar_name_1`) LIKE '%".mb_strtoupper(addslashes($searchq),'UTF-8')."%' or
                upper(`tovar_name_2`) LIKE '%".mb_strtoupper(addslashes($searchq),'UTF-8')."%') $where_brands $where_postav)
			   and `tovar_inet_id` > 0 $brand_filter $attr_str 
               GROUP BY tovar_name_1
               ORDER BY  /*CASE items WHEN 0 THEN 1 ELSE 0 END ASC,*/ T.sort ASC, items DESC, price_1 ASC, `tovar_name_1` ASC
               LIMIT $start, $step";
               //echo $sql;
   }else{
      $sql = "SELECT 	`tovar_inet_id_parent`,
               `tovar_artkl`,
               `tovar_model`,
               `tovar_name_".$_SESSION[BASE.'lang']."` AS tovar_name,
               T.tovar_id,
               `tovar_inet_id`,
			   `tovar_last_edit_user`,
			   `price_tovar_curr_".$setup['web default price']."` as curr1,
               `price_tovar_curr_".$_SESSION[BASE.'userprice']."` as curr2,
			   T.use_in_market
               
               FROM 
               `tbl_tovar` T
               LEFT JOIN tbl_price_tovar ON price_tovar_id = T.tovar_id
               $join_str
               /*RIGHT JOIN tbl_attribute_to_tovar AT ON AT.tovar_id = T.tovar_id AND attribute_id = 2*/
               WHERE
               `tovar_inet_id` > 0 $brand_filter $attr_str
               /*AND atribute_value = 'Черный'*/
                GROUP BY tovar_name_1
               ORDER BY T.tovar_id DESC
               LIMIT $start, $step
               /*LIMIT 0, $key*/
             ";
   }
//echo $sql;
$timer[] = timer('Получили продукты');    

   
   $getName = $folder->query($sql);

      if (!$getName){
         echo "Query error - tbl_price - ",$sql;
         exit();
      }
 
 
      if($getName->num_rows > 0){     
        
        $data = tovar_view($getName,"none",$setup);
        
       
        
        $data['children'] = $children;
        $data['category_id'] = $id;
        $data['category_info'] = $Category->getCategoryInfo($id);
        $data['products_count'] = $count;
        $data['timer1'] = $timer;
        
        if(isset($_POST['brand_name'])){
            $data['breadcrumb'][0]['id'] = 0;
            $data['breadcrumb'][0]['url'] = '';
            $data['breadcrumb'][0]['name'] = $_POST['brand_name'];
            $data['category_info']['name'] = 'Производитель '.$_POST['brand_name'];
        }else{
            $data['breadcrumb'] = $Category->getCategoryBreadcrumb($id);
        }
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
       //$min_price = 100000;
       //Разделитель артикула на Артикул и размер
       $separator = $setup['tovar artikl-size sep'];
       
       //Разделитель Названия и Коментария в названии товара
       $separator_comment = $setup['tovar name sep'];
       
       //Глобальный фильтр по атрибутам
       $attribute_filter = array();
  
       //Загоняем товары в массив
       $products = array();
       $artikles = array();
	   $users = array();
	   global $User;
       while($tmp = $getName->fetch_assoc()){
		
            /* Перенести в аякс   
            if($tmp['tovar_last_edit_user'] > 0){
                $users[$tmp['tovar_last_edit_user']] = $User->getKlientName($tmp['tovar_last_edit_user']);
            }
              //Если вообще выбрано сорт по пользователю
            if(isset($_GET['user'])){
                  if(!isset($_GET['user'][$tmp['tovar_last_edit_user']])){
                    continue;
                  }
            }*/
		
          //Разбиваем атрикл на тело и размер
            $artkl = $tmp['tovar_artkl'];
            $size = "none";
            if(strpos($tmp['tovar_artkl'],$separator) !== false){
                $x = explode($separator, $tmp['tovar_artkl']);
                $artkl = $x[0];
                $size = $x[1];
            }
          
			$products[$artkl]['color'] = '';
			
			 //Берем аттрибуты товара и сверяем попадает он под фильтр ($no_filter == false)
             /*
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
						 
						 if($value['attribute_name'] == 'Цвет'){
							$products[$artkl]['color'] = $value['attribute_value'];
						 }
						 
                      }
                }
             }*/
			 
			$products[$artkl]['model'] = $tmp['tovar_model'];
			$products[$artkl]['color_variants']	= $Product->getColorVariants($products[$artkl]['model'],$products[$artkl]['color']);
			 
			 
			//echo '<pre>'; print_r(var_dump($attribute_filter));         
             //Ключ проверки товара по фильтру
             $tovar_filtered = true;

             //если есть ключи для фильтрации вовара
             /*
            if($no_filter == false AND !empty($attributes)){
                foreach($attributes as $index => $value){
                   //На атрибуты
				   if($value['filter'] == 1){
                      if(isset($filter[$index])){
                         if(!array_key_exists($value['attribute_value'], $filter[$index])){
                            $tovar_filtered = false;
                         }
                      }
                   }
				   
                }
            }*/
        
            /*
            //Если не высталено фильтрование - уберем товары которые вообще без атрибутов
            if($no_filter == false AND empty($attributes)){
                $tovar_filtered = false;
            } */   
            /*
			 //Если товар не прошел проверку на фильтр
             if($tovar_filtered == false){
                unset($products[$artkl]);
                continue;
             }
            */  
             //Массив пишем по ключу Артикл
             //$products_info['parent'][$tmp['tovar_inet_id_parent']] = $tmp['tovar_inet_id_parent'];
      
             //Считаем количество артиклей в подкатегориях
             if(!isset($artikles[$artkl])){
                /*в аяксе теперь
                if(isset($products_parent_items[$tmp['tovar_inet_id_parent']])){
                   $products_parent_items[$tmp['tovar_inet_id_parent']]++;   
                }else{
                   $products_parent_items[$tmp['tovar_inet_id_parent']] = 1;
                }*/
                $artikles[$artkl] = $artkl;
             }
             
             $products[$artkl]['name'] = $tmp['tovar_name'];
             $products[$artkl]['id'] = $tmp['tovar_id'];
             $products[$artkl]['alias'] = HOST_URL.'/'.$Alias->getProductAlias($tmp['tovar_id']);
             $products[$artkl]['url'] = $products[$artkl]['alias'];
             $products[$artkl]['img'] = $Product->getProductPicOnArtkl($artkl);
			 $products[$artkl]['social'] = $Product->getProductSocial($artkl);
			  //$products[$artkl]['memo'] = $Product->getProductMemoShort($tmp['tovar_id']);
             $products[$artkl]['size'][$size]['id'] = $tmp['tovar_id'];
             $products[$artkl]['size'][$size]['size'] = $size;
             $products[$artkl]['size'][$size]['price'] = $Product->getProductPrice($tmp['tovar_id']);
             $products[$artkl]['size'][$size]['curr'] = $tmp['curr2'];
             $products[$artkl]['ymark'] = $tmp['use_in_market'];
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
			  
			  if(!isset($min_price))$min_price = $tmp_price;
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
    
$timer[] = timer('Товары в массив');    
        
        //Убираем из массива товары с нулевым остатком
        if(VIEW_EMPTY_PRODUCT == false){
            foreach($products as $index => $product){
               if($product['total'] > 0){
                  
               }else{
                  unset($products[$index]);
               }
             }
		  //Иначе сбрасываем их вниз 
        }else{
				$p1 = array();
				$p2 = array();
				foreach($products as $index => $product){
					if($product['total'] > 0){
					   $p1[$index] = $product;
					}else{
					   $p2[$index] = $product;
					}
				}
				$products = array();
				$products = $p1 + $p2;
				unset($p1);
				unset($p2);
        }
        
$timer[] = timer('Отсутствующие вниз');


          //Общий прогон по товарам
          $brands = array();
          $country = array();
          /*Перенесено в аякс
          foreach($products as $artkl => $product){
                //Создаем массив брендов и стран
                $tmp = $Brand->getBrandCodeOnProductArtkl($artkl);
             
			 	if($tmp){
				  $brands[$tmp['brand_id']] = $tmp['brand_name'];
				  $country[$tmp['country_id']] = $tmp['CountryName'];
                
				  //Фильтр брендов и страны
				  //Если вообще выбрано сорт по бренду
				  if(isset($_GET['brand'])){
					 //Если сортировка по брендлу идет - то проверим выбран ли он (бренд)
					 if(!isset($_GET['brand'][$tmp['brand_id']])){
					   unset($products[$artkl]);
					   continue;
					 }
				  }
				  //Если вообще выбрано сорт по стране
				  if(isset($_GET['country'])){
					 //Если сортировка по стране идет - то проверим выбран ли он (страна)
					 if(!isset($_GET['country'][$tmp['country_id']])){
					   unset($products[$artkl]);
					   continue;
					 }
				  }
				 	
				  $products[$artkl]['brand_country'] = $tmp['brand_name'].', '.$tmp['CountryName'];
				  
				}
          }
          $timer[] = timer('Общий прогон по товарам');
          */

/*  Теперь это по аяксу
          //Возмем подкаталоги
          if(isset($products_info)){
             $products_info['parent'] = $Category->getCategoriesInfo($products_info['parent']);
          }
          //echo "<pre>"; print_r(var_dump($products)); 
$timer[] = timer('Получили подкатегории');             
*/           
        //Уберем фильтры которые имеют по одному варианту
        foreach($attribute_filter as $index => $value){
             if(count($value['value']) < 2){
                unset($attribute_filter[$index]);
             }
        }
$timer[] = timer('Чистка фильтров');              
        
        if(!isset($min_price)) $min_price = 0;
          
            $data = array();
            $data['users'] = $users;
            $data['products'] = $products;
            $data['brands']   = $brands;
            $data['country']  = $country;
            $data['attribute_filter']  = $attribute_filter;
            //$data['products_info']  = $products_info;
            //$data['products_parent_items'] = $products_parent_items;
            $data['max_price'] = $max_price;
            $data['min_price'] = $min_price;
            $data['timer'] = $timer;    
         // $data['products_count'] = $count;
		 // echo '<br>=='.$min_price;
          return $data;
   }
?>