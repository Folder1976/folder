<?php
set_time_limit(0);
include '../config/config.php';
include '../simple_html_dom/simple_html_dom.php';
include 'constants.php';
include_once '../import/import_url_getfile.php';
include '../class/class_product_edit.php';
$ProductEdit = new ProductEdit($folder);


//================================================================================================================================
//================================================================================================================================
//================================================================================================================================
//================================================================================================================================
//================================================================================================================================
//================================================================================================================================

if(isset($_GET['brand'])) $brand_id = $_GET['brand'];

$sql = '';
$list = '';
if(!isset($_POST['url']) AND !isset($_GET['url'])) {
    echo 'no link for pars';
    die();
}

if(isset($_POST['list'])){
    $list = $_POST['list'];
}

if(isset($_POST['url'])){
    $url = $_POST['url'];
}ELSE{
    $url = $_GET['url'];
}

$return = array();



//Аллегро парсинг
    $find = array('РУБ', 'Код товара:', 'Цена:', 'руб.', 'qty:', '+', '&nbsp;', ' ');
    $rep = array('','','','','','','','');
    
    $postav_id = 59;
    $return['web'] = 'https://militarist.ua';
    $PREF = 'ST_';
    //если нам влетело list = делаем массив
     //если нам влетело list = делаем массив
    if($list != ''){
        $html = file_get_html($list);
        
        $str_tmp = $html->find('.itm');
        //echo '11'.$str_tmp->outertext;
        echo '<h4>Нашел такие сылки на товар</h4>';            
        
        $list = array();
        foreach($str_tmp as $option){
            //echo $option->innertext();
            $html_tmp = str_get_html($option->innertext());
            
            echo '<br>'.$html_tmp->find('.item-name', 0)->innertext();
            $list[] = $return['web'].$html_tmp->find('a',0)->href;
        }
        
    }else{

        $list = array($url);

    }
    unset($html);
    
    header("Content-Type: text/html; charset=UTF-8");
    echo "<pre>";  print_r(var_dump( $list )); echo "</pre>";
  die();
 
    foreach($list as $url){
            $original_url = $url;
            $html = file_get_html($url);
            
            
            //Взяли прайс
            $name = $html->find('h1', 0);
          
            //Имя
            $str_name = $name->innertext();
            
            //Размеры
            $size = array('');
            $str_artkl = '';
            $qty = '';
            $str_price = 0;
            //$brand_id = 0;
            $category_id = 0;
            $parent = $_POST['parent'];
            
            $str_artkl = $PREF.$html->find('.article span', 0)->innertext();
            
            $str_price = str_replace($find, $rep, $html->find('.curPrice span', 0)->innertext());
            
            $memo = $html->find('.elUnionBlocks #info', 0)->innertext();
            
            //Ищем все фото
            //Загружаем главные фото ==================================
            $photos = array();
            foreach($html->find('.dopPic a') as $p){
                $url = $p->href;
                $s = $return['web'].$url;
                $photos[] = $s;
            }
            
            //Ищем все цвета
            $params = array();
            foreach($html->find('.offersProps') as $prop){
                
                $val = str_get_html($prop->innertext());
                
                $val_name = $val->find('.propertyName', 0)->innertext();
                
                foreach($val->find('.propertyValue label') as $p){
                    //$s = $p->innertext();
                    //$s = $return['web'].$url;
                    $params[$val_name][$p->innertext()] = $p->innertext();
                }
            }
            
            $price = (int)$str_price;
            $color_size = array();
            if(isset($params['Цвет:']) AND $_POST['color'] == 1){
                    foreach($params['Цвет:'] as $i => $v){
                        $nacen = 0;
                        
                            if(strpos($v, '(+') !== false){
                                $tmp = explode('(+', $v);
                                $color = trim($tmp[0]);
                                $price = (int)((int)$str_price * ((100+(int)trim($tmp[1], ' )')) / 100));
                                
                            }else{
                                $color = trim($v);
                                $price = (int)$str_price;
                            }
                       
                        
                        $code = '';
                        $sql = 'SELECT * FROM tbl_colors WHERE  upper(`color`) LIKE "'.mb_strtoupper(addslashes($color),'UTF-8').'%";';
                        $col = $folder->query($sql) or die('Не удалось найти цвет ' . $sql);
                        if($col->num_rows == 0){
                            $sql = 'INSERT tbl_colors SET color = "'.$color.'";';
                            $folder->query($sql) or die('Добавил новый цвет ' . $sql);
                            echo '<br>Добавил новый цвет - ' . $color;
                            $code = $folder->insert_id;
                        }else{
                            $tmp = $col->fetch_assoc();
                            $code = (int)$tmp['id'];
                            $color = $tmp['color'];
                        }
                        
                        if($code < 9){
                            $code = '00'.$code;
                        }elseif($code < 100){
                            $code = '0'.$code;
                        }else{
                            $code = (string)$code;
                        }
                        $color_size[$code]['color'] = $color;
                        $color_size[$code]['price'] = $price;
                        $color_size[$code]['size'] = '';
                    }
            
            }
            
            //ЕСли цвет назначен
             //die('11111111111111');
            if(isset($_POST['color_value']) AND $_POST['color_value'] != ''){
          
                        $color = $_POST['color_value'];
                        //$price = (int)$str_price;
                        $code = '';
                        $sql = 'SELECT * FROM tbl_colors WHERE  upper(`color`) LIKE "'.mb_strtoupper(addslashes($color),'UTF-8').'%";';
                        $col = $folder->query($sql) or die('Не удалось найти цвет ' . $sql);
                        if($col->num_rows == 0){
                            echo 'Указанный цвет отсутствует в нашей таблице цветов!';
                            return false;
                        }else{
                            $tmp = $col->fetch_assoc();
                            $code = (int)$tmp['id'];
                            $color = $tmp['color'];
                        }
                        
                        if($code < 9){
                            $code = '00'.$code;
                        }elseif($code < 100){
                            $code = '0'.$code;
                        }else{
                            $code = (string)$code;
                        }
                        $color_size[$code]['color'] = $color;
                        $color_size[$code]['price'] = $price;
                        $color_size[$code]['size'] = '';
            }
                     

            if(count($color_size) == 0){
                 $color_size['0']['price'] = (int)$str_price;
                 $color_size['0']['color'] = '';
                 $color_size['0']['size'] = '';
            }
             $info = '';
            $info_a = array();
            if(isset($params['Ширина ремня:']) AND $_POST['wid'] == 1){
                foreach($color_size as $i => $val){
                    $size_tmp = array();
                    $info_a = array();
                    foreach($params['Ширина ремня:'] as $wid){
                        if(is_array($val['size'])){
                            foreach($val['size'] as $siz){
                                $info_a[$wid] = $wid;
                                $size = explode(' ',$wid);
                                $size_tmp[] = $siz . $size[0].'_';
                            }
                        }else{
                            $info_a[$wid] = $wid;
                            $size = explode(' ',$wid);
                            $size_tmp[] = $size[0].'_';
                        }
                    }
                    $color_size[$i]['size'] = $size_tmp;
                }
                $info .= '<h3>Ширина ремня:</h3>';
                $info .= '<b>' . implode(', ', $info_a) . '</b>';
            }
            
            if(isset($params['Длина:']) AND $_POST['hei'] == 1){
                 foreach($color_size as $i => $val){
                     $size_tmp = array();
                     $info_a = array();
                     foreach($params['Длина:'] as $wid){
                         if(is_array($val['size'])){
                             foreach($val['size'] as $siz){
                                 $info_a[$wid] = $wid;
                                 $size = explode(' ',$wid);
                                 $size_tmp[] = $siz . $size[0].'_';
                             }
                         }else{
                             $info_a[$wid] = $wid;
                             $size = explode(' ',$wid);
                             $size_tmp[] = $size[0].'_';
                         }
                     }
                     $color_size[$i]['size'] = $size_tmp;
                 }
                 $info .= '<h3>Длина ремня:</h3>';
                 $info .= '<b>' . implode(', ', $info_a) . '</b>';
             }

                $nacen = array();
                if(isset($params['Расположение:']) AND $_POST['sto'] == 1){
                     foreach($color_size as $i => $val){
                         $size_tmp = array();
                         $info_a = array();
                         foreach($params['Расположение:'] as $wid){
                             if(is_array($val['size'])){
                                 foreach($val['size'] as $siz){
                                    $info_a[$wid] = $wid;
                                      $size = 'NO';
                                      if(strpos($wid, 'лев') !== false){
                                        $size = 'L';
                                      }elseif(strpos($wid, 'прав') !== false){
                                        $size = 'R';
                                      }
                                   
                                    //Если наценка для стороны
                                    if(strpos($wid, '(+') !== false){
                                        
                                        $tmp = explode('(+', $wid);
                                        $price1 = (int)((int)$price * ((100+(int)trim($tmp[1], ' )')) / 100));
                                        //$nacen[$siz . $size[0]] = (int)trim($tmp[1], ' )');
                                    }else{
                                        $price1 = $price;
                                    }
                                    $size_tmp[] = $siz . $size[0].'_';
                                 }
                             }else{
                                
                                $info_a[$wid] = $wid;
                                $size = 'NO';
                                if(strpos($wid, 'лев') !== false){
                                  $size = 'L';
                                }elseif(strpos($wid, 'прав') !== false){
                                  $size = 'R';
                                }
                                if(strpos($wid, '(+') !== false){
                                    $tmp = explode('(+', $wid);
                                    $price1 = (int)((int)$price * ((100+(int)trim($tmp[1], ' )')) / 100));
                                    //$nacen[$siz . $size[0]] = (int)trim($tmp[1], ' )');
                                }else{
                                    $price1 = $price;
                                }
                               $size_tmp[] = $size[0].'_';
                               
                             }
                             $price_size[$size[0]] = $price1;
                         }
                         $color_size[$i]['size'] = $size_tmp;
                       
                     }
                     $info .= '<h3>Расположение:</h3>';
                     $info .= '<b>' . implode(', ', $info_a) . '</b>';
                 }

                 
            echo  '<br>'.$str_name;
            echo  '<br>'.$str_artkl;
            echo  '<br>'.$str_price;
            //echo "<pre>";  print_r(var_dump( $photos )); echo "</pre>";
            $memo = $info.'<hr>'.$memo;
            //echo "<pre>";  print_r(var_dump( $color_size )); echo "</pre>";die();

//=============================================================================================================================            
//=============================================================================================================================            
//=============================================================================================================================            
//=============================================================================================================================            
//=============================================================================================================================            
            $str_artkl = ru2lat($str_artkl);
            $qty = 1;
    //return true;
            $model = $str_artkl;
            foreach($color_size as $color_code => $color_values){
                     
                    $str_price = trim($color_values['price'], ' ');
                    
                    //Если у этого размера своя цена
                    $color_name = trim($color_values['color'], ' ');
                    $color_number = $color_code;
    
                    $size_t = $color_values['size'];
                    if(!is_array($size_t)){
                        $size_t = array(''=>'');
                    }
                    foreach($size_t as $siz){
                          
                        $siz = trim($siz, '_');
                        
                        //Если у нас для этого размера спец цена   
                        if(isset($price_size[$siz])){
                            $str_price = $price_size[$siz];
                        }
                       
                        $str_artkl =  $model;
                        
                        $str_artkl = $str_artkl . '-' . $color_number;
                        
                        $photo_name = $str_artkl;
    //echo '<br>'.$str_artkl.' '.$siz;                      
                        if($siz != ''){
                            $str_artkl = $str_artkl . '#' . $siz;
                        }
                
                        $str_artkl = str_replace(' ', '', $str_artkl);
                        $str_artkl = str_replace(' ', '', $str_artkl);
                        $str_artkl = str_replace(' ', '', $str_artkl);
                        
                        $product_ids = $ProductEdit->getProductIdOnArtiklAndSupplier($str_artkl);
                            //Проверям есть ли у на свсе данные
                          
                            if($parent == 0){
                                echo '<br><font color = "red">Не указана внутренняя папка!</font>';
                                    die();
                            }
                           
                            if($category_id == 0){
                                if(isset($_POST['category'])) $category_id = $_POST['category'];
                                
                                if($category_id == 0){
                                    echo '<br><font color = "red">Не знаю категорию!</font>';
                                    die();
                                }
                            }
                            //==========================================
                        if($product_ids AND count($product_ids) > 0){
                            foreach($product_ids as $product_id){
                                //echo '<br><font color="green">Нашел продукт <b>'.$product_id.'</b>. Если у него есть пустые поля которые я нашел - я их обновлю</font>';
                                
                                //Обновим некоторые параметры
                                if($brand_id > 0){
                                    $sql = 'UPDATE tbl_tovar SET brand_id = \''.$brand_id.'\' WHERE tovar_id = \''.$product_id.'\';';
                                    $folder->query($sql) or die('Не удалось обновить бренд' . $sql);
                                }
                                
                                if($parent > 0){
                                    $sql = 'UPDATE tbl_tovar SET tovar_parent = \''.$parent.'\' WHERE tovar_id = \''.$product_id.'\';';
                                    $folder->query($sql) or die('Не удалось обновить бренд' . $sql);
                                }
                                
                                if($category_id > 0){
                                    $sql = 'UPDATE tbl_tovar SET tovar_inet_id_parent = \''.$category_id.'\' WHERE tovar_id = \''.$product_id.'\';';
                                    $folder->query($sql) or die('Не удалось обновить бренд' . $sql);
                                }
                            }
                        }else{
                        
                            //echo '<br>'.$str_artkl . ' ' . $str_name .' = Такого продукта нет, Пробую добавить';
                            
                            $data['tovar_artkl'] = $str_artkl;
                            $data['tovar_name_1'] = $str_name. ' (' . $color_name . ')';
                            $data['tovar_inet_id'] = 10;
                            $data['tovar_inet_id_parent'] = $category_id;
                            $data['brand_id'] = $brand_id;
                            $data['tovar_model'] = $model;
                            $data['tovar_parent'] = 2;
                            $data['tovar_memo'] = $memo;
                            $data['tovar_purchase_currency'] = 1;
                            $data['tovar_sale_currency'] = 1;
                            $data['tovar_sale_currency'] = 1;
                            $data['tovar_dimension'] = 1;
                            
                            $product_id = $ProductEdit->addProduct($data);
                            //echo '==='.$product_id.'<br>';
                        }
                        
                        //Сохраним цвет для товара
                        $sql = 'INSERT INTO `tbl_attribute_to_tovar` SET
                                tovar_id = "'.$product_id.'",
                                attribute_id = "2",
                                attribute_value = "'.$color_name.'"
                                ON DUPLICATE KEY UPDATE
                                attribute_value = "'.$color_name.'";';
                        //echo $sql;
                        $folder->query($sql) or die(' - '.$sql);
                        
                        //Сохраним этот линк для этого поставщика на этот продукт.
                        $sql = 'INSERT INTO tbl_tovar_links SET
                                product_id = \''.$product_id.'\',
                                postav_id = \''.$postav_id.'\',
                                url = \''.$original_url.'\'
                                ON DUPLICATE KEY UPDATE
                                url = \''.$original_url.'\'
                                ';
                        //echo $sql;
                        $folder->query($sql) or die(' - '.$sql);
                        
                        //Проверим - может наценка на размер
                        $str_price_2 = $str_price;
                        if($siz != ''){
                            if(isset($nacen[$siz])){
                                $str_price_2 = (int)((int)$str_price_2 * ((100+(int)$nacen[$siz]) / 100));     
                            }
                        }
                        
                        //Запишем наличие и цены
                        $sql = 'INSERT INTO tbl_tovar_suppliers_items SET
                                `tovar_id` = \''.$product_id.'\',
                                `postav_id`=\''.$postav_id.'\',
                                `price_1`=\''.$str_price_2.'\',
                                `items`=\''.$qty.'\'
                                    ON DUPLICATE KEY UPDATE 
                                `price_1`=\''.$str_price.'\',
                                `items`=\''.$qty.'\';';
                        $folder->query($sql) or die('Остатки - '.$sql);
                        
                    
                    }
                    //Найдем цвет продукта
                    //$ProductEdit->setColorOnProductName($product_id);
                    
                     $noload = true;//ВАЖНО ! Загружаем библиотеку без кодировки УРЛ
                    //Загрузим фото - только если у товара нет фото
                    if(!file_exists(UPLOAD_DIR.$photo_name.'/'.$photo_name.'.0.small.jpg')) {
                        foreach($photos as $str_img){
                        
                            $direct_load = true;
                            $IMGPath = $str_img;
                            $str_artkl = $photo_name;
                            include '../import/import_url_photo.php';
                        }
                    
                    }else{
                           //include '../import/import_url_getfile.php';
                    }
                    
                    echo '<h3><a href="edit_tovar.php?tovar_id='.$product_id.'" target="_blank">Редактор продукта - тут</a></h3>';
            //return true;
            }
        
           
    }

    
?>