<?php

echo '<h1>Импорт URL фото Excel</h1>';
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

?>
<h2>Загрузите фаил</h2>
    <form name="import_exel_carfit" method="post" enctype="multipart/form-data">
            <table>
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
    <h3>Обязательные поля (без колонтитулов)</h3>
    <ul>
        <li><b>Колонка A</b> - Артикуль товара</li>
        <li><b>Колонка В</b> - URL фото для загрузки (1 шт)</li>
    </ul>
    <img src="http://dl2.joxi.net/drive/0012/1581/796205/150914/f1506d933c.jpg" width="800">
<?php

if(!isset( $_FILES['excel_kottem']['tmp_name'])){
    die();
}

$tmpFilename = $_FILES['excel_kottem']['tmp_name'];

require_once ('docs/PHPExcel/IOFactory.php');
require_once ('class/class_load_photo.php');
$LoadPhoto = new LoadPhoto($folder);

$worksheet = PHPExcel_IOFactory::load($tmpFilename)->getSheet(0);

if(!$worksheet) {die('<h2>Ошибка: лист c данными не найден</h2>');}
$rows = $worksheet->getHighestRow();

//Пока не встретим пустую строку
$count=1;
$add = 0;
$not_load = '';
while('' != $worksheet->getCellByColumnAndRow(0,$count)->getValue()){

    $row['code'] = $worksheet->getCellByColumnAndRow(0, $count)->getValue();
    $row['Photo'] = $worksheet->getCellByColumnAndRow(1, $count)->getValue();
    
    $row['code'] = translitArtkl($row['code']);
    
    //Загрузка фоток
    $tmp = $LoadPhoto->loadPhoto($row['code'],$row['Photo']);
    
    if($tmp == 0){
        $not_load .= $tmp. ', ';   
    }
    $add += $tmp;
   
   $count++;
}

function translitArtkl($str) {
    $rus = array('и','і','є','Є','ї','\"','\'','.',' ','А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
    $lat = array('u','i','e','E','i','','','','-','A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya');
   return str_replace($rus, $lat, $str);
}
 
  
?>
<h2>Отчет импорта </h2>
<ul>
    <li>Всего строк в файле : <?php echo $rows.' ('.$count.')';?></li>
    <li>Добавлено : <?php echo $add;?></li>
    <li>Не загрузил строки : <?php echo trim($not_load, ' ,');?></li>
    
</ul>
    
