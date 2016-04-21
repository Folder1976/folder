<?php

include '../init.lib.php';
include '../init.class.upload_0.31.php';

connect_to_mysql();//phpinfo();

session_start();
if (!session_verify($_SERVER["PHP_SELF"],"none")){
  exit();
}

$count=0;

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
  
  $type= "medium";
  if(isset($_POST['type'])) $type = $_POST['type'];
 
  $name = $_POST['filename'];
 
  $catalog = 'catalog';
  
  if($type == 'medium') $catalog = 'catalog';
  if($type == 'large') $catalog = 'mainpage';

  $uploaddir = UPLOAD_DIR . '../banners/'.$catalog.'/';

  $filename = $_POST['filename'].'_'.$_FILES['userfile']['name'];
  
  $uploadfile = $uploaddir . $filename;


  if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
	 
	 $sql = 'UPDATE tbl_baner SET baner_pic = \''. $filename .'\' WHERE baner_id=\''.$_POST['filename'].'\';';
	 //echo $sql;
	 $folder->query($sql);
	 
	//echo "<font color=\"green\">Файл корректен и был успешно загружен.</font>";
  } else {
	  //echo "<font color=\"green\">Возможная атака с помощью файловой загрузки!</font>";
  }
  if($type == 'medium') header('Location: ../main.php?func=banner&type=medium');
  if($type == 'large') header('Location: ../main.php?func=banner&type=large');

?>
