<?php
include 'init.lib.php';
include 'init.class.upload_0.31.php';

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
  
  $pref = "";
  if($_REQUEST['type']=="parent") $pref="GR";
  $ext = "jpg";
  
  $name = $pref.$_REQUEST['tovar_id'];
  //Разделитель артикула на Артикул и размер
   $separator = $setup['tovar artikl-size sep'];
   //Разбиваем атрикл на тело и размер
     $artkl = $tovar_artikl = $_REQUEST['tovar_artkl'];
     $size = "none";
     if(strpos($tovar_artikl,$separator) !== false){
	 $x = explode($separator, $tovar_artikl);
	 $artkl = $x[0];
	 $size = $x[1];
     }
     $name = $link = $artkl;

  
  $uploaddir = $setup['tovar photo patch'].$name;
  echo $uploaddir,"<br>",$_REQUEST['type'],"<br>";
  $uploaddir .= "/";
  if(!file_exists($uploaddir)){
      mkdir($uploaddir,0777);
      chmod($uploaddir,0777);
    }
    
   

$filecount=0;
while(isset($_FILES['userfile']['error'][$filecount])){

   
    while(file_exists($uploaddir.$name.".".$count.".large.".$ext)){
	$count++;
      }
    
    //мы загружаем первый фаил - он же будет главный - пишем его в базу
    if($count == 0){
     $filename = "$name/$name.0.small.jpg";
     $setup = mysql_query("SET NAMES utf8");
	$tQuery = "INSERT INTO tbl_tovar_pic SET pic_name = '".$filename."', tovar_artkl = '".$name."' ON DUPLICATE KEY UPDATE pic_name = '".$filename."'";;
	$setup = mysql_query($tQuery);
    }
    
    $handle = new upload($_FILES['userfile']['tmp_name'][$filecount]);//www.verot.net/php_class_upload_docs.htm
    if($handle->uploaded)
    {
    $handle->file_new_name_ext = $ext;
    $handle->file_new_name_body = $name.".".$count.".origin";
    $handle->image_convert = "jpg";
    $handle->jpeg_quality = 60;
   // $handle->png_compression = 30;
    $handle->file_overwrite=true;
    $handle->process($uploaddir);
    $handle->clean($uploaddir);
   }
   echo $uploaddir.$name.".".$count.".origin.".$ext,$uploaddir.$name.".".$count.".tmp.".$ext;
    copy($uploaddir.$name.".".$count.".origin.".$ext,$uploaddir.$name.".".$count.".tmp.".$ext);
    $handle = new upload($uploaddir.$name.".".$count.".tmp.".$ext);//www.verot.net/php_class_upload_docs.htm
    if($handle->uploaded)
    {
      $handle->file_new_name_body = $name.".".$count.".large";
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
    copy($uploaddir.$name.".".$count.".origin.".$ext,$uploaddir.$name.".".$count.".tmp.".$ext);
    $handle = new upload($uploaddir.$name.".".$count.".tmp.".$ext);//www.verot.net/php_class_upload_docs.htm
    if($handle->uploaded)
    {
      $handle->file_new_name_body = $name.".".$count.".medium";
      $handle->image_resize=true;
      $handle->image_background_color = '#FFFFFF';
      $handle->image_ratio_fill = "C";
    //  $handle->image_ratio_pixels = 300;
      $handle->image_x= 500; 
      $handle->image_y= 500; 
     // $handle->file_overwrite=true;
     // $handle->file_auto_rename=false;
      $handle->process($uploaddir);
      $handle->clean($uploaddir);
    }
    
    copy($uploaddir.$name.".".$count.".origin.".$ext,$uploaddir.$name.".".$count.".tmp.".$ext);
    $handle = new upload($uploaddir.$name.".".$count.".tmp.".$ext);//www.verot.net/php_class_upload_docs.htm
    if($handle->uploaded)
    {
      $handle->file_new_name_body = $name.".".$count.".small";
      $handle->image_resize=true;
      $handle->image_background_color = '#FFFFFF';
      $handle->image_ratio_fill = "C";
      $handle->image_x= 150; 
      $handle->image_y= 150; 
      $handle->file_overwrite=false;
      $handle->file_auto_rename=false;
      $handle->process($uploaddir);
      $handle->clean($uploaddir);
    }
unlink($uploaddir.$name.".".$count.".origin.".$ext);
$filecount++;  
} 
if($_REQUEST['type']=="parent") header ('Refresh: 1; url=edit_parent_inet.php?parent_inet_id=' . $_REQUEST["tovar_id"]);
if($_REQUEST['type']=="tovar") header ('Refresh: 10; url=edit_tovar.php?tovar_id=' . $_REQUEST["tovar_id"]);

?>
