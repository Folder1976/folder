<?php

ini_set('post_max_size', "1G");
ini_set('max_file_uploads', "200");
ini_set('upload_max_filesize', "1G");
ini_set('memory_limit', "1024M");
set_time_limit(0);
//echo phpinfo();
global $separator;

echo '<h1>Импорт Фотографий</h1>';
echo '<h2>Имена файлов в следующем формате (Артикуль товара # порядковый номер)
<br> * - соблюдение порядка не обязательно. Главной станет фото с наименьшим порядковым номером</h2>
<br><font color="red">Если добавляете новые фото для новых товаров - нужна двойная заливка!</font>
';
set_time_limit(0);

echo "<form enctype='multipart/form-data' method='post'>";
echo "<table border = 0 cellspacing='0' cellpadding='0'>";
echo "<tr><td>Load photo:</td><td>"; # Group name 1
echo "<input type='hidden' name='func' value='import_photo'>";
?>
    <Br>
    <input type="radio" name="browser" value="new"> Загрузить как новые (все фото которые уже есть у найденых товаров будут удалены)<Br>
    <input type="radio" name="browser" value="add" checked=checked> Добавить фото к уже имеющимся у товара<Br>
    <Br>
<?php
echo "<input type='hidden' name='MAX_FILE_SIZE' value='",1048*1048*1048*1048,"'>";
echo "<input type='file' min='1' max='999' multiple='true' style='width:200px'  name='userfile[]' /></td></tr>";
echo "<tr><td colspan='2' align='center' style=\"height:50px;\"><input type='submit' style='width:200px'  name='submit' value='Загрузить' /></td></tr>";
echo "</table></form>"; 
echo "</body>";
//================

if(!isset($_FILES['userfile'])){
    exit();
}

 if($_FILES['userfile']['error'][0] != 0)
  {
      switch($_FILES['userfile']['error'])
      {
	case 1: echo "UPLOAD_MAX_FILE_SIZE error!<br>";break;
	case 2: echo "MAX_FILE_SIZE erroe!<br>";break;
	case 3: echo "Not file loading, breaking process.<br>";break;
	case 4: echo "Not load<br>";break;
	case 6: echo "tmp folder error<br>";break;
	case 7: echo "write file error<br>";break;
	case 8: echo "php stop your load<br>";break;
      }
  }

include 'init.class.upload_0.31.php';

//Получим пути для сохранения файлов
    $pref = "";
    $ext = "jpg";
    $name = '';
    $uploaddir = UPLOAD_DIR;
 
$filecount=0;
$filelist = array();

//Загрузим файлы и сохраним их имена в массив
while(isset($_FILES['userfile']['error'][$filecount])){
  
    $handle = new upload($_FILES['userfile']['tmp_name'][$filecount]);//www.verot.net/php_class_upload_docs.htm
    
    $tmp = explode('.',$_FILES['userfile']['name'][$filecount]);
    $tmp[0] = str_replace('#', 'folderseparator', $tmp[0]);
    $filelist[] = $name = $tmp[0];

    
    if($handle->uploaded)
    {
    $handle->file_new_name_ext = $ext;
    $handle->file_new_name_body = $name;
    $handle->image_convert = "jpg";
    $handle->jpeg_quality = 60;
   // $handle->png_compression = 30;
    $handle->file_overwrite=true;
    $handle->process($uploaddir);
    $handle->clean($uploaddir);
   }
  $filecount++;  
}

