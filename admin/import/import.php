<?php

echo '<h1>Импорт УНИВЕРСАЛ</h1>';
set_time_limit(0);
echo '<h3><a href=\'/admin/setup.php\'>>> Настройки</a></h3>';
include_once('../class/class_alias.php');
$Alias = new Alias($folder);


$postavID = '12778'; //КОТТЕМ
$parent_error = 0;
$parent_error_str = 0;
$supplier_error = array();
$supplier_error['count'] = 0;
$supplier_error['str'] = '';

//Берем атрибуты
$AllAttribute = array();
$sql= "SELECT attribute_id, attribute_name FROM tbl_attribute;";
$tovar = $folder->query($sql);
while($tmp = $tovar->fetch_assoc()){
    $AllAttribute[mb_strtolower($tmp['attribute_name'], 'UTF-8')] = $tmp['attribute_id'];
}

?>
<h2>Загрузите фаил</h2>
    <form name="import_exel_carfit" method="post" enctype="multipart/form-data">
            <table>
                <!--tr>
                    <td><b>В какой колонке оригинальный код поставщика:</b></td>
                    <td><select name="supplier_code" style="width:300px;">
                        <option value="code" selected>code</option>
                        <option value="name">name</option>
                        <option value="memo">memo</option>
                       </select></td>
                </tr-->
                <tr>
                    <td><b>Лист:</b></td>
                    <td><input type="text" name="excel_table_name" style="width:300px;" placeholder="Имя листа! по умолчанию - первый"></td>
                </tr>
                <tr>
                    <td><b>Фаил для импорта :</b></td>
                    <td><input type="file" name="excel_kottem" style="width:300px;"></td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <input type="submit" value="Загрузить" style="width:300px;">
                    </td>
                </tr>
                
            </table>
    </form>
    <h2>Памятка по колонкам</h2>
    <h3>Обязательное поле CODE(артикул товара)</h3>
    
    <ul>
        <li><b>code</b> - Артикуль товара  <font color=red>* обязательное поле (Алиас товара будет сгенерирован по формуле parent/brand_code/tovar_artkl)</font></li>
        <li><b>model</b> - Модель товара  <font color=red>* обязательное поле (Это уникальная часть артикла свойственная всей модели продукта)</font></li>
        <li><b>on_ware</b> - Наличие на складе (0 - Нет, 1 - Есть, 2 - По наличию на складе) Если не указан - будет 2</li>
        <li><b>parent</b> - Алиас категории на сайте. Если не указан - залетит в Мусорник</li>
        <li><b>group</b> - Внутренняя группа продуктов. Если не указан - будет в НЕ СОРТИРОВАННОЕ (id = 11)</li>
        <li><b>name</b> - Название продукта</li>
        <li><b>brand</b> - Код бренда (не ид!) Если не уазан - будет helikon-tex</li>
        <li><b>price2</b> - Розничная цена</li>
        <li><b>currency</b> - Валюта для цен (1 - Руб, 2 - USD, 3 - EURO, 4 - Zl) Если не указано - будет Руб.</li>
        <li><b>dimm</b> - Измерения (1 - шт, 2 - уп, 3 - пар, 4 - м.п.) Если не указано - будет Шт</li>
        <li><b>photo</b> - URL фото для загрузки (1 шт)</li>
        <li><b>video</b> - URL или форма видео для закладки в описание товара <font color=red>НОВОЕ</font></li>
        <li><b>size</b> - Таблица или другая информация для закладки РАЗМЕР в описание товара <font color=red>НОВОЕ</font></li>
        <li><b>memo</b> - Описание к товару</li>
        <li><font color=red>* - Значение подставляемые (если не указано) касается только новых продуктов</font></li>
    </ul>
    <h3>Дополнительные поля (Атрибуты - Характеристики и Фильтры)</h3>
    <p><font color="red">Если название атрибута не будет найдено то вся колонка будет пропущена!</font></p>
    <ul>
    <?php
        foreach($AllAttribute as $name => $index){
            echo '<li><b>'.$name.'</b></li>';
        }
    ?>
    </ul>
    
