<?php
class LoadPhoto {
	
	private $base;
	private $separator;
	private $uploaddir;
	
	public function __construct($sql_connect) {
		$this->base = $sql_connect;
		$this->uploaddir = UPLOAD_DIR;
		
		global $setup;
		$this->separator = $setup['tovar artikl-size sep'];
		
	}
	
	public function loadPhoto($artkl, $url){
		
		if(strpos($url,'http') === false) return 0;
		
		if(strpos($artkl, $this->separator) !== false){
			$tmp = explode($this->separator, $artkl);
			$artkl = $tmp[0];
		}
		$name = $artkl;
		
		$IMGPath = $url;
		$Tdate = $this->DownloadFile($IMGPath);
	       
		if (!$Tdate === null) {
		    return 0;
		}
	    
		touch($this->uploaddir);
		
		if(!file_put_contents($this->uploaddir.'from_url_tmp.jpg', $Tdate)){
		    echo 'Не удалось загрузить фаил';
		    return 0;
		}
		
		/*    
		$res = $this->base->query('SELECT tovar_id FROM tbl_tovar WHERE tovar_artkl = \''.$_GET['tovar_id'].'\' OR tovar_artkl LIKE \''.$_GET['tovar_id'].$this->separator.'%\';') or die('123'.mysql_error());
		    
		if($res->num_rows == 0){
		    echo 'Не нащел товар';
		    exit();    
		}
			
		$tmp = $res->fetch_assoc();
		$tovar_id = $tmp['tovar_id'];
		*/
		
		$image_count = 0;
		//Если нет даже папки - создадим
		if(!file_exists($this->uploaddir.$name)){ 
		    mkdir($this->uploaddir.$name,0777);
		    chmod($this->uploaddir.$name,0777);
		}else{ //Если папка есть - найдем последнюю номерацию для добавления
		    if ($handle = opendir($this->uploaddir.$name)) {
		       
			while (false !== ($file = readdir($handle))) { 
			    if(strpos($file,'small') !== false){
				$image_count++;
			    }
			}
			
			closedir($handle); 
		    }
		}
		       
		//Запишем в таблицу имя картинки - не проверяем наличие этой записи 
		$firstname = "$name/$name.0.small.jpg";
		$sql = "INSERT INTO tbl_tovar_pic SET pic_name = '".$firstname."', tovar_artkl = '".$name."' ON DUPLICATE KEY UPDATE pic_name = '".$firstname."'";;
		$this->base->query($sql);
	       
		include_once 'init.class.upload_0.31.php';
		$ext = 'jpg';
	     
		//Обрещаем фотку и копируем ее в папку товара БОЛЬШОЙ РАЗМЕР
		$handle = new upload($this->uploaddir.'from_url_tmp.jpg');//www.verot.net/php_class_upload_docs.htm
		//echo $this->uploaddir.'from_url_tmp.jpg';
		//$new_name = $name.".".$image_count.".large";
		//echo $new_name;
		if($handle->uploaded)
		{
			$new_name = $name.".".$image_count.".large";
			$handle->file_new_name_body = $new_name;
			$handle->image_resize=true;
			$handle->image_background_color = '#FFFFFF';
			$handle->image_ratio_fill = "C";
			$handle->image_x= 900; 
			$handle->image_y= 900; 
			$handle->image_convert = "jpg";
			$handle->jpeg_quality = 60;
			$handle->file_overwrite=true;
			$handle->process($this->uploaddir);
			$handle->clean($this->uploaddir);
		}
		copy($this->uploaddir.$new_name.".".$ext,$this->uploaddir.$name."/".$new_name.".".$ext);
			
		//Обрещаем фотку и копируем ее в папку товара СРЕДНИЙ РАЗМЕР
		$handle = new upload($this->uploaddir.$new_name.".".$ext);//www.verot.net/php_class_upload_docs.htm
		if($handle->uploaded)
		{
			$new_name = $name.".".$image_count.".medium";
			$handle->file_new_name_body = $new_name;
			$handle->image_resize=true;
			$handle->image_background_color = '#FFFFFF';
			$handle->image_ratio_fill = "C";
			$handle->image_x= 450; 
			$handle->image_y= 450; 
			$handle->process($this->uploaddir);
			$handle->clean($this->uploaddir);
		}
		copy($this->uploaddir.$new_name.".".$ext,$this->uploaddir.$name."/".$new_name.".".$ext);
		
		//Обрещаем фотку и копируем ее в папку товара МАЛЫЙ РАЗМЕР
		$handle = new upload($this->uploaddir.$new_name.".".$ext);//www.verot.net/php_class_upload_docs.htm
		if($handle->uploaded)
		{
			$new_name = $name.".".$image_count.".small";
			$handle->file_new_name_body = $new_name;
			$handle->image_resize=true;
			$handle->image_background_color = '#FFFFFF';
			$handle->image_ratio_fill = "C";
			$handle->image_x= 150; 
			$handle->image_y= 150; 
			$handle->process($this->uploaddir);
			$handle->clean($this->uploaddir);
		}
		copy($this->uploaddir.$new_name.".".$ext,$this->uploaddir.$name."/".$new_name.".".$ext);
		
		unlink($this->uploaddir.$new_name.".".$ext);
		      
		       
		//Выплюнем путь к фото для подключения его в родительском модуле   
		//echo '../resources/products/'.$name."/".$new_name.".".$ext;
		return 1;	
		
	}
	
	/**
	* The function to download files
	*
	* @param string $url
	* @return mixed|null
	*/
	protected function DownloadFile($url)
	{
		if (!extension_loaded('curl')) {
		    return null;
		}
	
		$ch = curl_init();
	       
		curl_setopt_array(
			$ch,
			array(
				CURLOPT_AUTOREFERER => true,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_CONNECTTIMEOUT => 10,
				CURLOPT_TIMEOUT => 120,
				CURLOPT_URL => $url,
				CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.155 Safari/537.3',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_HTTPHEADER => array(
				    'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
				    'Accept-Encoding:gzip, deflate, sdch',
				    'Accept-Language:ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4',
				    'Cache-Control:max-age=0',
				    'Connection:keep-alive',
				    )
			)
		);
	
	
		$data = curl_exec($ch);
		if (curl_errno($ch) != CURLE_OK) {
		    return null;
		}
	
		return $data;
	}
	

}
?>