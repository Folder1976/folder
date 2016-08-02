<?php
include '../config/config.php';
include '../simple_html_dom/simple_html_dom.php';
include 'constants.php';

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

//$brand_id = '296';
$brand_id = 0;
if(isset($_GET['brand'])) $brand_id = $_GET['brand'];

//Аллегро парсинг
    $find = array('Код товара:', 'Цена:', 'руб.', 'qty:', '+', ' ');
    $rep = array('','','','','','');
    
    $postav_id = 3;
    $return['web'] = 'http://sturmuniform.ru/';
   
    //если нам влетело list = делаем массив
    if($list != ''){
        $html = file_get_html($list);
        
        
        $str_tmp = $html->find('.itemNewProductsDefault h3');
        echo '<h4>Нашел такие сылки на товар</h4>';            
        
        $list = array();
        foreach($str_tmp as $option){
            
            $html_tmp = str_get_html($option->innertext());
            echo '<br>'.$option->innertext();    
            $list[] = $html_tmp->find('a',0)->href;    
        }
        
    }else{

        $list = array($url);

    }
    unset($html);
    
    foreach($list as $url){
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
            $category_id = 0;
            $parent = $_POST['parent'];
            
            foreach($html->find('.ProductInfoRight p') as $p){
                //echo '<br>=='.$p->innertext();
                
                $str_tmp = $p->innertext();
                
                //Если это блок с кодом
                if(strpos($str_tmp, 'Код товара')){
                    $str_artkl = (string)str_replace($find, $rep, $str_tmp);
                
                //Если наличие
                }elseif(strpos($str_tmp, 'qty')){
                    $qty = str_replace($find, $rep, $str_tmp);
                
                //Если это блок с размером
                }elseif(strpos($str_tmp, 'Размер')){
                    $tmp_html = str_get_html($str_tmp);
                    $str_tmp = $tmp_html->find('select', 0);
                    
                    $tmp_html = str_get_html($str_tmp);
                    $str_tmp = $tmp_html->find('option');
                    
                    foreach($str_tmp as $option){
                        $size[] = $option->innertext();    
                    }
                    
                //Если это блок с ценой
                }elseif(strpos($str_tmp, 'Цена')){
                    $tmp_html = str_get_html($str_tmp);
                    $str_tmp = $tmp_html->find('span', 0)->innertext();
                    $str_tmp = str_replace($find, $rep, $str_tmp);
                    $str_price = str_replace('.', '', $str_tmp);
                }
                
            }
          
          
          if($str_artkl == ''){
            $tmp = date('Y-m-d H:i:s');
            $str_artkl = substr(md5($tmp),0,25);
          }
          
          
            //Поищем описани - у него див со стилем
            $memo = '';
            foreach($html->find('div') as $div){
                
                if(strpos($div->style, 'background: none repeat scroll') !== false){
                    $memo = $div->innertext();   
                }
            }
          
            //Картинка
            $str_img = $return['web'] . $html->find('.ProductInfoLeft a', 0)->href;
            
            //Возьмем блок с инфой и разобьем его еще на два блока
            $str = $html->find('.ProductInfoRight', 0);
            $tmp = str_get_html($str);
            $price = $html->find('p', 0);
            
            
            
            //Прайс
            $str_artkl = ru2lat($str_artkl);
            //Имя товара
            $str_name = $name->innertext();
            echo '<hr>
                    <br>Название ['.$str_name.']';//+
            echo '<br>Артикл ['.$str_artkl.']';//+
            //echo '<br>Цена ['.$str_price.']';
            //echo '<br>Наличие ['.$qty.']';
            //echo '<br>Картинка ['.$str_img.']';
            echo '<br>Размер ['; print_r(var_dump($size)); echo ']'; //+
           // echo '<br>Описание [... Не выводится ...]'; //+ $memo
            
            //Если нам пришли данные - мы их подставим
            //if(isset(isset()))
            
            
            //Начнем писать товары ========================================================================
            //сначала проверим наличие артикла
            if($str_artkl == ''){
                
                echo '<br><font color = "yellow">Нет артикла!</font>';
                //die();
                $str_artkl = $_POST['artkl'];
            }
            
            if($_POST['artkl'] != ''){
                
                echo '<br><font color = "yellow">Артикл назначен принудительно!</font>';
                //die();
                $str_artkl = $_POST['artkl'];
            }
            
            //Поищем в базе - может есть
            if(count($size) > 1) unset($size[0]);
            
            $str_artkl = str_replace(' ', '', $str_artkl);
            
            $model = $str_artkl;
            foreach($size as $siz){
                
                $str_artkl =  $model;
        
                if($siz != ''){
                    $siz = str_replace('М', 'M', $siz);
                    $str_artkl = $model . '#' . $siz;
                }
        
                $str_artkl = str_replace(' ', '', $str_artkl);
                $str_artkl = str_replace(' ', '', $str_artkl);
                $str_artkl = str_replace(' ', '', $str_artkl);
                
                $product_ids = $ProductEdit->getProductIdOnArtiklAndSupplier($str_artkl);
                if(!$product_ids){
                    $product_ids = $ProductEdit->getProductIdOnURL($url);
                }
                //echo $product_id.'<br>';
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
                        echo '<br><font color="green">Нашел продукт <b>'.$product_id.'</b>. Если у него есть пустые поля которые я нашел - я их обновлю</font>';
                        
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
                
                    echo '<br>'.$str_artkl . ' ' . $str_name .' = Такого продукта нет, Пробую добавить';
                    
                    $data['tovar_artkl'] = $str_artkl;
                    $data['tovar_name_1'] = $str_name;
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
                
                //Сохраним этот линк для этого поставщика на этот продукт.
                $sql = 'INSERT INTO tbl_tovar_links SET
                        product_id = \''.$product_id.'\',
                        postav_id = \''.$postav_id.'\',
                        url = \''.$url.'\'
                        ON DUPLICATE KEY UPDATE
                        url = \''.$url.'\'
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
            $ProductEdit->setColorOnProductName($product_id);
            
            //Загрузим фото - только если у товара нет фото
            if(!file_exists(UPLOAD_DIR.$model.'/'.$model.'.0.small.jpg')) {
                
                $direct_load = true;
                $IMGPath = $str_img;
                $str_artkl = $model;
                include '../import/import_url_photo.php';
            
            }
            
            
        
            echo '<h3><a href="edit_tovar.php?tovar_id='.$product_id.'" target="_blank">Редактор продукта - тут</a></h3>';
    }
//print_r(var_dump($price));


//echo json_encode($return);
/*
Каждый найденный элемент и сам $html имеют 5 полей

$html = str_get_html("<div>foo <b>bar</b></div>");
echo $html; // выведет <div>foo <b>bar</b></div>;
$e = $html->find("div", 0);
echo $e->tag; // Вернет: "div"
echo $e->outertext; // Вернет: <div>foo <b>bar</b></div>
echo $e->innertext; // Вернет: foo <b>bar</b>
echo $e->plaintext; // Вернет: foo bar
$e->tag            Читает или записывает имя тега элемента.

$e->outertext   Читает или записывает весь HTML элемента, включая его самого.

$e->innertext   Читает или записывает внутренний HTML элемента

$e->plaintext    Читает или записывает простой текст элемента, это эквивалентно функции strip_tags($e->innertext). Хотя поле доступно для записи, запись в него ничего не даст, и исходный html не изменит
*/
?>