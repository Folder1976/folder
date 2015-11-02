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
                <tr>
                    <td><b>В какой колонке оригинальный код поставщика:</b></td>
                    <td><select name="supplier_code" style="width:300px;">
                        <option value="code" selected>code</option>
                        <option value="name">name</option>
                        <option value="memo">memo</option>
                       </select></td>
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
    <h3>Обязательные поля</h3>
    <ul>
        <li><b>code</b> - Артикуль товара</li>
        <li><b>on_ware</b> - Наличие на складе (0 - Нет, 1 - Есть, 2 - По наличию на складе)</li>
        <li><b>parent</b> - Алиас категории на сайте</li>
        <li><b>group</b> - Внетренняя группа продуктов</li>
        <li><b>name</b> - Название продукта</li>
        <li><b>supplier</b> - Поставщик</li>
        <li><b>price1</b> - Цена закупа</li>
        <li><b>price2</b> - Розничная цена</li>
        <li><b>price3</b> - Мелкий опт</li>
        <li><b>price4</b> - Оптовая цена</li>
        <li><b>currency</b> - Валюта для цен (1 - ГРН, 2 - USD, 3 - EURO, 4 - Zl)</li>
        <li><b>dimm</b> - Измерения (1 - шт, 2 - уп, 3 - пар, 4 - м.п.)</li>
        <li><b>Photo</b> - URL фото для загрузки (1 шт) <font color=red>НОВОЕ</font></li>
        <li><b>memo</b> - Описание к товару</li>
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

//Берем поставщиков
$AllSupplier = array();
$sql= "SELECT klienti_id, klienti_name_1 FROM tbl_klienti WHERE klienti_group = '5';";// WHERE tovar_supplier = '$postavID';";
$tovar = $folder->query($sql);
while($tmp = $tovar->fetch_assoc()){
    $AllSupplier[$tmp['klienti_name_1']] = $tmp['klienti_id'];
}

//Берем внутренние группы
$AllGroups = array();
$sql= "SELECT tovar_parent_id, tovar_parent_name FROM tbl_parent;";// WHERE tovar_supplier = '$postavID';";
$tovar = $folder->query($sql);
while($tmp = $tovar->fetch_assoc()){
    $AllGroups[$tmp['tovar_parent_name']] = $tmp['tovar_parent_id'];
}




$worksheet = PHPExcel_IOFactory::load($tmpFilename)->getSheet(0);

if(!$worksheet) {die('<h2>Ошибка: лист c данными не найден</h2>');}
$rows = $worksheet->getHighestRow();

$add = 0;
$rename = 0;
$move = 0;

$Read = 0;
$count =2;

$parent_now = 0; //Тут храним парент на котором находимся при прохождении


//while($count <= $rows){
//Пока не встретим пустую строку
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
   
    $row['code'] = translitArtkl($row['code']);
    
    //Загрузка фоток
    $LoadPhoto->loadPhoto($row['code'],$row['Photo']);
    
    /*
    $artkl = $worksheet->getCell('A'. $count)->getValue();
    $parent_alias = $worksheet->getCell('B'. $count)->getValue();
    $name = $worksheet->getCell('C'. $count)->getValue();
    $price1 = $worksheet->getCell('D'. $count)->getCalculatedValue();
    $price2 = $worksheet->getCell('E'. $count)->getCalculatedValue();
    $price3 = $worksheet->getCell('F'. $count)->getCalculatedValue();
    $price4  = $worksheet->getCell('G'. $count)->getCalculatedValue();
    $curr  = $worksheet->getCell('H'. $count)->getValue();
    $dim  = $worksheet->getCell('I'. $count)->getValue();
    $memo = $worksheet->getCell('J'. $count)->getValue();
    */
    //Найдем ИД товара - если нет тогда создадим его
    if(isset($AllProduct[$row['code']])){
        $id = $AllProduct[$row['code']];
    }else{
        //Проверка на совпадения поля суплиер
        if(isset($AllSupplier[$row['supplier']])){
            $supplier = $AllSupplier[$row['supplier']];
        }else{
            $supplier = 1;
            $supplier_error['count']++;
            $supplier_error['str'] .= ', ' . $count;
        }
        $sql = 'INSERT INTO tbl_tovar SET
            tovar_artkl = \''.$row['code'].'\',
            tovar_supplier = \''.$supplier.'\'
            ON DUPLICATE KEY UPDATE tovar_artkl = \''.$row['code'].'\';';
        
        $folder->query($sql);
        $id = $folder->insert_id;
     }
     

  //echo '<pre>1 '.$AllProduct[$row['code']]; print_r(var_dump($row));die(); 
//Исправляем записи в базе
    //Получим алиасы
    $sql = 'SELECT seo_url FROM tbl_seo_url
            WHERE seo_alias = \''.$row['parent'].'\';';
            //echo $sql;die();
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
    
    //Сгенерируем алиас для товара
    $alias = $Alias->getAliasFromStr($row['name']);
    
    $alias = str_replace('//', '/', $row['parent'].'/'.$alias);
 
