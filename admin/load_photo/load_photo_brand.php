<?php

include '../init.lib.php';
include '../init.class.upload_0.31.php';

connect_to_mysql();//phpinfo();

session_start();
if (!session_verify($_SERVER["PHP_SELF"],"none")){
  exit();
}

$count=0;

  if($_FILES['brandfile']['error'][0] != 0)
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
  
  $name = "brand_code";
  if(isset($_POST['brand_code'])) $name = $_POST['brand_code'];
 
  $uploaddir = UPLOAD_DIR . '../brends/';
 /*
  if(!file_exists($uploaddir)){
      mkdir($uploaddir,0777);
      chmod($uploaddir,0777);
  }
  */
  $uploadfile = $uploaddir . $name . '.png';
 
  if (move_uploaded_file($_FILES['brandfile']['tmp_name'], $uploadfile)) {
	  //echo "<font color=\"green\">Файл корректен и был успешно загружен.</font>";
  } else {
	  //echo "<font color=\"green\">Возможная атака с помощью файловой загрузки!</font>";
  }

header('Location: /admin/edit_brands.php');
?>
