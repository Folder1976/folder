<?php
    include_once ('../config/config.php');
    $uploaddir = UPLOAD_DIR.'!category/';
    global $folder, $setup;
   
    //echo 'ajax load photo - ok';
    // print_r(var_dump($_GET));  
    $IMGPath = $_GET['url'];
    $Tdate = DownloadFile($IMGPath);
   
    if (!$Tdate === null) {
		return false;
    }

    //touch($uploaddir);
    
    if(!file_put_contents($uploaddir.'from_url_tmp.jpg', $Tdate)){
		echo 'Не удалось загрузить фаил';
		exit();
    }
	
	include '../init.class.upload_0.31.php';
    $ext = 'jpg';
	$name = $_GET['category_id'];
	$new_name = '';
	
	
	unlink($uploaddir.$name.'.large.jpg');
	unlink($uploaddir.$name.'.medium.jpg');
	unlink($uploaddir.$name.'.small.jpg');
	
    //Обрещаем фотку и копируем ее в папку товара БОЛЬШОЙ РАЗМЕР
    $handle = new upload($uploaddir.'from_url_tmp.jpg');//www.verot.net/php_class_upload_docs.htm
    if($handle->uploaded)
    {
	$new_name = $name.".large";
	$handle->file_new_name_body = $new_name;
	$handle->image_resize=true;
	$handle->image_background_color = '#FFFFFF';
	$handle->image_ratio_fill = "C";
	$handle->image_x= 900; 
	$handle->image_y= 900; 
 	$handle->image_convert = "jpg";
	$handle->jpeg_quality = 60;
	$handle->file_overwrite=true;
	$handle->process($uploaddir);
	$handle->clean($uploaddir);
    }
    copy($uploaddir.$new_name.".".$ext,$uploaddir."from_url_tmp.".$ext);
    
 
    //Обрещаем фотку и копируем ее в папку товара СРЕДНИЙ РАЗМЕР
    $handle = new upload($uploaddir."from_url_tmp.".$ext);//www.verot.net/php_class_upload_docs.htm
    if($handle->uploaded)
    {
		$new_name = $name.".medium";
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
    copy($uploaddir.$new_name.".".$ext,$uploaddir.'from_url_tmp.'.$ext);
    
    
    //Обрещаем фотку и копируем ее в папку товара МАЛЫЙ РАЗМЕР
    $handle = new upload($uploaddir."from_url_tmp.".$ext);//www.verot.net/php_class_upload_docs.htm
    if($handle->uploaded)
    {
	$new_name = $name.".small";
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
    //copy($uploaddir.$new_name.".".$ext,$uploaddir.$name."/".$new_name.".".$ext);
    
    //unlink($uploaddir."from_url_tmp.".$ext);
  
   
//Выплюнем путь к фото для подключения его в родительском модуле   
echo '/resources/products/!category/'.$new_name.".".$ext;
    
   
   
    
    
    /**
     * The function to download files
     *
     * @param string $url
     * @return mixed|null
     */
    function DownloadFile($url)
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

?>
