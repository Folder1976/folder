<?php

echo '<h1>Импорт KOTTEM</h1>';
set_time_limit(0);

include_once('../class/class_alias.php');
$Alias = new Alias($folder);

$postavID = '12778'; //КОТТЕМ
$parent_error = 0;
$parent_error_str = 0;

echo "<h2>Загрузите фаил</h2>
    <form name='import_exel_carfit' method=post enctype=\"multipart/form-data\">
			Фаил для импорта :
			<input type=\"file\" name=\"excel_kottem\">
			<input type=\"submit\" value=\"Загрузить\"></h4>
		</form>";
	

if(!isset( $_FILES['excel_kottem']['tmp_name'])){
    die();
}



$tmpFilename = $_FILES['excel_kottem']['tmp_name'];

require_once ('docs/PHPExcel/IOFactory.php');


//Берем автикуры и ИД товаров
$AllProduct = array();
$sql= "SELECT tovar_id, tovar_artkl FROM tbl_tovar WHERE tovar_supplier = '$postavID';";
$tovar = $folder->query($sql);
while($tmp = $tovar->fetch_assoc()){
    $AllProduct[$tmp['tovar_artkl']] = $tmp['tovar_id'];
}

//Берем атрибуты
$AllAttribute = array();
$sql= "SELECT attribute_id, attribute_name FROM tbl_attribute;";
$tovar = $folder->query($sql);
while($tmp = $tovar->fetch_assoc()){
    $AllAttribute[mb_strtolower($tmp['attribute_name'], 'UTF-8')] = $tmp['attribute_id'];
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

while($count <= $rows){
   
    //Прочитаесм строчку
       $x = 0;
    $attributes = array();
    $row = array();
    while('' != $worksheet->getCellByColumnAndRow($x,1)->getValue()){
        $row[$worksheet->getCellByColumnAndRow($x,1)->getValue()] = $worksheet->getCellByColumnAndRow($x,$count)->getCalculatedValue();
        $x++;
    }
    //echo '<pre>'; print_r(var_dump($row));
    
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
        $sql = 'INSERT INTO tbl_tovar SET
            tovar_artkl = \''.$row['code'].'\',
            tovar_supplier = \''.$postavID.'\';';
        $folder->query($sql);
        $id = $folder->insert_id;
     }
   
//Исправляем записи в базе
    //Получим алиасы
    $sql = 'SELECT seo_url FROM tbl_seo_url
            WHERE seo_alias = \''.$row['parent'].'\';';
    $parent_sql = $folder->query($sql);
    if($parent_sql->num_rows == 0){
        $parent_error++;
        $parent_error_str .= ','.$count;
        continue;
    }
    
    $tmp = $parent_sql->fetch_assoc();
    $tmp = explode('=',$tmp['seo_url']);
    $parent = $tmp[1];
    
    //Сгенерируем алиас для товара
    $alias = $Alias->getAliasFromStr($row['name']);
    
    //Пишем в товары
    $sql = 'UPDATE tbl_tovar SET
            tovar_name_1 = \''.$row['name'].'\',
            tovar_memo = \''.$row['memo'].'\',
            tovar_inet_id_parent = \''.$parent.'\',
            tovar_inet_id = \'10\',
            tovar_dimension = \''.$row['dimm'].'\',
            tovar_purchase_currency = \''.$row['currency'].'\',
            tovar_sale_currency = \''.$row['currency'].'\',
            tovar_parent = \'2\'
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
    $sql = 'INSERT INTO tbl_warehouse_unit SET
            warehouse_unit_tovar_id = \''.$id.'\'';
    $folder->query($sql) or die ($sql.'<br><br>'.mysql_error());
    
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

?>
<h2>Отчет импорта </h2>
<ul>
    <li>Всего строк в файле : <?php echo $rows;?></li>
    <li>Добавлено : <?php echo $add;?></li>
    <li>Переименовано : <?php echo $rename;?></li>
    <li>Перенесено : <?php echo $move;?></li>
    <li>Не найдено парентов : <?php echo $parent_error;?>, Строки: <?php echo $parent_error_str;?></li>

    
</ul>
    