sort($filelist);
$add = 0;
$not_found = 0;
//пойдем по массиву и разделим имена на артикл и номер
 foreach($filelist as $filename){
  
    if(strpos($filename, 'folderseparator')){
	list($name,$x) = explode('folderseparator', $filename);
    }else{
	$name = $filename;
	$x = 0;
    }
    
    //Найдем товар в базе
    $sql = 'SELECT tovar_id FROM tbl_tovar WHERE tovar_artkl = \''.$name.'\' OR tovar_artkl like \''.$name.$separator.'%\'';
    $res = $folder->query($sql);
    //Нет товаров с таким артикулом
    if($res->num_rows == 0){
	echo '<br>'.$name.' - товар не найден!';
	unlink($uploaddir.$filename.".".$ext);
	$not_found++;
	continue;
    }
    
    //Если нашли такой товар - проверим что у него в папке
    $image_count = 0;
    if(!file_exists($uploaddir.$name)){ //Если нет даже папки - создадим
	mkdir($uploaddir.$name,0777);
	chmod($uploaddir.$name,0777);

    }else{ //Если папка есть - найдем последнюю номерацию для добавления
	if ($handle = opendir($uploaddir.$name)) {
	    
	    //Если стоит флаг очищать директорию и она еще не очищалась - поставим флаг чтоб почистить
			$dell = false;
	    if($_POST['browser'] == 'new' AND !isset($is_dell[$uploaddir.$name])){
			$dell = true;
	    }
	    
	    while (false !== ($file = readdir($handle))) { 
			
			if(strpos($file,'small') !== false){
				$image_count++;
			}
			
			//Если установлено КАК НОВЫЕ - проверим не удаляли ли мы уже файлы из этой директории и очистим ее и счетчик фоток
			if($dell AND $file != '.' AND $file != '..'){
				unlink($uploaddir.$name.'/'.$file);
				$image_count = 0;
				$is_dell[$uploaddir.$name] = $uploaddir.$name;
			}
    
	    }
	    
	    closedir($handle); 
	}
    }

    //Запишем в таблицу с картинками что у товара появилась картинка
    $firstname = "$name/$name.0.small.jpg";
    $sql = "INSERT INTO tbl_tovar_pic SET pic_name = '".$firstname."', tovar_artkl = '".$name."' ON DUPLICATE KEY UPDATE pic_name = '".$firstname."'";;
    $folder->query($sql);
   
    //Обрещаем фотку и копируем ее в папку товара БОЛЬШОЙ РАЗМЕР
    $handle = new upload($uploaddir.$filename.".".$ext);//www.verot.net/php_class_upload_docs.htm
    if($handle->uploaded)
    {
	$new_name = $name.".".$image_count.".large";
	$handle->file_new_name_body = $new_name;
	$handle->image_resize=true;
	$handle->image_background_color = '#FFFFFF';
	$handle->image_ratio_fill = "C";
	$handle->image_x= 900; 
	$handle->image_y= 900; 
       // $handle->file_overwrite=true;
       // $handle->file_auto_rename=false;
	$handle->process($uploaddir);
	$handle->clean($uploaddir);
    }
    copy($uploaddir.$new_name.".".$ext,$uploaddir.$name."/".$new_name.".".$ext);

echo '<br>'.$uploaddir.$name."/".$new_name.".".$ext;
    
    //Обрещаем фотку и копируем ее в папку товара СРЕДНИЙ РАЗМЕР
    $handle = new upload($uploaddir.$new_name.".".$ext);//www.verot.net/php_class_upload_docs.htm
    if($handle->uploaded)
    {
	  $new_name = $name.".".$image_count.".medium";
      $handle->file_new_name_body = $new_name;
      $handle->image_resize=true;
      $handle->image_background_color = '#FFFFFF';
      $handle->image_ratio_fill = "C";
      $handle->image_x= 450; 
      $handle->image_y= 450; 
     // $handle->file_overwrite=true;
     // $handle->file_auto_rename=false;
      $handle->process($uploaddir);
      $handle->clean($uploaddir);
    }
    copy($uploaddir.$new_name.".".$ext,$uploaddir.$name."/".$new_name.".".$ext);
    
    
    //Обрещаем фотку и копируем ее в папку товара МАЛЫЙ РАЗМЕР
    $handle = new upload($uploaddir.$new_name.".".$ext);//www.verot.net/php_class_upload_docs.htm
    if($handle->uploaded)
    {
	$new_name = $name.".".$image_count.".small";
      $handle->file_new_name_body = $new_name;
      $handle->image_resize=true;
      $handle->image_background_color = '#FFFFFF';
      $handle->image_ratio_fill = "C";
      $handle->image_x= 150; 
      $handle->image_y= 150; 
     // $handle->file_overwrite=true;
     // $handle->file_auto_rename=false;
      $handle->process($uploaddir);
      $handle->clean($uploaddir);
    }
    copy($uploaddir.$new_name.".".$ext,$uploaddir.$name."/".$new_name.".".$ext);
    
    unlink($uploaddir.$new_name.".".$ext);
    $add++;
    
 }
 

?>
<h2>Отчет импорта </h2>
<ul>
    <li>Всего загружено файлов: <?php echo $filecount;?></li>
    <li>Добавлено : <?php echo $add;?></li>
    <li>Не найдено в базе товаров : <?php echo $not_found;?></li>

    
</ul>
<?php
    unset($res);
?>