$row['name'] = str_replace('\'','"', $row['name']);
$row['memo'] = str_replace('\'','"', $row['memo']);

    //Пишем в товары
    $sql = 'UPDATE tbl_tovar SET
            tovar_name_1 = \''.$row['name'].'\',
            tovar_memo = \''.$row['memo'].'\',
            tovar_inet_id_parent = \''.$parent.'\',
            tovar_inet_id = \'10\',
            on_ware = \''.$row['on_ware'].'\',
            tovar_dimension = \''.$row['dimm'].'\',
            tovar_purchase_currency = \''.$row['currency'].'\',
            tovar_sale_currency = \''.$row['currency'].'\',
            original_supplier_link = \''.$row[$_POST['supplier_code']].'\',
            tovar_parent = \''.$AllGroups[$row['group']].'\'
            WHERE tovar_id = \''.$id.'\'';
   
    $folder->query($sql) or die ($sql.'<br><br>'.mysql_error());
   
    //Пишем в алиасы
    $sql = 'INSERT INTO tbl_seo_url SET
            seo_url = \'tovar_id='.$id.'\',
            seo_alias = \''.$alias.'\'
            on duplicate key update
            seo_alias = \''.$alias.'\'';
    $folder->query($sql) or die ($sql.'<br><br>'.mysql_error());
    //echo '<br>'.$sql;
    //Пишем в склады
    $sql = 'SELECT warehouse_unit_tovar_id FROM tbl_warehouse_unit WHERE warehouse_unit_tovar_id = \''.$id.'\'';
    $res = $folder->query($sql) or die ($sql.'<br><br>'.mysql_error());
    if($res->num_rows > 0){
    }else{
        $sql = 'INSERT INTO tbl_warehouse_unit SET
                warehouse_unit_tovar_id = \''.$id.'\'';
        $folder->query($sql) or die ($sql.'<br><br>'.mysql_error());
    }
   //Пишем в описание
    $sql = 'INSERT INTO tbl_description SET
            description_tovar_id = \''.$id.'\',
            description_1 = \''.$row['memo'].'\'
            on duplicate key update
            description_1 = \''.$row['memo'].'\'';
    $folder->query($sql) or die ($sql.'<br><br>'.mysql_error());
    
    //Пишем в цены
    $curr = $row['currency'];
    $sql = 'INSERT INTO tbl_price_tovar SET
            price_tovar_id = \''.$id.'\',
            price_tovar_1 = \''.$row['price1'].'\',
            price_tovar_curr_1 = \''.$curr.'\',
            price_tovar_cof_1 = \'1\',
            
            price_tovar_2 = \''.$row['price2'].'\',
            price_tovar_curr_2 = \''.$curr.'\',
            price_tovar_cof_2 = \'1\',
            
            price_tovar_3 = \''.$row['price3'].'\',
            price_tovar_curr_3 = \''.$curr.'\',
            price_tovar_cof_3 = \'1\',
            
            price_tovar_4 = \''.$row['price4'].'\',
            price_tovar_curr_4 = \''.$curr.'\',
            price_tovar_cof_4 = \'1\'
                on duplicate key update
            price_tovar_1 = \''.$row['price1'].'\',
            price_tovar_curr_1 = \''.$curr.'\',
            price_tovar_cof_1 = \'1\',
            
            price_tovar_2 = \''.$row['price2'].'\',
            price_tovar_curr_2 = \''.$curr.'\',
            price_tovar_cof_2 = \'1\',
            
            price_tovar_3 = \''.$row['price3'].'\',
            price_tovar_curr_3 = \''.$curr.'\',
            price_tovar_cof_3 = \'1\',
            
            price_tovar_4 = \''.$row['price4'].'\',
            price_tovar_curr_4 = \''.$curr.'\',
            price_tovar_cof_4 = \'1\'
            ';
              
    $folder->query($sql) or die ($sql.'<br><br>'.mysql_error());
    
    
    
    $attributes = $row;
    if(count($attributes) > 0){
        $sql = 'DELETE FROM tbl_attribute_to_tovar WHERE tovar_id = \''.$id.'\'';
        $folder->query($sql);
    // echo '<pre>';print_r(var_dump($AllAttribute));die(); 
        foreach($attributes as $index => $value){
        
           if($value != '' AND isset($AllAttribute[mb_strtolower($index, 'UTF-8')])){
                $sql = 'INSERT INTO tbl_attribute_to_tovar SET
                tovar_id = \''.$id.'\',
                attribute_id = \''.$AllAttribute[mb_strtolower($index, 'UTF-8')].'\',
                attribute_value = \''.$value.'\';';
                
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
    <li>Добавлено : <?php echo $add;?></li>
    <li>Переименовано : <?php echo $rename;?></li>
    <li>Перенесено : <?php echo $move;?></li>
    <li>Не найдено парентов : <?php echo $parent_error;?>, Строки: <?php echo $parent_error_str;?></li>
   <li>Не найдено поставщиков : <?php echo $supplier_error['count'];?>, Строки: <?php echo $supplier_error['str'];?></li>

    
</ul>
    