<?php

if(!isset( $_FILES['excel_kottem']['tmp_name'])){
    die();
}

;

$tmpFilename = $_FILES['excel_kottem']['tmp_name'];

require_once ('docs/PHPExcel/IOFactory.php');
require_once ('class/class_load_photo.php');
$LoadPhoto = new LoadPhoto($folder);

//Берем автикуры и ИД товаров
$AllProduct = array();
$sql= "SELECT tovar_id, tovar_artkl FROM tbl_tovar;";// WHERE tovar_supplier = '$postavID';";
$tovar = $folder->query($sql);
while($tmp = $tovar->fetch_assoc()){
    $AllProduct[$tmp['tovar_artkl']] = $tmp['tovar_id'];
}

//Берем бренды
$Brands = array();
$sql= "SELECT brand_id, brand_code FROM tbl_brand;";// WHERE tovar_supplier = '$postavID';";
$tovar = $folder->query($sql);
while($tmp = $tovar->fetch_assoc()){
    $Brands[$tmp['brand_code']] = $tmp['brand_id'];
}

//Берем внутренние группы
$AllGroups = array();
$sql= "SELECT tovar_parent_id, tovar_parent_name FROM tbl_parent;";// WHERE tovar_supplier = '$postavID';";
$tovar = $folder->query($sql);
while($tmp = $tovar->fetch_assoc()){
    $AllGroups[$tmp['tovar_parent_name']] = $tmp['tovar_parent_id'];
}

//Берем категории
$AllCategories = array();
$sql= "SELECT seo_url, seo_alias FROM tbl_seo_url WHERE seo_url LIKE 'parent=%';";
$tovar = $folder->query($sql);
while($tmp = $tovar->fetch_assoc()){
    $id = explode('=',$tmp['seo_url']);
    $AllCategories[$tmp['seo_alias']] = $id['1'];
}

if(isset($_POST['excel_table_name']) AND $_POST['excel_table_name'] != ''){
    $worksheet = PHPExcel_IOFactory::load($tmpFilename)->getSheetByName($_POST['excel_table_name']);
}else{
    $worksheet = PHPExcel_IOFactory::load($tmpFilename)->getSheet(0);
}

if(!$worksheet) {die('<h2>Ошибка: лист c данными не найден</h2>');}
$rows = $worksheet->getHighestRow();

$add = 0;
$update = 0;
$move = 0;

$Read = 0;
$count =2;

$parent_now = 0; //Тут храним парент на котором находимся при прохождении

