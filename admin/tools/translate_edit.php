<?php

include '../init.lib.php';
connect_to_mysql();
session_start();
if (!session_verify($_SERVER["PHP_SELF"],"none")){
  exit();
}

header ('Content-Type: text/html; charset=utf8');
echo "<header><link rel='stylesheet' type='text/css' href='sturm.css'>
<title>Псевдопереводчик</title>
</header>";
echo '<h3><a href=\'/admin/setup.php\'>>> Настройки</a></h3>';
echo "<br><ul class=\"setup_menu\">Операции с таблицей автозамены";
echo "<li><a href='translate_export.php' target='_blank'>Экспорт таблицы</a></li>";
echo "<li><a href='translate_edit.php?import'>Импорт таблицы</a></li>";
echo "</ul>";

if(!isset($_GET['import'])) die();

?>
<h2>Загрузите фаил (Режим перезаписи!!! старые данные будут удалены!)</h2>
    <form name="import_exel" method="post" enctype="multipart/form-data">
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
    <h3>Обязательные поля</h3>
    <ul>
        <li><b>find</b> - Слово(фраза) котороен будет заменено</li>
        <li><b>replace</b> - Слово(фраза) которым заменяем</li>
    </ul>
<?php
if(!isset( $_FILES['excel_kottem']['tmp_name'])) die();

$tmpFilename = $_FILES['excel_kottem']['tmp_name'];

require_once ('../docs/PHPExcel/IOFactory.php');

$worksheet = PHPExcel_IOFactory::load($tmpFilename)->getSheet(0);

if(!$worksheet) {die('<h2>Ошибка: лист c данными не найден</h2>');}

$rows = $worksheet->getHighestRow();

$Read = 0;
$count =2;

//Если в файле чтото есть - очищаем таблицу и перезаписываем
if($rows > 1){

    $sql = 'DELETE FROM tbl_translate;';
    $folder->query($sql);

}

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
 
    $sql = 'INSERT INTO `tbl_translate`(`find`, `replace`) VALUES ("'.$row['find'].'","'.$row['replace'].'");';
        
    $folder->query($sql);
    $count++;
}
  
?>

