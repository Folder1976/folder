<?php
echo '<h1>Импорт остатки и цены</h1>';
set_time_limit(0);
echo '<h3><a href=\'/admin/setup.php\'>>> Настройки</a></h3>';


$postavID = '12778'; //КОТТЕМ
$parent_error = 0;
$parent_error_str = 0;
$supplier_error = array();
$supplier_error['count'] = 0;
$supplier_error['str'] = '';

//Берем поставщиков
$postav = array();
$sql= "SELECT klienti_id, klienti_name_1 FROM tbl_klienti WHERE klienti_group = 5 ORDER BY klienti_name_1 ASC;";

$r = $folder->query($sql);
while($tmp = $r->fetch_assoc()){
    $postav[$tmp['klienti_id']] = $tmp['klienti_name_1'];
}

?>
<h2>Загрузите фаил</h2>
    <form name="import_exel_carfit" method="post" enctype="multipart/form-data">
            <table>
                <tr>
                    <td><b>Поставщик:</b></td>
                    <td><select name="supplier" style="width:300px;">
                        <option value="0">Выбрать...</option>
                        <?php foreach($postav as $index => $value){ ?>
                            <?php if(isset($_POST['supplier']) AND $_POST['supplier'] == $index){ ?>    
                                <option selected value="<?php echo $index; ?>"><?php echo $value; ?></option>
                            <?php }else{ ?>
                                <option value="<?php echo $index; ?>"><?php echo $value; ?></option>
                            <?php } ?>
                        <?php } ?>
                        </select></td>
                </tr>
                <tr>
                    <td><b>Лист:</b></td>
                    <td><input type="text" name="excel_table_name" style="width:300px;" placeholder="Имя листа! по умолчанию - первый"></td>
                </tr>
                <tr>
                    <td><b>Фаил для импорта :</b></td>
                    <td><input type="file" name="excel_kottem" style="width:300px;"></td>
                </tr>
                <tr>
                    <td><b>Импортить цены :</b></td>
                    <td><input type="checkbox" name="import_price" checked></td>
                </tr>
                <tr>
                    <td><b>Импортить остатки :</b></td>
                    <td><input type="checkbox" name="import_item" checked></td>
                </tr>
                <tr>
                    <td><b>Обнулить поставщика перед импортом (Цены) :</b></td>
                    <td><input type="checkbox" name="import_update_price1" ></td>
                </tr>
                <tr>
                    <td><b>Обнулить поставщика перед импортом (Закупы) :</b></td>
                    <td><input type="checkbox" name="import_update_zakup" ></td>
                </tr>
                <tr>
                    <td><b>Обнулить поставщика перед импортом (Остатки) :</b></td>
                    <td><input type="checkbox" name="import_update_ostatki" ></td>
                </tr>
                <tr>
                    <td><b>Обнулить поставщика перед импортом (Все):</b></td>
                    <td><input type="checkbox" name="import_update_all" ></td>
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
        <li><b>code</b> - Артикуль товара  <font color=red>* обязательное поле (Будет искать в альтернативе поставщика, затем в базе товаров)</font></li>
        <li><b>items</b> - Остаток  <font color=red>если пусто будет - 0</font></li>
        <li><b>zakup</b> - Цена закупа</li>
        <li><b>zakup_curr</b> - Валюта закупа (по умолчанию <?php echo $curr_name[1];?>)
            <?php foreach($curr_name as $index => $value){ echo " ($index) - $value,";} ?>
        </li>
        <li><b>price</b> - Цена продажи (<?php echo $curr_name[1];?>)</li>
        <li><b>koef</b> - Коэф. наценки <font color=red>если пусто будет - будет взято у поставщика</font></li>
        <li><b>name</b> - Название продукта (Опционально. В импорте не участвует)</li>
    </ul>
 
<?php

if(!isset( $_FILES['excel_kottem']['tmp_name'])){
    die();
}

$supplier = 0;
if(isset($_POST['supplier']) AND $_POST['supplier'] > 0){
    $supplier = $_POST['supplier'] ;
}else{
    echo 'НЕ ВЫБРАН ПОСТАВЩИК!';
    die();
}

$tmpFilename = $_FILES['excel_kottem']['tmp_name'];

require_once ('docs/PHPExcel/IOFactory.php');

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
include_once ('class/class_product_edit.php');
$ProductEdit = new ProductEdit($folder);

$r = $folder->query('SELECT delivery_days, price_coef FROM tbl_klienti WHERE klienti_id = \''.$supplier.'\';');
$tmp = array();
$supplier_coef = 1.5;
while($tmp = $r->fetch_assoc()){
    $supplier_coef = $tmp['price_coef'];
}

$r = $folder->query('SELECT * FROM tbl_currency ORDER BY currency_id ASC;');
$currency_a = array();
while($tmp = $r->fetch_assoc()){
    $currency_a[$tmp['currency_id']] = $tmp['currency_ex'];
}

if(isset($_POST['import_update_all'])){
    
    $data['import_price'] = 0;
    $data['import_item']  = 0;
    if(isset($_POST['import_price'])){
        $data['import_price'] = 1;
    }
    if(isset($_POST['import_item'])){
        $data['import_item'] = 1;
    }
    //$ProductEdit->dellAllSupplierItems($supplier, $data);
}

if(isset($_POST['import_update_zakup'])){
   $r = $folder->query('UPDATE tbl_tovar_suppliers_items SET zakup = 0 WHERE postav_id = "'.$supplier.'";');
}

if(isset($_POST['import_update_price1'])){
   $r = $folder->query('UPDATE tbl_tovar_suppliers_items SET price_1 = 0 WHERE postav_id = "'.$supplier.'";');
}

if(isset($_POST['import_update_ostatki'])){
   $r = $folder->query('UPDATE tbl_tovar_suppliers_items SET items = 0 WHERE postav_id = "'.$supplier.'";');
}

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
 
    $code = $row['code'];
    $items = 0;
    if(isset($row['items'])) $items = $row['items'];
    
    $zakup = 0;
    if(isset($row['zakup'])) $zakup = $row['zakup'];

    if(isset($row['zakup_curr']) AND $row['zakup_curr'] > 0){
        $zakup_curr = $row['zakup_curr'];
    }else{
        $zakup_curr = 1;
    }
   
    if(isset($row['koef']) AND $row['koef'] > 0){
        $koef = $row['koef'];
    }else{
        $koef = $supplier_coef;
    }
   
    if(isset($row['price']) AND $row['price'] > 0){
        $price = $row['price'];
    }else{
        $price = ($zakup * $currency_a[$zakup_curr]) * $koef;
    }
    
    $ids = $ProductEdit->getProductIdOnArtiklAndSupplier($code, $supplier);
   
     
    if($ids AND count($ids) > 0){
        
        foreach($ids as $id){
             
            $data['id']         = $id;
            $data['postav_id']  = $supplier;
            $data['zakup']      = $zakup;
            $data['zakup_curr'] = $zakup_curr;
            $data['price_1']    = $price;
            $data['items']      = $items;
            $data['import_price'] = 0;
            $data['import_item']  = 0;
            if(isset($_POST['import_price'])){
                $data['import_price'] = 1;
            }
            if(isset($_POST['import_item'])){
                $data['import_item'] = 1;
            }
            
            
            $ProductEdit->addNewSupplierItem($data);    
        }
    }else{
        echo '<br>('.$count.')Не нашел - ' . $code;
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
