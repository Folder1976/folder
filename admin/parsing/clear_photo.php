<?php
set_time_limit(0);

$uploaddir = UPLOAD_DIR;

$count = 0;
$dir = 0;
$files = 0;
$html = '';
if ($handle = opendir($uploaddir)) {
    while (false !== ($file = readdir($handle))) { 
        if ($file != "." && $file != "..") { 
			
			//Если это категория - ищем по этому артиклу товар. Если его нет - удаляем категорию
			if(is_dir($uploaddir.$file) AND $file != '!category'){
				$sql = "SELECT tovar_id FROM tbl_tovar WHERE tovar_artkl LIKE '$file%'";
				$r = $folder->query($sql);
				if($r->num_rows == 0){
	
					$html .= '<hr>'.$uploaddir.$file."";
					if ($handle_in = opendir($uploaddir.$file.'/')) {
						 while (false !== ($file_in = readdir($handle_in))) { 
							if ($file_in != "." && $file_in != "..") {
								
								$html .= '<br>dell - '.$uploaddir.$file.'/'.$file_in;
								unlink($uploaddir.$file.'/'.$file_in);
								$files++;
							}
						 }
					}
					
					$html .= '<br><b>main_dell</b> - '.$uploaddir.$file;
					$dir++;
					rmdir($uploaddir.$file);
				}
			}
			
        
		
		
		
		}
		
		$count++;
		//limit
		//if($count > 220) die();
    }
    closedir($handle); 
}

//Подчистим базу
	$sql = "SELECT t1.description_tovar_id FROM tbl_description AS t1 WHERE t1.description_tovar_id NOT IN (SELECT t2.tovar_id FROM tbl_tovar AS t2)";
	$r = $folder->query($sql);
	if($r->num_rows > 0){
		echo '<br>Описания - <b>'.$r->num_rows.'</b>';
		$por = array();
		while($tmp = $r->fetch_assoc()){
			$por[] = $tmp['description_tovar_id'];
		}
		$sql = "DELETE FROM tbl_description WHERE description_tovar_id IN (".implode(',',$por).");";
		$r = $folder->query($sql);
	}

	
echo "<br>Удалил: <br>Категорий - $dir<br>Файлов - $files";
echo '<hr>'.$html;