//while($count <= $rows){
//Пока не встретим пустую строку
$msg = '';
$row_count=0;
while('' != $worksheet->getCellByColumnAndRow(0,$count)->getValue()){
    $row_count++;  
    


     //Прочитаесм строчку
       $x = 0;
    $attributes = array();
    $row = array();
    while('' != $worksheet->getCellByColumnAndRow($x,1)->getValue()){
        $row[$worksheet->getCellByColumnAndRow($x,1)->getValue()] = $worksheet->getCellByColumnAndRow($x,$count)->getCalculatedValue();
        $x++;
    }
 
 
    //$row['code'] = translitArtkl($row['code']);
    
    //Загрузка фоток
    if(isset($row['photo'])){
        $LoadPhoto->loadPhoto($row['code'],$row['photo']);
    }


    //Найдем ИД товара - если нет тогда создадим его
    if(isset($AllProduct[$row['code']])){
        $tovar_id = $AllProduct[$row['code']];
        $msg .= '<br>update - '.$row['code'];
    }else{
        
        $sql = 'INSERT INTO tbl_tovar SET
                    tovar_artkl = \''.$row['code'].'\',
                    tovar_model = \''.$row['model'].'\',
                    brand_id = \'1\',
                    tovar_name_1 = \'Какого фига новый товар заимпортился без названия!?!\',
                    tovar_parent = \'11\',
                    tovar_inet_id_parent = \'2\',
                    tovar_purchase_currency = \'1\',
                    tovar_sale_currency = \'1\',
                    tovar_dimension = \'1\',
                    tovar_inet_id = \'10\'
                    ;';
        
        $folder->query($sql);
        $tovar_id = $folder->insert_id;
        
        //Создадим вспомогательные таблицы
        //Склады
        $sql = 'INSERT INTO tbl_warehouse_unit SET
                warehouse_unit_tovar_id = \''.$tovar_id.'\'
                ON DUPLICATE KEY UPDATE warehouse_unit_0 = \'0\';';
        $folder->query($sql) or die ($sql.'<br><br>'.mysql_error());
    
        //Описание
        //Пишем в описание
        $sql = 'INSERT INTO tbl_description SET
                description_tovar_id = \''.$tovar_id.'\',
                description_1 = \'\'
                ON DUPLICATE KEY UPDATE description_1 = \'\';';
        $folder->query($sql) or die ($sql.'<br><br>'.mysql_error());
    
        //Пишем в цены
        $sql = 'INSERT INTO tbl_price_tovar SET
                        price_tovar_id = \''.$tovar_id.'\',
                        price_tovar_1 = \'0\',
                        price_tovar_curr_1 = \'1\',
                        price_tovar_cof_1 = \'1\',
                        price_tovar_2 = \'0\',
                        price_tovar_curr_2 = \'1\',
                        price_tovar_cof_2 = \'1\'
                    ON DUPLICATE KEY UPDATE
                        price_tovar_1 = \'0\',
                        price_tovar_curr_1 = \'1\',
                        price_tovar_cof_1 = \'1\',
                        price_tovar_2 = \'0\',
                        price_tovar_curr_2 = \'1\',
                        price_tovar_cof_2 = \'1\'
                    ;';
        $folder->query($sql) or die ($sql.'<br><br>'.mysql_error());
  
         
        $msg .= '<br><font color=green>add '.$row['code'].'</font>';
     }


    //Исправляем записи в базе
    //Получим алиасы

    if(isset($row['parent'])){
        $sql = 'SELECT seo_url FROM tbl_seo_url
                WHERE seo_alias = \''.$row['parent'].'\';';
        $parent_sql = $folder->query($sql);
        if($parent_sql->num_rows == 0){
            $parent_error++;
            $parent_error_str .= ','.$count;
            $count++;
            continue;
        }
   
        $tmp = $parent_sql->fetch_assoc();
        $tmp = explode('=',$tmp['seo_url']);
        $parent = $tmp[1];
    }
  
    //Сгенерируем алиас для товара
    $alias = $Alias->generateAlias($tovar_id);
  
    //Пишем в алиасы
    $sql = 'INSERT INTO tbl_seo_url SET
            seo_url = \'tovar_id='.$tovar_id.'\',
            seo_alias = \''.$alias.'\'
            on duplicate key update
            seo_alias = \''.$alias.'\'';
    $folder->query($sql) or die ($sql.'<br><br>'.mysql_error());
    
    
    //Пишем в товары
    $sql = '';

        if(isset($row['model'])){    
            $row['model'] = str_replace('\'','"', $row['model']);
            $sql .= 'tovar_model = \''.$row['model'].'\',';
        }
        
        if(isset($row['name'])){    
            $row['name'] = str_replace('\'','"', $row['name']);
            $sql .= 'tovar_name_1 = \''.$row['name'].'\',';
        }
        
        if(isset($row['brand']) AND $row['brand'] != ''){    
            $row['brand'] = str_replace('\'','"', $row['brand']);
            $sql .= 'brand_id = \''.$Brands[$row['brand']].'\',';
        }
        
        if(isset($row['video'])){    
            $row['video'] = $row['video'];
            $sql .= 'tovar_video_url = \''.str_replace('\'','"',$row['video']).'\',';
        }
        
        if(isset($row['size'])){    
            $row['size'] = str_replace('\'','"',$row['size']);
            $sql .= 'tovar_size_table = \''.$row['size'].'\',';
        }
        
        if(isset($row['on_ware'])){    
            $sql .= 'on_ware = \''.$row['on_ware'].'\',';
        }
        
        if(isset($row['dimm'])){    
            $sql .= 'tovar_dimension = \''.$row['dimm'].'\',';
        }
          
        if(isset($row['currency'])){    
            $sql .= 'tovar_purchase_currency = \''.$row['currency'].'\',
                    tovar_sale_currency = \''.$row['currency'].'\',';
        }
          
        if(isset($row['group'])){    
            $sql .= 'tovar_parent = \''.$AllGroups[$row['group']].'\',';
        }
  
        if(isset($row['parent'])){    
            $sql .= 'tovar_inet_id_parent = \''.$AllCategories[$row['parent']].'\',';
        }
  
        if($sql != ''){
            $sql = trim($sql, ' ,');
            $sql = 'UPDATE tbl_tovar SET '.$sql.' WHERE tovar_id = \''.$tovar_id.'\'';
            $folder->query($sql) or die ($sql.'<br><br>'.mysql_error());
        }
  //echo '<br>'.$sql; 
        //Пишем в описание
        if(isset($row['memo'])){    
  
            $row['memo'] = str_replace('\'','"', $row['memo']);
            
            $sql = 'UPDATE tbl_description SET
                        description_1 = \''.$row['memo'].'\'
                        WHERE description_tovar_id = \''.$tovar_id.'\';';
            $folder->query($sql) or die ($sql.'<br><br>'.mysql_error());
    
        }
 
    
    //Пишем в цены
    if(isset($row['currency']) OR isset($row['price2'])){
        $sql = 'UPDATE tbl_price_tovar SET ';
 
        if(isset($row['currency'])){    
            $sql .= 'price_tovar_curr_2 = \''.$row['currency'].'\',';
        }
        
        if(isset($row['price2'])){    
            $sql .= 'price_tovar_2 = \''.$row['price2'].'\',';
        }
   
        $sql = trim($sql, ' ,');
        $sql .= ' WHERE price_tovar_id = \''.$tovar_id.'\';';
        
        $folder->query($sql) or die ($sql.'<br><br>'.mysql_error());
    }
            
   
    
    
    
    $attributes = $row;
    if(count($attributes) > 0){

        foreach($attributes as $index => $value){
  
           if($value != '' AND isset($AllAttribute[mb_strtolower($index, 'UTF-8')])){
                $sql = 'INSERT INTO tbl_attribute_to_tovar SET
                tovar_id = \''.$tovar_id.'\',
                attribute_id = \''.$AllAttribute[mb_strtolower($index, 'UTF-8')].'\',
                attribute_value = \''.$value.'\'
                ON DUPLICATE KEY UPDATE attribute_value = \''.$value.'\';';
                
                //echo '<br>'.$sql;
                $folder->query($sql);
            } 
        }
    }
    
   $count++;
}

function translit($str) {
    $rus = array('и','і','є','Є','ї','\"','\'','.',' ','А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
    $lat = array('u','i','e','E','i','','','','-','A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya');
   return str_replace($rus, $lat, $str);
  }
    function clear_str($str) {
    $find = array('&quot;','\'');
    $replace = array('','');
    return str_replace($find, $replace, $str);
  }

  function translitArtkl($str) {
    $rus = array('и','і','є','Є','ї','\"','\'','.',' ','А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
    $lat = array('u','i','e','E','i','','','','-','A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya');
   return str_replace($rus, $lat, $str);
  }
 
  
?>
<h2>Отчет импорта </h2>
<ul>
    <li>Всего строк в файле : <?php echo $rows.' ('.$row_count.')';?></li>
</ul>
<?php
echo $msg;
?>
