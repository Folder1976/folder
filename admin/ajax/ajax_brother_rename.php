<?php
	include_once ('../config/config.php');
	session_start();
	$old_art = '';
	if(isset($_POST['old_art'])) $old_art = $_POST['old_art'];
	
	$new_art = '';
	if(isset($_POST['new_art'])) $new_art = $_POST['new_art'];
	
	$name = '';
	if(isset($_POST['name'])) $name= $_POST['name'];
	
	$model = '';
	if(isset($_POST['model'])) $model= $_POST['model'];
	
	$color = '';
	if(isset($_POST['color'])) $color= $_POST['color'];
	
	$brand = '0';
	if(isset($_POST['brand'])) $brand= $_POST['brand'];
	
	$category = '0';
	if(isset($_POST['category'])) $category= $_POST['category'];
	
	$show = '0';
	if(isset($_POST['show'])) $show= $_POST['show'];
	
	$ids = '';
	if(isset($_POST['ids'])) $ids = $_POST['ids'];
	
//Имя сменим полюбасу
$sql = 'UPDATE tbl_tovar SET tovar_name_1 = \''.$name.'\' WHERE tovar_id IN ('.$ids.');';
echo $sql;
$folder->query($sql) or die($sql);

	//Если сменили артикл
if($old_art != $new_art){
	
	$sql = 'SELECT tovar_id, tovar_artkl FROM tbl_tovar WHERE tovar_id IN ('.$ids.');';
	echo $sql;
	$r = $folder->query($sql) or die($sql);
	if($r->num_rows > 0){
		
		while($tovar = $r->fetch_assoc()){
			
			$art = $tovar['tovar_artkl'];
			
			$art = str_replace($old_art, $new_art, $art);
			
			$sql = 'UPDATE tbl_tovar SET
						tovar_artkl = \''.$art.'\'
						WHERE tovar_id = \''.$tovar['tovar_id'].'\';';
			$folder->query($sql) or die('Ошибка1 = ' . $sql);
			echo $sql;
			
			$pic_name = $new_art.'/'.$new_art.'.0.small.jpg';
			$sql = 'UPDATE tbl_tovar_pic SET
					tovar_artkl = \''.$new_art.'\',
					pic_name = \''.$pic_name.'\'
					WHERE tovar_artkl = \''.$old_art.'\';';
			$folder->query($sql) or die('Ошибка2 = ' . $sql);
			
		}
		echo ' конец';
	}
	$uploaddir = UPLOAD_DIR;
	rename($uploaddir.$old_art, $uploaddir.$new_art);
	
	$new_folder = $uploaddir.$new_art;
	$dh  = opendir($new_folder);
	while (false !== ($filename = readdir($dh))) {
		//echo $filename.'}';
		if($filename != '.' AND $filename != '..'){
			$new_file = str_replace($old_art, $new_art, $filename);
		
			rename($new_folder.'/'.$filename, $new_folder.'/'.$new_file);
		}
	}
	
}

//Сменим бренд
if($brand > 0){
	$sql = 'UPDATE tbl_tovar SET
				brand_id = \''.$brand.'\'
				WHERE tovar_id IN ('.$ids.');';
	$folder->query($sql) or die($sql);
}

//Сменим Категорию
if($brand > 0){
	$sql = 'UPDATE tbl_tovar SET
				tovar_inet_id_parent = \''.$category.'\'
				WHERE tovar_id IN ('.$ids.');';
	$folder->query($sql) or die($sql);
}

//Сменим модель
	$sql = 'UPDATE tbl_tovar SET
				tovar_model = \''.$model.'\'
				WHERE tovar_id IN ('.$ids.');';
	$folder->query($sql) or die($sql);
	//echo $sql;

//Сменим цвета

	$tmp = explode(',', $ids);
	foreach($tmp as $id){	
		$sql = 'INSERT INTO tbl_attribute_to_tovar SET
					tovar_id = \''.$id.'\',
					attribute_id = \'2\',
					attribute_value = \''.$color.'\'
					on duplicate key update
					attribute_value = \''.$color.'\';';
		//echo $sql;
		$folder->query($sql) or die($sql);
		
	}		


//Сменим время
//Установим дату и пользователя редактировавшего
$date = date("Y-m-d G:i:s");
$sql = 'UPDATE tbl_tovar SET
		tovar_inet_id = \''.$show.'\',
		tovar_last_edit = \''.$date.'\',
		tovar_last_edit_user = \''.$_SESSION[BASE.'userid'].'\'
		WHERE tovar_id IN ('.$ids.');';
$folder->query($sql) or die('add product - ' . $sql);


return true;
?>
