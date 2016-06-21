<?php
include '../config/config.php';
include '../simple_html_dom/simple_html_dom.php';
include 'constants.php';
include_once '../import/import_url_getfile.php';
include '../class/class_product_edit.php';
$ProductEdit = new ProductEdit($folder);

/*
$html = str_get_html("<div>foo <b>bar</b></div>");
echo $html; // выведет <div>foo <b>bar</b></div>;
$e = $html->find("div", 0);
echo $e->tag; // Вернет: "div"
echo $e->outertext; // Вернет: <div>foo <b>bar</b></div>
echo $e->innertext; // Вернет: foo <b>bar</b>
echo $e->plaintext; // Вернет: foo bar
*/
$brand_id = 296;
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
    $find = array('РУБ', 'Код товара:', 'Цена:', 'руб.', 'qty:', '+', ' ');
    $rep = array('','','','','','','');
    
    $postav_id = 44;
    //$return['web'] = 'http://sturmuniform.ru/';
    $PREF = 'MG_';
    //если нам влетело list = делаем массив
   
        $list = array($url);

    
    foreach($list as $url){
            $original_url = $url;
            $html = file_get_html($url);
            
            
            //Взяли прайс
            $name = $html->find('h1', 0);
          
            //Имя
            $str_name = $name->innertext();
          
            //Размеры
            $size = array();
            $str_artkl = '';
            $qty = '';
            $str_price = 0;
            //$brand_id = 0;
            $category_id = 0;
            $parent = $_POST['parent'];
            
            $blok = $html->find('.product-info', 0);
            
            $tmp_html = str_get_html($blok);
            $str_artkl = '';
            //if($str_tmp = $tmp_html->find('.product-number span', 0)){
            //    $str_artkl = $str_tmp->innertext();
            //}
            //$str_artkl = str_replace($find, $rep, $str_tmp);
            $tmp_url = trim($url, '/');
            $t = explode('/', $tmp_url);
            $str_artkl = $PREF.$t[count($t)-1];
            
            $str_tmp = $tmp_html->find('.product-price span', 0)->innertext();
            $str_price = str_replace($find, $rep, $str_tmp);
            
            $memo = $tmp_html->find('.product-desc', 0)->innertext();
            $memo .= $tmp_html->find('.product-characteristic', 0)->innertext();
            $memo .= $tmp_html->find('.product-materials', 0)->innertext();
            
            $str_size_url = $tmp_html->find('.product-size a', 0)->href;
           
            $str_tmp = $tmp_html->find('.product-size #ddl-sizes', 0)->innertext();
            $tmp_html = str_get_html($str_tmp);
                    $str_tmp = $tmp_html->find('option');
                   foreach($str_tmp as $option){
                        if($option->innertext() != '' AND $option->innertext() != 'ВЫБРАТЬ РАЗМЕР'){
                            $size[] = $option->innertext();
                        }
                    }
            //Поищем цвет
            //$html = file_get_html($url);
            $html_color = $html->find('.product-colors-wrapper', 0)->innertext();
            
            $html = str_get_html($html_color);
            $colors = array();
            foreach($html->find('.product-color') as $p){
                $r11 = str_get_html($p->innertext());
                
                $code = $p->innertext();
                
                $code1 = explode('data-variation', $code);
                $code1 = explode('"', $code1[1]);
                $code = $code1[1];
                
                $src =  $r11->find('img',0)->src;
                $color =  $r11->find('img',0)->alt;
                
                $colors[$code]['code'] = $code;
                $colors[$code]['src'] = $src;
                $colors[$code]['color'] = $color;

                //echo '<br>'.$code;
                //echo '<br>'.$src;
                //echo '<br>'.$color;
                
            }
            
            //Ищем все фото
            //Загружаем главные фото ==================================
            $html = file_get_html($url);
            foreach($html->find('a') as $p){
                $url = $p->href;
                if(strpos($url,'photos') !== false){
                    foreach($colors as $color => $var){
                        if(strpos($url, $color) !== false){
                            
                            $s = 'http://www.magellanrus.ru'.$url;
                            
                            //Хуйня короче с этими фото - будем грузито НО КОДЕ
                            //Нехуй два раза перекодировать! Его перекодирует загрузчик!
                           /* $i = parse_url($s); 
                            $p = ''; 
                            foreach(explode('/',trim($i['path'],'/')) as $v) {$p .= '/'.rawurlencode($v);} 
                            $ttt = $i['scheme'].'://'.$i['host'].$p; 
                            */
                            $colors[$color]['url'] = $s;
                        }
                    }
                      
                }
            }
            
            //Загружаем второстепенные фото
            foreach($html->find('img') as $p){
                $url = $p->src;
                if(strpos($url,'photos/products') !== false){
                     foreach($colors as $color => $var){
                        if(strpos($url, $color) !== false){
                            
                            $s = 'http://www.magellanrus.ru'.$url;
                            $i = parse_url($s); 
                            $p = ''; 
                            foreach(explode('/',trim($i['path'],'/')) as $v) {$p .= '/'.rawurlencode($v);} 
                            $ttt = $i['scheme'].'://'.$i['host'].$p; 
                            
                            $colors[$color]['photo'][$ttt] = $ttt;
                            //echo '<hr>'.$url;
                        }
                     }
                }
            }
            
            echo  '<br><b>Название</b>zaz - '.$str_name;
            echo  '<br><b>Артикл</b> - '.$str_artkl;
            echo  '<br><b>Цена</b> - '.$str_price . ' руб.';
            echo  '<br><b>Размер табл</b> - '.$str_size_url;
            //echo  '<br>'.$memo;
            echo '<br><b>Размеры</b> - ' . implode(', ', $size);
            //echo  '<br><pre>'; print_r(var_dump($colors)); echo '</pre>';
            //echo  '<hr><br>размер <pre>'; print_r(var_dump($size)); echo '</pre>';
            //return true;
            $str_artkl = ru2lat($str_artkl);
            $qty = 1;
    //return true;
            $model = $str_artkl;
            foreach($colors as $color_code => $color_values){
                    $color_number = 0;
                    if(!isset($magelan_color_table[trim($color_values['color'], ' ')])){
                        echo '<br> - Не смог определить цвет = <b>'.trim($color_values['color'], ' ').'</b><hr>';
                        continue;
                    }
                    
                    $color_name = trim($color_values['color'], ' ');
                    $color_number = $magelan_color_table[$color_name];
    
    //echo '<br><b>'.$color_name.' => '.$color_number.'</b>';
    
                    $size_t = $size;
                    foreach($size_t as $siz){
                          
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
                        
                        //Запишем наличие и цены
                        $sql = 'INSERT INTO tbl_tovar_suppliers_items SET
                                `tovar_id` = \''.$product_id.'\',
                                `postav_id`=\''.$postav_id.'\',
                                `price_1`=\''.$str_price.'\',
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
                        if(isset($color_values['photo'])){
                            foreach($color_values['photo'] as $str_img){
                            
                                $direct_load = true;
                                $IMGPath = $str_img;
                                $str_artkl = $photo_name;
                                include '../import/import_url_photo.php';
                            }
                        }else{
                            echo '<br>Нет фото';    
                        }
                    
                    }else{
                           //include '../import/import_url_getfile.php';
                    }
                    
                    //Загрузим фото таблицы размера и подвяжем его к таблице в товаре
                    if($str_size_url){
                                              
                        $uploaddir = UPLOAD_DIR;
                        $Tdate = DownloadFile($str_size_url);
                       
                        if (!$Tdate === null) {
                            //return false;
                        }else{
                            touch($uploaddir);
                            $file = $uploaddir.'../img/'.$photo_name.'_table_size.jpg';
                            $file_url = '/resources/img/'.$photo_name.'_table_size.jpg';
                            if(file_put_contents($file, $Tdate)){
                                //Запишем линк на таблицу
                                $sql = 'UPDATE tbl_tovar SET
                                        `tovar_size_table` = \'<img src="'.$file_url.'" title="Картинка таблица размеров для '.$str_name. ', цвет ' . $color_name . ''.'">\'
                                        WHERE
                                        `tovar_artkl`LIKE\''.$str_artkl.'%\';';
                                $folder->query($sql) or die('Картинка размеров - '.$sql);
                            }
                        }
                    }
                     echo '<h3><a href="edit_tovar.php?tovar_id='.$product_id.'" target="_blank">Редактор продукта - тут</a></h3>';
            //return true;
            }
        
           
    }

    
?>