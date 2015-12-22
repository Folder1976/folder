<?php

echo '<h1>Импорт Альтернативые артиклы</h1>';
set_time_limit(0);


$postavID = '12778'; //КОТТЕМ
$parent_error = 0;
$parent_error_str = 0;
$supplier_error = array();
$supplier_error['count'] = 0;
$supplier_error['str'] = '';

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

echo "<h2>Загрузите фаил</h2>
    <form name='import_exel_carfit' method=post enctype=\"multipart/form-data\">
    	    <br>Выбрать поставщика:
	    <select name=\"klienti_id\">
	    <option value=\"0\">Выбрать поставщика!</option>";
    
            foreach($Suppliers as $index => $val){
                    echo '<option value="'.$index.'">'.$val.'</option>';
            }
            
    echo "</select><br><br>
          <b>Лист:</b>
          <input type=\"text\" name=\"excel_table_name\" style=\"width:300px;\" placeholder=\"Имя листа! по умолчанию - первый\">
          <br><br>
        Фаил для импорта :
        <input type=\"file\" name=\"excel_kottem\">
        <input type=\"submit\" value=\"Загрузить\"></h4>
		</form>";
	
?>
<ul class="setup_menu">Памятка!
    <li>Поля (порядок полей не обязателен, главное колонки без пропусков!):</li>
    <li><b>tovar_artkl</b> Артикул товара к которому будет применен фильтр</li>
    <li><b>tovar_postav_artkl</b> Альтернативный артикл для товара</li>
    <li>&nbsp;</li>
</ul>
<?php
if(!isset( $_FILES['excel_kottem']['tmp_name'])){
    die();
}

if(!isset($_POST['klienti_id']) OR (isset($_POST['klienti_id']) AND $_POST['klienti_id'] == '0')){
    echo '<h1>Не указан поставщик!!!</h1>';
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

  
  //Определимся с поставщиком
  $postav_id = 0;

  //Если есть ид и ононе пустое - взяли и отвалили
    $postav_id = $_POST['klienti_id'];
  
 //Если указан ид фильтра и он не пустой - обновим это ид - если нет Добавим 
 if(isset($row['tovar_artkl'])){
    $sql = 'INSERT INTO tbl_tovar_postav_artikl SET
                tovar_artkl = "' . $row['tovar_artkl'] . '",
                tovar_postav_artkl = "' . $row['tovar_postav_artkl'] . '",
                postav_id = "' . $postav_id . '"
                on duplicate key UPDATE tovar_artkl = "' . $row['tovar_artkl'] . '";';
    $folder->query($sql);
    $update++;
 }
   
   $count++;
}

?>
<h2>Отчет импорта </h2>
<ul class="setup_menu">
    <li>Всего строк в файле : <?php echo $rows;?></li>
    <li>Обновлено : <?php echo $update;?></li>
     
</ul>
    
