<?php

echo '<h1>Импорт остатки и цены</h1>';
set_time_limit(0);

include('class/class_product_edit.php');
$ProductEdit = new ProductEdit($folder);


//Получим поставщиков
$supplier = $folder->query("SELECT `klienti_id`,`klienti_name_1`,`klienti_phone_1` FROM tbl_klienti WHERE `klienti_group`='5' ORDER BY `klienti_name_1` ASC");
if (!$supplier)
{
  echo "Query error - tbl_Supplier";
  exit();
}else{
  $Suppliers = array('0' => 'Для всех (без учета поставщика)');
  while($tmp = $supplier->fetch_assoc()){
    $Suppliers[$tmp['klienti_id']] = $tmp['klienti_name_1'];
  }
}

/*
$postavID = '12778'; //КОТТЕМ
$parent_error = 0;
$parent_error_str = 0;
$supplier_error = array();
$supplier_error['count'] = 0;
$supplier_error['str'] = '';
*/
echo "<h2>Загрузите фаил</h2>
    <form name='import_exel_carfit' method=post enctype=\"multipart/form-data\">
			Поставщик : <select name=\"supplier\">";

			foreach($Suppliers as $index => $value){
			    echo '<option value="'.$index.'">'.$value.'</option>';			    
			}
				
echo "</select>	<br><br>	Фаил для импорта : <input type=\"file\" name=\"excel_kottem\">
			<input type=\"submit\" value=\"Загрузить\"></h4>
		</form>";
	
?>
<ul class="setup_menu">Памятка!
    <li>Поля (порядок полей не обязателен, главное колонки и строки без пропусков!):</li>
    <li><b>tovar_artkl</b> Артикул(или Альтернативный) товара к которому будет применен фильтр (обязательное поле!!!)</li>
    <li><b>zakup</b> Цена закупа. (Если колонка не указана или поле пустое - будет проигнорировано)</li>
    <li><b>price</b> Цена розницы. (Если колонка не указана или поле пустое - будет проигнорировано)</li>
    <li><b>items</b> Количество товара (Если колонка не указана или поле пустое - будет проигнорировано)</li>
    <li>&nbsp;</li>
</ul>
<?php
if(!isset( $_FILES['excel_kottem']['tmp_name'])){
    die();
}

$postavID = $_POST['supplier'];

$tmpFilename = $_FILES['excel_kottem']['tmp_name'];

require_once ('docs/PHPExcel/IOFactory.php');

$worksheet = PHPExcel_IOFactory::load($tmpFilename)->getSheet(0);

if(!$worksheet) {die('<h2>Ошибка: лист c данными не найден</h2>');}
$rows = $worksheet->getHighestRow();

$add = 0;
$update = 0;
$add_suppliers = 0;

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

 
    //Найдем товар
    $product_ids = $ProductEdit->getProductIdOnArtiklAndSupplier($row['tovar_artkl'], $postavID);
 
 
 echo '<br>'.$product_ids[0].' '.$row['tovar_artkl'];
 
 
 /* 
  //Определимся с поставщиком
  $postav_id = 0;

  //Если есть ид и ононе пустое - взяли и отвалили
  if(isset($row['postav_id']) AND $row['postav_id'] != ''){
    $postav_id = $row['postav_id'];
    
  }else{
    //Если его нет или оно пустое - посмотрим может нам скормили Имя поставщика
    if(isset($row['postav_name']) AND $row['postav_name'] != ''){
        
        //Если случайно залетел НОЛЬ
        if($row['postav_id'] == '0'){
            $postav_id = $row['postav_id'];
        }else{
            $sql = 'INSERT INTO tbl_klienti
                    SET klienti_name_1 = "'.$row['postav_name'].'",
                            klienti_group = "5",
                    klienti_memo = "Добавлен через импорт альтернативных артиклей"';
            $folder->query($sql);
            $postav_id = $folder->insert_id;
            $add_suppliers++;
        }
    }
    
  }
  
 //Если указан ид фильтра и он не пустой - обновим это ид - если нет Добавим 
 if(isset($row['id']) AND $row['id'] != ''){
    $sql = 'UPDATE tbl_tovar_postav_artikl SET
                tovar_artkl = "' . $row['tovar_artkl'] . '",
                tovar_postav_artkl = "' . $row['tovar_postav_artkl'] . '",
                postav_id = "' . $postav_id . '"
                WHERE  id = "' . $row['id'] . '";';
    $folder->query($sql);
    $update++;
 }else{
    $sql = 'INSERT INTO tbl_tovar_postav_artikl SET
            tovar_artkl = "' . $row['tovar_artkl'] . '",
            tovar_postav_artkl = "' . $row['tovar_postav_artkl'] . '",
            postav_id = "' . $postav_id . '";';
    $folder->query($sql);
    $add++;
 }
 */ 
   
   $count++;
}

?>
<h2>Отчет импорта </h2>
<ul class="setup_menu">
    <li>Всего строк в файле : <?php echo $rows;?></li>
    <li>Добавлено : <?php echo $add;?></li>
    <li>Обновлено : <?php echo $update;?></li>
    <li>Добавил поставщиков : <?php echo $add_suppliers;?></li>
     
</ul>
    
