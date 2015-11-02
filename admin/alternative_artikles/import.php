<?php

echo '<h1>Импорт Альтернативые артиклы</h1>';
set_time_limit(0);


$postavID = '12778'; //КОТТЕМ
$parent_error = 0;
$parent_error_str = 0;
$supplier_error = array();
$supplier_error['count'] = 0;
$supplier_error['str'] = '';

echo "<h2>Загрузите фаил</h2>
    <form name='import_exel_carfit' method=post enctype=\"multipart/form-data\">
			Фаил для импорта :
			<input type=\"file\" name=\"excel_kottem\">
			<input type=\"submit\" value=\"Загрузить\"></h4>
		</form>";
	
?>
<ul class="setup_menu">Памятка!
    <li>Поля (порядок полей не обязателен, главное колонки без пропусков!):</li>
    <li><b>id</b> - ID в таблице артикулов. Если пусто - будет добален новый фильтр</li>
    <li><b>tovat_artkl</b> Артикул товара к которому будет применен фильтр</li>
    <li><b>tovar_postav_artkl</b> Альтернативный артикл для товара</li>
    <li><b>postav_id</b> ID поставщика к которому будет применен фильтр, ( 0 ) - ВСЕМ! (если это поле пустое, а имя Поставщика заполнено - будет добавлен новый поставщик и его ид будет назначено этому фильтру. Если не указано не ИД не Имя - будет присвоено ( 0 ) ВСЕМ)</li>
    <li><b>postav_name</b> Имя поставщика. Нужно только при добавлении а базу нового поставщика.(Можно не указывать)</li>
    <li>&nbsp;</li>
    <li><font color="red"><b>Важно!</b></font> Не испортировать файлы с новыми(пустое ID) значеними несколько раз! Каждый раз эти значения не находя ID будут добавлятьс в базу!</li>
</ul>
<?php
if(!isset( $_FILES['excel_kottem']['tmp_name'])){
    die();
}



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
                tovat_artkl = "' . $row['tovat_artkl'] . '",
                tovar_postav_artkl = "' . $row['tovar_postav_artkl'] . '",
                postav_id = "' . $postav_id . '"
                WHERE  id = "' . $row['id'] . '";';
    $folder->query($sql);
    $update++;
 }else{
    $sql = 'INSERT INTO tbl_tovar_postav_artikl SET
            tovat_artkl = "' . $row['tovat_artkl'] . '",
            tovar_postav_artkl = "' . $row['tovar_postav_artkl'] . '",
            postav_id = "' . $postav_id . '";';
    $folder->query($sql);
    $add++;
 }
  
   
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
    
