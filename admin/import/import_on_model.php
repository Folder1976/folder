<?php

echo '<h1>Импорт по моделям товаров (изменяет только существующие товары)</h1>';
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
<style>
    .content{
        padding-left: 10px;
    }
    .result{
        width:40%;
        padding-left: 10px;
        display: block;
        float: left;
    }
    .edit{
        width: 55%;
        display: block;
        float: left;

    }
    .find{
        width:400px;
        font-size: 18px;
        background-color: #F9C5C5;
        padding: 3px;
    }
    .submit{
        font-size:14px;
        width: 100px;
        height: 30px;
    }
</style>
<script src="//tinymce.cachefly.net/4.2/tinymce.min.js"></script>
<script>
    tinymce.init({selector:'textarea'});
    
    $(document).on('change','.find', function(){
        
        var str = $(this).val();
        
        $.ajax({
            url: 'import/ajax_get_products_find.php?find=' + str,
            dataType: 'json',
            beforeSend: function(){
                //console.log('before');
            },
            success: function(json){
                
                $('.result').html('<h4>Результат поиска(Эти товары будут изменены)</h4><table>');
                $('.id').val();
                
                $.each(json, function (index, data) {
                        console.log(index, data);
                        $('.id').val($('.id').val()+index+',');
                        $('.result').append('<tr><td>'+data['model']+'</td><td>'+data['artikl']+'</td><td>'+data.name+'</td></tr>');
                    });
                $('.result').append('</table>');
                
            }
        });
        
    });
</script>
<?php

if(isset($_POST['id'])){
    if($_POST['id'] != ''){
        
        $ids = $_POST['id'];
        $ids = trim($ids, ' ,');
        
        if($_POST['size_table'] != ''){
            $sql = 'UPDATE tbl_tovar SET tovar_size_table = \''.$_POST['size_table'].'\' WHERE tovar_id IN ('.$ids.');';
            $folder->query($sql);
        }
        
        if($_POST['video'] != ''){
            $sql = 'UPDATE tbl_tovar SET tovar_video_url = \''.$_POST['video'].'\' WHERE tovar_id IN ('.$ids.');';
            $folder->query($sql);
        }
        
        if($_POST['description'] != ''){
            $sql = 'UPDATE tbl_description SET description_1 = \''.$_POST['description'].'\' WHERE description_tovar_id IN ('.$ids.');';
            $folder->query($sql);
        }

        echo '<h3>Данные обновлены</h3>';
    }
}

?>
<div class="content">
    <h2>Работа с контентом</h2>
    <a href="main.php?func=import_on_model&file">Импортировать фаил</a><br>
    <a href="main.php?func=import_on_model&mode">Ручной режим</a><br>
        <?php if(isset($_GET['mode'])){ ?>
            <form METHOD="POST">
            <div class="edit">
                <h3>Ручной режим
                </h3>
                <input type="text" class="find" name='find' placeholder="Строка для поиска/выборкию Ищу по артиклу, моделе, названию!"/>
                <input type="hidden" class="id" name='id' placeholder="Строка для поиска/выборкию Ищу по артиклу, моделе, названию!"/>
                <input type="submit" class="submit" name="submit" value="Сохранить">
                    <br>
                        <font color="red">При записи пустые поля(блоки) буду проигнорированы</font>
                
                <h3>Описание товаров
                <a href='javascript:' class='translate' data-find-id='description'><b>Перевести</b></a>
                &nbsp;&nbsp;&nbsp;&nbsp;(<a href='tools/translate_edit.php' target='_blank'>Редактировать</a>)
                </h3>
                <textarea cols='50' width='500' rows='20' id='description' name='description'></textarea>  
                
                <h3>HTML код видео
                </h3>
                <input style="width:700px;" name='video' placeholder="html код видео с ютуба"/>
        
                <h3>Таблица размеров товара
                <a href='javascript:' class='translate' data-find-id='size_table'><b>Перевести</b></a>
                &nbsp;&nbsp;&nbsp;&nbsp;(<a href='tools/translate_edit.php' target='_blank'>Редактировать</a>)
                </h3>
                <textarea width='500' rows='10' id='size_table' name='size_table'></textarea>  
            </div>
            <div class="result">Тут список товарок к которым будет применены данные изменения</div>
            </form>
        <?php die();
            } ?>
</div>

    <!--============= -->
    <?php if(isset($_GET['file'])){ ?>
    <h3>Загрузите фаил</h3>
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
            <li><b>model</b> - Модель товара  <font color=red>* обязательное поле (Это уникальная часть артикла свойственная всей модели продукта)</font></li>
            <li><b>video</b> - URL или форма видео для закладки в описание товара <font color=red>НОВОЕ</font></li>
            <li><b>size</b> - Таблица или другая информация для закладки РАЗМЕР в описание товара <font color=red>НОВОЕ</font></li>
            <li><b>memo</b> - Описание к товару</li>
        </ul>
    <?php } ?>
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
$sql= "SELECT tovar_id, tovar_model FROM tbl_tovar;";// WHERE tovar_supplier = '$postavID';";
$tovar = $folder->query($sql);
while($tmp = $tovar->fetch_assoc()){
    $AllProduct[$tmp['tovar_model']] = $tmp['tovar_id'];
}


if(!isset($_POST['excel_table_name']) AND $_POST['excel_table_name'] != ''){
    $worksheet = PHPExcel_IOFactory::load($tmpFilename)->getSheetByName($_POST['excel_table_name']);
}else{
    $worksheet = PHPExcel_IOFactory::load($tmpFilename)->getSheet(0);
}

if(!$worksheet) {die('<h2>Ошибка: лист c данными не найден</h2>');}
$rows = $worksheet->getHighestRow();

$update = 0;
$not_find = 0;

$Read = 0;
$count =2;

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
 
    //Найдем ИД товара - если нет запишем в ошибку
    if(isset($AllProduct[$row['model']])){
        $tovar_model = $AllProduct[$row['model']];
    }else{
        $msg .= '<br><font color=red>Не найден - '.$row['model'].'</font>';
        continue;
    }


    //Исправляем записи в базе
    //Пишем в товары
    $sql = 'UPDATE tbl_tovar SET ';
        $x = 0;
        if(isset($row['video'])){    
            $row['video'] = $row['video'];
            $sql .= 'tovar_video_url = \''.str_replace('\'','"',$row['video']).'\',';
            $x++;
        }
        
        if(isset($row['size'])){    
            $row['size'] = str_replace('\'','"',$row['size']);
            $sql .= 'tovar_size_table = \''.$row['size'].'\',';
            $x++;
        }
        
        //Если изменения есть - пишем в базу
        if($x > 0){
            $sql = trim($sql, ' ,');
            $sql .= ' WHERE tovar_model = \''.$tovar_model.'\'';
   
            $folder->query($sql) or die ($sql.'<br><br>'.mysql_error());
        }

        //Пишем в описание
        if(isset($row['memo'])){    
  
            $row['memo'] = str_replace('\'','"', $row['memo']);
            
            $sql = 'UPDATE tbl_description SET
                        description_1 = \''.$row['memo'].'\'
                        WHERE description_tovar_id IN (SELECT tovar_id FROM tbl_tovar WHERE tovar_model = \''.$tovar_model.'\');';
                         
            $folder->query($sql) or die ($sql.'<br><br>'.mysql_error());
    
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
