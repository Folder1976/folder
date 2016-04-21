<?php

error_reporting(E_ALL ^ E_DEPRECATED);

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
        <li><b>id</b> - id товара на Штурме</font></li>
        <li><b>code</b> - Наш артикл (можно и с размером - пофиг)</font></li>
    </ul>
<h4>Это только покажет файлы доступные для парсинга, для оценки - стоит ли парсить эти фотки вообще. Сам парсинг у Котлярова.</h4>
    
<?php

if(!isset( $_FILES['excel_kottem']['tmp_name'])){
    die();
}

$tmpFilename = $_FILES['excel_kottem']['tmp_name'];

require_once ('docs/PHPExcel/IOFactory.php');
require_once ('class/class_load_photo.php');
$LoadPhoto = new LoadPhoto($folder);


if(isset($_POST['excel_table_name']) AND $_POST['excel_table_name'] != ''){
    $worksheet = PHPExcel_IOFactory::load($tmpFilename)->getSheetByName($_POST['excel_table_name']);
}else{
    $worksheet = PHPExcel_IOFactory::load($tmpFilename)->getSheet(0);
}

if(!$worksheet) {die('<h2>Ошибка: лист c данными не найден</h2>');}
$rows = $worksheet->getHighestRow();

$parent_now = 0; //Тут храним парент на котором находимся при прохождении

//while($count <= $rows){
//Пока не встретим пустую строку
$msg = '';
$row_count=0;
$find = array();
$count = 2;
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
 

    //Если это размерный товар - возьмем его парент
    if($row['id'] > 0 AND strpos($row['code'],'#') !== false){
        
        $sql = 'SELECT tovar_inet_id_parent FROM tbl_tovar_sturm WHERE tovar_id = \''.$row['id'].'\';';
        $r = $folder->query($sql);
        
        if($r->num_rows > 0){
            
            $tmp = $r->fetch_assoc();
            $key = 'GR'.$tmp['tovar_inet_id_parent'];
            
            $tmp = explode('#', $row['code']);
            $row['code'] = $tmp[0];
            
        }
    }else{
        $key = $row['id'];
    }
    
        $x=0;
        $www = 'http://sturm.com.ua/resources/products/'.$key.'//'.$key.'.'.$x.'.small.jpg';
            
        if(!isset($find[$key])){
            echo '<br>'. $row['code'].'<img src="'.$www.'" width="100px">'.$www;
        
        }
    
    //Флажек что такой фаил уже пролетал
    $find[$key] = '1';
    $count++;
}
?>
<h2>Отчет импорта </h2>
<ul>
    <li>Всего строк в файле : <?php echo $rows.' ('.$row_count.')';?></li>
</ul>
<?php
echo $msg;
?>
